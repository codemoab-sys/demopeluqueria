<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\MovimientoCaja;
use App\Models\MovimientoStock;
use App\Models\PagoVenta;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Empleado;
use App\Models\TipoBono;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TpvController extends Controller
{
    public function index()
    {
        $empresaId = auth()->user()->empresa_id;
        $servicios = Servicio::where('empresa_id', $empresaId)->where('activo', true)->with('categoria')->get();
        $productos = Producto::where('empresa_id', $empresaId)->where('activo', true)->where('vendible', true)->with('categoria')->get();
        $empleados = Empleado::where('empresa_id', $empresaId)->where('activo', true)->get();
        $cajaAbierta = Caja::where('empresa_id', $empresaId)->where('estado', 'abierta')->latest()->first();

        return view('tpv.index', compact('servicios', 'productos', 'empleados', 'cajaAbierta'));
    }

    public function buscarProducto(Request $request)
    {
        $q = $request->input('q', '');
        $productos = Producto::where('empresa_id', auth()->user()->empresa_id)
            ->where('activo', true)
            ->where(function ($s) use ($q) {
                $s->where('nombre', 'like', "%{$q}%")
                  ->orWhere('codigo_barras', $q)
                  ->orWhere('codigo', $q);
            })
            ->limit(20)->get();
        return response()->json($productos);
    }

    public function buscarCliente(Request $request)
    {
        $q = $request->input('q', '');
        $clientes = Cliente::where('empresa_id', auth()->user()->empresa_id)
            ->where(function ($s) use ($q) {
                $s->where('nombre', 'like', "%{$q}%")
                  ->orWhere('apellidos', 'like', "%{$q}%")
                  ->orWhere('telefono', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            })
            ->limit(20)->get();
        return response()->json($clientes);
    }

    public function cobrar(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $data = $request->validate([
            'cliente_id' => ['nullable', Rule::exists('clientes', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'empleado_id' => ['nullable', Rule::exists('empleados', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'items' => 'required|array|min:1',
            'items.*.tipo' => 'required|in:servicio,producto,bono',
            'items.*.referencia_id' => 'required|integer',
            'items.*.cantidad' => 'required|numeric|min:0.01',
            'items.*.precio' => 'required|numeric|min:0',
            'items.*.descuento' => 'nullable|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,yapeplin,otro',
            'importe_pagado' => 'required|numeric|min:0',
            'descuento_global' => 'nullable|numeric|min:0',
            'notas' => 'nullable|string',
        ]);

        $cajaAbierta = Caja::where('empresa_id', $empresaId)->where('estado', 'abierta')->latest()->first();

        if (!$cajaAbierta) {
            return response()->json(['error' => 'Debes abrir la caja antes de cobrar.'], 422);
        }

        $subtotal = 0;
        $impuestoTotal = 0;
        $detalles = [];

        foreach ($data['items'] as $item) {
            $baseLinea = $item['cantidad'] * $item['precio'];
            $descuentoLinea = $item['descuento'] ?? 0;
            if ($descuentoLinea > $baseLinea) {
                return response()->json(['error' => 'El descuento del ítem no puede superar el importe de la línea.'], 422);
            }

            $linea = $baseLinea - $descuentoLinea;
            $impuestoPct = 18.00;
            if ($item['tipo'] === 'servicio') {
                $servicio = Servicio::where('empresa_id', $empresaId)->find($item['referencia_id']);
                if (!$servicio) {
                    return response()->json(['error' => 'Servicio inválido o de otra empresa.'], 422);
                }
                $impuestoPct = $servicio->impuesto ?? 18;
                $concepto = $servicio->nombre ?? 'Servicio';
            } elseif ($item['tipo'] === 'producto') {
                $producto = Producto::where('empresa_id', $empresaId)->find($item['referencia_id']);
                if (!$producto) {
                    return response()->json(['error' => 'Producto inválido o de otra empresa.'], 422);
                }
                $impuestoPct = $producto->impuesto ?? 18;
                $concepto = $producto->nombre ?? 'Producto';
            } else {
                $tipoBono = TipoBono::where('empresa_id', $empresaId)->find($item['referencia_id']);
                if (!$tipoBono) {
                    return response()->json(['error' => 'Tipo de bono inválido o de otra empresa.'], 422);
                }
                $concepto = $tipoBono->nombre ?? 'Bono';
            }
            $impLinea = $linea - ($linea / (1 + $impuestoPct / 100));

            $subtotal += $linea - $impLinea;
            $impuestoTotal += $impLinea;

            $detalles[] = [
                'tipo' => $item['tipo'],
                'referencia_id' => $item['referencia_id'],
                'concepto' => $concepto,
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
                'descuento' => $item['descuento'] ?? 0,
                'impuesto_porcentaje' => $impuestoPct,
                'subtotal' => $linea - $impLinea,
                'total' => $linea,
            ];
        }

        $descuentoGlobal = $data['descuento_global'] ?? 0;
        if ($descuentoGlobal > ($subtotal + $impuestoTotal)) {
            return response()->json(['error' => 'El descuento global no puede superar el total de la venta.'], 422);
        }

        $total = $subtotal + $impuestoTotal - $descuentoGlobal;

        $venta = DB::transaction(function () use ($data, $empresaId, $cajaAbierta, $subtotal, $impuestoTotal, $detalles, $descuentoGlobal, $total) {

            $venta = Venta::create([
                'empresa_id' => $empresaId,
                'cliente_id' => $data['cliente_id'] ?? null,
                'empleado_id' => $data['empleado_id'] ?? null,
                'user_id' => auth()->id(),
                'caja_id' => $cajaAbierta->id,
                'numero' => 'V-' . now()->format('YmdHis') . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4)),
                'fecha' => now(),
                'subtotal' => $subtotal,
                'descuento' => $descuentoGlobal,
                'impuesto' => $impuestoTotal,
                'total' => $total,
                'metodo_pago' => $data['metodo_pago'],
                'importe_pagado' => $data['importe_pagado'],
                'cambio' => max(0, $data['importe_pagado'] - $total),
                'estado' => 'pagada',
                'notas' => $data['notas'] ?? null,
            ]);

            foreach ($detalles as $det) {
                $det['venta_id'] = $venta->id;
                $det['empleado_id'] = $data['empleado_id'] ?? null;
                DetalleVenta::create($det);

                // Reducir stock si es producto
                if ($det['tipo'] === 'producto') {
                    $producto = Producto::where('empresa_id', $empresaId)->find($det['referencia_id']);
                    if ($producto && $producto->controlar_stock) {
                        $stockAnt = $producto->stock;
                        $stockNue = $stockAnt - $det['cantidad'];
                        $producto->update(['stock' => $stockNue]);
                        MovimientoStock::create([
                            'empresa_id' => $empresaId,
                            'producto_id' => $producto->id,
                            'user_id' => auth()->id(),
                            'tipo' => 'venta',
                            'cantidad' => $det['cantidad'],
                            'stock_anterior' => $stockAnt,
                            'stock_nuevo' => $stockNue,
                            'precio_unitario' => $det['precio_unitario'],
                            'referencia' => $venta->numero,
                        ]);
                    }
                }
            }

            // Pago
            PagoVenta::create([
                'venta_id' => $venta->id,
                'metodo' => $data['metodo_pago'],
                'importe' => $total,
                'fecha' => now(),
            ]);

            // Movimiento caja
            MovimientoCaja::create([
                'caja_id' => $cajaAbierta->id,
                'user_id' => auth()->id(),
                'tipo' => 'venta',
                'importe' => $total,
                'metodo_pago' => $data['metodo_pago'],
                'concepto' => 'Venta ' . $venta->numero,
                'referencia' => $venta->numero,
                'fecha' => now(),
            ]);

            return $venta;
        });

        return response()->json([
            'success' => true,
            'venta_id' => $venta->id,
            'numero' => $venta->numero,
            'total' => $venta->total,
        ]);
    }
}

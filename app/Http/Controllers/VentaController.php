<?php

namespace App\Http\Controllers;

use App\Models\MovimientoCaja;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::where('empresa_id', auth()->user()->empresa_id)
            ->with(['cliente', 'empleado']);

        if ($request->filled('desde')) $query->whereDate('fecha', '>=', $request->input('desde'));
        if ($request->filled('hasta')) $query->whereDate('fecha', '<=', $request->input('hasta'));
        if ($request->filled('estado')) $query->where('estado', $request->input('estado'));

        $ventas = $query->latest('fecha')->paginate(25)->withQueryString();
        return view('ventas.index', compact('ventas'));
    }

    public function show(Venta $venta)
    {
        $this->autorizar($venta);
        $venta->load(['cliente', 'empleado', 'detalles', 'pagos', 'user']);
        return view('ventas.show', compact('venta'));
    }

    public function ticket(Venta $venta)
    {
        $this->autorizar($venta);
        $venta->load(['cliente', 'empleado', 'detalles']);
        return view('ventas.ticket', compact('venta'));
    }

    public function destroy(Venta $venta)
    {
        $this->autorizar($venta);
        if ($venta->estado === 'anulada') {
            return back()->with('error', 'La venta ya está anulada.');
        }

        DB::transaction(function () use ($venta) {
            $venta->loadMissing(['detalles', 'caja']);

            foreach ($venta->detalles as $detalle) {
                if ($detalle->tipo !== 'producto') {
                    continue;
                }

                $producto = Producto::where('empresa_id', $venta->empresa_id)->find($detalle->referencia_id);
                if (!$producto) {
                    continue;
                }

                $stockAnterior = $producto->stock;
                $stockNuevo = $stockAnterior + $detalle->cantidad;
                $producto->update(['stock' => $stockNuevo]);

                MovimientoStock::create([
                    'empresa_id' => $venta->empresa_id,
                    'producto_id' => $producto->id,
                    'user_id' => auth()->id(),
                    'tipo' => 'devolucion',
                    'cantidad' => $detalle->cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockNuevo,
                    'precio_unitario' => $detalle->precio_unitario,
                    'referencia' => $venta->numero,
                ]);
            }

            if ($venta->caja_id) {
                $metodoCaja = in_array($venta->metodo_pago, ['efectivo', 'tarjeta', 'transferencia', 'yapeplin', 'otro'], true)
                    ? $venta->metodo_pago
                    : 'otro';

                MovimientoCaja::create([
                    'caja_id' => $venta->caja_id,
                    'user_id' => auth()->id(),
                    'tipo' => 'devolucion',
                    'importe' => $venta->total,
                    'metodo_pago' => $metodoCaja,
                    'concepto' => 'Devolución venta ' . $venta->numero,
                    'referencia' => $venta->numero,
                    'fecha' => now(),
                ]);
            }

            $venta->update(['estado' => 'anulada']);
        });

        return back()->with('success', 'Venta anulada.');
    }

    private function autorizar(Venta $venta): void
    {
        abort_if($venta->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

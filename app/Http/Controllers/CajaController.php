<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index()
    {
        $empresaId = auth()->user()->empresa_id;
        $cajaAbierta = Caja::where('empresa_id', $empresaId)
            ->where('estado', 'abierta')
            ->latest('fecha_apertura')
            ->first();

        if ($cajaAbierta) {
            $cajaAbierta->load(['movimientos.user', 'userApertura']);
            $totales = $cajaAbierta->movimientos->groupBy('metodo_pago')->map(fn($g) => $g->sum('importe'));
        } else {
            $totales = collect();
        }

        return view('caja.index', compact('cajaAbierta', 'totales'));
    }

    public function abrir(Request $request)
    {
        $data = $request->validate([
            'importe_inicial' => 'required|numeric|min:0',
            'notas_apertura' => 'nullable|string',
        ]);

        $empresaId = auth()->user()->empresa_id;
        $existe = Caja::where('empresa_id', $empresaId)->where('estado', 'abierta')->exists();
        if ($existe) {
            return back()->with('error', 'Ya hay una caja abierta. Ciérrala antes de abrir una nueva.');
        }

        $caja = Caja::create([
            'empresa_id' => $empresaId,
            'user_apertura_id' => auth()->id(),
            'fecha_apertura' => now(),
            'importe_inicial' => $data['importe_inicial'],
            'estado' => 'abierta',
            'notas_apertura' => $data['notas_apertura'] ?? null,
        ]);

        MovimientoCaja::create([
            'caja_id' => $caja->id,
            'user_id' => auth()->id(),
            'tipo' => 'apertura',
            'importe' => $data['importe_inicial'],
            'metodo_pago' => 'efectivo',
            'concepto' => 'Apertura de caja',
            'fecha' => now(),
        ]);

        return back()->with('success', 'Caja abierta correctamente.');
    }

    public function cerrar(Request $request)
    {
        $data = $request->validate([
            'caja_id' => 'required|integer',
            'importe_final' => 'required|numeric|min:0',
            'notas_cierre' => 'nullable|string',
        ]);

        $caja = Caja::where('empresa_id', auth()->user()->empresa_id)->findOrFail($data['caja_id']);

        DB::transaction(function () use ($caja, $data) {
            $movs = $caja->movimientos;
            $efectivo = $movs->where('metodo_pago', 'efectivo')->whereIn('tipo', ['venta', 'ingreso', 'apertura'])->sum('importe')
                      - $movs->where('metodo_pago', 'efectivo')->whereIn('tipo', ['gasto', 'devolucion'])->sum('importe');
            $tarjeta = $movs->where('metodo_pago', 'tarjeta')->whereIn('tipo', ['venta', 'ingreso'])->sum('importe')
                      - $movs->where('metodo_pago', 'tarjeta')->where('tipo', 'devolucion')->sum('importe');
            $transferencia = $movs->where('metodo_pago', 'transferencia')->whereIn('tipo', ['venta', 'ingreso'])->sum('importe')
                      - $movs->where('metodo_pago', 'transferencia')->where('tipo', 'devolucion')->sum('importe');
            $otros = $movs->whereNotIn('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->whereIn('tipo', ['venta', 'ingreso'])->sum('importe')
                      - $movs->whereNotIn('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->where('tipo', 'devolucion')->sum('importe');
            $totalVentas = $movs->where('tipo', 'venta')->sum('importe');
            $totalIngresos = $movs->where('tipo', 'ingreso')->sum('importe');
            $totalGastos = $movs->where('tipo', 'gasto')->sum('importe');
            $descuadre = $data['importe_final'] - $efectivo;

            $caja->update([
                'user_cierre_id' => auth()->id(),
                'fecha_cierre' => now(),
                'importe_final' => $data['importe_final'],
                'importe_efectivo' => $efectivo,
                'importe_tarjeta' => $tarjeta,
                'importe_transferencia' => $transferencia,
                'importe_otros' => $otros,
                'total_ventas' => $totalVentas,
                'total_ingresos' => $totalIngresos,
                'total_gastos' => $totalGastos,
                'descuadre' => $descuadre,
                'estado' => 'cerrada',
                'notas_cierre' => $data['notas_cierre'] ?? null,
            ]);

            MovimientoCaja::create([
                'caja_id' => $caja->id,
                'user_id' => auth()->id(),
                'tipo' => 'cierre',
                'importe' => $data['importe_final'],
                'metodo_pago' => 'efectivo',
                'concepto' => 'Cierre de caja',
                'fecha' => now(),
            ]);
        });

        return redirect()->route('caja.index')->with('success', 'Caja cerrada correctamente.');
    }

    public function movimiento(Request $request)
    {
        $data = $request->validate([
            'caja_id' => 'required|integer',
            'tipo' => 'required|in:ingreso,gasto',
            'importe' => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,yapeplin,otro',
            'concepto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $caja = Caja::where('empresa_id', auth()->user()->empresa_id)->findOrFail($data['caja_id']);
        abort_if($caja->estado !== 'abierta', 403);

        $data['user_id'] = auth()->id();
        $data['fecha'] = now();
        MovimientoCaja::create($data);

        return back()->with('success', 'Movimiento registrado.');
    }

    public function historial()
    {
        $cajas = Caja::where('empresa_id', auth()->user()->empresa_id)
            ->with(['userApertura', 'userCierre', 'movimientos'])
            ->latest('fecha_apertura')
            ->paginate(20);

        $cajas->getCollection()->transform(function ($caja) {
            [$totalVentas, $importeFinal, $descuadre] = $this->calcularResumen($caja);
            $caja->total_ventas_calculado = $totalVentas;
            $caja->importe_final_calculado = $importeFinal;
            $caja->descuadre_calculado = $descuadre;
            return $caja;
        });

        return view('caja.historial', compact('cajas'));
    }

    public function show(Caja $caja)
    {
        abort_if($caja->empresa_id !== auth()->user()->empresa_id, 403);
        $caja->load(['movimientos.user', 'userApertura', 'userCierre']);
        [$totalVentas, $importeFinal, $descuadre] = $this->calcularResumen($caja);
        $caja->total_ventas_calculado = $totalVentas;
        $caja->importe_final_calculado = $importeFinal;
        $caja->descuadre_calculado = $descuadre;
        return view('caja.show', compact('caja'));
    }

    private function calcularResumen(Caja $caja): array
    {
        $movs = $caja->movimientos;
        $efectivo = $movs->where('metodo_pago', 'efectivo')->whereIn('tipo', ['venta', 'ingreso', 'apertura'])->sum('importe')
                  - $movs->where('metodo_pago', 'efectivo')->whereIn('tipo', ['gasto', 'devolucion'])->sum('importe');
        $totalVentas = $movs->where('tipo', 'venta')->sum('importe') - $movs->where('tipo', 'devolucion')->sum('importe');
        $importeFinal = $caja->importe_inicial + $efectivo;
        $descuadre = $importeFinal - ($caja->importe_final ?? $importeFinal);

        return [$totalVentas, $importeFinal, $descuadre];
    }
}

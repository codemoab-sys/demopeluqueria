<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformesController extends Controller
{
    public function index()
    {
        return view('informes.index');
    }

    public function ventas(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $desde = Carbon::parse($request->input('desde', now()->startOfMonth()));
        $hasta = Carbon::parse($request->input('hasta', now()->endOfMonth()));

        $ventas = Venta::where('empresa_id', $empresaId)
            ->whereBetween('fecha', [$desde, $hasta])
            ->whereNotIn('estado', ['anulada', 'devuelta'])
            ->get();

        $totalVentas = $ventas->sum('total');
        $totalImpuestos = $ventas->sum('impuesto');
        $cantidadVentas = $ventas->count();
        $ticketMedio = $cantidadVentas > 0 ? $totalVentas / $cantidadVentas : 0;

        $porDia = $ventas->groupBy(fn($v) => $v->fecha->format('Y-m-d'))
            ->map(fn($g) => $g->sum('total'));

        $porMetodo = $ventas->groupBy('metodo_pago')->map(fn($g) => $g->sum('total'));

        return view('informes.ventas', compact(
            'desde', 'hasta', 'totalVentas', 'totalImpuestos',
            'cantidadVentas', 'ticketMedio', 'porDia', 'porMetodo', 'ventas'
        ));
    }

    public function clientes(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $clientes = Cliente::where('empresa_id', $empresaId)
            ->withCount('citas')
            ->withSum(['ventas as total_gastado' => function ($q) {
                $q->whereNotIn('estado', ['anulada', 'devuelta']);
            }], 'total')
            ->orderByDesc('total_gastado')
            ->paginate(50);

        return view('informes.clientes', compact('clientes'));
    }

    public function empleados(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $desde = Carbon::parse($request->input('desde', now()->startOfMonth()));
        $hasta = Carbon::parse($request->input('hasta', now()->endOfMonth()));

        $empleados = Empleado::where('empresa_id', $empresaId)
            ->withCount(['citas' => fn($q) => $q->whereBetween('fecha', [$desde, $hasta])])
            ->withSum(['ventas as total_ventas' => function ($q) use ($desde, $hasta) {
                $q->whereBetween('fecha', [$desde, $hasta])->whereNotIn('estado', ['anulada', 'devuelta']);
            }], 'total')
            ->orderByDesc('total_ventas')
            ->get();

        return view('informes.empleados', compact('empleados', 'desde', 'hasta'));
    }

    public function servicios(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $desde = Carbon::parse($request->input('desde', now()->startOfMonth()));
        $hasta = Carbon::parse($request->input('hasta', now()->endOfMonth()));

        $p = DB::getTablePrefix();
        $top = DB::table('detalle_ventas')
            ->join('ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->where('ventas.empresa_id', $empresaId)
            ->whereBetween('ventas.fecha', [$desde, $hasta])
            ->where('detalle_ventas.tipo', 'servicio')
            ->whereNotIn('ventas.estado', ['anulada', 'devuelta'])
            ->select('detalle_ventas.concepto',
                DB::raw("SUM({$p}detalle_ventas.cantidad) as cantidad"),
                DB::raw("SUM({$p}detalle_ventas.total) as total"))
            ->groupBy('detalle_ventas.concepto')
            ->orderByDesc('total')
            ->get();

        return view('informes.servicios', compact('top', 'desde', 'hasta'));
    }
}

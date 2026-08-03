<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Venta;
use App\Models\Caja;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $empresaId = auth()->user()->empresa_id;
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();
        $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $finMesAnterior = Carbon::now()->subMonth()->endOfMonth();

        // KPIs principales
        $ventasHoy = Venta::where('empresa_id', $empresaId)
            ->whereDate('fecha', $hoy)
            ->whereNotIn('estado', ['anulada', 'devuelta'])
            ->sum('total');

        $ventasMes = Venta::where('empresa_id', $empresaId)
            ->where('fecha', '>=', $inicioMes)
            ->whereNotIn('estado', ['anulada', 'devuelta'])
            ->sum('total');

        $ventasMesAnterior = Venta::where('empresa_id', $empresaId)
            ->whereBetween('fecha', [$inicioMesAnterior, $finMesAnterior])
            ->whereNotIn('estado', ['anulada', 'devuelta'])
            ->sum('total');

        $variacionMes = $ventasMesAnterior > 0
            ? round((($ventasMes - $ventasMesAnterior) / $ventasMesAnterior) * 100, 1)
            : 0;

        $citasHoy = Cita::where('empresa_id', $empresaId)
            ->whereDate('fecha', $hoy)
            ->count();

        $citasPendientes = Cita::where('empresa_id', $empresaId)
            ->whereDate('fecha', '>=', $hoy)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->count();

        $clientesTotal = Cliente::where('empresa_id', $empresaId)->where('activo', true)->count();
        $clientesNuevosMes = Cliente::where('empresa_id', $empresaId)
            ->where('created_at', '>=', $inicioMes)
            ->count();

        $productosBajoStock = Producto::where('empresa_id', $empresaId)
            ->where('controlar_stock', true)
            ->where('activo', true)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->count();

        $cajaAbierta = Caja::where('empresa_id', $empresaId)
            ->where('estado', 'abierta')
            ->latest('fecha_apertura')
            ->first();

        // Citas de hoy
        $citasDeHoy = Cita::where('empresa_id', $empresaId)
            ->whereDate('fecha', $hoy)
            ->with(['cliente', 'empleado', 'servicios.servicio'])
            ->orderBy('hora_inicio')
            ->limit(10)
            ->get();

        // Próximas citas
        $proximasCitas = Cita::where('empresa_id', $empresaId)
            ->whereDate('fecha', '>', $hoy)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->with(['cliente', 'empleado'])
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->limit(5)
            ->get();

        // Gráfico ventas últimos 7 días
        $ventasUltimos7Dias = [];
        $labelsDias = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $total = Venta::where('empresa_id', $empresaId)
                ->whereDate('fecha', $fecha)
                ->whereNotIn('estado', ['anulada', 'devuelta'])
                ->sum('total');
            $ventasUltimos7Dias[] = (float) $total;
            $labelsDias[] = $fecha->isoFormat('ddd D');
        }

        // Top servicios del mes
        $p = DB::getTablePrefix();
        $topServicios = DB::table('detalle_ventas')
            ->join('ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->where('ventas.empresa_id', $empresaId)
            ->where('ventas.fecha', '>=', $inicioMes)
            ->where('detalle_ventas.tipo', 'servicio')
            ->whereNotIn('ventas.estado', ['anulada', 'devuelta'])
            ->select('detalle_ventas.concepto', DB::raw("SUM({$p}detalle_ventas.cantidad) as cantidad"), DB::raw("SUM({$p}detalle_ventas.total) as total"))
            ->groupBy('detalle_ventas.concepto')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Top empleados del mes
        $topEmpleados = Empleado::where('empresa_id', $empresaId)
            ->withSum(['ventas as total_mes' => function ($q) use ($inicioMes) {
                $q->where('fecha', '>=', $inicioMes)->whereNotIn('estado', ['anulada', 'devuelta']);
            }], 'total')
            ->orderByDesc('total_mes')
            ->limit(5)
            ->get();

        // Métodos de pago del mes
        $pagosPorMetodo = Venta::where('empresa_id', $empresaId)
            ->where('fecha', '>=', $inicioMes)
            ->whereNotIn('estado', ['anulada', 'devuelta'])
            ->select('metodo_pago', DB::raw('SUM(total) as total'))
            ->groupBy('metodo_pago')
            ->get();

        return view('dashboard.index', compact(
            'ventasHoy', 'ventasMes', 'variacionMes',
            'citasHoy', 'citasPendientes',
            'clientesTotal', 'clientesNuevosMes',
            'productosBajoStock', 'cajaAbierta',
            'citasDeHoy', 'proximasCitas',
            'ventasUltimos7Dias', 'labelsDias',
            'topServicios', 'topEmpleados', 'pagosPorMetodo'
        ));
    }

    public function datos(Request $request)
    {
        // Endpoint AJAX para refrescar datos del dashboard
        return response()->json(['ok' => true]);
    }
}

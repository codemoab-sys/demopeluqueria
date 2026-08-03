@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp

<!-- Saludo -->
<div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="mb-1" style="font-weight:800;letter-spacing:-0.5px;">¡Hola, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
        <p class="text-muted mb-0">{{ ucfirst(\Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('agenda.index') }}" class="btn btn-soft"><i class="bi bi-calendar-plus"></i> Nueva cita</a>
        <a href="{{ route('tpv.index') }}" class="btn btn-primary"><i class="bi bi-cash-coin"></i> Cobrar</a>
    </div>
</div>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="kpi-card gradient-violet">
            <div class="kpi-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="kpi-label">Ventas hoy</div>
            <div class="kpi-value">{{ number_format($ventasHoy, 2, ',', '.') }} {{ $moneda }}</div>
            <div class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> Día activo</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card gradient-pink">
            <div class="kpi-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="kpi-label">Ventas del mes</div>
            <div class="kpi-value">{{ number_format($ventasMes, 2, ',', '.') }} {{ $moneda }}</div>
            <div class="kpi-trend {{ $variacionMes >= 0 ? 'up' : 'down' }}">
                <i class="bi bi-arrow-{{ $variacionMes >= 0 ? 'up' : 'down' }}-right"></i>
                {{ abs($variacionMes) }}% vs. mes anterior
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card gradient-blue">
            <div class="kpi-icon"><i class="bi bi-calendar3"></i></div>
            <div class="kpi-label">Citas hoy</div>
            <div class="kpi-value">{{ $citasHoy }}</div>
            <div class="kpi-trend up"><i class="bi bi-clock"></i> {{ $citasPendientes }} próximas</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card gradient-green">
            <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
            <div class="kpi-label">Clientes activos</div>
            <div class="kpi-value">{{ $clientesTotal }}</div>
            <div class="kpi-trend up"><i class="bi bi-plus-circle"></i> {{ $clientesNuevosMes }} nuevos</div>
        </div>
    </div>
</div>

<!-- Alertas -->
@if($productosBajoStock > 0 || !$cajaAbierta)
<div class="row g-3 mb-4">
    @if(!$cajaAbierta)
    <div class="col-md-6">
        <div class="card-modern" style="border-left:4px solid #f59e0b;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="kpi-icon warning mb-0"><i class="bi bi-safe2"></i></div>
                <div class="flex-grow-1">
                    <h6 class="mb-1" style="font-weight:700;">Caja sin abrir</h6>
                    <p class="mb-0 text-muted small">Abre la caja para empezar a registrar ventas del día.</p>
                </div>
                <a href="{{ route('caja.index') }}" class="btn btn-soft">Abrir caja</a>
            </div>
        </div>
    </div>
    @endif
    @if($productosBajoStock > 0)
    <div class="col-md-6">
        <div class="card-modern" style="border-left:4px solid #ef4444;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="kpi-icon danger mb-0"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="flex-grow-1">
                    <h6 class="mb-1" style="font-weight:700;">Stock bajo</h6>
                    <p class="mb-0 text-muted small">{{ $productosBajoStock }} producto{{ $productosBajoStock > 1 ? 's' : '' }} con stock bajo el mínimo.</p>
                </div>
                <a href="{{ route('productos.index', ['bajo_stock' => 1]) }}" class="btn btn-soft">Ver productos</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Gráficos principales -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card-modern h-100">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-bar-chart-line text-primary me-2"></i>Ventas últimos 7 días</h5>
                <span class="badge-soft primary">Total: {{ number_format(array_sum($ventasUltimos7Dias), 2, ',', '.') }} {{ $moneda }}</span>
            </div>
            <div class="card-body">
                <canvas id="chartVentas" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-modern h-100">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-credit-card text-primary me-2"></i>Métodos de pago (mes)</h5>
            </div>
            <div class="card-body">
                @if($pagosPorMetodo->isEmpty())
                    <div class="empty-state"><i class="bi bi-pie-chart"></i><h5>Sin datos</h5><p>Aún no hay ventas este mes</p></div>
                @else
                    <canvas id="chartMetodosPago" height="180"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Listas -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card-modern h-100">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-calendar-check text-primary me-2"></i>Citas de hoy</h5>
                <a href="{{ route('agenda.index') }}" class="btn-soft btn btn-sm">Ver agenda</a>
            </div>
            <div class="card-body p-0">
                @if($citasDeHoy->isEmpty())
                    <div class="empty-state"><i class="bi bi-calendar-x"></i><h5>Sin citas hoy</h5><p>Disfruta el día tranquilo</p></div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($citasDeHoy as $cita)
                            <div class="list-group-item d-flex align-items-center gap-3 px-3 py-3 border-0 border-bottom">
                                <div style="width:60px;text-align:center;">
                                    <div style="font-weight:800;font-size:18px;color:#a855f7;">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}</div>
                                    <small class="text-muted">{{ $cita->duracion_total }}min</small>
                                </div>
                                <div class="flex-grow-1">
                                    <div style="font-weight:600;">{{ $cita->cliente?->nombre_completo ?? 'Sin cliente' }}</div>
                                    <small class="text-muted">
                                        @foreach($cita->servicios as $s){{ $s->servicio?->nombre }}@if(!$loop->last), @endif @endforeach
                                        @if($cita->empleado) · {{ $cita->empleado->nombre }}@endif
                                    </small>
                                </div>
                                <span class="badge-soft {{ $cita->estado_badge }}">{{ ucfirst(str_replace('_', ' ', $cita->estado)) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-modern h-100">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-trophy text-primary me-2"></i>Top empleados del mes</h5>
            </div>
            <div class="card-body p-0">
                @if($topEmpleados->isEmpty() || $topEmpleados->sum('total_mes') == 0)
                    <div class="empty-state"><i class="bi bi-person"></i><h5>Sin datos aún</h5><p>Los empleados aparecerán aquí cuando registren ventas</p></div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($topEmpleados as $i => $emp)
                            @if($emp->total_mes > 0)
                            <div class="list-group-item d-flex align-items-center gap-3 px-3 py-3 border-0 border-bottom">
                                <div style="width:36px;height:36px;border-radius:50%;background:{{ $emp->color }};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">
                                    {{ strtoupper(substr($emp->nombre, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div style="font-weight:600;">{{ $emp->nombre_completo }}</div>
                                    <small class="text-muted">{{ $emp->cargo ?? 'Empleado' }}</small>
                                </div>
                                <div class="text-end">
                                    <div style="font-weight:700;color:#a855f7;">{{ number_format($emp->total_mes, 2, ',', '.') }} {{ $moneda }}</div>
                                    <span class="badge-soft primary">#{{ $i + 1 }}</span>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Top servicios y próximas citas -->
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card-modern">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-stars text-primary me-2"></i>Servicios más vendidos del mes</h5>
            </div>
            <div class="card-body p-0">
                @if($topServicios->isEmpty())
                    <div class="empty-state"><i class="bi bi-stars"></i><h5>Sin datos</h5><p>No hay ventas registradas este mes</p></div>
                @else
                    <table class="table-modern" style="box-shadow:none;border-radius:0;">
                        <thead><tr>
                            <th>Servicio</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Total</th>
                        </tr></thead>
                        <tbody>
                            @foreach($topServicios as $serv)
                                <tr>
                                    <td><strong>{{ $serv->concepto }}</strong></td>
                                    <td class="text-center"><span class="badge-soft primary">{{ (int) $serv->cantidad }}</span></td>
                                    <td class="text-end" style="font-weight:700;">{{ number_format($serv->total, 2, ',', '.') }} {{ $moneda }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-modern h-100">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-clock-history text-primary me-2"></i>Próximas citas</h5>
            </div>
            <div class="card-body p-0">
                @if($proximasCitas->isEmpty())
                    <div class="empty-state"><i class="bi bi-calendar-week"></i><h5>Sin citas próximas</h5></div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($proximasCitas as $cita)
                            <div class="list-group-item d-flex align-items-center gap-3 px-3 py-3 border-0 border-bottom">
                                <div style="width:48px;height:48px;background:linear-gradient(135deg,#faf5ff,#fdf2f8);border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                    <div style="font-weight:800;font-size:14px;color:#a855f7;line-height:1;">{{ $cita->fecha->format('d') }}</div>
                                    <small style="font-size:10px;color:#6b21a8;text-transform:uppercase;">{{ $cita->fecha->isoFormat('MMM') }}</small>
                                </div>
                                <div class="flex-grow-1">
                                    <div style="font-weight:600;">{{ $cita->cliente?->nombre_completo ?? 'Sin cliente' }}</div>
                                    <small class="text-muted"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }} · {{ $cita->empleado?->nombre ?? '—' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#6b7280';

    // Ventas últimos 7 días
    const ctxVentas = document.getElementById('chartVentas');
    if (ctxVentas) {
        const grad = ctxVentas.getContext('2d').createLinearGradient(0, 0, 0, 300);
        grad.addColorStop(0, 'rgba(168, 85, 247, 0.4)');
        grad.addColorStop(1, 'rgba(236, 72, 153, 0.05)');

        new Chart(ctxVentas, {
            type: 'line',
            data: {
                labels: @json($labelsDias),
                datasets: [{
                    label: 'Ventas (S/.)',
                    data: @json($ventasUltimos7Dias),
                    borderColor: '#a855f7',
                    backgroundColor: grad,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ec4899',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f0f9' }, ticks: { callback: v => v + ' {{ $moneda }}' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Métodos de pago
    const ctxMetodos = document.getElementById('chartMetodosPago');
    if (ctxMetodos) {
        new Chart(ctxMetodos, {
            type: 'doughnut',
            data: {
                labels: @json($pagosPorMetodo->pluck('metodo_pago')->map(fn($m) => ucfirst($m))->values()),
                datasets: [{
                    data: @json($pagosPorMetodo->pluck('total')->values()),
                    backgroundColor: ['#a855f7', '#ec4899', '#06b6d4', '#10b981', '#f59e0b', '#6b7280'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 12, font: { size: 12 } } }
                }
            }
        });
    }
});
</script>
@endpush
@endsection

@extends('layouts.app')
@section('title', 'Informe de ventas')
@section('page-title', 'Informe de ventas')

@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
<form method="GET" class="card-modern mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label">Desde</label><input type="date" name="desde" class="form-control" value="{{ $desde->format('Y-m-d') }}"></div>
            <div class="col-md-4"><label class="form-label">Hasta</label><input type="date" name="hasta" class="form-control" value="{{ $hasta->format('Y-m-d') }}"></div>
            <div class="col-md-4"><button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Aplicar</button></div>
        </div>
    </div>
</form>

<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="kpi-card gradient-violet"><div class="kpi-icon"><i class="bi bi-cash-coin"></i></div><div class="kpi-label">Total ventas</div><div class="kpi-value">{{ number_format($totalVentas, 2, ',', '.') }} {{ $moneda }}</div></div></div>
    <div class="col-md-3"><div class="kpi-card gradient-blue"><div class="kpi-icon"><i class="bi bi-receipt"></i></div><div class="kpi-label">Tickets</div><div class="kpi-value">{{ $cantidadVentas }}</div></div></div>
    <div class="col-md-3"><div class="kpi-card gradient-green"><div class="kpi-icon"><i class="bi bi-graph-up"></i></div><div class="kpi-label">Ticket medio</div><div class="kpi-value">{{ number_format($ticketMedio, 2, ',', '.') }} {{ $moneda }}</div></div></div>
    <div class="col-md-3"><div class="kpi-card gradient-orange"><div class="kpi-icon"><i class="bi bi-percent"></i></div><div class="kpi-label">IGV recaudado</div><div class="kpi-value">{{ number_format($totalImpuestos, 2, ',', '.') }} {{ $moneda }}</div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card-modern"><div class="card-header"><h6 class="card-title">Ventas por día</h6></div><div class="card-body"><canvas id="chartDias" height="100"></canvas></div></div>
    </div>
    <div class="col-lg-4">
        <div class="card-modern"><div class="card-header"><h6 class="card-title">Por método de pago</h6></div><div class="card-body">@if($porMetodo->isEmpty())<div class="empty-state"><p>Sin datos</p></div>@else<canvas id="chartMetodo"></canvas>@endif</div></div>
    </div>
</div>

@push('scripts')
<script>
const lblDias = @json($porDia->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->values());
const datDias = @json($porDia->values());
new Chart(document.getElementById('chartDias'), {
    type: 'bar',
    data: { labels: lblDias, datasets: [{ data: datDias, backgroundColor: '#a855f7', borderRadius: 6 }] },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
@if($porMetodo->isNotEmpty())
new Chart(document.getElementById('chartMetodo'), {
    type: 'doughnut',
    data: {
        labels: @json($porMetodo->keys()->map(fn($k) => ucfirst($k))->values()),
        datasets: [{ data: @json($porMetodo->values()), backgroundColor: ['#a855f7','#ec4899','#06b6d4','#10b981','#f59e0b','#6b7280'] }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});
@endif
</script>
@endpush
@endsection

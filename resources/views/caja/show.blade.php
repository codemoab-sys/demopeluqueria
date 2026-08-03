@extends('layouts.app')
@section('title', 'Caja #' . $caja->id)
@section('page-title', 'Cierre de caja #' . $caja->id)

@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
<a href="{{ route('caja.historial') }}" class="btn btn-soft mb-3"><i class="bi bi-arrow-left"></i> Volver</a>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-body">
                <h6 style="font-weight:700;">Resumen</h6>
                <p class="mb-1"><strong>Apertura:</strong> {{ $caja->fecha_apertura->format('d/m/Y H:i') }} ({{ $caja->userApertura->name }})</p>
                <p class="mb-1"><strong>Cierre:</strong> {{ $caja->fecha_cierre?->format('d/m/Y H:i') ?? '—' }} ({{ $caja->userCierre?->name ?? '—' }})</p>
                <hr>
                <p class="mb-1"><strong>Inicial:</strong> {{ number_format($caja->importe_inicial, 2, ',', '.') }} {{ $moneda }}</p>
                <p class="mb-1"><strong>Final efectivo:</strong> {{ number_format($caja->importe_final_calculado ?? $caja->importe_final ?? 0, 2, ',', '.') }} {{ $moneda }}</p>
                <p class="mb-1"><strong>Total ventas:</strong> {{ number_format($caja->total_ventas_calculado ?? $caja->total_ventas, 2, ',', '.') }} {{ $moneda }}</p>
                <p class="mb-0"><strong>Descuadre:</strong> <span style="color:{{ ($caja->descuadre_calculado ?? $caja->descuadre) < 0 ? '#ef4444' : '#10b981' }};">{{ number_format($caja->descuadre_calculado ?? $caja->descuadre, 2, ',', '.') }} {{ $moneda }}</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-modern">
            <div class="card-header"><h6 class="card-title">Movimientos</h6></div>
            <div class="card-body p-0">
                <table class="table-modern" style="box-shadow:none;">
                    <thead><tr><th>Hora</th><th>Tipo</th><th>Concepto</th><th>Método</th><th class="text-end">Importe</th></tr></thead>
                    <tbody>
                        @foreach($caja->movimientos as $m)
                            <tr>
                                <td>{{ $m->fecha->format('H:i') }}</td>
                                <td><span class="badge-soft {{ in_array($m->tipo, ['gasto']) ? 'danger' : 'success' }}">{{ ucfirst($m->tipo) }}</span></td>
                                <td>{{ $m->concepto }}</td>
                                <td>{{ ucfirst($m->metodo_pago) }}</td>
                                <td class="text-end">{{ number_format($m->importe, 2, ',', '.') }} {{ $moneda }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

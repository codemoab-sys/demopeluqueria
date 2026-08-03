@extends('layouts.app')
@section('title', 'Historial caja')
@section('page-title', 'Historial de cierres')

@section('content')
<a href="{{ route('caja.index') }}" class="btn btn-soft mb-3"><i class="bi bi-arrow-left"></i> Volver a caja</a>

<div class="card-modern">
    <div class="card-body p-0">
        <table class="table-modern" style="box-shadow:none;">
            <thead><tr>
                <th>#</th><th>Apertura</th><th>Cierre</th><th>Apertura por</th>
                <th class="text-end">Inicial</th><th class="text-end">Ventas</th><th class="text-end">Final</th><th class="text-end">Descuadre</th><th>Estado</th><th></th>
            </tr></thead>
            <tbody>
                @foreach($cajas as $c)
                    @php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
                    <tr>
                        <td><strong>#{{ $c->id }}</strong></td>
                        <td>{{ $c->fecha_apertura->format('d/m/Y H:i') }}</td>
                        <td>{{ $c->fecha_cierre?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td>{{ $c->userApertura->name }}</td>
                        <td class="text-end">{{ number_format($c->importe_inicial, 2, ',', '.') }} {{ $moneda }}</td>
                        <td class="text-end" style="font-weight:600;color:#10b981;">{{ number_format($c->total_ventas_calculado ?? $c->total_ventas, 2, ',', '.') }} {{ $moneda }}</td>
                        <td class="text-end" style="font-weight:600;">{{ isset($c->importe_final_calculado) ? number_format($c->importe_final_calculado, 2, ',', '.') . ' ' . $moneda : ($c->importe_final !== null ? number_format($c->importe_final, 2, ',', '.') . ' ' . $moneda : '—') }}</td>
                        <td class="text-end" style="font-weight:600;color:{{ ($c->descuadre_calculado ?? $c->descuadre) < 0 ? '#ef4444' : '#10b981' }};">{{ number_format($c->descuadre_calculado ?? $c->descuadre, 2, ',', '.') }} {{ $moneda }}</td>
                        <td><span class="badge-soft {{ $c->estado === 'abierta' ? 'success' : 'secondary' }}">{{ ucfirst($c->estado) }}</span></td>
                        <td><a href="{{ route('caja.show', $c) }}" class="btn btn-sm btn-soft"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $cajas->links() }}</div>
@endsection

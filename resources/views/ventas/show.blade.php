@extends('layouts.app')
@section('title', 'Venta ' . $venta->numero)
@section('page-title', 'Detalle de venta')

@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('ventas.index') }}" class="btn btn-soft"><i class="bi bi-arrow-left"></i> Volver</a>
    <div>
        <a href="{{ route('ventas.ticket', $venta) }}" target="_blank" class="btn btn-soft"><i class="bi bi-printer"></i> Ticket</a>
        @if($venta->estado !== 'anulada')
            <form method="POST" action="{{ route('ventas.destroy', $venta) }}" class="d-inline" onsubmit="return confirm('¿Anular venta?')">
                @csrf @method('DELETE')<button class="btn btn-danger"><i class="bi bi-x-circle"></i> Anular</button>
            </form>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card-modern" style="background:var(--gradient-primary);color:#fff;">
            <div class="card-body">
                <small style="opacity:0.85;">Nº Venta</small>
                <h3 style="font-weight:800;">{{ $venta->numero }}</h3>
                <hr style="border-color:rgba(255,255,255,0.3);">
                <small style="opacity:0.85;">Total</small>
                <h2 style="font-weight:800;">{{ number_format($venta->total, 2, ',', '.') }} {{ $moneda }}</h2>
                <p class="mb-0" style="opacity:0.85;">{{ ucfirst($venta->metodo_pago) }} · {{ $venta->fecha->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="card-modern mt-3">
            <div class="card-body">
                <h6 style="font-weight:700;">Detalles</h6>
                <p class="mb-1 small"><strong>Cliente:</strong> {{ $venta->cliente?->nombre_completo ?? '—' }}</p>
                <p class="mb-1 small"><strong>Empleado:</strong> {{ $venta->empleado?->nombre ?? '—' }}</p>
                <p class="mb-1 small"><strong>Atendido por:</strong> {{ $venta->user?->name ?? '—' }}</p>
                <p class="mb-0 small"><strong>Estado:</strong> <span class="badge-soft {{ $venta->estado === 'pagada' ? 'success' : 'secondary' }}">{{ ucfirst($venta->estado) }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card-modern">
            <div class="card-header"><h6 class="card-title">Líneas</h6></div>
            <div class="card-body p-0">
                <table class="table-modern" style="box-shadow:none;">
                    <thead><tr><th>Concepto</th><th>Tipo</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        @foreach($venta->detalles as $d)
                            <tr>
                                <td><strong>{{ $d->concepto }}</strong></td>
                                <td><span class="badge-soft primary">{{ ucfirst($d->tipo) }}</span></td>
                                <td class="text-center">{{ rtrim(rtrim($d->cantidad, '0'), '.') }}</td>
                                <td class="text-end">{{ number_format($d->precio_unitario, 2, ',', '.') }} {{ $moneda }}</td>
                                <td class="text-end" style="font-weight:600;">{{ number_format($d->total, 2, ',', '.') }} {{ $moneda }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="4" class="text-end">Subtotal</td><td class="text-end">{{ number_format($venta->subtotal, 2, ',', '.') }} {{ $moneda }}</td></tr>
                        <tr><td colspan="4" class="text-end">Impuestos</td><td class="text-end">{{ number_format($venta->impuesto, 2, ',', '.') }} {{ $moneda }}</td></tr>
                        @if($venta->descuento > 0)<tr><td colspan="4" class="text-end">Descuento</td><td class="text-end">-{{ number_format($venta->descuento, 2, ',', '.') }} {{ $moneda }}</td></tr>@endif
                        <tr style="background:var(--gradient-soft);"><td colspan="4" class="text-end" style="font-weight:800;">TOTAL</td><td class="text-end" style="font-weight:800;color:var(--primary-dark);">{{ number_format($venta->total, 2, ',', '.') }} {{ $moneda }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

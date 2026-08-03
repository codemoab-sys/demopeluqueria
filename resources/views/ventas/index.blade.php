@extends('layouts.app')
@section('title', 'Ventas')
@section('page-title', 'Histórico de ventas')

@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp

<form method="GET" class="card-modern mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label">Desde</label><input type="date" name="desde" class="form-control" value="{{ request('desde') }}"></div>
            <div class="col-md-3"><label class="form-label">Hasta</label><input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}"></div>
            <div class="col-md-3"><label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="pagada" @selected(request('estado') === 'pagada')>Pagadas</option>
                    <option value="anulada" @selected(request('estado') === 'anulada')>Anuladas</option>
                </select>
            </div>
            <div class="col-md-3"><button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filtrar</button></div>
        </div>
    </div>
</form>

<div class="card-modern">
    <div class="card-body p-0">
        @if($ventas->isEmpty())
            <div class="empty-state"><i class="bi bi-receipt"></i><h5>Sin ventas</h5></div>
        @else
            <table class="table-modern" style="box-shadow:none;">
                <thead><tr><th>Nº</th><th>Fecha</th><th>Cliente</th><th>Empleado</th><th>Método</th><th class="text-end">Total</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    @foreach($ventas as $v)
                        <tr>
                            <td><strong>{{ $v->numero }}</strong></td>
                            <td>{{ $v->fecha->format('d/m/Y H:i') }}</td>
                            <td>{{ $v->cliente?->nombre_completo ?? '—' }}</td>
                            <td>{{ $v->empleado?->nombre ?? '—' }}</td>
                            <td>{{ ucfirst($v->metodo_pago) }}</td>
                            <td class="text-end" style="font-weight:700;">{{ number_format($v->total, 2, ',', '.') }} {{ $moneda }}</td>
                            <td><span class="badge-soft {{ $v->estado === 'pagada' ? 'success' : 'secondary' }}">{{ ucfirst($v->estado) }}</span></td>
                            <td>
                                <a href="{{ route('ventas.show', $v) }}" class="btn btn-sm btn-soft"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('ventas.ticket', $v) }}" target="_blank" class="btn btn-sm btn-soft"><i class="bi bi-printer"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
<div class="mt-3">{{ $ventas->links() }}</div>
@endsection

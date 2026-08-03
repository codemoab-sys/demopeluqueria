@extends('layouts.app')
@section('title', 'Informe servicios')
@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
<form method="GET" class="card-modern mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label">Desde</label><input type="date" name="desde" class="form-control" value="{{ $desde->format('Y-m-d') }}"></div>
            <div class="col-md-4"><label class="form-label">Hasta</label><input type="date" name="hasta" class="form-control" value="{{ $hasta->format('Y-m-d') }}"></div>
            <div class="col-md-4"><button class="btn btn-primary w-100">Filtrar</button></div>
        </div>
    </div>
</form>
<div class="card-modern">
    <div class="card-header"><h5 class="card-title">Servicios más vendidos</h5></div>
    <div class="card-body p-0">
        <table class="table-modern" style="box-shadow:none;">
            <thead><tr><th>#</th><th>Servicio</th><th class="text-center">Cantidad</th><th class="text-end">Total</th></tr></thead>
            <tbody>
                @foreach($top as $i => $s)
                    <tr>
                        <td><span class="badge-soft primary">#{{ $i + 1 }}</span></td>
                        <td><strong>{{ $s->concepto }}</strong></td>
                        <td class="text-center">{{ (int) $s->cantidad }}</td>
                        <td class="text-end" style="font-weight:700;">{{ number_format($s->total, 2, ',', '.') }} {{ $moneda }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

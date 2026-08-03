@extends('layouts.app')
@section('title', 'Informe empleados')
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
    <div class="card-body p-0">
        <table class="table-modern" style="box-shadow:none;">
            <thead><tr><th>Empleado</th><th class="text-center">Citas</th><th class="text-end">Ventas</th><th class="text-end">Comisión est.</th></tr></thead>
            <tbody>
                @foreach($empleados as $e)
                    <tr>
                        <td><strong>{{ $e->nombre_completo }}</strong></td>
                        <td class="text-center">{{ $e->citas_count }}</td>
                        <td class="text-end" style="font-weight:700;">{{ number_format($e->total_ventas ?? 0, 2, ',', '.') }} {{ $moneda }}</td>
                        <td class="text-end">{{ number_format(($e->total_ventas ?? 0) * $e->comision / 100, 2, ',', '.') }} {{ $moneda }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

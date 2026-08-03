@extends('layouts.app')
@section('title', 'Informe clientes')
@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
<div class="card-modern">
    <div class="card-header"><h5 class="card-title"><i class="bi bi-people me-2"></i>Top clientes por gasto</h5></div>
    <div class="card-body p-0">
        <table class="table-modern" style="box-shadow:none;">
            <thead><tr><th>#</th><th>Cliente</th><th class="text-center">Citas</th><th class="text-end">Total gastado</th><th class="text-center">Puntos</th></tr></thead>
            <tbody>
                @foreach($clientes as $i => $c)
                    <tr>
                        <td><strong>#{{ $clientes->firstItem() + $i }}</strong></td>
                        <td><a href="{{ route('clientes.show', $c) }}" style="color:inherit;font-weight:600;text-decoration:none;">{{ $c->nombre_completo }}</a></td>
                        <td class="text-center">{{ $c->citas_count }}</td>
                        <td class="text-end" style="font-weight:700;color:var(--primary-dark);">{{ number_format($c->total_gastado ?? 0, 2, ',', '.') }} {{ $moneda }}</td>
                        <td class="text-center"><span class="badge-soft pink">{{ $c->puntos_fidelidad }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $clientes->links() }}</div>
@endsection

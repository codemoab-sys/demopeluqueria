@extends('layouts.app')
@section('title', 'Servicios')
@section('page-title', 'Servicios')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('categorias-servicios.index') }}" class="btn btn-soft"><i class="bi bi-tags"></i> Categorías</a>
    <a href="{{ route('servicios.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo servicio</a>
</div>

<div class="card-modern">
    <div class="card-body p-0">
        @if($servicios->isEmpty())
            <div class="empty-state"><i class="bi bi-stars"></i><h5>Sin servicios</h5><p>Crea el primer servicio del catálogo</p></div>
        @else
            @php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
            <table class="table-modern" style="box-shadow:none;">
                <thead><tr><th>Servicio</th><th>Categoría</th><th>Duración</th><th class="text-end">Precio</th><th class="text-end">Comisión</th><th></th></tr></thead>
                <tbody>
                    @foreach($servicios as $s)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="display:inline-block;width:6px;height:36px;border-radius:3px;background:{{ $s->color }};"></span>
                                    <div>
                                        <div style="font-weight:600;">{{ $s->nombre }}</div>
                                        @if($s->codigo)<small class="text-muted">{{ $s->codigo }}</small>@endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $s->categoria?->nombre ?? '—' }}</td>
                            <td>{{ $s->duracion_formateada }}</td>
                            <td class="text-end" style="font-weight:600;">{{ number_format($s->precio, 2, ',', '.') }} {{ $moneda }}</td>
                            <td class="text-end">{{ $s->comision }}%</td>
                            <td class="text-end">
                                <a href="{{ route('servicios.edit', $s) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('servicios.destroy', $s) }}" class="d-inline" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
<div class="mt-3">{{ $servicios->links() }}</div>
@endsection

@extends('layouts.app')
@section('title', 'Tipos de bonos')
@section('page-title', 'Tipos de bonos')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('bonos.index') }}" class="btn btn-soft"><i class="bi bi-arrow-left"></i> Volver a bonos</a>
    <a href="{{ route('tipos-bonos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo tipo</a>
</div>

<div class="card-modern">
    <div class="card-body p-0">
        @if($tipos->isEmpty())
            <div class="empty-state"><i class="bi bi-tag"></i><h5>Sin tipos definidos</h5><p>Crea plantillas de bonos reutilizables</p></div>
        @else
            <table class="table-modern" style="box-shadow:none;">
                <thead><tr><th>Nombre</th><th>Servicio</th><th>Sesiones</th><th>Validez</th><th class="text-end">Precio total</th><th class="text-end">Por sesión</th><th></th></tr></thead>
                <tbody>
                    @foreach($tipos as $t)
                        <tr>
                            <td><strong>{{ $t->nombre }}</strong></td>
                            <td>{{ $t->servicio?->nombre ?? '—' }}</td>
                            <td>{{ $t->sesiones }}</td>
                            <td>{{ $t->validez_dias }} días</td>
                            <td class="text-end" style="font-weight:600;">{{ number_format($t->precio, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}</td>
                            <td class="text-end"><span class="badge-soft primary">{{ number_format($t->precio_sesion, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('tipos-bonos.edit', $t) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('tipos-bonos.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

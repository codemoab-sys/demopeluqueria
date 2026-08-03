@extends('layouts.app')
@section('title', 'Categorías productos')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('productos.index') }}" class="btn btn-soft"><i class="bi bi-arrow-left"></i> Productos</a>
    <a href="{{ route('categorias-productos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nueva categoría</a>
</div>
<div class="card-modern">
    <div class="card-body p-0">
        <table class="table-modern" style="box-shadow:none;">
            <thead><tr><th>Color</th><th>Nombre</th><th>Orden</th><th></th></tr></thead>
            <tbody>
                @foreach($categorias as $c)
                    <tr>
                        <td><span style="display:inline-block;width:18px;height:18px;border-radius:50%;background:{{ $c->color }};"></span></td>
                        <td><strong>{{ $c->nombre }}</strong></td>
                        <td>{{ $c->orden }}</td>
                        <td class="text-end">
                            <a href="{{ route('categorias-productos.edit', $c) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('categorias-productos.destroy', $c) }}" class="d-inline" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

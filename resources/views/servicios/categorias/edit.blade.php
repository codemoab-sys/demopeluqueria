@extends('layouts.app')
@section('content')
<div class="card-modern" style="max-width:560px;margin:0 auto;">
    <div class="card-header"><h5 class="card-title">Editar categoría</h5></div>
    <form method="POST" action="{{ route('categorias-servicios.update', $categoria) }}" class="card-body">
        @csrf @method('PUT')
        <div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" value="{{ $categoria->nombre }}" required></div>
        <div class="mb-3"><label class="form-label">Color</label><input type="color" name="color" class="form-control form-control-color" value="{{ $categoria->color }}"></div>
        <div class="mb-3"><label class="form-label">Orden</label><input type="number" name="orden" class="form-control" value="{{ $categoria->orden }}"></div>
        <div class="mb-3"><input type="hidden" name="activo" value="0"><div class="form-check"><input type="checkbox" name="activo" value="1" class="form-check-input" id="ac" @checked($categoria->activo)><label for="ac" class="form-check-label">Activo</label></div></div>
        <div class="d-flex justify-content-between"><a href="{{ route('categorias-servicios.index') }}" class="btn btn-soft">Cancelar</a><button class="btn btn-primary">Guardar</button></div>
    </form>
</div>
@endsection

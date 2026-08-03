@extends('layouts.app')
@section('content')
<div class="card-modern" style="max-width:560px;margin:0 auto;">
    <div class="card-header"><h5 class="card-title">Nueva categoría</h5></div>
    <form method="POST" action="{{ route('categorias-servicios.store') }}" class="card-body">
        @csrf
        <div class="mb-3"><label class="form-label">Nombre *</label><input type="text" name="nombre" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Color</label><input type="color" name="color" class="form-control form-control-color" value="#ec4899"></div>
        <div class="mb-3"><label class="form-label">Icono (Bootstrap Icons)</label><input type="text" name="icono" class="form-control" placeholder="bi-scissors"></div>
        <div class="mb-3"><label class="form-label">Orden</label><input type="number" name="orden" class="form-control" value="0"></div>
        <div class="mb-3"><input type="hidden" name="activo" value="0"><div class="form-check"><input type="checkbox" name="activo" value="1" class="form-check-input" id="ac" checked><label for="ac" class="form-check-label">Activo</label></div></div>
        <div class="d-flex justify-content-between"><a href="{{ route('categorias-servicios.index') }}" class="btn btn-soft">Cancelar</a><button class="btn btn-primary">Crear</button></div>
    </form>
</div>
@endsection

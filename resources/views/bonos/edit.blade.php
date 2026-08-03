@extends('layouts.app')
@section('title', 'Editar bono')
@section('page-title', 'Editar bono')

@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title">Editar bono {{ $bono->codigo }}</h5></div>
    <form method="POST" action="{{ route('bonos.update', $bono) }}" class="card-body">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Sesiones totales</label>
                <input type="number" name="sesiones_total" class="form-control" value="{{ $bono->sesiones_total }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio</label>
                <input type="number" step="0.01" name="precio" class="form-control" value="{{ $bono->precio }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="activo" @selected($bono->estado === 'activo')>Activo</option>
                    <option value="agotado" @selected($bono->estado === 'agotado')>Agotado</option>
                    <option value="caducado" @selected($bono->estado === 'caducado')>Caducado</option>
                    <option value="cancelado" @selected($bono->estado === 'cancelado')>Cancelado</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha caducidad</label>
                <input type="date" name="fecha_caducidad" class="form-control" value="{{ $bono->fecha_caducidad?->format('Y-m-d') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Notas</label>
                <textarea name="notas" class="form-control" rows="2">{{ $bono->notas }}</textarea>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('bonos.show', $bono) }}" class="btn btn-soft">Cancelar</a>
            <button class="btn btn-primary">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection

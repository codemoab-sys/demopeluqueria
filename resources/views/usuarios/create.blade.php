@extends('layouts.app')
@section('content')
<div class="card-modern" style="max-width:680px;margin:0 auto;">
    <div class="card-header"><h5 class="card-title"><i class="bi bi-person-plus text-primary me-2"></i>Nuevo usuario</h5></div>
    <form method="POST" action="{{ route('usuarios.store') }}" class="card-body">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nombre *</label><input type="text" name="name" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Contraseña *</label><input type="password" name="password" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Confirmar contraseña *</label><input type="password" name="password_confirmation" class="form-control" required></div>
            <div class="col-md-6">
                <label class="form-label">Rol *</label>
                <select name="rol" class="form-select" required>
                    <option value="empleado">Empleado</option>
                    <option value="recepcionista">Recepcionista</option>
                    <option value="gerente">Gerente</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control"></div>
            <div class="col-12">
                <input type="hidden" name="activo" value="0">
                <div class="form-check"><input type="checkbox" name="activo" value="1" class="form-check-input" id="ac" checked><label for="ac" class="form-check-label">Activo</label></div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('usuarios.index') }}" class="btn btn-soft">Cancelar</a>
            <button class="btn btn-primary">Crear usuario</button>
        </div>
    </form>
</div>
@endsection

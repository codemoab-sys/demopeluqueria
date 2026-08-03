@csrf
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Nombre *</label><input type="text" name="nombre" class="form-control" value="{{ old('nombre', $empleado->nombre ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label">Apellidos</label><input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', $empleado->apellidos ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">DNI</label><input type="text" name="dni" class="form-control" value="{{ old('dni', $empleado->dni ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" value="{{ old('telefono', $empleado->telefono ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $empleado->email ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label">Cargo</label><input type="text" name="cargo" class="form-control" placeholder="Ej: Estilista, Barbero..." value="{{ old('cargo', $empleado->cargo ?? '') }}"></div>
    <div class="col-md-3"><label class="form-label">Color en agenda</label><input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $empleado->color ?? '#a855f7') }}"></div>
    <div class="col-md-3"><label class="form-label">Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
    <div class="col-md-4"><label class="form-label">Comisión (%)</label><input type="number" step="0.01" name="comision" class="form-control" value="{{ old('comision', $empleado->comision ?? 0) }}"></div>
    <div class="col-md-4"><label class="form-label">Salario base</label><input type="number" step="0.01" name="salario" class="form-control" value="{{ old('salario', $empleado->salario ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Fecha alta</label><input type="date" name="fecha_alta" class="form-control" value="{{ old('fecha_alta', $empleado->fecha_alta?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"></div>
    <div class="col-12"><label class="form-label">Notas</label><textarea name="notas" class="form-control" rows="2">{{ old('notas', $empleado->notas ?? '') }}</textarea></div>
    <div class="col-md-6">
        <input type="hidden" name="activo" value="0">
        <div class="form-check"><input type="checkbox" id="ac" name="activo" value="1" class="form-check-input" @checked(old('activo', $empleado->activo ?? true))><label for="ac" class="form-check-label">Activo</label></div>
    </div>
</div>
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('empleados.index') }}" class="btn btn-soft">Cancelar</a>
    <button class="btn btn-primary"><i class="bi bi-check2"></i> Guardar</button>
</div>

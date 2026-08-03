@csrf
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Nombre *</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $servicio->nombre ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Código</label>
        <input type="text" name="codigo" class="form-control" value="{{ old('codigo', $servicio->codigo ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Categoría</label>
        <select name="categoria_id" class="form-select">
            <option value="">— Sin categoría —</option>
            @foreach($categorias as $c)<option value="{{ $c->id }}" @selected(old('categoria_id', $servicio->categoria_id ?? '') == $c->id)>{{ $c->nombre }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Color</label>
        <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $servicio->color ?? '#a855f7') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Duración (min) *</label>
        <input type="number" name="duracion" class="form-control" value="{{ old('duracion', $servicio->duracion ?? 30) }}" required min="5">
    </div>
    <div class="col-md-4">
        <label class="form-label">Precio *</label>
        <input type="number" step="0.01" name="precio" class="form-control" value="{{ old('precio', $servicio->precio ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">IGV (%)</label>
        <input type="number" step="0.01" name="impuesto" class="form-control" value="{{ old('impuesto', $servicio->impuesto ?? 18) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Comisión empleado (%)</label>
        <input type="number" step="0.01" name="comision" class="form-control" value="{{ old('comision', $servicio->comision ?? 0) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $servicio->descripcion ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <input type="hidden" name="reservable_online" value="0">
        <div class="form-check"><input type="checkbox" id="ro" name="reservable_online" value="1" class="form-check-input" @checked(old('reservable_online', $servicio->reservable_online ?? true))><label for="ro" class="form-check-label">Reservable online</label></div>
    </div>
    <div class="col-md-6">
        <input type="hidden" name="activo" value="0">
        <div class="form-check"><input type="checkbox" id="ac" name="activo" value="1" class="form-check-input" @checked(old('activo', $servicio->activo ?? true))><label for="ac" class="form-check-label">Activo</label></div>
    </div>
</div>
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('servicios.index') }}" class="btn btn-soft">Cancelar</a>
    <button class="btn btn-primary"><i class="bi bi-check2"></i> Guardar</button>
</div>

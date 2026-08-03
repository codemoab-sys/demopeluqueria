<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Nombre *</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $tipo->nombre ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Servicio</label>
        <select name="servicio_id" class="form-select">
            <option value="">— Genérico —</option>
            @foreach($servicios as $s)<option value="{{ $s->id }}" @selected(old('servicio_id', $tipo->servicio_id ?? '') == $s->id)>{{ $s->nombre }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Sesiones *</label>
        <input type="number" name="sesiones" class="form-control" value="{{ old('sesiones', $tipo->sesiones ?? 5) }}" required min="1">
    </div>
    <div class="col-md-4">
        <label class="form-label">Precio *</label>
        <input type="number" step="0.01" name="precio" class="form-control" value="{{ old('precio', $tipo->precio ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Validez (días) *</label>
        <input type="number" name="validez_dias" class="form-control" value="{{ old('validez_dias', $tipo->validez_dias ?? 365) }}" required>
    </div>
    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $tipo->descripcion ?? '') }}</textarea>
    </div>
</div>
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('tipos-bonos.index') }}" class="btn btn-soft">Cancelar</a>
    <button class="btn btn-primary">Guardar</button>
</div>

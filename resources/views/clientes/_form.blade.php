@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre *</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Apellidos</label>
        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', $cliente->apellidos ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">DNI/NIE</label>
        <input type="text" name="dni" class="form-control" value="{{ old('dni', $cliente->dni ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Fecha nacimiento</label>
        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento?->format('Y-m-d') ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Género</label>
        <select name="genero" class="form-select">
            <option value="">— Sin especificar —</option>
            <option value="femenino" @selected(old('genero', $cliente->genero ?? '') === 'femenino')>Femenino</option>
            <option value="masculino" @selected(old('genero', $cliente->genero ?? '') === 'masculino')>Masculino</option>
            <option value="otro" @selected(old('genero', $cliente->genero ?? '') === 'otro')>Otro</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Foto</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
    </div>
    <div class="col-12">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion ?? '') }}">
    </div>
    <div class="col-md-8">
        <label class="form-label">Ciudad</label>
        <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad', $cliente->ciudad ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Código postal</label>
        <input type="text" name="codigo_postal" class="form-control" value="{{ old('codigo_postal', $cliente->codigo_postal ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Alergias</label>
        <textarea name="alergias" class="form-control" rows="2">{{ old('alergias', $cliente->alergias ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Preferencias</label>
        <textarea name="preferencias" class="form-control" rows="2">{{ old('preferencias', $cliente->preferencias ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Notas</label>
        <textarea name="notas" class="form-control" rows="2">{{ old('notas', $cliente->notas ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <div class="form-check">
            <input type="hidden" name="acepta_marketing" value="0">
            <input type="checkbox" name="acepta_marketing" value="1" class="form-check-input" id="m1" @checked(old('acepta_marketing', $cliente->acepta_marketing ?? false))>
            <label class="form-check-label" for="m1">Acepta comunicaciones de marketing</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-check">
            <input type="hidden" name="acepta_rgpd" value="0">
            <input type="checkbox" name="acepta_rgpd" value="1" class="form-check-input" id="r1" @checked(old('acepta_rgpd', $cliente->acepta_rgpd ?? false))>
            <label class="form-check-label" for="r1">Acepta la política de protección de datos (Ley 29733)</label>
        </div>
    </div>
</div>
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('clientes.index') }}" class="btn btn-soft"><i class="bi bi-arrow-left"></i> Cancelar</a>
    <button class="btn btn-primary"><i class="bi bi-check2"></i> Guardar cliente</button>
</div>

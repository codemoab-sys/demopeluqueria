@csrf
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Nombre *</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Marca</label>
        <input type="text" name="marca" class="form-control" value="{{ old('marca', $producto->marca ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Código</label>
        <input type="text" name="codigo" class="form-control" value="{{ old('codigo', $producto->codigo ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Código barras</label>
        <input type="text" name="codigo_barras" class="form-control" value="{{ old('codigo_barras', $producto->codigo_barras ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Categoría</label>
        <select name="categoria_id" class="form-select">
            <option value="">— Sin categoría —</option>
            @foreach($categorias as $c)<option value="{{ $c->id }}" @selected(old('categoria_id', $producto->categoria_id ?? '') == $c->id)>{{ $c->nombre }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Precio compra</label>
        <input type="number" step="0.01" name="precio_compra" class="form-control" value="{{ old('precio_compra', $producto->precio_compra ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Precio venta *</label>
        <input type="number" step="0.01" name="precio_venta" class="form-control" value="{{ old('precio_venta', $producto->precio_venta ?? 0) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">IGV (%)</label>
        <input type="number" step="0.01" name="impuesto" class="form-control" value="{{ old('impuesto', $producto->impuesto ?? 18) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Unidad</label>
        <input type="text" name="unidad" class="form-control" value="{{ old('unidad', $producto->unidad ?? 'uds') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Stock actual</label>
        <input type="number" step="0.01" name="stock" class="form-control" value="{{ old('stock', $producto->stock ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Stock mínimo</label>
        <input type="number" step="0.01" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', $producto->stock_minimo ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Imagen</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
    </div>
    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
    </div>
    <div class="col-md-4">
        <input type="hidden" name="controlar_stock" value="0">
        <div class="form-check"><input type="checkbox" id="cs" name="controlar_stock" value="1" class="form-check-input" @checked(old('controlar_stock', $producto->controlar_stock ?? true))><label for="cs" class="form-check-label">Controlar stock</label></div>
    </div>
    <div class="col-md-4">
        <input type="hidden" name="vendible" value="0">
        <div class="form-check"><input type="checkbox" id="vd" name="vendible" value="1" class="form-check-input" @checked(old('vendible', $producto->vendible ?? true))><label for="vd" class="form-check-label">Disponible para venta</label></div>
    </div>
    <div class="col-md-4">
        <input type="hidden" name="activo" value="0">
        <div class="form-check"><input type="checkbox" id="ac" name="activo" value="1" class="form-check-input" @checked(old('activo', $producto->activo ?? true))><label for="ac" class="form-check-label">Activo</label></div>
    </div>
</div>
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('productos.index') }}" class="btn btn-soft">Cancelar</a>
    <button class="btn btn-primary"><i class="bi bi-check2"></i> Guardar</button>
</div>

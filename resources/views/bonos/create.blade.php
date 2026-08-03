@extends('layouts.app')
@section('title', 'Nuevo bono')
@section('page-title', 'Nuevo bono')

@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title"><i class="bi bi-ticket-perforated text-primary me-2"></i>Crear bono</h5></div>
    <form method="POST" action="{{ route('bonos.store') }}" class="card-body">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Cliente *</label>
                <select name="cliente_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($clientes as $c)<option value="{{ $c->id }}">{{ $c->nombre_completo }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tipo de bono</label>
                <select name="tipo_bono_id" class="form-select" id="tipoSelect">
                    <option value="">— Personalizado —</option>
                    @foreach($tiposBonos as $t)
                        <option value="{{ $t->id }}" data-sesiones="{{ $t->sesiones }}" data-precio="{{ $t->precio }}" data-validez="{{ $t->validez_dias }}" data-servicio="{{ $t->servicio_id }}">{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Servicio asociado</label>
                <select name="servicio_id" class="form-select" id="servicioSelect">
                    <option value="">— Genérico —</option>
                    @foreach($servicios as $s)<option value="{{ $s->id }}">{{ $s->nombre }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sesiones *</label>
                <input type="number" name="sesiones_total" id="sesiones" class="form-control" min="1" value="5" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio *</label>
                <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha compra *</label>
                <input type="date" name="fecha_compra" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha caducidad</label>
                <input type="date" name="fecha_caducidad" class="form-control" value="{{ now()->addYear()->format('Y-m-d') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Notas</label>
                <textarea name="notas" class="form-control" rows="2"></textarea>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('bonos.index') }}" class="btn btn-soft"><i class="bi bi-arrow-left"></i> Cancelar</a>
            <button class="btn btn-primary"><i class="bi bi-check2"></i> Crear bono</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('tipoSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (!opt.value) return;
    document.getElementById('sesiones').value = opt.dataset.sesiones;
    document.getElementById('precio').value = opt.dataset.precio;
    if (opt.dataset.servicio) document.getElementById('servicioSelect').value = opt.dataset.servicio;
});
</script>
@endpush
@endsection

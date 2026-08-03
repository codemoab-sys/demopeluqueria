@extends('layouts.app')
@section('title', 'Configuración')
@section('page-title', 'Configuración del sistema')

@section('content')
<div class="row g-4">
    <!-- Tabs lateral -->
    <div class="col-lg-3">
        <div class="card-modern">
            <div class="card-body p-2">
                <div class="nav flex-column nav-pills" role="tablist" style="gap:4px;">
                    <button class="nav-link-config active" data-bs-toggle="pill" data-bs-target="#tab-empresa">
                        <i class="bi bi-building"></i> Datos empresa
                    </button>
                    <button class="nav-link-config" data-bs-toggle="pill" data-bs-target="#tab-logo">
                        <i class="bi bi-image"></i> Logo
                    </button>
                    <button class="nav-link-config" data-bs-toggle="pill" data-bs-target="#tab-parametros">
                        <i class="bi bi-sliders"></i> Moneda e impuestos
                    </button>
                    <button class="nav-link-config" data-bs-toggle="pill" data-bs-target="#tab-horario">
                        <i class="bi bi-clock"></i> Horario
                    </button>
                    <button class="nav-link-config" data-bs-toggle="pill" data-bs-target="#tab-ticket">
                        <i class="bi bi-receipt"></i> Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido tabs -->
    <div class="col-lg-9">
        <div class="tab-content">

            <!-- Datos empresa -->
            <div class="tab-pane fade show active" id="tab-empresa">
                <div class="card-modern">
                    <div class="card-header">
                        <h5 class="card-title"><i class="bi bi-building text-primary me-2"></i>Datos de la empresa</h5>
                    </div>
                    <form method="POST" action="{{ route('configuracion.empresa.update') }}" class="card-body">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label">Nombre comercial *</label>
                                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $empresa->nombre) }}" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">CIF / NIF</label>
                                <input type="text" name="cif" class="form-control" value="{{ old('cif', $empresa->cif) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $empresa->direccion) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ciudad</label>
                                <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad', $empresa->ciudad) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Código postal</label>
                                <input type="text" name="codigo_postal" class="form-control" value="{{ old('codigo_postal', $empresa->codigo_postal) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Provincia</label>
                                <input type="text" name="provincia" class="form-control" value="{{ old('provincia', $empresa->provincia) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">País</label>
                                <input type="text" name="pais" class="form-control" value="{{ old('pais', $empresa->pais) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $empresa->telefono) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $empresa->email) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Web</label>
                                <input type="text" name="web" class="form-control" value="{{ old('web', $empresa->web) }}">
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button class="btn btn-primary"><i class="bi bi-check2"></i> Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Logo -->
            <div class="tab-pane fade" id="tab-logo">
                <div class="card-modern">
                    <div class="card-header">
                        <h5 class="card-title"><i class="bi bi-image text-primary me-2"></i>Logo de la empresa</h5>
                    </div>
                    <form method="POST" action="{{ route('configuracion.logo.update') }}" enctype="multipart/form-data" class="card-body">
                        @csrf
                        <div class="row g-4 align-items-center">
                            <div class="col-md-4 text-center">
                                <div style="width:180px;height:180px;border-radius:24px;background:var(--gradient-soft);border:2px dashed var(--primary-light);display:flex;align-items:center;justify-content:center;margin:0 auto;overflow:hidden;">
                                    @if($empresa->logo)
                                        <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo" style="max-width:100%;max-height:100%;">
                                    @else
                                        <i class="bi bi-image" style="font-size:54px;color:var(--primary-light);"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Subir nuevo logo</label>
                                <input type="file" name="logo" class="form-control" accept="image/*" required>
                                <small class="text-muted d-block mt-2">Formatos: JPG, PNG, WEBP, SVG. Tamaño máximo: 2MB. Recomendado: 400x400px.</small>
                                <button class="btn btn-primary mt-3"><i class="bi bi-upload"></i> Subir logo</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Moneda e impuestos -->
            <div class="tab-pane fade" id="tab-parametros">
                <div class="card-modern">
                    <div class="card-header">
                        <h5 class="card-title"><i class="bi bi-sliders text-primary me-2"></i>Moneda, IGV y zona</h5>
                    </div>
                    <form method="POST" action="{{ route('configuracion.parametros.update') }}" class="card-body">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Símbolo moneda</label>
                                <input type="text" name="simbolo_moneda" class="form-control" value="{{ $empresa->simbolo_moneda }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Código moneda</label>
                                <select name="codigo_moneda" class="form-select" required>
                                    <option value="PEN" @selected($empresa->codigo_moneda === 'PEN')>PEN S/.</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">IGV / Impuesto por defecto (%)</label>
                                <input type="number" step="0.01" min="0" max="100" name="impuesto_default" class="form-control" value="{{ $empresa->impuesto_default }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Zona horaria</label>
                                <select name="zona_horaria" class="form-select">
                                    <option value="America/Lima" @selected($empresa->zona_horaria === 'America/Lima')>America/Lima</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Idioma</label>
                                <select name="idioma" class="form-select">
                                    <option value="es" @selected($empresa->idioma === 'es')>Español</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Formato fecha</label>
                                <select name="formato_fecha" class="form-select">
                                    <option value="d/m/Y" @selected($empresa->formato_fecha === 'd/m/Y')>DD/MM/YYYY</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button class="btn btn-primary"><i class="bi bi-check2"></i> Guardar parámetros</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Horario -->
            <div class="tab-pane fade" id="tab-horario">
                <div class="card-modern">
                    <div class="card-header">
                        <h5 class="card-title"><i class="bi bi-clock text-primary me-2"></i>Horario laboral</h5>
                    </div>
                    <form method="POST" action="{{ route('configuracion.horario.update') }}" class="card-body">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Hora apertura</label>
                                <input type="time" name="hora_apertura" class="form-control" value="{{ \Carbon\Carbon::parse($empresa->hora_apertura)->format('H:i') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hora cierre</label>
                                <input type="time" name="hora_cierre" class="form-control" value="{{ \Carbon\Carbon::parse($empresa->hora_cierre)->format('H:i') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Intervalo de citas (min)</label>
                                <input type="number" name="intervalo_citas" class="form-control" min="5" max="120" value="{{ $empresa->intervalo_citas }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Días laborables</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @php $dias = $empresa->dias_laborables ?? ['lun','mar','mie','jue','vie','sab']; @endphp
                                    @foreach(['lun'=>'Lunes','mar'=>'Martes','mie'=>'Miércoles','jue'=>'Jueves','vie'=>'Viernes','sab'=>'Sábado','dom'=>'Domingo'] as $key => $lbl)
                                        <label class="dia-toggle">
                                            <input type="checkbox" name="dias_laborables[]" value="{{ $key }}" @checked(in_array($key, $dias))>
                                            <span>{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button class="btn btn-primary"><i class="bi bi-check2"></i> Guardar horario</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mensaje ticket -->
            <div class="tab-pane fade" id="tab-ticket">
                <div class="card-modern">
                    <div class="card-header">
                        <h5 class="card-title"><i class="bi bi-receipt text-primary me-2"></i>Mensaje del ticket</h5>
                    </div>
                    <form method="POST" action="{{ route('configuracion.parametros.update') }}" class="card-body">
                        @csrf
                        <input type="hidden" name="simbolo_moneda" value="{{ $empresa->simbolo_moneda }}">
                        <input type="hidden" name="codigo_moneda" value="{{ $empresa->codigo_moneda }}">
                        <input type="hidden" name="impuesto_default" value="{{ $empresa->impuesto_default }}">
                        <input type="hidden" name="zona_horaria" value="{{ $empresa->zona_horaria }}">
                        <input type="hidden" name="idioma" value="{{ $empresa->idioma }}">
                        <input type="hidden" name="formato_fecha" value="{{ $empresa->formato_fecha }}">
                        <label class="form-label">Mensaje pie de ticket</label>
                        <textarea name="mensaje_ticket" class="form-control" rows="4" placeholder="¡Gracias por tu visita! Te esperamos pronto.">{{ $empresa->mensaje_ticket }}</textarea>
                        <small class="text-muted d-block mt-2">Este mensaje aparecerá al final de cada ticket impreso.</small>
                        <div class="text-end mt-4">
                            <button class="btn btn-primary"><i class="bi bi-check2"></i> Guardar mensaje</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
.nav-link-config {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 14px; border-radius: 10px;
    background: transparent; border: 0; text-align: left;
    color: var(--text-secondary); font-weight: 600; font-size: 14px;
    cursor: pointer; transition: all 0.2s; width: 100%;
}
.nav-link-config:hover { background: var(--gradient-soft); color: var(--primary); }
.nav-link-config.active {
    background: var(--gradient-primary);
    color: #fff;
    box-shadow: 0 6px 14px rgba(236, 72, 153, 0.3);
}
.nav-link-config i { font-size: 16px; }

.dia-toggle {
    cursor: pointer;
}
.dia-toggle input { display: none; }
.dia-toggle span {
    display: inline-block;
    padding: 8px 18px;
    border-radius: 10px;
    background: var(--bg-body);
    color: var(--text-secondary);
    font-size: 13px; font-weight: 600;
    transition: all 0.2s;
    border: 1.5px solid transparent;
}
.dia-toggle input:checked + span {
    background: var(--gradient-primary);
    color: #fff;
}
.dia-toggle:hover span { border-color: var(--primary-light); }
</style>
@endpush
@endsection

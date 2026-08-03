@extends('layouts.app')
@section('title', 'Backup & Sistema')
@section('page-title', 'Backup y mantenimiento del sistema')

@section('content')
<div class="row g-4">
    <!-- Crear backup -->
    <div class="col-lg-6">
        <div class="card-modern">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-cloud-arrow-down-fill text-primary me-2"></i>Crear copia de seguridad</h5>
            </div>
            <form method="POST" action="{{ route('sistema.backup.crear') }}" class="card-body">
                @csrf
                <p class="text-muted small">Genera un archivo SQL completo con toda la información de la base de datos. Guarda esta copia en un lugar seguro.</p>
                <div class="mb-3">
                    <label class="form-label">Descripción (opcional)</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="Ej: Antes de actualización">
                </div>
                <button class="btn btn-primary w-100"><i class="bi bi-cloud-download"></i> Generar copia de seguridad</button>
            </form>
        </div>
    </div>

    <!-- Restaurar backup -->
    <div class="col-lg-6">
        <div class="card-modern">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i>Restaurar copia de seguridad</h5>
            </div>
            <form method="POST" action="{{ route('sistema.backup.restaurar') }}" enctype="multipart/form-data" class="card-body" onsubmit="return confirm('¿Estás seguro? Esto sobrescribirá TODA la información actual.')">
                @csrf
                <div class="alert alert-warning small mb-3"><i class="bi bi-exclamation-triangle me-2"></i><strong>Atención:</strong> Restaurar sobrescribe todos los datos actuales. Crea una copia antes.</div>
                <div class="mb-3">
                    <label class="form-label">Archivo .sql</label>
                    <input type="file" name="archivo" accept=".sql,.txt" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmación (escribe CONFIRMAR)</label>
                    <input type="text" name="confirmar" class="form-control" placeholder="CONFIRMAR" required>
                </div>
                <button class="btn btn-soft w-100"><i class="bi bi-arrow-counterclockwise"></i> Restaurar copia</button>
            </form>
        </div>
    </div>
</div>

<!-- Lista de copias -->
<div class="card-modern mt-4">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-archive me-2"></i>Copias guardadas</h5>
    </div>
    <div class="card-body p-0">
        @if($backups->isEmpty())
            <div class="empty-state"><i class="bi bi-archive"></i><h5>Sin copias</h5><p>Genera tu primera copia de seguridad</p></div>
        @else
            <table class="table-modern" style="box-shadow:none;">
                <thead><tr><th>Fecha</th><th>Nombre</th><th>Descripción</th><th>Tipo</th><th>Tamaño</th><th>Usuario</th><th></th></tr></thead>
                <tbody>
                    @foreach($backups as $b)
                        <tr>
                            <td>{{ $b->created_at->format('d/m/Y H:i') }}</td>
                            <td><code style="color:var(--primary-dark);">{{ $b->nombre }}</code></td>
                            <td>{{ $b->descripcion ?? '—' }}</td>
                            <td><span class="badge-soft {{ $b->tipo === 'automatico' ? 'info' : 'primary' }}">{{ ucfirst($b->tipo) }}</span></td>
                            <td>{{ $b->tamano_formateado }}</td>
                            <td>{{ $b->user?->name ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('sistema.backup.descargar', $b) }}" class="btn btn-sm btn-soft" title="Descargar"><i class="bi bi-download"></i></a>
                                <form method="POST" action="{{ route('sistema.backup.destroy', $b) }}" class="d-inline" onsubmit="return confirm('¿Eliminar esta copia?')">
                                    @csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
<div class="mt-3">{{ $backups->links() }}</div>

<!-- Reset -->
<div class="card-modern mt-4" style="border:2px solid #ef4444;">
    <div class="card-header" style="background:rgba(239,68,68,0.05);border-bottom-color:rgba(239,68,68,0.2);">
        <h5 class="card-title text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Zona de peligro · Resetear sistema</h5>
    </div>
    <form method="POST" action="{{ route('sistema.backup.reset') }}" class="card-body" onsubmit="return confirm('⚠️ ATENCIÓN: Esto eliminará TODOS los datos operativos (clientes, citas, ventas, productos…). Solo se mantendrán los usuarios. ¿Continuar?')">
        @csrf
        <p class="text-muted">Útil cuando empiezas a usar el sistema en una nueva empresa o quieres limpiar todos los datos de prueba. <strong class="text-danger">Esta acción no se puede deshacer</strong>. Genera una copia de seguridad antes.</p>
        <div class="row g-3">
            <div class="col-md-6">
                <input type="hidden" name="mantener_empresa" value="0">
                <div class="form-check">
                    <input type="checkbox" name="mantener_empresa" value="1" class="form-check-input" id="me" checked>
                    <label for="me" class="form-check-label">Mantener empleados y configuración</label>
                </div>
                <small class="text-muted">Si lo desmarcas, también se borrarán empleados y configuración del sistema.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label small">Para confirmar, escribe: <code>RESETEAR</code></label>
                <input type="text" name="confirmar" class="form-control" placeholder="RESETEAR" required>
            </div>
        </div>
        <button class="btn btn-danger mt-3"><i class="bi bi-trash3"></i> Resetear sistema</button>
    </form>
</div>
@endsection

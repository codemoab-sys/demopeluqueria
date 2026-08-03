@extends('layouts.app')
@section('title', 'Bono ' . $bono->codigo)
@section('page-title', 'Detalle de bono')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card-modern" style="background:var(--gradient-primary);color:#fff;border:0;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <small style="opacity:0.8;text-transform:uppercase;letter-spacing:1px;">Código</small>
                        <h2 style="font-weight:800;margin:0;">{{ $bono->codigo }}</h2>
                    </div>
                    <i class="bi bi-ticket-perforated-fill" style="font-size:48px;opacity:0.4;"></i>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <small style="opacity:0.8;">Cliente</small>
                        <h5 style="margin:0;">{{ $bono->cliente?->nombre_completo }}</h5>
                        <small style="opacity:0.8;">{{ $bono->servicio?->nombre ?? 'Bono genérico' }}</small>
                    </div>
                    <div class="text-end">
                        <small style="opacity:0.8;">Importe</small>
                        <h4 style="margin:0;font-weight:800;">{{ number_format($bono->precio, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-modern mt-3">
            <div class="card-body">
                <h6 class="mb-3" style="font-weight:700;">Progreso</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span><strong>{{ $bono->sesiones_usadas }}</strong> de <strong>{{ $bono->sesiones_total }}</strong> sesiones</span>
                    <span class="badge-soft primary">{{ $bono->porcentaje_usado }}%</span>
                </div>
                <div style="height:10px;background:#e9e5f0;border-radius:5px;overflow:hidden;">
                    <div style="width:{{ $bono->porcentaje_usado }}%;height:100%;background:var(--gradient-primary);"></div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col">
                        <small class="text-muted">Sesiones restantes</small>
                        <h4 style="font-weight:800;color:var(--primary);">{{ $bono->sesiones_restantes }}</h4>
                    </div>
                    <div class="col">
                        <small class="text-muted">Estado</small>
                        <div><span class="badge-soft {{ $bono->estado === 'activo' ? 'success' : 'secondary' }}">{{ ucfirst($bono->estado) }}</span></div>
                    </div>
                </div>
                <hr>
                <p class="mb-1 small"><strong>Compra:</strong> {{ $bono->fecha_compra->format('d/m/Y') }}</p>
                <p class="mb-0 small"><strong>Caduca:</strong> {{ $bono->fecha_caducidad?->format('d/m/Y') ?? 'Sin caducidad' }}</p>

                @if($bono->estado === 'activo' && $bono->sesiones_restantes > 0)
                    <form method="POST" action="{{ route('bonos.usar', $bono) }}" class="mt-3">
                        @csrf
                        <button class="btn btn-primary w-100"><i class="bi bi-check-circle"></i> Registrar sesión usada</button>
                    </form>
                @endif

                <div class="d-flex gap-2 mt-2">
                    <a href="{{ route('bonos.edit', $bono) }}" class="btn btn-soft flex-grow-1"><i class="bi bi-pencil"></i> Editar</a>
                    <form method="POST" action="{{ route('bonos.destroy', $bono) }}" onsubmit="return confirm('¿Eliminar este bono?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card-modern">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-clock-history me-2"></i>Historial de uso</h6></div>
            <div class="card-body p-0">
                @if($bono->usos->isEmpty())
                    <div class="empty-state"><i class="bi bi-clock"></i><h5>Sin usos aún</h5></div>
                @else
                    <table class="table-modern" style="box-shadow:none;">
                        <thead><tr><th>#</th><th>Fecha</th><th>Notas</th></tr></thead>
                        <tbody>
                            @foreach($bono->usos as $i => $uso)
                                <tr>
                                    <td><strong>{{ $i + 1 }}</strong></td>
                                    <td>{{ $uso->fecha->format('d/m/Y') }}</td>
                                    <td>{{ $uso->notas ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

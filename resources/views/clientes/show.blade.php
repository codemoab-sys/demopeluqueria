@extends('layouts.app')
@section('title', $cliente->nombre_completo)
@section('page-title', 'Ficha de cliente')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-modern">
            <div class="card-body text-center">
                <div style="width:110px;height:110px;border-radius:50%;background:linear-gradient(135deg,#a855f7,#ec4899);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:42px;margin:0 auto 14px;overflow:hidden;">
                    @if($cliente->foto)
                        <img src="{{ asset('storage/' . $cliente->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                    @endif
                </div>
                <h5 class="mb-1" style="font-weight:700;">{{ $cliente->nombre_completo }}</h5>
                @if($cliente->edad)<p class="text-muted mb-2">{{ $cliente->edad }} años</p>@endif

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge-soft pink">{{ $cliente->puntos_fidelidad }} puntos</span>
                    <span class="badge-soft primary">{{ number_format($cliente->saldo, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}</span>
                </div>

                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-soft w-100"><i class="bi bi-pencil"></i> Editar datos</a>
            </div>
        </div>

        <div class="card-modern mt-3">
            <div class="card-header"><h6 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Contacto</h6></div>
            <div class="card-body">
                @if($cliente->telefono)<p class="mb-2"><i class="bi bi-telephone-fill text-primary me-2"></i>{{ $cliente->telefono }}</p>@endif
                @if($cliente->email)<p class="mb-2"><i class="bi bi-envelope-fill text-primary me-2"></i>{{ $cliente->email }}</p>@endif
                @if($cliente->dni)<p class="mb-2"><i class="bi bi-card-text text-primary me-2"></i>{{ $cliente->dni }}</p>@endif
                @if($cliente->direccion)<p class="mb-2"><i class="bi bi-geo-alt-fill text-primary me-2"></i>{{ $cliente->direccion }}@if($cliente->ciudad), {{ $cliente->ciudad }}@endif</p>@endif
                @if($cliente->fecha_nacimiento)<p class="mb-0"><i class="bi bi-cake2-fill text-primary me-2"></i>{{ $cliente->fecha_nacimiento->format('d/m/Y') }}</p>@endif
            </div>
        </div>

        @if($cliente->alergias || $cliente->preferencias || $cliente->notas)
        <div class="card-modern mt-3">
            <div class="card-header"><h6 class="card-title mb-0"><i class="bi bi-heart-pulse me-2"></i>Información adicional</h6></div>
            <div class="card-body">
                @if($cliente->alergias)<p class="mb-2 small"><strong>Alergias:</strong> {{ $cliente->alergias }}</p>@endif
                @if($cliente->preferencias)<p class="mb-2 small"><strong>Preferencias:</strong> {{ $cliente->preferencias }}</p>@endif
                @if($cliente->notas)<p class="mb-0 small"><strong>Notas:</strong> {{ $cliente->notas }}</p>@endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-8">
        <ul class="nav nav-tabs nav-tabs-modern mb-3">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-citas"><i class="bi bi-calendar3"></i> Citas ({{ $cliente->citas->count() }})</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-bonos"><i class="bi bi-ticket-perforated"></i> Bonos ({{ $cliente->bonos->count() }})</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-ventas"><i class="bi bi-receipt"></i> Ventas ({{ $cliente->ventas->count() }})</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="t-citas">
                <div class="card-modern">
                    <div class="card-body p-0">
                        @if($cliente->citas->isEmpty())
                            <div class="empty-state"><i class="bi bi-calendar-x"></i><h5>Sin citas registradas</h5></div>
                        @else
                            <table class="table-modern" style="box-shadow:none;">
                                <thead><tr><th>Fecha</th><th>Hora</th><th>Servicios</th><th>Profesional</th><th>Estado</th><th class="text-end">Total</th></tr></thead>
                                <tbody>
                                    @foreach($cliente->citas->sortByDesc('fecha') as $cita)
                                        <tr>
                                            <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}</td>
                                            <td>@foreach($cita->servicios as $s){{ $s->servicio?->nombre }}@if(!$loop->last), @endif @endforeach</td>
                                            <td>{{ $cita->empleado?->nombre ?? '—' }}</td>
                                            <td><span class="badge-soft {{ $cita->estado_badge }}">{{ ucfirst(str_replace('_', ' ', $cita->estado)) }}</span></td>
                                            <td class="text-end" style="font-weight:600;">{{ number_format($cita->precio_total, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="t-bonos">
                <div class="card-modern">
                    <div class="card-body p-0">
                        @if($cliente->bonos->isEmpty())
                            <div class="empty-state"><i class="bi bi-ticket-perforated"></i><h5>Sin bonos</h5><p><a href="{{ route('bonos.create') }}">Crear bono</a></p></div>
                        @else
                            <table class="table-modern" style="box-shadow:none;">
                                <thead><tr><th>Código</th><th>Servicio</th><th>Sesiones</th><th>Caduca</th><th>Estado</th></tr></thead>
                                <tbody>
                                    @foreach($cliente->bonos as $bono)
                                        <tr>
                                            <td><code>{{ $bono->codigo }}</code></td>
                                            <td>{{ $bono->servicio?->nombre ?? 'Genérico' }}</td>
                                            <td>{{ $bono->sesiones_usadas }}/{{ $bono->sesiones_total }}</td>
                                            <td>{{ $bono->fecha_caducidad?->format('d/m/Y') ?? 'Sin caducidad' }}</td>
                                            <td><span class="badge-soft {{ $bono->estado === 'activo' ? 'success' : 'secondary' }}">{{ ucfirst($bono->estado) }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="t-ventas">
                <div class="card-modern">
                    <div class="card-body p-0">
                        @if($cliente->ventas->isEmpty())
                            <div class="empty-state"><i class="bi bi-receipt"></i><h5>Sin ventas</h5></div>
                        @else
                            <table class="table-modern" style="box-shadow:none;">
                                <thead><tr><th>Nº</th><th>Fecha</th><th>Método</th><th class="text-end">Total</th><th></th></tr></thead>
                                <tbody>
                                    @foreach($cliente->ventas->sortByDesc('fecha') as $v)
                                        <tr>
                                            <td><strong>{{ $v->numero }}</strong></td>
                                            <td>{{ $v->fecha->format('d/m/Y H:i') }}</td>
                                            <td>{{ ucfirst($v->metodo_pago) }}</td>
                                            <td class="text-end" style="font-weight:600;">{{ number_format($v->total, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}</td>
                                            <td class="text-end"><a href="{{ route('ventas.show', $v) }}" class="btn btn-sm btn-soft">Ver</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.nav-tabs-modern { border: 0; }
.nav-tabs-modern .nav-link {
    border: 0; color: var(--text-secondary); font-weight: 600;
    padding: 10px 18px; border-radius: 10px 10px 0 0;
}
.nav-tabs-modern .nav-link.active {
    background: var(--gradient-soft);
    color: var(--primary-dark);
    border-bottom: 3px solid var(--primary);
}
</style>
@endpush
@endsection

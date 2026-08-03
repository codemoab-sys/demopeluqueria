@extends('layouts.app')
@section('title', 'Bonos')
@section('page-title', 'Bonos de fidelización')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="d-flex gap-2">
        <a href="{{ route('tipos-bonos.index') }}" class="btn btn-soft"><i class="bi bi-tag"></i> Tipos de bonos</a>
        <form method="GET" class="d-flex gap-2">
            <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                <option value="activo" @selected(request('estado') === 'activo')>Activos</option>
                <option value="agotado" @selected(request('estado') === 'agotado')>Agotados</option>
                <option value="caducado" @selected(request('estado') === 'caducado')>Caducados</option>
            </select>
        </form>
    </div>
    <a href="{{ route('bonos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo bono</a>
</div>

<div class="card-modern">
    <div class="card-body p-0">
        @if($bonos->isEmpty())
            <div class="empty-state"><i class="bi bi-ticket-perforated"></i><h5>Sin bonos</h5><p>Crea bonos para fidelizar a tus clientes</p></div>
        @else
            <div class="table-responsive">
                <table class="table-modern" style="box-shadow:none;">
                    <thead><tr>
                        <th>Código</th><th>Cliente</th><th>Servicio</th>
                        <th>Sesiones</th><th>Compra</th><th>Caduca</th>
                        <th class="text-end">Precio</th><th>Estado</th><th></th>
                    </tr></thead>
                    <tbody>
                        @foreach($bonos as $b)
                            <tr>
                                <td><code style="color:var(--primary-dark);">{{ $b->codigo }}</code></td>
                                <td><a href="{{ route('clientes.show', $b->cliente) }}" style="font-weight:600;color:inherit;text-decoration:none;">{{ $b->cliente?->nombre_completo }}</a></td>
                                <td>{{ $b->servicio?->nombre ?? $b->tipoBono?->nombre ?? 'Genérico' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:60px;height:6px;background:#e9e5f0;border-radius:3px;overflow:hidden;">
                                            <div style="width:{{ $b->porcentaje_usado }}%;height:100%;background:var(--gradient-primary);"></div>
                                        </div>
                                        <small><strong>{{ $b->sesiones_usadas }}/{{ $b->sesiones_total }}</strong></small>
                                    </div>
                                </td>
                                <td>{{ $b->fecha_compra->format('d/m/Y') }}</td>
                                <td>{{ $b->fecha_caducidad?->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-end" style="font-weight:600;">{{ number_format($b->precio, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}</td>
                                <td><span class="badge-soft {{ $b->estado === 'activo' ? 'success' : ($b->estado === 'agotado' ? 'warning' : 'secondary') }}">{{ ucfirst($b->estado) }}</span></td>
                                <td class="text-end"><a href="{{ route('bonos.show', $b) }}" class="btn btn-sm btn-soft"><i class="bi bi-eye"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
<div class="mt-3">{{ $bonos->links() }}</div>
@endsection

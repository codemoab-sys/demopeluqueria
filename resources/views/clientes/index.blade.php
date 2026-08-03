@extends('layouts.app')
@section('title', 'Clientes')
@section('page-title', 'Clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form method="GET" class="d-flex" style="max-width:380px;flex:1;">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input type="search" name="q" id="busquedaClientes" class="form-control border-start-0" placeholder="Buscar por nombre, teléfono, email…" value="{{ request('q') }}">
        </div>
    </form>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> Nuevo cliente</a>
</div>

<div class="card-modern">
    <div class="card-body p-0">
        @if($clientes->isEmpty())
            <div class="empty-state"><i class="bi bi-people"></i><h5>Sin clientes</h5><p>Crea el primer cliente para empezar</p></div>
        @else
            <div class="table-responsive">
                <table class="table-modern" style="box-shadow:none;">
                    <thead><tr>
                        <th>Cliente</th>
                        <th>Contacto</th>
                        <th>Última visita</th>
                        <th class="text-center">Puntos</th>
                        <th class="text-end">Saldo</th>
                        <th></th>
                    </tr></thead>
                    <tbody>
                        @foreach($clientes as $c)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#a855f7,#ec4899);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">
                                        @if($c->foto)
                                            <img src="{{ asset('storage/' . $c->foto) }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                                        @else
                                            {{ strtoupper(substr($c->nombre, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight:600;">{{ $c->nombre_completo }}</div>
                                        <small class="text-muted">{{ $c->dni ?? 'Sin DNI' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($c->telefono)<div><i class="bi bi-telephone-fill text-muted me-1"></i>{{ $c->telefono }}</div>@endif
                                @if($c->email)<small class="text-muted"><i class="bi bi-envelope-fill me-1"></i>{{ $c->email }}</small>@endif
                            </td>
                            <td>{{ $c->ultima_visita ? $c->ultima_visita->format('d/m/Y') : '—' }}</td>
                            <td class="text-center"><span class="badge-soft pink">{{ $c->puntos_fidelidad }}</span></td>
                            <td class="text-end" style="font-weight:600;">{{ number_format($c->saldo, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}</td>
                            <td class="text-end">
                                <a href="{{ route('clientes.show', $c) }}" class="btn btn-sm btn-soft" title="Ver"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('clientes.edit', $c) }}" class="btn btn-sm btn-soft" title="Editar"><i class="bi bi-pencil"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
<div class="mt-3">{{ $clientes->links() }}</div>
@endsection

@push('scripts')
<script>
    const busquedaClientes = document.getElementById('busquedaClientes');
    if (busquedaClientes) {
        let timerBusqueda;
        busquedaClientes.addEventListener('input', () => {
            clearTimeout(timerBusqueda);
            timerBusqueda = setTimeout(() => busquedaClientes.form.submit(), 400);
        });
    }
</script>
@endpush

@extends('layouts.app')
@section('title', 'Usuarios')
@section('page-title', 'Gestión de usuarios')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <div></div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Nuevo usuario</a>
</div>
<div class="card-modern">
    <div class="card-body p-0">
        <table class="table-modern" style="box-shadow:none;">
            <thead><tr><th>Usuario</th><th>Email</th><th>Rol</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @foreach($usuarios as $u)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#a855f7,#ec4899);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                                <strong>{{ $u->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge-soft {{ $u->rol === 'admin' ? 'danger' : ($u->rol === 'gerente' ? 'warning' : 'primary') }}">{{ ucfirst($u->rol) }}</span></td>
                        <td><span class="badge-soft {{ $u->activo ? 'success' : 'secondary' }}">{{ $u->activo ? 'Activo' : 'Inactivo' }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i></a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('usuarios.destroy', $u) }}" class="d-inline" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

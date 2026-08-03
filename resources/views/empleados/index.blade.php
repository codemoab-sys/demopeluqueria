@extends('layouts.app')
@section('title', 'Equipo')
@section('page-title', 'Equipo de trabajo')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <div></div>
    <a href="{{ route('empleados.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Nuevo empleado</a>
</div>

<div class="row g-3">
    @forelse($empleados as $e)
    <div class="col-md-6 col-lg-4">
        <div class="card-modern h-100">
            <div class="card-body text-center">
                <div style="width:80px;height:80px;border-radius:50%;background:{{ $e->color }};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:30px;margin:0 auto 12px;overflow:hidden;">
                    @if($e->foto)<img src="{{ asset('storage/' . $e->foto) }}" style="width:100%;height:100%;object-fit:cover;">@else{{ strtoupper(substr($e->nombre, 0, 1)) }}@endif
                </div>
                <h5 style="font-weight:700;margin-bottom:2px;">{{ $e->nombre_completo }}</h5>
                <p class="text-muted small mb-2">{{ $e->cargo ?? 'Empleado' }}</p>
                @if($e->telefono)<p class="small mb-1"><i class="bi bi-telephone-fill text-primary"></i> {{ $e->telefono }}</p>@endif
                @if($e->email)<p class="small mb-2"><i class="bi bi-envelope-fill text-primary"></i> {{ $e->email }}</p>@endif
                <span class="badge-soft {{ $e->activo ? 'success' : 'secondary' }}">{{ $e->activo ? 'Activo' : 'Inactivo' }}</span>
                <hr>
                <div class="d-flex gap-2">
                    <a href="{{ route('empleados.edit', $e) }}" class="btn btn-soft flex-grow-1"><i class="bi bi-pencil"></i> Editar</a>
                    <form method="POST" action="{{ route('empleados.destroy', $e) }}" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')<button class="btn btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="empty-state"><i class="bi bi-people"></i><h5>Sin empleados</h5></div></div>
    @endforelse
</div>
<div class="mt-3">{{ $empleados->links() }}</div>
@endsection

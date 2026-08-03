@extends('layouts.app')
@section('content')
<div class="card-modern" style="max-width:560px;margin:0 auto;">
    <div class="card-body text-center">
        <div style="width:120px;height:120px;border-radius:50%;background:{{ $empleado->color }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:48px;font-weight:800;margin:0 auto 16px;">
            {{ strtoupper(substr($empleado->nombre, 0, 1)) }}
        </div>
        <h3 style="font-weight:800;">{{ $empleado->nombre_completo }}</h3>
        <p class="text-muted">{{ $empleado->cargo ?? 'Empleado' }}</p>
        <a href="{{ route('empleados.edit', $empleado) }}" class="btn btn-primary">Editar</a>
    </div>
</div>
@endsection

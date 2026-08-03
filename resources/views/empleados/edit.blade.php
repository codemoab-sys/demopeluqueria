@extends('layouts.app')
@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title">Editar empleado</h5></div>
    <form method="POST" action="{{ route('empleados.update', $empleado) }}" enctype="multipart/form-data" class="card-body">
        @method('PUT')
        @include('empleados._form')
    </form>
</div>
@endsection

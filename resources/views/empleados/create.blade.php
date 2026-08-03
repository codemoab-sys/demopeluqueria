@extends('layouts.app')
@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title"><i class="bi bi-person-plus text-primary me-2"></i>Nuevo empleado</h5></div>
    <form method="POST" action="{{ route('empleados.store') }}" enctype="multipart/form-data" class="card-body">
        @include('empleados._form')
    </form>
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title">Editar servicio</h5></div>
    <form method="POST" action="{{ route('servicios.update', $servicio) }}" class="card-body">
        @method('PUT')
        @include('servicios._form')
    </form>
</div>
@endsection

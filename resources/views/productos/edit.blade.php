@extends('layouts.app')
@section('title', 'Editar ' . $producto->nombre)
@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title">Editar producto</h5></div>
    <form method="POST" action="{{ route('productos.update', $producto) }}" enctype="multipart/form-data" class="card-body">
        @method('PUT')
        @include('productos._form')
    </form>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Editar cliente')
@section('page-title', 'Editar cliente')

@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title"><i class="bi bi-pencil text-primary me-2"></i>Editar {{ $cliente->nombre_completo }}</h5></div>
    <form method="POST" action="{{ route('clientes.update', $cliente) }}" enctype="multipart/form-data" class="card-body">
        @method('PUT')
        @include('clientes._form')
    </form>
</div>
@endsection

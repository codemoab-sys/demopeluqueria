@extends('layouts.app')
@section('title', 'Nuevo cliente')
@section('page-title', 'Nuevo cliente')

@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title"><i class="bi bi-person-plus text-primary me-2"></i>Datos del cliente</h5></div>
    <form method="POST" action="{{ route('clientes.store') }}" enctype="multipart/form-data" class="card-body">
        @include('clientes._form')
    </form>
</div>
@endsection

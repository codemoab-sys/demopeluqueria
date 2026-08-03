@extends('layouts.app')
@section('title', 'Nuevo producto')
@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title"><i class="bi bi-box-seam text-primary me-2"></i>Nuevo producto</h5></div>
    <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data" class="card-body">
        @include('productos._form')
    </form>
</div>
@endsection

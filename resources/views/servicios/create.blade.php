@extends('layouts.app')
@section('content')
<div class="card-modern">
    <div class="card-header"><h5 class="card-title"><i class="bi bi-stars text-primary me-2"></i>Nuevo servicio</h5></div>
    <form method="POST" action="{{ route('servicios.store') }}" class="card-body">
        @include('servicios._form')
    </form>
</div>
@endsection

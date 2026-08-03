@extends('layouts.app')
@section('title', 'Nuevo tipo de bono')

@section('content')
    <div class="card-modern">
        <div class="card-header"><h5 class="card-title">Nuevo tipo de bono</h5></div>
        <form method="POST" action="{{ route('tipos-bonos.store') }}" class="card-body">
            @csrf
            @include('bonos.tipos._form')
            <div class="mt-3"><input type="hidden" name="activo" value="0"><div class="form-check"><input type="checkbox" name="activo" value="1" class="form-check-input" id="ac" checked><label for="ac" class="form-check-label">Activo</label></div></div>
        </form>
    </div>
@endsection

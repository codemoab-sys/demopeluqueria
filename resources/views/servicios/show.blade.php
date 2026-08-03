@extends('layouts.app')
@section('content')
<div class="card-modern" style="max-width:680px;margin:0 auto;">
    <div class="card-body">
        <h3 style="font-weight:800;">{{ $servicio->nombre }}</h3>
        <p class="text-muted">{{ $servicio->descripcion }}</p>
        <hr>
        <p><strong>Duración:</strong> {{ $servicio->duracion_formateada }}</p>
        <p><strong>Precio:</strong> {{ number_format($servicio->precio, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}</p>
        <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-primary">Editar</a>
    </div>
</div>
@endsection

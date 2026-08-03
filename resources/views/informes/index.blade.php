@extends('layouts.app')
@section('title', 'Informes')
@section('page-title', 'Informes y estadísticas')

@section('content')
<div class="row g-3">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('informes.ventas') }}" class="card-modern d-block text-decoration-none text-dark">
            <div class="card-body text-center">
                <div class="kpi-icon primary mx-auto"><i class="bi bi-receipt"></i></div>
                <h5 style="font-weight:700;">Ventas</h5>
                <p class="text-muted small mb-0">Análisis de ventas, ingresos por método y por período</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('informes.clientes') }}" class="card-modern d-block text-decoration-none text-dark">
            <div class="card-body text-center">
                <div class="kpi-icon pink mx-auto"><i class="bi bi-people"></i></div>
                <h5 style="font-weight:700;">Clientes</h5>
                <p class="text-muted small mb-0">Top clientes por gasto, frecuencia y fidelización</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('informes.empleados') }}" class="card-modern d-block text-decoration-none text-dark">
            <div class="card-body text-center">
                <div class="kpi-icon info mx-auto"><i class="bi bi-person-badge"></i></div>
                <h5 style="font-weight:700;">Empleados</h5>
                <p class="text-muted small mb-0">Productividad y comisiones por empleado</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('informes.servicios') }}" class="card-modern d-block text-decoration-none text-dark">
            <div class="card-body text-center">
                <div class="kpi-icon success mx-auto"><i class="bi bi-stars"></i></div>
                <h5 style="font-weight:700;">Servicios</h5>
                <p class="text-muted small mb-0">Servicios más vendidos y rentables</p>
            </div>
        </a>
    </div>
</div>
@endsection

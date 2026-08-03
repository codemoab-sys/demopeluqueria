@extends('layouts.app')
@section('title', $producto->nombre)
@section('page-title', 'Detalle producto')

@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
<div class="row g-3">
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-body text-center">
                <div style="width:160px;height:160px;border-radius:16px;background:var(--gradient-soft);margin:0 auto 14px;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                    @if($producto->imagen)<img src="{{ asset('storage/' . $producto->imagen) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else<i class="bi bi-box-seam" style="font-size:60px;color:var(--primary-light);"></i>@endif
                </div>
                <h5 style="font-weight:700;">{{ $producto->nombre }}</h5>
                <p class="text-muted">{{ $producto->marca ?? '' }}</p>
                <h3 style="font-weight:800;color:var(--primary-dark);">{{ number_format($producto->precio_venta, 2, ',', '.') }} {{ $moneda }}</h3>
                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-soft w-100 mt-2"><i class="bi bi-pencil"></i> Editar</a>
            </div>
        </div>

        <div class="card-modern mt-3">
            <div class="card-body">
                <h6 style="font-weight:700;">Inventario</h6>
                <div class="row text-center mb-3">
                    <div class="col">
                        <small class="text-muted">Stock</small>
                        <h3 style="font-weight:800;color:{{ $producto->stock_bajo ? '#ef4444' : '#10b981' }};">{{ rtrim(rtrim($producto->stock, '0'), '.') }}</h3>
                    </div>
                    <div class="col">
                        <small class="text-muted">Mínimo</small>
                        <h3 style="font-weight:800;">{{ rtrim(rtrim($producto->stock_minimo, '0'), '.') }}</h3>
                    </div>
                </div>
                <hr>
                <form method="POST" action="{{ route('productos.ajuste-stock', $producto) }}">
                    @csrf
                    <h6 class="small mb-2" style="font-weight:700;">Ajuste rápido</h6>
                    <div class="d-flex gap-2 mb-2">
                        <select name="tipo" class="form-select form-select-sm">
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                            <option value="ajuste">Ajuste a</option>
                        </select>
                        <input type="number" step="0.01" name="cantidad" class="form-control form-control-sm" placeholder="Cantidad" required>
                    </div>
                    <input type="text" name="motivo" class="form-control form-control-sm mb-2" placeholder="Motivo (opcional)">
                    <button class="btn btn-primary btn-sm w-100">Aplicar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-modern">
            <div class="card-header"><h6 class="card-title">Movimientos de stock</h6></div>
            <div class="card-body p-0">
                @if($movimientos->isEmpty())
                    <div class="empty-state"><i class="bi bi-box-arrow-in-down"></i><p>Sin movimientos</p></div>
                @else
                    <table class="table-modern" style="box-shadow:none;">
                        <thead><tr><th>Fecha</th><th>Tipo</th><th>Cantidad</th><th>Stock antes</th><th>Stock después</th><th>Usuario</th></tr></thead>
                        <tbody>
                            @foreach($movimientos as $m)
                                <tr>
                                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge-soft {{ in_array($m->tipo, ['entrada', 'devolucion']) ? 'success' : 'warning' }}">{{ ucfirst($m->tipo) }}</span></td>
                                    <td>{{ rtrim(rtrim($m->cantidad, '0'), '.') }}</td>
                                    <td>{{ rtrim(rtrim($m->stock_anterior, '0'), '.') }}</td>
                                    <td><strong>{{ rtrim(rtrim($m->stock_nuevo, '0'), '.') }}</strong></td>
                                    <td>{{ $m->user?->name ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

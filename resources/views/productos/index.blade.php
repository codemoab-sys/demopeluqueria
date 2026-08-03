@extends('layouts.app')
@section('title', 'Productos')
@section('page-title', 'Productos y stock')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('categorias-productos.index') }}" class="btn btn-soft"><i class="bi bi-tags"></i> Categorías</a>
        <form method="GET" class="d-flex gap-2">
            <input type="search" name="q" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('q') }}">
            <label class="d-flex align-items-center gap-2 small">
                <input type="checkbox" name="bajo_stock" value="1" @checked(request('bajo_stock'))> Solo stock bajo
            </label>
            <button class="btn btn-sm btn-soft">Filtrar</button>
        </form>
    </div>
    <a href="{{ route('productos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo producto</a>
</div>

<div class="card-modern">
    <div class="card-body p-0">
        @if($productos->isEmpty())
            <div class="empty-state"><i class="bi bi-box-seam"></i><h5>Sin productos</h5></div>
        @else
            @php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
            <table class="table-modern" style="box-shadow:none;">
                <thead><tr><th>Producto</th><th>Categoría</th><th>Código</th><th class="text-end">Precio</th><th class="text-center">Stock</th><th></th></tr></thead>
                <tbody>
                    @foreach($productos as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:40px;height:40px;border-radius:8px;background:var(--gradient-soft);display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                        @if($p->imagen)<img src="{{ asset('storage/' . $p->imagen) }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else<i class="bi bi-box-seam text-primary"></i>@endif
                                    </div>
                                    <div>
                                        <div style="font-weight:600;">{{ $p->nombre }}</div>
                                        <small class="text-muted">{{ $p->marca ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $p->categoria?->nombre ?? '—' }}</td>
                            <td><code>{{ $p->codigo ?? '—' }}</code></td>
                            <td class="text-end" style="font-weight:600;">{{ number_format($p->precio_venta, 2, ',', '.') }} {{ $moneda }}</td>
                            <td class="text-center">
                                @if($p->controlar_stock)
                                    <span class="badge-soft {{ $p->stock_bajo ? 'danger' : 'success' }}">{{ rtrim(rtrim($p->stock, '0'), '.') }}</span>
                                @else
                                    <span class="badge-soft secondary">∞</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('productos.show', $p) }}" class="btn btn-sm btn-soft"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('productos.edit', $p) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
<div class="mt-3">{{ $productos->links() }}</div>
@endsection

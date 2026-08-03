@extends('layouts.app')
@section('title', 'TPV - Cobrar')
@section('page-title', 'TPV · Punto de venta')

@push('styles')
<style>
.tpv-grid { display: grid; grid-template-columns: 1fr 420px; gap: 16px; height: calc(100vh - 130px); }
@media (max-width: 1100px) { .tpv-grid { grid-template-columns: 1fr; height: auto; } }

.tpv-products { background: #fff; border-radius: var(--radius-lg); padding: 16px; box-shadow: var(--shadow-md); display: flex; flex-direction: column; min-height: 0; }
.tpv-tabs { display: flex; gap: 6px; margin-bottom: 12px; }
.tpv-tab {
    background: var(--bg-body); border: 0; padding: 9px 16px; border-radius: 10px;
    font-weight: 600; font-size: 13px; color: var(--text-secondary); cursor: pointer;
}
.tpv-tab.active { background: var(--gradient-primary); color: #fff; }
.tpv-search { position: relative; margin-bottom: 12px; }
.tpv-search input { padding-left: 40px; }
.tpv-search i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }

.cat-pills { display: flex; gap: 6px; margin-bottom: 12px; flex-wrap: wrap; }
.cat-pill {
    padding: 6px 12px; border-radius: 8px; background: var(--bg-body);
    color: var(--text-secondary); font-size: 12px; font-weight: 600;
    cursor: pointer; border: 0;
}
.cat-pill.active { background: var(--secondary); color: #fff; }

.product-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 12px; overflow-y: auto; flex: 1; padding: 4px 4px 16px;
}
.product-card {
    background: #ffffff;
    border: 2px solid #e9e5f0;
    border-radius: 14px;
    padding: 0;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    display: flex; flex-direction: column;
    min-height: 138px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
.product-card::before {
    content: '';
    display: block;
    height: 6px;
    width: 100%;
    background: var(--gradient-primary);
}
.product-card.servicio::before {
    background: linear-gradient(90deg, #a855f7 0%, #c084fc 100%);
}
.product-card.producto::before {
    background: linear-gradient(90deg, #ec4899 0%, #f9a8d4 100%);
}
.product-card .card-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    margin: 12px auto 6px;
    font-size: 18px;
    transition: transform 0.2s;
}
.product-card.servicio .card-icon {
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.15), rgba(192, 132, 252, 0.15));
    color: #7c3aed;
}
.product-card.producto .card-icon {
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.15), rgba(249, 168, 212, 0.15));
    color: #be185d;
}
.product-card .card-body-info {
    padding: 0 12px 12px;
    display: flex; flex-direction: column; gap: 6px;
    flex: 1;
    justify-content: space-between;
}
.product-card .nombre {
    font-weight: 700;
    font-size: 13px;
    color: #1f2937;
    line-height: 1.25;
    min-height: 32px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.product-card .duracion {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 700;
    color: #6b21a8;
    background: linear-gradient(135deg, #faf5ff, #fdf2f8);
    border: 1px solid #e9d5ff;
    padding: 3px 10px;
    border-radius: 20px;
    align-self: center;
    width: fit-content;
    letter-spacing: 0.2px;
}
.product-card.producto .duracion {
    color: #be185d;
    background: linear-gradient(135deg, #fdf2f8, #fce7f3);
    border-color: #fbcfe8;
}
.product-card .precio {
    font-weight: 800;
    color: #7c3aed;
    font-size: 16px;
    letter-spacing: -0.3px;
    margin-top: 2px;
}
.product-card.producto .precio {
    color: #be185d;
}
.product-card:hover {
    transform: translateY(-4px);
    border-color: #a855f7;
    box-shadow: 0 12px 28px rgba(168, 85, 247, 0.18);
}
.product-card.producto:hover {
    border-color: #ec4899;
    box-shadow: 0 12px 28px rgba(236, 72, 153, 0.18);
}
.product-card:hover .card-icon {
    transform: scale(1.1) rotate(-5deg);
}
.product-card:active {
    transform: translateY(-1px);
}

.tpv-cart { background: #fff; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); display: flex; flex-direction: column; }
.cart-header { padding: 16px; border-bottom: 1px solid var(--border-color); }
.cart-items { flex: 1; overflow-y: auto; padding: 8px 16px; min-height: 200px; }
.cart-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px; border-radius: 10px; margin-bottom: 6px;
    background: var(--bg-body);
}
.cart-item .info { flex: 1; min-width: 0; }
.cart-item .nombre { font-weight: 600; font-size: 13px; }
.cart-item .precio { font-size: 12px; color: var(--text-muted); }
.cart-item .total { font-weight: 700; color: var(--primary-dark); font-size: 14px; }
.qty-control { display: flex; align-items: center; gap: 4px; }
.qty-btn {
    width: 24px; height: 24px; border-radius: 6px;
    border: 0; background: #fff; color: var(--primary);
    font-weight: 700; cursor: pointer;
}
.cart-totals { padding: 16px; border-top: 1px solid var(--border-color); background: var(--gradient-soft); }
.cart-total-line { display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 13px; }
.cart-total-line.big { font-size: 22px; font-weight: 800; color: var(--primary-dark); margin-top: 8px; }

.payment-methods { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; margin: 12px 0; }
.payment-method {
    padding: 10px; border-radius: 10px; background: #fff; border: 1.5px solid var(--border-color);
    text-align: center; cursor: pointer; font-size: 12px; font-weight: 600;
}
.payment-method.active { background: var(--gradient-primary); color: #fff; border-color: transparent; }
.payment-method i { display: block; font-size: 18px; margin-bottom: 4px; }

[data-theme="dark"] .tpv-products,
[data-theme="dark"] .tpv-cart { background: #1e1e2a; }
[data-theme="dark"] .product-card {
    background: #1e1e2a;
    border-color: #2c2c3a;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
}
[data-theme="dark"] .product-card .nombre { color: #e5e7eb; }
[data-theme="dark"] .product-card .duracion {
    color: #c084fc;
    background: linear-gradient(135deg, #2a1c4a, #312e81);
    border-color: #3b2d6e;
}
[data-theme="dark"] .product-card.producto .duracion {
    color: #f9a8d4;
    background: linear-gradient(135deg, #3b1d2e, #4c1d3a);
    border-color: #7c2d5b;
}
[data-theme="dark"] .product-card:hover { border-color: #a855f7; }
[data-theme="dark"] .product-card.producto:hover { border-color: #ec4899; }
[data-theme="dark"] .cart-item { background: #232333; }
[data-theme="dark"] .qty-btn { background: #2c2c3a; }
[data-theme="dark"] .payment-method { background: #1a1a24; }
</style>
@endpush

@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp

@if(!$cajaAbierta)
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>La caja no está abierta. <a href="{{ route('caja.index') }}" class="alert-link">Abrir caja</a> antes de cobrar.
</div>
@endif

<div class="tpv-grid">
    <!-- Productos / Servicios -->
    <div class="tpv-products">
        <div class="tpv-tabs">
            <button class="tpv-tab active" onclick="cambiarTab('servicios', this)"><i class="bi bi-stars"></i> Servicios</button>
            <button class="tpv-tab" onclick="cambiarTab('productos', this)"><i class="bi bi-box-seam"></i> Productos</button>
        </div>

        <div class="tpv-search">
            <i class="bi bi-search"></i>
            <input type="text" id="searchTpv" class="form-control" placeholder="Buscar por nombre o código de barras...">
        </div>

        <div class="product-grid" id="gridServicios">
            @foreach($servicios as $s)
                <div class="product-card servicio" onclick='agregarItem("servicio", {{ $s->id }}, @json($s->nombre), {{ $s->precio }}, {{ $s->impuesto }})'>
                    <div class="card-icon"><i class="bi bi-stars"></i></div>
                    <div class="card-body-info">
                        <div class="nombre">{{ $s->nombre }}</div>
                        <div class="duracion"><i class="bi bi-clock-fill"></i> {{ $s->duracion_formateada }}</div>
                        <div class="precio">{{ number_format($s->precio, 2, ',', '.') }} {{ $moneda }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="product-grid" id="gridProductos" style="display:none;">
            @foreach($productos as $p)
                <div class="product-card producto" onclick='agregarItem("producto", {{ $p->id }}, @json($p->nombre), {{ $p->precio_venta }}, {{ $p->impuesto }})'>
                    <div class="card-icon"><i class="bi bi-box-seam-fill"></i></div>
                    <div class="card-body-info">
                        <div class="nombre">{{ $p->nombre }}</div>
                        <div class="duracion"><i class="bi bi-archive-fill"></i> Stock: {{ (int) $p->stock }}</div>
                        <div class="precio">{{ number_format($p->precio_venta, 2, ',', '.') }} {{ $moneda }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Carrito -->
    <div class="tpv-cart">
        <div class="cart-header">
            <h6 class="mb-2" style="font-weight:700;"><i class="bi bi-cart-fill text-primary"></i> Ticket actual</h6>
            <div class="d-flex gap-2">
                <select id="empleadoCobro" class="form-select form-select-sm">
                    <option value="">— Empleado —</option>
                    @foreach($empleados as $emp)<option value="{{ $emp->id }}">{{ $emp->nombre }}</option>@endforeach
                </select>
                <input type="text" id="clienteNombre" class="form-control form-control-sm" placeholder="Cliente (opcional)">
                <input type="hidden" id="clienteId">
            </div>
        </div>
        <div class="cart-items" id="cartItems">
            <div class="empty-state" id="cartEmpty"><i class="bi bi-cart-x"></i><p>Selecciona productos o servicios</p></div>
        </div>
        <div class="cart-totals">
            <div class="cart-total-line"><span>Subtotal</span><strong id="subtotal">0,00 {{ $moneda }}</strong></div>
            <div class="cart-total-line"><span>Impuestos</span><strong id="impuestos">0,00 {{ $moneda }}</strong></div>
            <div class="cart-total-line big"><span>Total</span><strong id="total">0,00 {{ $moneda }}</strong></div>

            <div class="payment-methods">
                <div class="payment-method active" data-metodo="efectivo"><i class="bi bi-cash"></i>Efectivo</div>
                <div class="payment-method" data-metodo="tarjeta"><i class="bi bi-credit-card"></i>Tarjeta</div>
                <div class="payment-method" data-metodo="yapeplin"><i class="bi bi-phone"></i>Yape/Plin</div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-6">
                    <label class="form-label small mb-1">Recibido</label>
                    <input type="number" id="recibido" class="form-control" step="0.01" value="0">
                </div>
                <div class="col-6">
                    <label class="form-label small mb-1">Vuelto</label>
                    <input type="text" id="cambio" class="form-control" readonly value="0,00 {{ $moneda }}">
                </div>
            </div>

            <button class="btn btn-primary w-100" onclick="cobrar()" {{ !$cajaAbierta ? 'disabled' : '' }}>
                <i class="bi bi-check-circle-fill"></i> COBRAR
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const moneda = '{{ $moneda }}';
let carrito = [];
let metodoPago = 'efectivo';

function cambiarTab(tipo, btn) {
    document.querySelectorAll('.tpv-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('gridServicios').style.display = tipo === 'servicios' ? 'grid' : 'none';
    document.getElementById('gridProductos').style.display = tipo === 'productos' ? 'grid' : 'none';
}

function fmt(n) { return Number(n).toFixed(2).replace('.', ',') + ' ' + moneda; }

function agregarItem(tipo, id, nombre, precio, impuesto) {
    const idx = carrito.findIndex(i => i.tipo === tipo && i.referencia_id === id);
    if (idx >= 0) {
        carrito[idx].cantidad += 1;
    } else {
        carrito.push({ tipo, referencia_id: id, nombre, precio: parseFloat(precio), impuesto: parseFloat(impuesto), cantidad: 1, descuento: 0 });
    }
    render();
}

function cambiarCantidad(idx, delta) {
    carrito[idx].cantidad += delta;
    if (carrito[idx].cantidad <= 0) carrito.splice(idx, 1);
    render();
}

function quitar(idx) { carrito.splice(idx, 1); render(); }

function render() {
    const cont = document.getElementById('cartItems');
    if (carrito.length === 0) {
        cont.innerHTML = '<div class="empty-state"><i class="bi bi-cart-x"></i><p>Selecciona productos o servicios</p></div>';
    } else {
        cont.innerHTML = carrito.map((it, i) => `
            <div class="cart-item">
                <div class="info">
                    <div class="nombre">${it.nombre}</div>
                    <div class="precio">${fmt(it.precio)} c/u</div>
                </div>
                <div class="qty-control">
                    <button class="qty-btn" onclick="cambiarCantidad(${i}, -1)">−</button>
                    <strong>${it.cantidad}</strong>
                    <button class="qty-btn" onclick="cambiarCantidad(${i}, 1)">+</button>
                </div>
                <div class="total">${fmt(it.precio * it.cantidad)}</div>
                <button class="qty-btn" onclick="quitar(${i})" title="Quitar"><i class="bi bi-x"></i></button>
            </div>
        `).join('');
    }
    let total = 0, impTotal = 0;
    carrito.forEach(it => {
        const linea = it.precio * it.cantidad;
        const imp = linea - (linea / (1 + it.impuesto / 100));
        total += linea;
        impTotal += imp;
    });
    document.getElementById('subtotal').textContent = fmt(total - impTotal);
    document.getElementById('impuestos').textContent = fmt(impTotal);
    document.getElementById('total').textContent = fmt(total);
    actualizarCambio();
}

document.querySelectorAll('.payment-method').forEach(p => {
    p.addEventListener('click', () => {
        document.querySelectorAll('.payment-method').forEach(x => x.classList.remove('active'));
        p.classList.add('active');
        metodoPago = p.dataset.metodo;
    });
});

document.getElementById('recibido').addEventListener('input', actualizarCambio);

function actualizarCambio() {
    const total = carrito.reduce((s, it) => s + it.precio * it.cantidad, 0);
    const recibido = parseFloat(document.getElementById('recibido').value) || 0;
    document.getElementById('cambio').value = fmt(Math.max(0, recibido - total));
}

function cobrar() {
    if (carrito.length === 0) return alert('Añade items al ticket');
    const total = carrito.reduce((s, it) => s + it.precio * it.cantidad, 0);
    const recibido = parseFloat(document.getElementById('recibido').value) || total;

    const btn = document.querySelector('.btn[onclick="cobrar()"]');
    if (btn) btn.disabled = true;

    fetch('{{ route("tpv.cobrar") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({
            cliente_id: document.getElementById('clienteId').value || null,
            empleado_id: document.getElementById('empleadoCobro').value || null,
            items: carrito.map(it => ({
                tipo: it.tipo, referencia_id: it.referencia_id,
                cantidad: it.cantidad, precio: it.precio, descuento: 0,
            })),
            metodo_pago: metodoPago,
            importe_pagado: recibido,
        }),
    })
    .then(r => r.json().catch(() => ({ error: 'El servidor no devolvió una respuesta válida. Inténtalo de nuevo.' })))
    .then(res => {
        if (btn) btn.disabled = false;
        if (res.error) { alert(res.error); return; }
        if (!res.success) { alert(res.message || 'No se pudo registrar la venta.'); return; }
        Swal.fire({
            title: 'Venta registrada',
            html: `Venta <strong>${res.numero}</strong> por <strong>${fmt(res.total)}</strong>. ¿Imprimir ticket?`,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Sí, imprimir',
            cancelButtonText: 'No',
            confirmButtonColor: '#8b5cf6',
        }).then(result => {
            if (result.isConfirmed) {
                window.open(`/ventas/${res.venta_id}/ticket`, '_blank');
            }
            carrito = [];
            document.getElementById('recibido').value = 0;
            render();
        });
    })
    .catch(() => {
        if (btn) btn.disabled = false;
        alert('Error de conexión al registrar la venta.');
    });
}

// Búsqueda
document.getElementById('searchTpv').addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(c => {
        c.style.display = c.querySelector('.nombre').textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush
@endsection

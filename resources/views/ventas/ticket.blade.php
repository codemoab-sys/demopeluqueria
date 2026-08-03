<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket {{ $venta->numero }}</title>
<style>
@page { size: 80mm auto; margin: 4mm; }
body { font-family: 'Courier New', monospace; font-size: 11px; max-width: 280px; margin: 0 auto; padding: 8px; color: #000; }
.center { text-align: center; }
.right { text-align: right; }
.bold { font-weight: bold; }
hr { border: 0; border-top: 1px dashed #000; margin: 6px 0; }
table { width: 100%; border-collapse: collapse; }
td { padding: 1px 0; vertical-align: top; }
.logo { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
.totales td { padding: 2px 0; }
.totales .label { font-weight: bold; }
.total-final { font-size: 14px; font-weight: bold; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 4px 0; }
@media print { body { padding: 0; } .no-print { display: none; } }
</style>
</head>
<body>
<div class="center">
    <div class="logo">{{ $empresaConfig->nombre ?? 'TPV PELUQUERÍA' }}</div>
    @if($empresaConfig?->cif)<div>CIF: {{ $empresaConfig->cif }}</div>@endif
    @if($empresaConfig?->direccion)<div>{{ $empresaConfig->direccion }}</div>@endif
    @if($empresaConfig?->ciudad)<div>{{ $empresaConfig->ciudad }}@if($empresaConfig?->codigo_postal), {{ $empresaConfig->codigo_postal }}@endif</div>@endif
    @if($empresaConfig?->telefono)<div>Tel: {{ $empresaConfig->telefono }}</div>@endif
</div>
<hr>
<table>
    <tr><td>Nº ticket:</td><td class="right bold">{{ $venta->numero }}</td></tr>
    <tr><td>Fecha:</td><td class="right">{{ $venta->fecha->format('d/m/Y H:i') }}</td></tr>
    @if($venta->cliente)<tr><td>Cliente:</td><td class="right">{{ $venta->cliente->nombre_completo }}</td></tr>@endif
    @if($venta->empleado)<tr><td>Atendió:</td><td class="right">{{ $venta->empleado->nombre }}</td></tr>@endif
</table>
<hr>
<table>
    @foreach($venta->detalles as $d)
        <tr><td colspan="2" class="bold">{{ $d->concepto }}</td></tr>
        <tr>
            <td>{{ rtrim(rtrim($d->cantidad, '0'), '.') }} x {{ number_format($d->precio_unitario, 2, ',', '.') }}</td>
            <td class="right">{{ number_format($d->total, 2, ',', '.') }}</td>
        </tr>
    @endforeach
</table>
<hr>
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp
<table class="totales">
    <tr><td class="label">Subtotal:</td><td class="right">{{ number_format($venta->subtotal, 2, ',', '.') }} {{ $moneda }}</td></tr>
    <tr><td class="label">IGV:</td><td class="right">{{ number_format($venta->impuesto, 2, ',', '.') }} {{ $moneda }}</td></tr>
    @if($venta->descuento > 0)<tr><td class="label">Descuento:</td><td class="right">-{{ number_format($venta->descuento, 2, ',', '.') }} {{ $moneda }}</td></tr>@endif
    <tr class="total-final"><td>TOTAL:</td><td class="right">{{ number_format($venta->total, 2, ',', '.') }} {{ $moneda }}</td></tr>
    <tr><td class="label">Pagado ({{ ucfirst($venta->metodo_pago) }}):</td><td class="right">{{ number_format($venta->importe_pagado, 2, ',', '.') }} {{ $moneda }}</td></tr>
    @if($venta->cambio > 0)<tr><td class="label">Vuelto:</td><td class="right">{{ number_format($venta->cambio, 2, ',', '.') }} {{ $moneda }}</td></tr>@endif
</table>
<hr>
@if($empresaConfig?->mensaje_ticket)
<div class="center">{{ $empresaConfig->mensaje_ticket }}</div>
@else
<div class="center">¡Gracias por tu visita!<br>Te esperamos pronto</div>
@endif

<div class="no-print center" style="margin-top:14px;">
    <button onclick="window.print()" style="padding:8px 16px;background:#a855f7;color:#fff;border:0;border-radius:6px;font-weight:600;cursor:pointer;">🖨 Imprimir</button>
    <button onclick="window.close()" style="padding:8px 16px;background:#e5e7eb;border:0;border-radius:6px;cursor:pointer;margin-left:6px;">Cerrar</button>
</div>
<script>setTimeout(() => window.print(), 400);</script>
</body>
</html>

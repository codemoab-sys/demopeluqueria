@extends('layouts.app')
@section('title', 'Caja')
@section('page-title', 'Caja')

@section('content')
@php $moneda = $empresaConfig->simbolo_moneda ?? 'S/.'; @endphp

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('caja.historial') }}" class="btn btn-soft"><i class="bi bi-clock-history"></i> Ver historial</a>
</div>

@if(!$cajaAbierta)
    <div class="card-modern" style="max-width:560px;margin:40px auto;">
        <div class="card-body text-center p-5">
            <div class="kpi-icon warning mx-auto mb-3" style="width:80px;height:80px;font-size:36px;"><i class="bi bi-safe2"></i></div>
            <h4 style="font-weight:800;">Caja sin abrir</h4>
            <p class="text-muted mb-4">Abre la caja para empezar a registrar ventas y movimientos del día.</p>
            <form method="POST" action="{{ route('caja.abrir') }}" class="text-start">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Importe inicial en caja</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="importe_inicial" class="form-control" value="0.00" required>
                        <span class="input-group-text">{{ $moneda }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notas (opcional)</label>
                    <textarea name="notas_apertura" class="form-control" rows="2"></textarea>
                </div>
                <button class="btn btn-primary w-100"><i class="bi bi-unlock-fill"></i> Abrir caja</button>
            </form>
        </div>
    </div>
@else
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card-modern" style="background:var(--gradient-primary);color:#fff;border:0;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small style="opacity:0.85;">CAJA ABIERTA</small>
                            <h3 style="font-weight:800;margin:4px 0;">Sesión #{{ $cajaAbierta->id }}</h3>
                            <small style="opacity:0.85;">Abierta por {{ $cajaAbierta->userApertura->name }} · {{ $cajaAbierta->fecha_apertura->format('d/m/Y H:i') }}</small>
                        </div>
                        <i class="bi bi-safe2-fill" style="font-size:60px;opacity:0.4;"></i>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-6 col-md-3">
                    <div class="kpi-card gradient-green">
                        <div class="kpi-icon"><i class="bi bi-cash"></i></div>
                        <div class="kpi-label">Efectivo</div>
                        <div class="kpi-value" style="font-size:18px;">{{ number_format($totales['efectivo'] ?? 0, 2, ',', '.') }} {{ $moneda }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card gradient-blue">
                        <div class="kpi-icon"><i class="bi bi-credit-card"></i></div>
                        <div class="kpi-label">Tarjeta</div>
                        <div class="kpi-value" style="font-size:18px;">{{ number_format($totales['tarjeta'] ?? 0, 2, ',', '.') }} {{ $moneda }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card gradient-pink">
                        <div class="kpi-icon"><i class="bi bi-phone"></i></div>
                        <div class="kpi-label">Yape/Plin</div>
                        <div class="kpi-value" style="font-size:18px;">{{ number_format($totales['yapeplin'] ?? 0, 2, ',', '.') }} {{ $moneda }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card gradient-violet">
                        <div class="kpi-icon"><i class="bi bi-bank"></i></div>
                        <div class="kpi-label">Transfer.</div>
                        <div class="kpi-value" style="font-size:18px;">{{ number_format($totales['transferencia'] ?? 0, 2, ',', '.') }} {{ $moneda }}</div>
                    </div>
                </div>
            </div>

            <div class="card-modern mt-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-list-ul me-2"></i>Movimientos de hoy</h6></div>
                <div class="card-body p-0">
                    @if($cajaAbierta->movimientos->isEmpty())
                        <div class="empty-state"><i class="bi bi-receipt"></i><p>Sin movimientos aún</p></div>
                    @else
                        <table class="table-modern" style="box-shadow:none;">
                            <thead><tr><th>Hora</th><th>Tipo</th><th>Concepto</th><th>Método</th><th>Usuario</th><th class="text-end">Importe</th></tr></thead>
                            <tbody>
                                @foreach($cajaAbierta->movimientos->sortByDesc('fecha') as $m)
                                    <tr>
                                        <td>{{ $m->fecha->format('H:i') }}</td>
                                        <td><span class="badge-soft {{ in_array($m->tipo, ['venta', 'ingreso', 'apertura']) ? 'success' : 'warning' }}">{{ ucfirst($m->tipo) }}</span></td>
                                        <td>{{ $m->concepto }}</td>
                                        <td>{{ $m->metodo_pago === 'yapeplin' ? 'Yape/Plin' : ucfirst($m->metodo_pago) }}</td>
                                        <td>{{ $m->user->name }}</td>
                                        <td class="text-end" style="font-weight:600;color:{{ in_array($m->tipo, ['gasto']) ? '#ef4444' : '#10b981' }};">
                                            {{ in_array($m->tipo, ['gasto']) ? '-' : '+' }}{{ number_format($m->importe, 2, ',', '.') }} {{ $moneda }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-modern">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-plus-circle me-2"></i>Movimiento manual</h6></div>
                <form method="POST" action="{{ route('caja.movimiento') }}" class="card-body">
                    @csrf
                    <input type="hidden" name="caja_id" value="{{ $cajaAbierta->id }}">
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select" required>
                            <option value="ingreso">Ingreso</option>
                            <option value="gasto">Gasto</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Importe</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="importe" class="form-control" required>
                            <span class="input-group-text">{{ $moneda }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Método pago</label>
                        <select name="metodo_pago" class="form-select" required>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="yapeplin">Yape/Plin</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <input type="text" name="concepto" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2"></textarea>
                    </div>
                    <button class="btn btn-primary w-100">Registrar</button>
                </form>
            </div>

            <div class="card-modern mt-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-lock-fill me-2"></i>Cerrar caja</h6></div>
                <form method="POST" action="{{ route('caja.cerrar') }}" class="card-body" onsubmit="return confirm('¿Cerrar la caja? No podrás registrar más ventas hasta abrir una nueva.')">
                    @csrf
                    <input type="hidden" name="caja_id" value="{{ $cajaAbierta->id }}">
                    <div class="mb-3">
                        <label class="form-label">Efectivo contado</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="importe_final" class="form-control" required>
                            <span class="input-group-text">{{ $moneda }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notas cierre</label>
                        <textarea name="notas_cierre" class="form-control" rows="2"></textarea>
                    </div>
                    <button class="btn btn-danger w-100"><i class="bi bi-lock"></i> Cerrar caja</button>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

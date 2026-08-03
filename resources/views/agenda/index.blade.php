@extends('layouts.app')
@section('title', 'Agenda')
@section('page-title', 'Agenda y citas')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
.agenda-wrapper { display: grid; grid-template-columns: 280px 1fr; gap: 20px; }
@media (max-width: 991px) { .agenda-wrapper { grid-template-columns: 1fr; } }

.empleado-chip {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 10px;
    background: var(--bg-body); margin-bottom: 6px;
    cursor: pointer; transition: all 0.2s;
    border: 1.5px solid transparent;
}
.empleado-chip:hover { background: var(--gradient-soft); }
.empleado-chip input { display: none; }
.empleado-chip input:checked ~ .check-mark { background: var(--primary); border-color: var(--primary); color: #fff; }
.empleado-color {
    width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0;
}
.check-mark {
    width: 18px; height: 18px; border: 2px solid #d1d5db; border-radius: 4px;
    margin-left: auto; display: flex; align-items: center; justify-content: center;
    color: transparent; font-size: 11px; transition: all 0.2s;
}
.empleado-chip input:checked ~ .check-mark { color: #fff; }

#calendar { background: #fff; border-radius: var(--radius-lg); padding: 18px; box-shadow: var(--shadow-md); }
.fc .fc-button {
    background: var(--bg-body) !important;
    color: var(--text-secondary) !important;
    border: 0 !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    text-transform: capitalize !important;
    padding: 6px 14px !important;
}
.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):hover {
    background: var(--gradient-primary) !important;
    color: #fff !important;
}
.fc .fc-toolbar-title { font-weight: 800; color: var(--text-primary); }
.fc .fc-col-header-cell { background: var(--gradient-soft); font-weight: 700; }
.fc-event { border-radius: 6px !important; padding: 2px 4px !important; font-weight: 600 !important; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <button class="btn btn-primary" onclick="abrirNuevaCita()"><i class="bi bi-plus-lg"></i> Nueva cita</button>
</div>

<div class="agenda-wrapper">
    <!-- Sidebar empleados -->
    <aside>
        <div class="card-modern mb-3">
            <div class="card-header"><h6 class="card-title mb-0"><i class="bi bi-people me-2"></i>Empleados</h6></div>
            <div class="card-body">
                @foreach($empleados as $emp)
                <label class="empleado-chip">
                    <input type="checkbox" class="filtro-empleado" value="{{ $emp->id }}" checked>
                    <span class="empleado-color" style="background:{{ $emp->color }};"></span>
                    <span>{{ $emp->nombre }}</span>
                    <span class="check-mark"><i class="bi bi-check-lg"></i></span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="card-modern">
            <div class="card-header"><h6 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Estados</h6></div>
            <div class="card-body">
                <div class="d-flex flex-column gap-2 small">
                    <span><span class="badge-soft warning">Pendiente</span></span>
                    <span><span class="badge-soft info">Confirmada</span></span>
                    <span><span class="badge-soft primary">En curso</span></span>
                    <span><span class="badge-soft success">Finalizada</span></span>
                    <span><span class="badge-soft secondary">Cancelada</span></span>
                    <span><span class="badge-soft danger">No asistió</span></span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Calendario -->
    <div id="calendar"></div>
</div>

<!-- Modal nueva cita -->
<div class="modal fade" id="modalCita" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:18px;border:0;">
            <div class="modal-header" style="border-bottom:1px solid var(--border-color);">
                <h5 class="modal-title"><i class="bi bi-calendar-plus text-primary me-2"></i><span id="modalTitulo">Nueva cita</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCita">
                <div class="modal-body">
                    <input type="hidden" id="cita_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Cliente</label>
                            <select id="cliente_id" class="form-select"></select>
                            <small class="text-muted">Escribe dentro del select para buscar</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profesional</label>
                            <select id="empleado_id" class="form-select">
                                <option value="">— Asignar —</option>
                                @foreach($empleados as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha</label>
                            <input type="date" id="fecha" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora inicio</label>
                            <input type="time" id="hora_inicio" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Servicios</label>
                            <select id="servicios" class="form-select" multiple size="6" required>
                                @foreach($servicios as $s)
                                    <option value="{{ $s->id }}" data-duracion="{{ $s->duracion }}" data-precio="{{ $s->precio }}">
                                        {{ $s->nombre }} · {{ $s->duracion_formateada }} · {{ number_format($s->precio, 2, ',', '.') }} {{ $empresaConfig->simbolo_moneda ?? 'S/.' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Mantén Ctrl o Cmd para seleccionar varios</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select id="estado" class="form-select">
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="en_curso">En curso</option>
                                <option value="finalizada">Finalizada</option>
                                <option value="cancelada">Cancelada</option>
                                <option value="no_asistio">No asistió</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="card-modern flex-grow-1" style="background:var(--gradient-soft);box-shadow:none;">
                                <div class="card-body p-3">
                                    <small class="text-muted d-block">Resumen</small>
                                    <strong id="resumenDuracion">0 min</strong> · <strong id="resumenPrecio">0,00 S/.</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas</label>
                            <textarea id="notas" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border-color);">
                    <button type="button" id="btnEliminar" class="btn btn-danger" style="display:none;"><i class="bi bi-trash"></i> Eliminar</button>
                    <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Guardar cita</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
let calendar;
let modal;
let clienteSelect;

document.addEventListener('DOMContentLoaded', () => {
    modal = new bootstrap.Modal(document.getElementById('modalCita'));
    clienteSelect = new TomSelect('#cliente_id', {
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        create: false,
        maxOptions: 25,
        preload: true,
        placeholder: 'Buscar cliente por nombre, teléfono o email',
        render: {
            option: function(data, escape) {
                return `<div>${escape(data.text)}</div>`;
            },
            item: function(data, escape) {
                return `<div>${escape(data.text)}</div>`;
            },
            no_results: function() {
                return '<div class="p-2 text-muted small">No se encontraron clientes</div>';
            },
        },
        load: function(query, callback) {
            fetch(`{{ route('agenda.clientes') }}?q=${encodeURIComponent(query)}`)
                .then(r => {
                    if (!r.ok) throw new Error('Error');
                    return r.json();
                })
                .then(data => callback(data))
                .catch(() => callback());
        }
    });
    const calEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        slotMinTime: '{{ \Carbon\Carbon::parse($empresaConfig->hora_apertura ?? "09:00")->format("H:i:s") }}',
        slotMaxTime: '{{ \Carbon\Carbon::parse($empresaConfig->hora_cierre ?? "20:00")->format("H:i:s") }}',
        slotDuration: '00:{{ str_pad($empresaConfig->intervalo_citas ?? 15, 2, "0", STR_PAD_LEFT) }}:00',
        firstDay: 1,
        nowIndicator: true,
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
        buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día' },
        height: 'auto',
        events: '{{ route("agenda.eventos") }}',
        dateClick: function(info) {
            abrirNuevaCita(info.dateStr);
        },
        eventClick: function(info) {
            cargarCita(info.event.id);
        },
        eventDrop: function(info) {
            actualizarFechaCita(info.event);
        },
        eventResize: function(info) {
            actualizarFechaCita(info.event);
        },
        editable: true,
    });
    calendar.render();

    // Resumen al seleccionar servicios
    document.getElementById('servicios').addEventListener('change', actualizarResumen);

    // Form submit
    document.getElementById('formCita').addEventListener('submit', guardarCita);
    document.getElementById('btnEliminar').addEventListener('click', eliminarCita);

    // Filtros empleados
    document.querySelectorAll('.filtro-empleado').forEach(c => c.addEventListener('change', () => calendar.refetchEvents()));
});

function abrirNuevaCita(fecha = null) {
    document.getElementById('formCita').reset();
    document.getElementById('cita_id').value = '';
    document.getElementById('btnEliminar').style.display = 'none';
    document.getElementById('modalTitulo').textContent = 'Nueva cita';
    clienteSelect.clear(true);
    if (fecha) {
        const d = new Date(fecha);
        document.getElementById('fecha').value = d.toISOString().slice(0, 10);
        if (fecha.includes('T')) {
            document.getElementById('hora_inicio').value = fecha.slice(11, 16);
        }
    } else {
        document.getElementById('fecha').value = new Date().toISOString().slice(0, 10);
    }
    actualizarResumen();
    modal.show();
}

function cargarCita(id) {
    fetch('{{ route("agenda.citas.show", ["cita" => "ID_PLACEHOLDER"]) }}'.replace('ID_PLACEHOLDER', id))
        .then(r => r.json())
        .then(c => {
            document.getElementById('cita_id').value = c.id;
            clienteSelect.clear(true);
            if (c.cliente) {
                const text = `${c.cliente.nombre_completo}${c.cliente.telefono ? ' · ' + c.cliente.telefono : ''}`;
                clienteSelect.addOption({ id: c.cliente.id, text });
                clienteSelect.setValue(c.cliente.id);
            }
            document.getElementById('empleado_id').value = c.empleado_id ?? '';
            document.getElementById('fecha').value = c.fecha.slice(0, 10);
            document.getElementById('hora_inicio').value = c.hora_inicio.slice(0, 5);
            document.getElementById('estado').value = c.estado;
            document.getElementById('notas').value = c.notas ?? '';
            const sel = document.getElementById('servicios');
            [...sel.options].forEach(o => o.selected = c.servicios.some(s => s.servicio_id == o.value));
            document.getElementById('btnEliminar').style.display = 'inline-block';
            document.getElementById('modalTitulo').textContent = 'Editar cita';
            actualizarResumen();
            modal.show();
        });
}

function actualizarResumen() {
    const sel = document.getElementById('servicios');
    let dur = 0, prec = 0;
    [...sel.selectedOptions].forEach(o => {
        dur += parseInt(o.dataset.duracion);
        prec += parseFloat(o.dataset.precio);
    });
    document.getElementById('resumenDuracion').textContent = dur + ' min';
    document.getElementById('resumenPrecio').textContent = prec.toFixed(2).replace('.', ',') + ' {{ $empresaConfig->simbolo_moneda ?? "S/." }}';
}

function guardarCita(e) {
    e.preventDefault();
    const id = document.getElementById('cita_id').value;
    const servicios = [...document.getElementById('servicios').selectedOptions].map(o => o.value);
    const data = {
        cliente_id: clienteSelect.getValue() || null,
        empleado_id: document.getElementById('empleado_id').value || null,
        fecha: document.getElementById('fecha').value,
        hora_inicio: document.getElementById('hora_inicio').value,
        servicios,
        estado: document.getElementById('estado').value,
        notas: document.getElementById('notas').value,
    };
    const url = id ? '{{ route("agenda.citas.update", ["cita" => "ID_PLACEHOLDER"]) }}'.replace('ID_PLACEHOLDER', id) : '{{ route("agenda.citas.store") }}';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify(data),
    }).then(r => r.json()).then(res => {
        if (res.success) {
            modal.hide();
            calendar.refetchEvents();
        } else {
            alert('Error al guardar la cita');
        }
    });
}

function eliminarCita() {
    if (!confirm('¿Eliminar esta cita?')) return;
    const id = document.getElementById('cita_id').value;
    fetch('{{ route("agenda.citas.destroy", ["cita" => "ID_PLACEHOLDER"]) }}'.replace('ID_PLACEHOLDER', id), {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    }).then(r => r.json()).then(() => {
        modal.hide();
        calendar.refetchEvents();
    });
}

function actualizarFechaCita(event) {
    fetch('{{ route("agenda.citas.update", ["cita" => "ID_PLACEHOLDER"]) }}'.replace('ID_PLACEHOLDER', event.id), {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({
            fecha: event.start.toISOString().slice(0, 10),
            hora_inicio: event.start.toTimeString().slice(0, 8),
        }),
    });
}
</script>
@endpush
@endsection

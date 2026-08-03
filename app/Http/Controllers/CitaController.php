<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\CitaServicio;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CitaController extends Controller
{
    public function index()
    {
        $empresaId = auth()->user()->empresa_id;
        $empleados = Empleado::where('empresa_id', $empresaId)->where('activo', true)->get();
        $servicios = Servicio::where('empresa_id', $empresaId)->where('activo', true)->with('categoria')->get();
        return view('agenda.index', compact('empleados', 'servicios'));
    }

    public function eventos(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $start = Carbon::parse($request->input('start', now()->startOfMonth()));
        $end = Carbon::parse($request->input('end', now()->endOfMonth()));

        $citas = Cita::where('empresa_id', $empresaId)
            ->whereBetween('fecha', [$start, $end])
            ->with(['cliente', 'empleado', 'servicios.servicio'])
            ->get();

        $eventos = $citas->map(function ($c) {
            $serviciosTxt = $c->servicios->map(fn($s) => $s->servicio?->nombre)->filter()->join(', ');
            return [
                'id' => $c->id,
                'title' => ($c->cliente?->nombre_completo ?? 'Sin cliente') . ' - ' . $serviciosTxt,
                'start' => $c->fecha->format('Y-m-d') . 'T' . $c->hora_inicio,
                'end' => $c->fecha->format('Y-m-d') . 'T' . $c->hora_fin,
                'backgroundColor' => $c->color ?? $c->empleado?->color ?? '#a855f7',
                'borderColor' => $c->color ?? $c->empleado?->color ?? '#a855f7',
                'extendedProps' => [
                    'estado' => $c->estado,
                    'cliente' => $c->cliente?->nombre_completo,
                    'telefono' => $c->cliente?->telefono,
                    'empleado' => $c->empleado?->nombre,
                    'servicios' => $serviciosTxt,
                    'precio' => $c->precio_total,
                    'notas' => $c->notas,
                ],
            ];
        });

        return response()->json($eventos);
    }

    public function buscarClientes(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $q = trim((string) $request->input('q', ''));

        $clientes = Cliente::where('empresa_id', $empresaId)
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                    ->orWhere('apellidos', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('nombre')
            ->limit(25)
            ->get(['id', 'nombre', 'apellidos', 'telefono', 'email']);

        return response()->json($clientes->map(function ($cliente) {
            return [
                'id' => $cliente->id,
                'text' => $cliente->nombre_completo . ($cliente->telefono ? ' · ' . $cliente->telefono : ''),
            ];
        }));
    }

    public function store(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $data = $request->validate([
            'cliente_id' => ['nullable', Rule::exists('clientes', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'empleado_id' => ['nullable', Rule::exists('empleados', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'integer',
            'notas' => 'nullable|string',
            'estado' => 'nullable|in:pendiente,confirmada,en_curso,finalizada,cancelada,no_asistio',
        ]);

        $servicios = Servicio::where('empresa_id', $empresaId)->whereIn('id', $data['servicios'])->get();
        if ($servicios->count() !== count($data['servicios'])) {
            return response()->json(['error' => 'Uno o más servicios no pertenecen a tu empresa.'], 422);
        }

        $duracionTotal = $servicios->sum('duracion');
        $precioTotal = $servicios->sum('precio');
        $horaFin = Carbon::parse($data['hora_inicio'])->addMinutes($duracionTotal)->format('H:i:s');

        if ($data['empleado_id'] && $this->existeSolapamiento($empresaId, $data['empleado_id'], $data['fecha'], $data['hora_inicio'], $horaFin)) {
            return response()->json(['error' => 'El empleado ya tiene una cita en ese horario.'], 422);
        }

        DB::transaction(function () use ($data, $servicios, &$cita, $empresaId, $duracionTotal, $precioTotal, $horaFin) {
            $cita = Cita::create([
                'empresa_id' => $empresaId,
                'cliente_id' => $data['cliente_id'] ?? null,
                'empleado_id' => $data['empleado_id'] ?? null,
                'fecha' => $data['fecha'],
                'hora_inicio' => $data['hora_inicio'],
                'hora_fin' => $horaFin,
                'duracion_total' => $duracionTotal,
                'precio_total' => $precioTotal,
                'estado' => $data['estado'] ?? 'pendiente',
                'notas' => $data['notas'] ?? null,
            ]);

            foreach ($servicios as $i => $servicio) {
                CitaServicio::create([
                    'cita_id' => $cita->id,
                    'servicio_id' => $servicio->id,
                    'empleado_id' => $data['empleado_id'] ?? null,
                    'duracion' => $servicio->duracion,
                    'precio' => $servicio->precio,
                    'orden' => $i,
                ]);
            }
        });

        return response()->json(['success' => true, 'cita' => $cita->load('servicios.servicio')]);
    }

    public function show(Cita $cita)
    {
        $this->autorizar($cita);
        return response()->json($cita->load(['cliente', 'empleado', 'servicios.servicio']));
    }

    public function update(Request $request, Cita $cita)
    {
        $this->autorizar($cita);
        $empresaId = auth()->user()->empresa_id;
        $data = $request->validate([
            'fecha' => 'sometimes|date',
            'hora_inicio' => 'sometimes',
            'empleado_id' => ['sometimes', 'nullable', Rule::exists('empleados', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'estado' => 'sometimes|in:pendiente,confirmada,en_curso,finalizada,cancelada,no_asistio',
            'notas' => 'nullable|string',
        ]);

        if (isset($data['hora_inicio'])) {
            $data['hora_fin'] = Carbon::parse($data['hora_inicio'])->addMinutes($cita->duracion_total)->format('H:i:s');
            $emp = $data['empleado_id'] ?? $cita->empleado_id;
            $fecha = $data['fecha'] ?? $cita->fecha->toDateString();
            if ($emp && $this->existeSolapamiento($empresaId, $emp, $fecha, $data['hora_inicio'], $data['hora_fin'], $cita->id)) {
                return response()->json(['error' => 'El empleado ya tiene una cita en ese horario.'], 422);
            }
        }

        $cita->update($data);
        return response()->json(['success' => true, 'cita' => $cita]);
    }

    public function destroy(Cita $cita)
    {
        $this->autorizar($cita);
        $cita->delete();
        return response()->json(['success' => true]);
    }

    public function cambiarEstado(Request $request, Cita $cita)
    {
        $this->autorizar($cita);
        $cita->update(['estado' => $request->input('estado')]);
        return response()->json(['success' => true, 'estado' => $cita->estado]);
    }

    private function autorizar(Cita $cita): void
    {
        abort_if($cita->empresa_id !== auth()->user()->empresa_id, 403);
    }

    private function existeSolapamiento(int $empresaId, int $empleadoId, string $fecha, string $horaInicio, string $horaFin, ?int $excluirId = null): bool
    {
        return Cita::where('empresa_id', $empresaId)
            ->when($excluirId, fn ($q) => $q->where('id', '!=', $excluirId))
            ->where('empleado_id', $empleadoId)
            ->where('fecha', $fecha)
            ->whereNotIn('estado', ['cancelada', 'no_asistio'])
            ->where('hora_inicio', '<', $horaFin)
            ->where('hora_fin', '>', $horaInicio)
            ->exists();
    }
}

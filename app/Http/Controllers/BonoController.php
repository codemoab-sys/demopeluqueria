<?php

namespace App\Http\Controllers;

use App\Models\Bono;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\TipoBono;
use App\Models\UsoBono;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BonoController extends Controller
{
    public function index(Request $request)
    {
        $query = Bono::where('empresa_id', auth()->user()->empresa_id)
            ->with(['cliente', 'servicio', 'tipoBono']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        $bonos = $query->latest()->paginate(20)->withQueryString();
        return view('bonos.index', compact('bonos'));
    }

    public function create()
    {
        $clientes = Cliente::where('empresa_id', auth()->user()->empresa_id)->orderBy('nombre')->get();
        $servicios = Servicio::where('empresa_id', auth()->user()->empresa_id)->where('activo', true)->get();
        $tiposBonos = TipoBono::where('empresa_id', auth()->user()->empresa_id)->where('activo', true)->get();
        return view('bonos.create', compact('clientes', 'servicios', 'tiposBonos'));
    }

    public function store(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $data = $request->validate([
            'cliente_id' => ['required', Rule::exists('clientes', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'tipo_bono_id' => ['nullable', Rule::exists('tipos_bonos', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'servicio_id' => ['nullable', Rule::exists('servicios', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'sesiones_total' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'fecha_compra' => 'required|date',
            'fecha_caducidad' => 'nullable|date',
            'notas' => 'nullable|string',
        ]);
        $data['empresa_id'] = $empresaId;
        $data['codigo'] = 'BONO-' . strtoupper(Str::random(8));

        Bono::create($data);
        return redirect()->route('bonos.index')->with('success', 'Bono creado correctamente.');
    }

    public function show(Bono $bono)
    {
        $this->autorizar($bono);
        $bono->load(['cliente', 'servicio', 'usos.bono']);
        return view('bonos.show', compact('bono'));
    }

    public function edit(Bono $bono)
    {
        $this->autorizar($bono);
        $clientes = Cliente::where('empresa_id', auth()->user()->empresa_id)->orderBy('nombre')->get();
        $servicios = Servicio::where('empresa_id', auth()->user()->empresa_id)->where('activo', true)->get();
        return view('bonos.edit', compact('bono', 'clientes', 'servicios'));
    }

    public function update(Request $request, Bono $bono)
    {
        $this->autorizar($bono);
        $data = $request->validate([
            'sesiones_total' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'fecha_caducidad' => 'nullable|date',
            'estado' => 'required|in:activo,agotado,caducado,cancelado',
            'notas' => 'nullable|string',
        ]);
        $bono->update($data);
        return redirect()->route('bonos.show', $bono)->with('success', 'Bono actualizado.');
    }

    public function destroy(Bono $bono)
    {
        $this->autorizar($bono);
        $bono->delete();
        return redirect()->route('bonos.index')->with('success', 'Bono eliminado.');
    }

    public function usar(Request $request, Bono $bono)
    {
        $this->autorizar($bono);
        $request->validate([
            'empleado_id' => ['nullable', Rule::exists('empleados', 'id')->where(fn ($q) => $q->where('empresa_id', auth()->user()->empresa_id))],
            'notas' => 'nullable|string',
        ]);
        if ($bono->sesiones_restantes <= 0) {
            return back()->with('error', 'El bono no tiene sesiones disponibles.');
        }

        UsoBono::create([
            'bono_id' => $bono->id,
            'empleado_id' => $request->input('empleado_id'),
            'fecha' => now(),
            'notas' => $request->input('notas'),
        ]);

        $bono->increment('sesiones_usadas');
        if ($bono->sesiones_usadas >= $bono->sesiones_total) {
            $bono->update(['estado' => 'agotado']);
        }

        return back()->with('success', 'Sesión registrada en el bono.');
    }

    private function autorizar(Bono $bono): void
    {
        abort_if($bono->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

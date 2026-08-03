<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\TipoBono;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoBonoController extends Controller
{
    public function index()
    {
        $tipos = TipoBono::where('empresa_id', auth()->user()->empresa_id)
            ->with('servicio')->orderBy('nombre')->paginate(20);
        return view('bonos.tipos.index', compact('tipos'));
    }

    public function create()
    {
        $servicios = Servicio::where('empresa_id', auth()->user()->empresa_id)->where('activo', true)->get();
        return view('bonos.tipos.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['empresa_id'] = auth()->user()->empresa_id;
        $data['precio_sesion'] = $data['sesiones'] > 0 ? round($data['precio'] / $data['sesiones'], 2) : 0;
        TipoBono::create($data);
        return redirect()->route('tipos-bonos.index')->with('success', 'Tipo de bono creado.');
    }

    public function edit(TipoBono $tiposBono)
    {
        $this->autorizar($tiposBono);
        $servicios = Servicio::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('bonos.tipos.edit', ['tipo' => $tiposBono, 'servicios' => $servicios]);
    }

    public function update(Request $request, TipoBono $tiposBono)
    {
        $this->autorizar($tiposBono);
        $data = $this->validateData($request);
        $data['precio_sesion'] = $data['sesiones'] > 0 ? round($data['precio'] / $data['sesiones'], 2) : 0;
        $tiposBono->update($data);
        return redirect()->route('tipos-bonos.index')->with('success', 'Tipo actualizado.');
    }

    public function destroy(TipoBono $tiposBono)
    {
        $this->autorizar($tiposBono);
        $tiposBono->delete();
        return back()->with('success', 'Tipo eliminado.');
    }

    private function validateData(Request $request): array
    {
        $empresaId = auth()->user()->empresa_id;

        return $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'servicio_id' => ['nullable', Rule::exists('servicios', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'sesiones' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'validez_dias' => 'required|integer|min:1',
            'activo' => 'nullable|boolean',
        ]);
    }

    private function autorizar(TipoBono $tipo): void
    {
        abort_if($tipo->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

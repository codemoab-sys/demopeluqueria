<?php

namespace App\Http\Controllers;

use App\Models\CategoriaServicio;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('empresa_id', auth()->user()->empresa_id)
            ->with('categoria')
            ->orderBy('nombre')->paginate(20);
        $categorias = CategoriaServicio::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('servicios.index', compact('servicios', 'categorias'));
    }

    public function create()
    {
        $categorias = CategoriaServicio::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('servicios.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['empresa_id'] = auth()->user()->empresa_id;
        Servicio::create($data);
        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function show(Servicio $servicio)
    {
        $this->autorizar($servicio);
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        $this->autorizar($servicio);
        $categorias = CategoriaServicio::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('servicios.edit', compact('servicio', 'categorias'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $this->autorizar($servicio);
        $servicio->update($this->validateData($request));
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado.');
    }

    public function destroy(Servicio $servicio)
    {
        $this->autorizar($servicio);
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado.');
    }

    private function validateData(Request $request): array
    {
        $empresaId = auth()->user()->empresa_id;

        return $request->validate([
            'categoria_id' => ['nullable', Rule::exists('categorias_servicios', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'codigo' => 'nullable|string|max:30',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion' => 'required|integer|min:5',
            'precio' => 'required|numeric|min:0',
            'impuesto' => 'required|numeric|min:0|max:100',
            'comision' => 'nullable|numeric|min:0|max:100',
            'color' => 'nullable|string|max:10',
            'reservable_online' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
        ]);
    }

    private function autorizar(Servicio $servicio): void
    {
        abort_if($servicio->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

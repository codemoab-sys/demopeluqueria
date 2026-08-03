<?php

namespace App\Http\Controllers;

use App\Models\CategoriaServicio;
use Illuminate\Http\Request;

class CategoriaServicioController extends Controller
{
    public function index()
    {
        $categorias = CategoriaServicio::where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('orden')->orderBy('nombre')->get();
        return view('servicios.categorias.index', compact('categorias'));
    }

    public function create() { return view('servicios.categorias.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:10',
            'icono' => 'nullable|string|max:50',
            'orden' => 'nullable|integer',
            'activo' => 'nullable|boolean',
        ]);
        $data['empresa_id'] = auth()->user()->empresa_id;
        CategoriaServicio::create($data);
        return redirect()->route('categorias-servicios.index')->with('success', 'Categoría creada.');
    }

    public function edit(CategoriaServicio $categoriasServicio)
    {
        $this->autorizar($categoriasServicio);
        return view('servicios.categorias.edit', ['categoria' => $categoriasServicio]);
    }

    public function update(Request $request, CategoriaServicio $categoriasServicio)
    {
        $this->autorizar($categoriasServicio);
        $categoriasServicio->update($request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:10',
            'icono' => 'nullable|string|max:50',
            'orden' => 'nullable|integer',
            'activo' => 'nullable|boolean',
        ]));
        return redirect()->route('categorias-servicios.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(CategoriaServicio $categoriasServicio)
    {
        $this->autorizar($categoriasServicio);
        $categoriasServicio->delete();
        return back()->with('success', 'Categoría eliminada.');
    }

    private function autorizar(CategoriaServicio $categoria): void
    {
        abort_if($categoria->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

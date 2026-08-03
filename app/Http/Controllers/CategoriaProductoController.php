<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProducto;
use Illuminate\Http\Request;

class CategoriaProductoController extends Controller
{
    public function index()
    {
        $categorias = CategoriaProducto::where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('orden')->orderBy('nombre')->get();
        return view('productos.categorias.index', compact('categorias'));
    }

    public function create() { return view('productos.categorias.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:10',
            'orden' => 'nullable|integer',
            'activo' => 'nullable|boolean',
        ]);
        $data['empresa_id'] = auth()->user()->empresa_id;
        CategoriaProducto::create($data);
        return redirect()->route('categorias-productos.index')->with('success', 'Categoría creada.');
    }

    public function edit(CategoriaProducto $categoriasProducto)
    {
        $this->autorizar($categoriasProducto);
        return view('productos.categorias.edit', ['categoria' => $categoriasProducto]);
    }

    public function update(Request $request, CategoriaProducto $categoriasProducto)
    {
        $this->autorizar($categoriasProducto);
        $categoriasProducto->update($request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:10',
            'orden' => 'nullable|integer',
            'activo' => 'nullable|boolean',
        ]));
        return redirect()->route('categorias-productos.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(CategoriaProducto $categoriasProducto)
    {
        $this->autorizar($categoriasProducto);
        $categoriasProducto->delete();
        return back()->with('success', 'Categoría eliminada.');
    }

    private function autorizar(CategoriaProducto $categoria): void
    {
        abort_if($categoria->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

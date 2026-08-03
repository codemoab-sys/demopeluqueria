<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProducto;
use App\Models\MovimientoStock;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::where('empresa_id', auth()->user()->empresa_id)->with('categoria');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($s) use ($q) {
                $s->where('nombre', 'like', "%{$q}%")
                  ->orWhere('codigo', 'like', "%{$q}%")
                  ->orWhere('codigo_barras', 'like', "%{$q}%");
            });
        }
        if ($request->boolean('bajo_stock')) {
            $query->whereColumn('stock', '<=', 'stock_minimo')->where('controlar_stock', true);
        }

        $productos = $query->orderBy('nombre')->paginate(20)->withQueryString();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = CategoriaProducto::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['empresa_id'] = auth()->user()->empresa_id;
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        Producto::create($data);
        return redirect()->route('productos.index')->with('success', 'Producto creado.');
    }

    public function show(Producto $producto)
    {
        $this->autorizar($producto);
        $movimientos = $producto->movimientosStock()->with('user')->latest()->limit(50)->get();
        return view('productos.show', compact('producto', 'movimientos'));
    }

    public function edit(Producto $producto)
    {
        $this->autorizar($producto);
        $categorias = CategoriaProducto::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $this->autorizar($producto);
        $data = $this->validateData($request);
        if ($request->hasFile('imagen')) {
            if ($producto->imagen) Storage::disk('public')->delete($producto->imagen);
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        $producto->update($data);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $this->autorizar($producto);
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }

    public function ajusteStock(Request $request, Producto $producto)
    {
        $this->autorizar($producto);
        $data = $request->validate([
            'tipo' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|numeric',
            'motivo' => 'nullable|string',
        ]);

        DB::transaction(function () use ($producto, $data) {
            $stockAnterior = $producto->stock;
            $cantidad = abs($data['cantidad']);

            $stockNuevo = match($data['tipo']) {
                'entrada' => $stockAnterior + $cantidad,
                'salida' => $stockAnterior - $cantidad,
                'ajuste' => $cantidad,
                default => $stockAnterior,
            };

            MovimientoStock::create([
                'empresa_id' => $producto->empresa_id,
                'producto_id' => $producto->id,
                'user_id' => auth()->id(),
                'tipo' => $data['tipo'],
                'cantidad' => $cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockNuevo,
                'motivo' => $data['motivo'] ?? null,
            ]);

            $producto->update(['stock' => $stockNuevo]);
        });

        return back()->with('success', 'Stock ajustado correctamente.');
    }

    private function validateData(Request $request): array
    {
        $empresaId = auth()->user()->empresa_id;

        return $request->validate([
            'categoria_id' => ['nullable', Rule::exists('categorias_productos', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId))],
            'codigo' => 'nullable|string|max:50',
            'codigo_barras' => 'nullable|string|max:50',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'marca' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|max:2048',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'impuesto' => 'required|numeric|min:0|max:100',
            'stock' => 'nullable|numeric',
            'stock_minimo' => 'nullable|numeric',
            'unidad' => 'nullable|string|max:20',
            'controlar_stock' => 'nullable|boolean',
            'vendible' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
        ]);
    }

    private function autorizar(Producto $producto): void
    {
        abort_if($producto->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

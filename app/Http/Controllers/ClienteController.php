<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::where('empresa_id', auth()->user()->empresa_id);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('apellidos', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('dni', 'like', "%{$q}%");
            });
        }

        $clientes = $query->orderBy('nombre')->paginate(20)->withQueryString();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $cliente = new Cliente();
        return view('clientes.create', compact('cliente'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['empresa_id'] = auth()->user()->empresa_id;
        $data['fecha_alta'] = now();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('clientes', 'public');
        }

        $cliente = Cliente::create($data);
        return redirect()->route('clientes.show', $cliente)->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        $this->autorizar($cliente);
        $cliente->load(['citas.empleado', 'citas.servicios.servicio', 'bonos.servicio', 'ventas']);
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $this->autorizar($cliente);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $this->autorizar($cliente);
        $data = $this->validateData($request, $cliente->id);

        if ($request->hasFile('foto')) {
            if ($cliente->foto) Storage::disk('public')->delete($cliente->foto);
            $data['foto'] = $request->file('foto')->store('clientes', 'public');
        }

        $cliente->update($data);
        return redirect()->route('clientes.show', $cliente)->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->autorizar($cliente);
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }

    public function historial(Cliente $cliente)
    {
        $this->autorizar($cliente);
        return view('clientes.historial', compact('cliente'));
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'dni' => 'nullable|string|max:30',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|in:masculino,femenino,otro',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:15',
            'foto' => 'nullable|image|max:2048',
            'notas' => 'nullable|string',
            'alergias' => 'nullable|string',
            'preferencias' => 'nullable|string',
            'acepta_marketing' => 'nullable|boolean',
            'acepta_rgpd' => 'nullable|boolean',
        ]);
    }

    private function autorizar(Cliente $cliente): void
    {
        abort_if($cliente->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('nombre')->paginate(20);
        return view('empleados.index', compact('empleados'));
    }

    public function create() { return view('empleados.create'); }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['empresa_id'] = auth()->user()->empresa_id;
        $data['fecha_alta'] = $data['fecha_alta'] ?? now();
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('empleados', 'public');
        }
        Empleado::create($data);
        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    public function show(Empleado $empleado)
    {
        $this->autorizar($empleado);
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        $this->autorizar($empleado);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $this->autorizar($empleado);
        $data = $this->validateData($request);
        if ($request->hasFile('foto')) {
            if ($empleado->foto) Storage::disk('public')->delete($empleado->foto);
            $data['foto'] = $request->file('foto')->store('empleados', 'public');
        }
        $empleado->update($data);
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $this->autorizar($empleado);
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'dni' => 'nullable|string|max:30',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'cargo' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:10',
            'foto' => 'nullable|image|max:2048',
            'comision' => 'nullable|numeric|min:0|max:100',
            'salario' => 'nullable|numeric|min:0',
            'fecha_alta' => 'nullable|date',
            'fecha_baja' => 'nullable|date',
            'notas' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);
    }

    private function autorizar(Empleado $empleado): void
    {
        abort_if($empleado->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

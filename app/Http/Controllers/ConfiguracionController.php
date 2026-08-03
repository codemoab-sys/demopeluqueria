<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $empresa = Empresa::firstOrCreate(['id' => auth()->user()->empresa_id], [
            'nombre' => 'Mi Peluquería',
        ]);
        return view('configuracion.index', compact('empresa'));
    }

    public function updateEmpresa(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'cif' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:15',
            'provincia' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'web' => 'nullable|string|max:255',
        ]);

        $empresa = Empresa::where('id', auth()->user()->empresa_id)->firstOrFail();
        $empresa->update($data);

        return back()->with('success', 'Datos de la empresa actualizados correctamente.');
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $empresa = Empresa::where('id', auth()->user()->empresa_id)->firstOrFail();

        if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
            Storage::disk('public')->delete($empresa->logo);
        }

        $path = $request->file('logo')->store('logos', 'public');
        $empresa->update(['logo' => $path]);

        return back()->with('success', 'Logo actualizado correctamente.');
    }

    public function updateParametros(Request $request)
    {
        $data = $request->validate([
            'simbolo_moneda' => 'required|string|max:5',
            'codigo_moneda' => 'required|string|max:5',
            'impuesto_default' => 'required|numeric|min:0|max:100',
            'zona_horaria' => 'required|string|max:50',
            'idioma' => 'required|string|max:5',
            'formato_fecha' => 'required|string|max:20',
            'mensaje_ticket' => 'nullable|string',
        ]);

        Empresa::where('id', auth()->user()->empresa_id)->firstOrFail()->update($data);
        return back()->with('success', 'Parámetros actualizados correctamente.');
    }

    public function updateHorario(Request $request)
    {
        $data = $request->validate([
            'hora_apertura' => 'required',
            'hora_cierre' => 'required',
            'intervalo_citas' => 'required|integer|min:5|max:120',
            'dias_laborables' => 'nullable|array',
        ]);

        Empresa::where('id', auth()->user()->empresa_id)->firstOrFail()->update($data);
        return back()->with('success', 'Horario actualizado correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CategoriaServicio;
use App\Models\Empresa;

class InformativaController extends Controller
{
    public function index()
    {
        $empresa = Empresa::first();

        $categorias = CategoriaServicio::with(['servicios' => fn ($q) => $q->where('activo', true)->orderBy('precio')])
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return view('web.inicio', compact('empresa', 'categorias'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::where('empresa_id', auth()->user()->empresa_id)->paginate(20);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create() { return view('usuarios.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'rol' => 'required|in:admin,gerente,empleado,recepcionista',
            'telefono' => 'nullable|string|max:30',
            'activo' => 'nullable|boolean',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['empresa_id'] = auth()->user()->empresa_id;
        User::create($data);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
    }

    public function edit(User $user)
    {
        $this->autorizar($user);
        return view('usuarios.edit', ['usuario' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $this->autorizar($user);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6|confirmed',
            'rol' => 'required|in:admin,gerente,empleado,recepcionista',
            'telefono' => 'nullable|string|max:30',
            'activo' => 'nullable|boolean',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        $this->autorizar($user);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }
        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }

    private function autorizar(User $user): void
    {
        abort_if($user->empresa_id !== auth()->user()->empresa_id, 403);
    }
}

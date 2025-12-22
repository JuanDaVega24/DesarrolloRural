<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
   public function index(Request $request)
{
    $query = User::query();

    // Filtrar por nombre
    if ($request->buscar) {
        $query->where('name', 'LIKE', "%{$request->buscar}%");
    }

    // Filtrar por rol
    if ($request->rol) {
        $query->where('role', $request->rol);
    }

    $usuarios = $query->paginate(10);

    return view('usuarios.index', compact('usuarios'));
}
    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => ['required','email', Rule::unique('users')],
            'password' => 'required|min:6',
            'role'     => 'required'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario creado correctamente');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name'  => 'required',
            'email' => ['required','email', Rule::unique('users')->ignore($usuario->id)],
            'role'  => 'required'
        ]);

        $usuario->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('ok', 'Usuario eliminado');
    }
}

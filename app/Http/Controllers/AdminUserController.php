<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
        ],[
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required' => 'El rol es obligatorio.',
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
            'password' => 'nullable|min:6',
            'role'  => 'required'
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required' => 'El rol es obligatorio.',
        ]);

        $updateData = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        // Solo actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $usuario->update($updateData);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado');
    }

    public function destroy($id)
    {
        // Busca el usuario si llega como ID, o usa el modelo si llega inyectado
        $usuario = ($id instanceof User) ? $id : User::find($id);

        if (!$usuario) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }

        if (Auth::check() && Auth::id() == $usuario->id) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        try {
            $usuario->delete();
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'No se puede eliminar el usuario porque tiene registros asociados.');
        }

        return redirect()->route('usuarios.index')->with('ok', 'Usuario eliminado');
    }
}

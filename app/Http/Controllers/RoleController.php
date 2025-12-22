<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function assign(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($userId);
        $user->roles()->sync([$request->role_id]);

        return back()->with('success', 'Rol asignado correctamente.');
    }
}

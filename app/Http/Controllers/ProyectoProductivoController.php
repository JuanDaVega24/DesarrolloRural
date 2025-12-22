<?php

namespace App\Http\Controllers;

use App\Models\ProyectoProductivo;
use Illuminate\Http\Request;

class ProyectoProductivoController extends Controller
{
    public function index()
    {
        $proyectos = ProyectoProductivo::latest()->paginate(20);
        return view('proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        return view('proyectos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|string',
        ]);

        ProyectoProductivo::create($data);

        return redirect()->route('proyectos.index')->with('success','Proyecto registrado');
    }

    public function edit(ProyectoProductivo $proyecto)
    {
        return view('proyectos.edit', compact('proyecto'));
    }

    public function update(Request $request, ProyectoProductivo $proyecto)
    {
        $proyecto->update($request->all());
        return redirect()->route('proyectos.index')->with('success','Proyecto actualizado');
    }

    public function destroy(ProyectoProductivo $proyecto)
    {
        $proyecto->delete();
        return back()->with('success','Proyecto eliminado');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Vivienda;
use App\Models\Encuesta;
use Illuminate\Http\Request;

class ViviendaController extends Controller
{
    // Mostrar formulario de vivienda para la encuesta actual
    public function create()
    {
        $encuesta_id = session('encuesta_id');

   if(!$encuesta_id) {
        return redirect()->route('encuestas.datosPersonales')
            ->with('error', 'Primero debes completar los datos personales.');
    }

    $encuesta = Encuesta::findOrFail($encuesta_id);
    return view('encuestas.vivienda', compact('encuesta'));
    }

    // Guardar los datos de vivienda desde el formulario
 public function guardarVivienda(Request $request)
{
    $encuesta_id = session('encuesta_id');

    if (!$encuesta_id || !is_numeric($encuesta_id)) {
        return redirect()->route('encuestas.datosPersonales')
            ->with('error', 'Primero debes completar los datos personales.');
    }

    $request->validate([
        'tipo_vivienda' => 'nullable|string|max:255',
        'condicion_ocupacion' => 'nullable|string|max:255',
        'material_piso' => 'nullable|string|max:255',
        'material_pared_exterior' => 'nullable|string|max:255',
        'destino_aguas_residuales' => 'nullable|string|max:255',
        'combustible_cocina' => 'nullable|string|max:255',
        'medios_comunicacion' => 'nullable|string|max:255',
        'medios_electronicos' => 'nullable|string|max:255',
        'acueducto_veredal' => 'nullable|boolean',
        'cuenta_con_filtro' => 'nullable|boolean',
        'tipo_servicio_sanitario' => 'nullable|string|max:255',
    ]);

    Vivienda::create([
        'encuesta_id' => $encuesta_id,
        'tipo_vivienda' => $request->tipo_vivienda,
        'condicion_ocupacion' => $request->condicion_ocupacion,
        'material_piso' => $request->material_piso,
        'material_pared_exterior' => $request->material_pared_exterior,
        'destino_aguas_residuales' => $request->destino_aguas_residuales,
        'combustible_cocina' => $request->combustible_cocina,
        'medios_comunicacion' => $request->medios_comunicacion,
        'medios_electronicos' => $request->medios_electronicos,
        'acueducto_veredal' => $request->acueducto_veredal,
        'cuenta_con_filtro' => $request->cuenta_con_filtro,
        'tipo_servicio_sanitario' => $request->tipo_servicio_sanitario,
    ]);

    return redirect()
        ->route('encuestas.descripcion')
        ->with('success', 'Información de vivienda guardada. Continúa con Producción.');
}


    public function show(Vivienda $vivienda)
    {
        $vivienda->load('encuesta.descripcion');
        return view('encuestas.vivienda_show', compact('vivienda'));
    }

    public function edit(Vivienda $vivienda)
    {
        $vivienda->load('encuesta');
        return view('encuestas.vivienda_edit', compact('vivienda'));
    }

    public function update(Request $request, Vivienda $vivienda)
    {
        $request->validate([
            'tipo_vivienda' => 'nullable|string|max:255',
            'condicion_ocupacion' => 'nullable|string|max:255',
            'material_piso' => 'nullable|string|max:255',
            'material_pared_exterior' => 'nullable|string|max:255',
            'destino_aguas_residuales' => 'nullable|string|max:255',
            'combustible_cocina' => 'nullable|string|max:255',
            'medios_comunicacion' => 'nullable|string|max:255',
            'medios_electronicos' => 'nullable|string|max:255',
            'acueducto_veredal' => 'nullable|boolean',
            'cuenta_con_filtro' => 'nullable|boolean',
            'tipo_servicio_sanitario' => 'nullable|string|max:255',
        ]);

        $vivienda->update([
            'tipo_vivienda' => $request->tipo_vivienda,
            'condicion_ocupacion' => $request->condicion_ocupacion,
            'material_piso' => $request->material_piso,
            'material_pared_exterior' => $request->material_pared_exterior,
            'destino_aguas_residuales' => $request->destino_aguas_residuales,
            'combustible_cocina' => $request->combustible_cocina,
            'medios_comunicacion' => $request->medios_comunicacion,
            'medios_electronicos' => $request->medios_electronicos,
            'acueducto_veredal' => $request->acueducto_veredal,
            'cuenta_con_filtro' => $request->cuenta_con_filtro,
            'tipo_servicio_sanitario' => $request->tipo_servicio_sanitario,
        ]);

        return redirect()
            ->route('viviendas.show', $vivienda->id)
            ->with('success', 'Información de vivienda actualizada correctamente.');
    }

    public function destroy(Vivienda $vivienda)
    {
        $encuesta_id = $vivienda->encuesta_id;
        $vivienda->delete();

        return redirect()
            ->route('encuestas.show', $encuesta_id)
            ->with('success', 'Registro de vivienda eliminado.');
    }
}

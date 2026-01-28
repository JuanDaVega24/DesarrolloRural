<?php

namespace App\Http\Controllers;

use App\Models\Familiar;
use App\Models\Encuesta;
use Illuminate\Http\Request;

class FamiliarController extends Controller
{
    /**
     * Mostrar el formulario de familiares.
     */
    public function create()
    {
        // Obtener el ID de encuesta desde sesión
        $encuesta_id = session('encuesta_id');

        if (!$encuesta_id) {
            return redirect()->route('encuestas.datos_personales')
                ->with('error', 'Debe iniciar la encuesta primero.');
        }

        $encuesta = Encuesta::findOrFail($encuesta_id);

        return view('encuestas.familiares', compact('encuesta'));
    }

    /**
     * Guardar o actualizar los datos del formulario.
     */
    public function guardarFamiliares(Request $request)
    {
        $modo = $request->input('modo');

        if ($modo === 'edit') {
            $encuesta_id = $request->input('encuesta_id');
        } else {
            $encuesta_id = session('encuesta_id');
        }

        if (!$encuesta_id) {
            return redirect()->route('encuestas.datos_personales')
                ->with('error', 'Debe iniciar la encuesta primero.');
        }

        // Validamos arrays primero (antes de procesar)
        $request->validate([
            'familiares' => 'required|array|min:1',
            'familiares.*.nombre_completo' => 'required|string|max:255',
            'familiares.*.fecha_nacimiento' => 'nullable|date',
            'familiares.*.tipo_documento' => 'nullable|string',
            'familiares.*.documento' => 'nullable|string',
            'familiares.*.fecha_expedicion' => 'nullable|date',
            'familiares.*.lugar_expedicion' => 'nullable|string',
            'familiares.*.parentesco' => 'nullable|string',
            'familiares.*.genero' => 'nullable|string',
            'familiares.*.poblacion' => 'nullable|string',
            'familiares.*.condicion' => 'nullable|string',
            'familiares.*.sabe_leer' => 'nullable|boolean',
            'familiares.*.estudia' => 'nullable|boolean',
            'familiares.*.nivel_educativo' => 'nullable|string',
            'familiares.*.celular' => 'nullable|string',
        ]);

        // Procesar cada familiar individualmente
        $familiaresData = $request->input('familiares', []);

        // Eliminar familiares existentes para esta encuesta
        Familiar::where('encuesta_id', $encuesta_id)->delete();

        // Crear nuevos familiares
        foreach ($familiaresData as $familiarData) {
            if (!empty($familiarData['nombre_completo'])) {
                $familiarData['encuesta_id'] = $encuesta_id;

                // Convertir valores booleanos de string a boolean
                $familiarData['sabe_leer'] = isset($familiarData['sabe_leer']) && $familiarData['sabe_leer'] === '1';
                $familiarData['estudia'] = isset($familiarData['estudia']) && $familiarData['estudia'] === '1';

                Familiar::create($familiarData);
            }
        }

        // Determinar la redirección según el modo
        $modo = $request->input('modo');

        if ($modo === 'edit') {
            return redirect()
                ->route('familiares.show', $encuesta_id)
                ->with('success', 'Familiares actualizados exitosamente.');
        } else {
            return redirect()
                ->route('encuestas.afectaciones')
                ->with('success', 'Familiares guardados correctamente. Continúa con Afectaciones.');
        }
    }

    /**
     * Mostrar la vista de detalles de familiares para una encuesta
     */
    public function show(Encuesta $encuesta)
    {
        session(['encuesta_id' => $encuesta->id]);
        $familiares = Familiar::where('encuesta_id', $encuesta->id)->get();
        $encuesta->load('afectaciones');
        return view('encuestas.familiares_show', compact('encuesta', 'familiares'));
    }

    /**
     * Mostrar el formulario de edición de familiares para una encuesta
     */
    public function edit(Encuesta $encuesta)
    {
        $familiares = Familiar::where('encuesta_id', $encuesta->id)->get();
        return view('encuestas.familiares_edit', compact('encuesta', 'familiares'));
    }

    /**
     * Eliminar todos los familiares de una encuesta
     */
    public function destroy(Encuesta $encuesta)
    {
        Familiar::where('encuesta_id', $encuesta->id)->delete();

        return redirect()->route('familiares.show', $encuesta->id)
            ->with('success', 'Familiares eliminados correctamente.');
    }
}

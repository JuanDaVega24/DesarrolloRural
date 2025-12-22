<?php

namespace App\Http\Controllers;

use App\Models\Afectacion;
use App\Models\Encuesta;
use Illuminate\Http\Request;

class AfectacionController extends Controller
{
    /**
     * Mostrar el formulario de afectaciones.
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

        return view('encuestas.afectaciones', compact('encuesta'));
    }

    /**
     * Guardar o actualizar los datos del formulario.
     */
    public function guardarAfectaciones(Request $request)
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
            'afectaciones' => 'nullable|array',
            'afectaciones.*.actividad_productiva' => 'required|array|min:1', // Ahora es array de checkboxes
            'afectaciones.*.fenomeno' => 'nullable|string',
            'afectaciones.*.anio' => 'nullable|integer|min:2000|max:2030',
            'afectaciones.*.semestre' => 'nullable|string|in:1,2',
            'afectaciones.*.hectareas' => 'nullable|numeric|min:0',
            'afectaciones.*.unidades_afectadas' => 'nullable|integer|min:0',
            'afectaciones.*.soluciones' => 'nullable|string',
            'afectaciones.*.actividades' => 'nullable|string',
            'afectaciones.*.afectacion' => 'nullable|string',
        ]);

        // Procesar cada afectacion individualmente
        $afectacionesData = $request->input('afectaciones', []);

        // Eliminar afectaciones existentes para esta encuesta
        Afectacion::where('encuesta_id', $encuesta_id)->delete();

        // Crear nuevas afectaciones
        if (!empty($afectacionesData)) {
            foreach ($afectacionesData as $afectacionData) {
                if (!empty($afectacionData['actividad_productiva'])) {
                    $afectacionData['encuesta_id'] = $encuesta_id;
                    Afectacion::create($afectacionData);
                }
            }
        }

        // Determinar la redirección según el modo
        $modo = $request->input('modo');

        if ($modo === 'edit') {
            return redirect()
                ->route('afectaciones.show', $encuesta_id)
                ->with('success', 'Afectaciones actualizadas exitosamente.');
        } else {
            return redirect()
                ->route('encuestas.index')
                ->with('success', '¡Encuesta completada exitosamente! Gracias por su participación.');
        }
    }

    /**
     * Mostrar la vista de detalles de afectaciones para una encuesta
     */
    public function show(Encuesta $encuesta)
    {
        $afectaciones = Afectacion::where('encuesta_id', $encuesta->id)->get();
        return view('encuestas.afectaciones_show', compact('encuesta', 'afectaciones'));
    }

    /**
     * Mostrar el formulario de edición de afectaciones para una encuesta
     */
    public function edit(Encuesta $encuesta)
    {
        $afectaciones = Afectacion::where('encuesta_id', $encuesta->id)->get();
        return view('encuestas.afectaciones_edit', compact('encuesta', 'afectaciones'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Predio;
use App\Models\Encuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PredioController extends Controller
{
    /**
     * Mostrar el formulario de predio
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

        // Verificar si ya existe registro
        $predio = Predio::where('encuesta_id', $encuesta_id)->first();

        // Convertir campos JSON a arrays para la vista
        if ($predio) {
            $camposJson = [
                'nombre_predio', 'area', 'area2', 'vereda', 'corregimiento',
                'municipio', 'departamento', 'tipo_actividad', 'cantidad'
            ];

            foreach ($camposJson as $campo) {
                if ($predio->$campo && is_string($predio->$campo)) {
                    $predio->$campo = json_decode($predio->$campo, true) ?: $predio->$campo;
                }
            }
        }

        return view('encuestas.predio', compact('encuesta', 'predio'));
    }

    /**
     * Guardar o actualizar los datos del formulario.
     */
    public function guardarPredio(Request $request)
    {
        $encuesta_id = session('encuesta_id');

        if (!$encuesta_id) {
            return redirect()->route('encuestas.datos_personales')
                ->with('error', 'Debe iniciar la encuesta primero.');
        }

        // Primero obtenemos todos los datos sin validar para procesar arrays
        $input = $request->all();

        // Convertir arrays a JSON para campos que son arrays
        $camposArray = [
            'nombre_predio', 'area', 'area2', 'vereda', 'corregimiento',
            'municipio', 'departamento', 'tipo_actividad', 'cantidad'
        ];

        foreach ($camposArray as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Validamos nuestros datos procesados
        $validator = Validator::make($input, [
            // Campos numéricos para uso del suelo
            'uso_agropecuario' => 'nullable|numeric|min:0|max:100',
            'barbecho' => 'nullable|numeric|min:0|max:100',
            'descanso' => 'nullable|numeric|min:0|max:100',
            'rastrojos' => 'nullable|numeric|min:0|max:100',
            'bosques_naturales' => 'nullable|numeric|min:0|max:100',
            'construcciones_infraestructura_agropecuaria' => 'nullable|numeric|min:0|max:100',
            'construcciones_infraestructura_no_agropecuaria' => 'nullable|numeric|min:0|max:100',
            'otros_usos' => 'nullable|numeric|min:0|max:100',

            // Campos de ubicación y predio
            'predio_no_continuo' => 'nullable|string',
            'nombre_predio' => 'nullable|string',
            'area' => 'nullable|string',
            'area2' => 'nullable|string',
            'vereda' => 'nullable|string',
            'corregimiento' => 'nullable|string',
            'municipio' => 'nullable|string',
            'departamento' => 'nullable|string',
            'tipo_actividad' => 'nullable|string',
            'cantidad' => 'nullable|string',
            'actividades_no_agropecuarias' => 'nullable|string',
            'actividades' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Usamos los datos procesados (con JSON) en lugar de los validados
        $data = array_merge($data, array_intersect_key($input, array_flip($camposArray)));

        // Insertamos el ID de encuesta
        $data['encuesta_id'] = $encuesta_id;

        // Crear o actualizar
        Predio::updateOrCreate(
            ['encuesta_id' => $encuesta_id],
            $data
        );

        return redirect()
            ->route('encuestas.control_actividades')
            ->with('success', 'Información del predio guardada correctamente.');
    }

    public function show(Predio $predio)
    {
        session(['encuesta_id' => $predio->encuesta_id]);
        $predio->load('encuesta.controlActividade');
        return view('encuestas.predio_show', compact('predio'));
    }

    public function edit(Predio $predio)
    {
        $predio->load('encuesta');

        // Convertir campos JSON a arrays para la vista
        $camposJson = [
            'nombre_predio', 'area', 'area2', 'vereda', 'corregimiento',
            'municipio', 'departamento', 'tipo_actividad', 'cantidad'
        ];

        foreach ($camposJson as $campo) {
            if ($predio->$campo && is_string($predio->$campo)) {
                $predio->$campo = json_decode($predio->$campo, true) ?: $predio->$campo;
            }
        }

        return view('encuestas.predio_edit', compact('predio'));
    }

    public function update(Request $request, Predio $predio)
    {
        $encuesta_id = $predio->encuesta_id;

        // Primero obtenemos todos los datos sin validar para procesar arrays
        $input = $request->all();

        // Convertir arrays a JSON para campos que son arrays
        $camposArray = [
            'nombre_predio', 'area', 'area2', 'vereda', 'corregimiento',
            'municipio', 'departamento', 'tipo_actividad', 'cantidad'
        ];

        foreach ($camposArray as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Validamos nuestros datos procesados
        $validator = Validator::make($input, [
            // Campos numéricos para uso del suelo
            'uso_agropecuario' => 'nullable|numeric|min:0|max:100',
            'barbecho' => 'nullable|numeric|min:0|max:100',
            'descanso' => 'nullable|numeric|min:0|max:100',
            'rastrojos' => 'nullable|numeric|min:0|max:100',
            'bosques_naturales' => 'nullable|numeric|min:0|max:100',
            'construcciones_infraestructura_agropecuaria' => 'nullable|numeric|min:0|max:100',
            'construcciones_infraestructura_no_agropecuaria' => 'nullable|numeric|min:0|max:100',
            'otros_usos' => 'nullable|numeric|min:0|max:100',

            // Campos de ubicación y predio
            'predio_no_continuo' => 'nullable|string',
            'nombre_predio' => 'nullable|string',
            'area' => 'nullable|string',
            'area2' => 'nullable|string',
            'vereda' => 'nullable|string',
            'corregimiento' => 'nullable|string',
            'municipio' => 'nullable|string',
            'departamento' => 'nullable|string',
            'tipo_actividad' => 'nullable|string',
            'cantidad' => 'nullable|string',
            'actividades_no_agropecuarias' => 'nullable|string',
            'actividades' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Usamos los datos procesados (con JSON)
        $data = array_merge($data, array_intersect_key($input, array_flip($camposArray)));

        $predio->update($data);

        return redirect()
            ->route('predio.show', $predio->id)
            ->with('success', 'Información del predio actualizada correctamente.');
    }

    public function destroy(Predio $predio)
    {
        $encuesta_id = $predio->encuesta_id;
        $predio->delete();

        return redirect()
            ->route('encuestas.show', $encuesta_id)
            ->with('success', 'Registro del predio eliminado.');
    }
}

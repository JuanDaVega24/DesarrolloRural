<?php

namespace App\Http\Controllers;

use App\Models\GestionAgropecuaria;
use App\Models\Encuesta;
use App\Models\Maquinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GestionAgropecuariaController extends Controller
{
    /**
     * Mostrar el formulario de gestión agropecuaria.
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
        $gestion = GestionAgropecuaria::where('encuesta_id', $encuesta_id)->first();

        // Convertir campos JSON a arrays para la vista
        if ($gestion) {
            $camposJson = [
                'entidad', 'valor_credito', 'plazo', 'fecha_aprobacion', 'al_dia', 'seguro',
                'fuentes', 'personas'
            ];

            foreach ($camposJson as $campo) {
                if ($gestion->$campo && is_string($gestion->$campo)) {
                    $gestion->$campo = json_decode($gestion->$campo, true) ?: $gestion->$campo;
                }
            }

            // Para cuantos, convertir JSON a array si es necesario
            if ($gestion->cuantos && is_string($gestion->cuantos)) {
                $gestion->cuantos = json_decode($gestion->cuantos, true) ?: $gestion->cuantos;
            }
        }

        return view('encuestas.gestion_agropecuaria', compact('encuesta', 'gestion'));
    }

    /**
     * Guardar o actualizar los datos del formulario.
     */
    public function guardarGestionAgropecuaria(Request $request)
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
            'entidad', 'valor_credito', 'plazo', 'fecha_aprobacion', 'al_dia', 'seguro'
        ];

        foreach ($camposArray as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Convertir arrays específicos a JSON
        if (isset($input['fuentes']) && is_array($input['fuentes'])) {
            $input['fuentes'] = json_encode($input['fuentes']);
        }

        if (isset($input['personas']) && is_array($input['personas'])) {
            $input['personas'] = json_encode($input['personas']);
        }

        if (isset($input['cuantos']) && is_array($input['cuantos'])) {
            $input['cuantos'] = json_encode($input['cuantos']);
        }

        // Validamos nuestros datos procesados
        $validator = Validator::make($input, [
            'participa' => 'nullable|string|in:0,1',
            'año' => 'nullable|integer',
            'entidad_gestiono' => 'nullable|string',
            'entidad_otro' => 'nullable|string',
            'consistio' => 'nullable|string',
            'credito' => 'nullable|string|in:0,1',
            'aprobado' => 'nullable|string|in:0,1',
            'fuentes' => 'nullable|string',
            'destino_recursos' => 'nullable|string',
            'tiene_creditos' => 'nullable|string|in:0,1',
            'entidad.*' => 'nullable|string',
            'valor_credito.*' => 'nullable|string',
            'plazo.*' => 'nullable|string',
            'fecha_aprobacion.*' => 'nullable|string',
            'al_dia.*' => 'nullable|string|in:Si,No',
            'seguro.*' => 'nullable|string',
            'personas' => 'nullable|string',
            'cuantos' => 'nullable|string',
            'jornales' => 'nullable|integer',
            'trabajo_colectivo' => 'nullable|string|in:0,1',
            'valor_jornal' => 'nullable|numeric',
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
        $gestion = GestionAgropecuaria::updateOrCreate(
            ['encuesta_id' => $encuesta_id],
            $data
        );

        // Debug: verificar que se guardó
        \Illuminate\Support\Facades\Log::info('GestionAgropecuaria guardada:', $data);

        // Verificar si venimos de una vista .show
        if (session('from_show') && session('show_route') === 'maquinaria.show') {
            // Limpiar las variables de sesión
            session()->forget(['from_show', 'show_route']);

            // Obtener la maquinaria de la misma encuesta para redirigir correctamente
            $maquinaria = Maquinaria::where('encuesta_id', $encuesta_id)->first();

            return redirect()
                ->route('maquinaria.show', $maquinaria->id)
                ->with('success', 'Gestión agropecuaria guardada correctamente.');
        }

        return redirect()
            ->route('encuestas.predio')   // lista de encuestas
            ->with('success', 'Gestión agropecuaria guardada correctamente.');
    }

    public function show(GestionAgropecuaria $gestion)
    {
        session(['encuesta_id' => $gestion->encuesta_id]);
        $gestion->load('encuesta.predio');
        return view('encuestas.gestion_agropecuaria_show', compact('gestion'));
    }

    public function edit(GestionAgropecuaria $gestion)
    {
        $gestion->load('encuesta');

        // Convertir campos JSON a arrays para la vista
        $camposJson = [
            'entidad', 'valor_credito', 'plazo', 'fecha_aprobacion', 'al_dia', 'seguro',
            'fuentes', 'personas'
        ];

        foreach ($camposJson as $campo) {
            if ($gestion->$campo && is_string($gestion->$campo)) {
                $gestion->$campo = json_decode($gestion->$campo, true) ?: $gestion->$campo;
            }
        }

        // Para cuantos, convertir JSON a array si es necesario
        if ($gestion->cuantos && is_string($gestion->cuantos)) {
            $gestion->cuantos = json_decode($gestion->cuantos, true) ?: $gestion->cuantos;
        }

        return view('encuestas.gestion_agropecuaria_edit', compact('gestion'));
    }

    public function update(Request $request, GestionAgropecuaria $gestion)
    {
        $encuesta_id = $gestion->encuesta_id;

        // Primero obtenemos todos los datos sin validar para procesar arrays
        $input = $request->all();

        // Convertir arrays a JSON para campos que son arrays
        $camposArray = [
            'entidad', 'valor_credito', 'plazo', 'fecha_aprobacion', 'al_dia', 'seguro'
        ];

        foreach ($camposArray as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Convertir arrays específicos a JSON
        if (isset($input['fuentes']) && is_array($input['fuentes'])) {
            $input['fuentes'] = json_encode($input['fuentes']);
        }

        if (isset($input['personas']) && is_array($input['personas'])) {
            $input['personas'] = json_encode($input['personas']);
        }

        if (isset($input['cuantos']) && is_array($input['cuantos'])) {
            $input['cuantos'] = json_encode($input['cuantos']);
        }

        // Validamos nuestros datos procesados
        $validator = Validator::make($input, [
            'participa' => 'nullable|string|in:0,1',
            'año' => 'nullable|integer',
            'entidad_gestiono' => 'nullable|string',
            'consistio' => 'nullable|string',
            'credito' => 'nullable|string|in:0,1',
            'aprobado' => 'nullable|string|in:0,1',
            'fuentes' => 'nullable|string',
            'destino_recursos' => 'nullable|string',
            'tiene_creditos' => 'nullable|string|in:0,1',
            'entidad.*' => 'nullable|string',
            'valor_credito.*' => 'nullable|string',
            'plazo.*' => 'nullable|string',
            'fecha_aprobacion.*' => 'nullable|string',
            'al_dia.*' => 'nullable|string|in:Si,No',
            'seguro.*' => 'nullable|string',
            'personas' => 'nullable|string',
            'cuantos' => 'nullable|string',
            'jornales' => 'nullable|integer',
            'trabajo_colectivo' => 'nullable|string|in:0,1',
            'valor_jornal' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Usamos los datos procesados (con JSON)
        $data = array_merge($data, array_intersect_key($input, array_flip($camposArray)));

        $gestion->update($data);

        return redirect()
            ->route('gestion_agropecuaria.show', $gestion->id)
            ->with('success', 'Información de gestión agropecuaria actualizada correctamente.');
    }

    public function destroy(GestionAgropecuaria $gestion)
    {
        $encuesta_id = $gestion->encuesta_id;

        // Obtener la maquinaria de la misma encuesta para redirigir correctamente
        $maquinaria = Maquinaria::where('encuesta_id', $encuesta_id)->first();

        $gestion->delete();

        return redirect()
            ->route('maquinaria.show', $maquinaria->id)
            ->with('success', 'Registro de gestión agropecuaria eliminado.');
    }
}

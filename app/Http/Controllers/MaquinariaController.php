<?php

namespace App\Http\Controllers;

use App\Models\Maquinaria;
use App\Models\Encuesta;
use Illuminate\Http\Request;

class MaquinariaController extends Controller
{
    /**
     * Mostrar el formulario de maquinaria
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
        $maquinaria = Maquinaria::where('encuesta_id', $encuesta_id)->first();

        // Convertir campos JSON a arrays para la vista
        if ($maquinaria) {
            $camposMaquinaria = [
                'tipo_maquinaria', 'cantidad_maquinaria', 'antiguedad_maquinaria', 'estado_maquinaria'
            ];

            foreach ($camposMaquinaria as $campo) {
                if ($maquinaria->$campo && is_string($maquinaria->$campo)) {
                    $maquinaria->$campo = json_decode($maquinaria->$campo, true) ?: $maquinaria->$campo;
                }
            }

            $camposConstruccion = [
                'tipo_construccion', 'antiguedad_construccion', 'cantidad_construccion', 'area_construccion'
            ];

            foreach ($camposConstruccion as $campo) {
                if ($maquinaria->$campo && is_string($maquinaria->$campo)) {
                    $maquinaria->$campo = json_decode($maquinaria->$campo, true) ?: $maquinaria->$campo;
                }
            }
        }

        return view('encuestas.maquinaria', compact('encuesta', 'maquinaria'));
    }

    /**
     * Guardar o actualizar los datos del formulario.
     */
    public function guardarMaquinaria(Request $request)
    {
        $encuesta_id = session('encuesta_id');

        if (!$encuesta_id) {
            return redirect()->route('encuestas.datos_personales')
                ->with('error', 'Debe iniciar la encuesta primero.');
        }

        // Primero obtenemos todos los datos sin validar para procesar arrays
        $input = $request->all();

        // Convertir arrays a JSON para maquinaria
        $camposMaquinaria = [
            'tipo_maquinaria', 'cantidad_maquinaria', 'antiguedad_maquinaria', 'estado_maquinaria'
        ];

        foreach ($camposMaquinaria as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Convertir arrays a JSON para construcción
        $camposConstruccion = [
            'tipo_construccion', 'antiguedad_construccion', 'cantidad_construccion', 'area_construccion'
        ];

        foreach ($camposConstruccion as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Función helper para convertir valores booleanos
        $convertToBoolean = function($value) {
            if ($value === null || $value === '') {
                return null;
            }
            return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? null;
        };

        // Validamos arrays primero (antes de convertir a JSON)
        $data = $request->validate([
            /* MAQUINARIA - Arrays */
            'tipo_maquinaria.*'         => 'nullable|string',
            'cantidad_maquinaria.*'     => 'nullable|integer',
            'antiguedad_maquinaria.*'   => 'nullable|integer',
            'estado_maquinaria.*'       => 'nullable|string',

            /* CONSTRUCCIÓN - Arrays */
            'tipo_construccion.*'       => 'nullable|string',
            'antiguedad_construccion.*' => 'nullable|integer',
            'cantidad_construccion.*'   => 'nullable|integer',
            'area_construccion.*'       => 'nullable|numeric',

            // Maquinaria (puede venir como JSON o texto)
            'maquinaria' => 'nullable|string',

            // Construcción
            'tiene_construccion' => 'nullable|boolean',

            // Asociación
            'pertenece_asociacion' => 'nullable|string',
            'nombre_asociacion'   => 'nullable|string',

            // Asesoría
            'entidad_asesoria' => 'nullable|string',
            'entidad_asesoria_nombre' => 'nullable|string',
            'recibio_asesoria_ultimo_anio' => 'nullable|boolean',
        ]);

        // Procesar campos de asesoría manualmente
        $camposAsesoria = [
            'tema_buenas_practicas_agricolas', 'pago_bpa',
            'tema_buenas_practicas_pecuarias', 'pago_bpp',
            'tema_manejo_ambiental', 'pago_ma',
            'tema_manejo_suelos', 'pago_ms',
            'tema_manejo_postcosecha', 'pago_mpc',
            'tema_comercializacion', 'pago_comercializacion',
            'tema_asociatividad', 'pago_asociatividad',
            'tema_credito', 'pago_credito',
            'tema_empresarial', 'pago_empresarial',
            'tema_tradicional', 'pago_tradicional'
        ];

        $recibioAsesoria = isset($input['recibio_asesoria_ultimo_anio']) && $input['recibio_asesoria_ultimo_anio'] == '1';

        if (!$recibioAsesoria) {
            // Si no recibió asesoría, establecer todos los campos en null
            foreach ($camposAsesoria as $campo) {
                $data[$campo] = null;
            }
        } else {
            // Si recibió asesoría, procesar cada campo que llegó
            foreach ($camposAsesoria as $campo) {
                $data[$campo] = isset($input[$campo]) ? $convertToBoolean($input[$campo]) : null;
            }
        }

        // Usamos los datos procesados (con JSON) en lugar de los validados
        $data = array_merge($data, array_intersect_key($input, array_flip(array_merge($camposMaquinaria, $camposConstruccion))));

        // Limpiar datos de maquinaria si el usuario marcó "No"
        if (isset($data['maquinaria']) && $data['maquinaria'] == '0') {
            $data['tipo_maquinaria'] = null;
            $data['cantidad_maquinaria'] = null;
            $data['antiguedad_maquinaria'] = null;
            $data['estado_maquinaria'] = null;
        }

        // Limpiar datos de construcción si el usuario marcó "No"
        if (isset($data['tiene_construccion']) && $data['tiene_construccion'] == '0') {
            $data['tipo_construccion'] = null;
            $data['antiguedad_construccion'] = null;
            $data['cantidad_construccion'] = null;
            $data['area_construccion'] = null;
        }

        // Insertamos el ID de encuesta
        $data['encuesta_id'] = $encuesta_id;

        // Crear o actualizar
        Maquinaria::updateOrCreate(
            ['encuesta_id' => $encuesta_id],
            $data
        );

        return redirect()
            ->route('encuestas.gestion_agropecuaria')
            ->with('success', 'Datos de maquinaria guardados correctamente.');
    }

    /**
     * Mostrar detalles de la maquinaria
     */
    public function show(Maquinaria $maquinaria)
    {
        session(['encuesta_id' => $maquinaria->encuesta_id]);
        $maquinaria->load('encuesta');
        return view('encuestas.maquinaria_show', compact('maquinaria'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Maquinaria $maquinaria)
    {
        $maquinaria->load('encuesta');

        // Convertir campos JSON a arrays para la vista de edición
        $camposMaquinaria = [
            'tipo_maquinaria', 'cantidad_maquinaria', 'antiguedad_maquinaria', 'estado_maquinaria'
        ];

        foreach ($camposMaquinaria as $campo) {
            if ($maquinaria->$campo && is_string($maquinaria->$campo)) {
                $maquinaria->$campo = json_decode($maquinaria->$campo, true) ?: $maquinaria->$campo;
            }
        }

        $camposConstruccion = [
            'tipo_construccion', 'antiguedad_construccion', 'cantidad_construccion', 'area_construccion'
        ];

        foreach ($camposConstruccion as $campo) {
            if ($maquinaria->$campo && is_string($maquinaria->$campo)) {
                $maquinaria->$campo = json_decode($maquinaria->$campo, true) ?: $maquinaria->$campo;
            }
        }

        return view('encuestas.maquinaria_edit', compact('maquinaria'));
    }

    /**
     * Actualizar maquinaria
     */
    public function update(Request $request, Maquinaria $maquinaria)
    {
        // Primero obtenemos todos los datos sin validar para procesar arrays
        $input = $request->all();

        // Convertir arrays a JSON para maquinaria
        $camposMaquinaria = [
            'tipo_maquinaria', 'cantidad_maquinaria', 'antiguedad_maquinaria', 'estado_maquinaria'
        ];

        foreach ($camposMaquinaria as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Convertir arrays a JSON para construcción
        $camposConstruccion = [
            'tipo_construccion', 'antiguedad_construccion', 'cantidad_construccion', 'area_construccion'
        ];

        foreach ($camposConstruccion as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Función helper para convertir valores booleanos
        $convertToBoolean = function($value) {
            if ($value === null || $value === '') {
                return null;
            }
            return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? null;
        };

        // Función helper para convertir valores booleanos
        $convertToBoolean = function($value) {
            if ($value === null || $value === '') {
                return null;
            }
            return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? null;
        };

        // Validamos arrays primero (antes de convertir a JSON)
        $data = $request->validate([
            /* MAQUINARIA - Arrays */
            'tipo_maquinaria.*'         => 'nullable|string',
            'cantidad_maquinaria.*'     => 'nullable|integer',
            'antiguedad_maquinaria.*'   => 'nullable|integer',
            'estado_maquinaria.*'       => 'nullable|string',

            /* CONSTRUCCIÓN - Arrays */
            'tipo_construccion.*'       => 'nullable|string',
            'antiguedad_construccion.*' => 'nullable|integer',
            'cantidad_construccion.*'   => 'nullable|integer',
            'area_construccion.*'       => 'nullable|numeric',

            // Maquinaria (puede venir como JSON o texto)
            'maquinaria' => 'nullable|string',

            // Construcción
            'tiene_construccion' => 'nullable|boolean',

            // Asociación
            'pertenece_asociacion' => 'nullable|string',
            'nombre_asociacion'   => 'nullable|string',

            // Asesoría
            'entidad_asesoria' => 'nullable|string',
            'recibio_asesoria_ultimo_anio' => 'nullable|boolean',

            // Campo adicional de asesoría
            'entidad_asesoria_nombre' => 'nullable|string',
        ]);

        // Procesar campos de asesoría manualmente
        $camposAsesoria = [
            'tema_buenas_practicas_agricolas', 'pago_bpa',
            'tema_buenas_practicas_pecuarias', 'pago_bpp',
            'tema_manejo_ambiental', 'pago_ma',
            'tema_manejo_suelos', 'pago_ms',
            'tema_manejo_postcosecha', 'pago_mpc',
            'tema_comercializacion', 'pago_comercializacion',
            'tema_asociatividad', 'pago_asociatividad',
            'tema_credito', 'pago_credito',
            'tema_empresarial', 'pago_empresarial',
            'tema_tradicional', 'pago_tradicional'
        ];

        $recibioAsesoria = isset($input['recibio_asesoria_ultimo_anio']) && $input['recibio_asesoria_ultimo_anio'] == '1';

        if (!$recibioAsesoria) {
            // Si no recibió asesoría, establecer todos los campos en null
            foreach ($camposAsesoria as $campo) {
                $data[$campo] = null;
            }
        } else {
            // Si recibió asesoría, procesar cada campo que llegó
            foreach ($camposAsesoria as $campo) {
                $data[$campo] = isset($input[$campo]) ? $convertToBoolean($input[$campo]) : null;
            }
        }

        // Usamos los datos procesados (con JSON) en lugar de los validados
        $data = array_merge($data, array_intersect_key($input, array_flip(array_merge($camposMaquinaria, $camposConstruccion))));

        // Limpiar datos de maquinaria si el usuario marcó "No"
        if (isset($data['maquinaria']) && $data['maquinaria'] == '0') {
            $data['tipo_maquinaria'] = null;
            $data['cantidad_maquinaria'] = null;
            $data['antiguedad_maquinaria'] = null;
            $data['estado_maquinaria'] = null;
        }

        // Limpiar datos de construcción si el usuario marcó "No"
        if (isset($data['tiene_construccion']) && $data['tiene_construccion'] == '0') {
            $data['tipo_construccion'] = null;
            $data['antiguedad_construccion'] = null;
            $data['cantidad_construccion'] = null;
            $data['area_construccion'] = null;
        }

        // Actualizar registro
        $maquinaria->update($data);

        return redirect()
            ->route('maquinaria.show', $maquinaria->id)
            ->with('success', 'Datos de maquinaria actualizados correctamente.');
    }

    /**
     * Eliminar maquinaria
     */
    public function destroy(Maquinaria $maquinaria)
    {
        $maquinaria->delete();

        return redirect()
            ->route('encuestas.show', $maquinaria->encuesta_id)
            ->with('success', 'Datos de maquinaria eliminados correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use App\Models\Encuesta;
use Illuminate\Http\Request;

class ProduccionController extends Controller
{
    /**
     * Mostrar el formulario de producción.
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
        $produccion = Produccion::where('encuesta_id', $encuesta_id)->first();

        // Convertir campos JSON a arrays para la vista
        if ($produccion) {
            $camposJson = [
                'tipo_cultivo', 'area_cultivo', 'unidad_area_cultivo',
                'cantidad_arboles_plantas', 'nivel_produccion', 'edades_cultivo',
                'seguridad_alimentaria', 'uso_comercial', 'bajo_cubierta',
                'cielo_abierto', 'hidroponia'
            ];

            // Campos booleanos que necesitan conversión de "si"/"no"/"null" a 1/0/null
            $camposBooleanos = ['seguridad_alimentaria', 'uso_comercial', 'bajo_cubierta', 'cielo_abierto', 'hidroponia'];

            foreach ($camposJson as $campo) {
                if ($produccion->$campo && is_string($produccion->$campo)) {
                    $decoded = json_decode($produccion->$campo, true) ?: $produccion->$campo;

                    // Para campos booleanos, convertir "si"/"no"/"null" de vuelta a 1/0/null
                    if (is_array($decoded) && in_array($campo, $camposBooleanos)) {
                        $decoded = array_map(function($valor) {
                            if ($valor === 'si') return 1;
                            if ($valor === 'no') return 0;
                            return null;
                        }, $decoded);
                    }

                    $produccion->$campo = $decoded;
                }
            }

            // Para agroindustriales también
            $camposAgroJson = [
                'producto_nombre', 'producto_alimentario', 'producto_no_alimentario',
                'producto_presentacion', 'producto_precio', 'producto_capacidad',
                'producto_unidad_capacidad', 'producto_tiene_etiqueta', 'producto_tiene_registro'
            ];

            $camposBooleanosAgro = ['producto_alimentario', 'producto_no_alimentario', 'producto_tiene_etiqueta', 'producto_tiene_registro'];

            foreach ($camposAgroJson as $campo) {
                if ($produccion->$campo && is_string($produccion->$campo)) {
                    $decoded = json_decode($produccion->$campo, true) ?: $produccion->$campo;

                    // Para campos booleanos agro, convertir "si"/"no"/"null" de vuelta a 1/0/null
                    if (is_array($decoded) && in_array($campo, $camposBooleanosAgro)) {
                        $decoded = array_map(function($valor) {
                            if ($valor === 'si') return 1;
                            if ($valor === 'no') return 0;
                            return null;
                        }, $decoded);
                    }

                    $produccion->$campo = $decoded;
                }
            }

            // Para campos restantes (forestal, vivero, pastos)
            $camposRestantesJson = [
                'forestal_modalidad', 'forestal_cantidad',
                'vivero_especies', 'vivero_cantidad',
                'pastos_especies', 'pastos_hectareas', 'pastos_productos'
            ];

            foreach ($camposRestantesJson as $campo) {
                if ($produccion->$campo && is_string($produccion->$campo)) {
                    $produccion->$campo = json_decode($produccion->$campo, true) ?: $produccion->$campo;
                }
            }
        }

        return view('encuestas.produccion', compact('encuesta', 'produccion'));
    }

    /**
     * Guardar o actualizar los datos del formulario.
     */
   public function guardarProduccion(Request $request)
{
    $encuesta_id = session('encuesta_id');

    if (!$encuesta_id) {
        return redirect()->route('encuestas.datos_personales')
            ->with('error', 'Debe iniciar la encuesta primero.');
    }

    // Primero obtenemos todos los datos sin validar para procesar arrays
    $input = $request->all();

    // Convertir arrays a JSON para campos agrícolas
    $camposAgricolas = [
        'tipo_cultivo', 'area_cultivo', 'unidad_area_cultivo',
        'cantidad_arboles_plantas', 'nivel_produccion', 'edades_cultivo',
        'seguridad_alimentaria', 'uso_comercial', 'bajo_cubierta',
        'cielo_abierto', 'hidroponia'
    ];

    // Campos booleanos que necesitan conversión especial
    $camposBooleanos = ['seguridad_alimentaria', 'uso_comercial', 'bajo_cubierta', 'cielo_abierto', 'hidroponia'];

    foreach ($camposAgricolas as $campo) {
        if (isset($input[$campo]) && is_array($input[$campo])) {
            // Para campos booleanos, convertir valores antes de filtrar
            if (in_array($campo, $camposBooleanos)) {
                $input[$campo] = array_map(function($valor) {
                    if ($valor === '1' || $valor === 1) return 'si';
                    if ($valor === '0' || $valor === 0) return 'no';
                    return 'null';
                }, $input[$campo]);
                $input[$campo] = json_encode($input[$campo]);
            } else {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }
    }

    // Convertir arrays a JSON para campos agroindustriales
    $camposAgroindustriales = [
        'producto_nombre', 'producto_alimentario', 'producto_no_alimentario',
        'producto_presentacion', 'producto_precio', 'producto_capacidad',
        'producto_unidad_capacidad', 'producto_tiene_etiqueta', 'producto_tiene_registro'
    ];

    // Campos booleanos agroindustriales
    $camposBooleanosAgro = ['producto_alimentario', 'producto_no_alimentario', 'producto_tiene_etiqueta', 'producto_tiene_registro'];

    foreach ($camposAgroindustriales as $campo) {
        if (isset($input[$campo]) && is_array($input[$campo])) {
            // Para campos booleanos agro, convertir valores
            if (in_array($campo, $camposBooleanosAgro)) {
                $input[$campo] = array_map(function($valor) {
                    if ($valor === '1' || $valor === 1) return 'si';
                    if ($valor === '0' || $valor === 0) return 'no';
                    return 'null';
                }, $input[$campo]);
                $input[$campo] = json_encode($input[$campo]);
            } else {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }
    }

    // Convertir arrays a JSON para campos restantes
    $camposRestantes = [
        'forestal_modalidad', 'forestal_cantidad',
        'vivero_especies', 'vivero_cantidad',
        'pastos_especies', 'pastos_hectareas', 'pastos_productos'
    ];

    foreach ($camposRestantes as $campo) {
        if (isset($input[$campo]) && is_array($input[$campo])) {
            $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
        }
    }

    // Validamos arrays primero (antes de convertir a JSON)
    $data = $request->validate([
        /* ACTIVIDADES AGRÍCOLAS - Arrays */
        'tipo_cultivo.*'                => 'nullable|string',
        'area_cultivo.*'                => 'nullable|numeric',
        'unidad_area_cultivo.*'         => 'nullable|in:HA,MTS2',
        'cantidad_arboles_plantas.*'    => 'nullable|integer',
        'nivel_produccion.*'            => 'nullable|string',
        'edades_cultivo.*'              => 'nullable|string',
        'seguridad_alimentaria.*'       => 'nullable|boolean',
        'uso_comercial.*'               => 'nullable|boolean',
        'bajo_cubierta.*'               => 'nullable|boolean',
        'cielo_abierto.*'               => 'nullable|boolean',
        'hidroponia.*'                  => 'nullable|boolean',

        /* ACTIVIDADES AGROINDUSTRIALES - Arrays */
        'producto_nombre.*'             => 'nullable|string',
        'producto_alimentario.*'        => 'nullable|boolean',
        'producto_no_alimentario.*'     => 'nullable|boolean',
        'producto_presentacion.*'       => 'nullable|numeric',
        'producto_precio.*'             => 'nullable|numeric',
        'producto_capacidad.*'          => 'nullable|numeric',
        'producto_unidad_capacidad.*'   => 'nullable|in:kg,g,lts,cm3',
        'producto_tiene_etiqueta.*'     => 'nullable|boolean',
        'producto_tiene_registro.*'     => 'nullable|boolean',

        /* ACTIVIDADES FORESTALES - Arrays */
        'forestal_modalidad.*'        => 'nullable|string',
        'forestal_cantidad.*'         => 'nullable|integer',

        /* ACTIVIDAD VIVERO - Arrays */
        'vivero_especies.*'           => 'nullable|string',
        'vivero_cantidad.*'           => 'nullable|integer',

        /* PASTOS NATURALES - Arrays */
        'pastos_especies.*'           => 'nullable|string',
        'pastos_hectareas.*'          => 'nullable|numeric',
        'pastos_productos.*'          => 'nullable|string',
    ]);

    // Usamos los datos procesados (con JSON) en lugar de los validados
    $data = array_merge($data, array_intersect_key($input, array_flip(array_merge($camposAgricolas, $camposAgroindustriales, $camposRestantes))));

    // Insertamos el ID de encuesta
    $data['encuesta_id'] = $encuesta_id;

    // Crear o actualizar
    Produccion::updateOrCreate(
        ['encuesta_id' => $encuesta_id],
        $data
    );

    return redirect()
        ->route('encuestas.inventario_pecuarios')   // cambia por la siguiente sección
        ->with('success', 'Producción guardada correctamente.');
}

    public function show(Produccion $produccion)
    {
        $produccion->load('encuesta');
        return view('encuestas.produccion_show', compact('produccion'));
    }

    public function edit(Produccion $produccion)
    {
        $produccion->load('encuesta');
        return view('encuestas.produccion_edit', compact('produccion'));
    }

    public function update(Request $request, Produccion $produccion)
    {
        $encuesta_id = $produccion->encuesta_id;

        // Primero obtenemos todos los datos sin validar para procesar arrays
        $input = $request->all();

        // Convertir arrays a JSON para campos agrícolas
        $camposAgricolas = [
            'tipo_cultivo', 'area_cultivo', 'unidad_area_cultivo',
            'cantidad_arboles_plantas', 'nivel_produccion', 'edades_cultivo',
            'seguridad_alimentaria', 'uso_comercial', 'bajo_cubierta',
            'cielo_abierto', 'hidroponia'
        ];

        // Campos booleanos que necesitan conversión especial
        $camposBooleanos = ['seguridad_alimentaria', 'uso_comercial', 'bajo_cubierta', 'cielo_abierto', 'hidroponia'];

        foreach ($camposAgricolas as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                // Para campos booleanos, convertir valores antes de filtrar
                if (in_array($campo, $camposBooleanos)) {
                    $input[$campo] = array_map(function($valor) {
                        if ($valor === '1' || $valor === 1) return 'si';
                        if ($valor === '0' || $valor === 0) return 'no';
                        return 'null';
                    }, $input[$campo]);
                    $input[$campo] = json_encode($input[$campo]);
                } else {
                    $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
                }
            }
        }

        // Convertir arrays a JSON para campos agroindustriales
        $camposAgroindustriales = [
            'producto_nombre', 'producto_alimentario', 'producto_no_alimentario',
            'producto_presentacion', 'producto_precio', 'producto_capacidad',
            'producto_unidad_capacidad', 'producto_tiene_etiqueta', 'producto_tiene_registro'
        ];

        // Campos booleanos agroindustriales
        $camposBooleanosAgro = ['producto_alimentario', 'producto_no_alimentario', 'producto_tiene_etiqueta', 'producto_tiene_registro'];

        foreach ($camposAgroindustriales as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                // Para campos booleanos agro, convertir valores
                if (in_array($campo, $camposBooleanosAgro)) {
                    $input[$campo] = array_map(function($valor) {
                        if ($valor === '1' || $valor === 1) return 'si';
                        if ($valor === '0' || $valor === 0) return 'no';
                        return 'null';
                    }, $input[$campo]);
                    $input[$campo] = json_encode($input[$campo]);
                } else {
                    $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
                }
            }
        }

        // Convertir arrays a JSON para campos restantes
        $camposRestantes = [
            'forestal_modalidad', 'forestal_cantidad',
            'vivero_especies', 'vivero_cantidad',
            'pastos_especies', 'pastos_hectareas', 'pastos_productos'
        ];

        foreach ($camposRestantes as $campo) {
            if (isset($input[$campo]) && is_array($input[$campo])) {
                $input[$campo] = json_encode(array_filter($input[$campo], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Validamos arrays primero (antes de convertir a JSON)
        $validated = $request->validate([
            /* ACTIVIDADES AGRÍCOLAS - Arrays */
            'tipo_cultivo.*'                => 'nullable|string',
            'area_cultivo.*'                => 'nullable|numeric',
            'unidad_area_cultivo.*'         => 'nullable|in:HA,MTS2',
            'cantidad_arboles_plantas.*'    => 'nullable|integer',
            'nivel_produccion.*'            => 'nullable|string',
            'edades_cultivo.*'              => 'nullable|string',
            'seguridad_alimentaria.*'       => 'nullable|boolean',
            'uso_comercial.*'               => 'nullable|boolean',
            'bajo_cubierta.*'               => 'nullable|boolean',
            'cielo_abierto.*'               => 'nullable|boolean',
            'hidroponia.*'                  => 'nullable|boolean',

            /* ACTIVIDADES AGROINDUSTRIALES - Arrays */
            'producto_nombre.*'             => 'nullable|string',
            'producto_alimentario.*'        => 'nullable|boolean',
            'producto_no_alimentario.*'     => 'nullable|boolean',
            'producto_presentacion.*'       => 'nullable|numeric',
            'producto_precio.*'             => 'nullable|numeric',
            'producto_capacidad.*'          => 'nullable|numeric',
            'producto_unidad_capacidad.*'   => 'nullable|in:kg,lts,g,cm3',
            'producto_tiene_etiqueta.*'     => 'nullable|boolean',
            'producto_tiene_registro.*'     => 'nullable|boolean',

            /* ACTIVIDADES FORESTALES - Arrays */
            'forestal_modalidad.*'        => 'nullable|string',
            'forestal_cantidad.*'         => 'nullable|integer',

            /* ACTIVIDAD VIVERO - Arrays */
            'vivero_especies.*'           => 'nullable|string',
            'vivero_cantidad.*'           => 'nullable|integer',

            /* PASTOS NATURALES - Arrays */
            'pastos_especies.*'           => 'nullable|string',
            'pastos_hectareas.*'          => 'nullable|numeric',
            'pastos_productos.*'          => 'nullable|string',
        ]);

        // Usamos los datos procesados (con JSON) en lugar de los validados
        $data = array_merge($validated, array_intersect_key($input, array_flip(array_merge($camposAgricolas, $camposAgroindustriales, $camposRestantes))));

        $produccion->update($data);

        return redirect()
            ->route('producciones.show', $produccion->id)
            ->with('success', 'Información de producción actualizada correctamente.');
    }

    public function destroy(Produccion $produccion)
    {
        $encuesta_id = $produccion->encuesta_id;
        $produccion->delete();

        return redirect()
            ->route('encuestas.show', $encuesta_id)
            ->with('success', 'Registro de producción eliminado.');
    }

}

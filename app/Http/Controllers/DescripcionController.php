<?php

namespace App\Http\Controllers;

use App\Models\Descripcion;
use App\Models\Encuesta;
use Illuminate\Http\Request;

class DescripcionController extends Controller
{
    /**
     * Mostrar el formulario de descripción.
     */
    public function create()
    {
        // Obtener el ID de la encuesta desde sesión
        $encuesta_id = session('encuesta_id');

        if (!$encuesta_id) {
            return redirect()->route('encuestas.datos_personales')
                    ->with('error', 'Debe iniciar la encuesta primero.');
        }

        $encuesta = Encuesta::findOrFail($encuesta_id);

        // Verificar si ya existe registro
        $descripcion = Descripcion::where('encuesta_id', $encuesta_id)->first();

        return view('encuestas.descripcion', compact('encuesta', 'descripcion'));
    }

    /**
     * Mostrar detalles de una descripción específica.
     */
    public function show(Descripcion $descripcion)
    {
        session(['encuesta_id' => $descripcion->encuesta_id]);
        $descripcion->load('encuesta.vivienda', 'encuesta.produccion');
        return view('encuestas.descripcion_show', compact('descripcion'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Descripcion $descripcion)
    {
        $descripcion->load('encuesta');
        return view('encuestas.descripcion_edit', compact('descripcion'));
    }

    /**
     * Actualizar los datos de la descripción.
     */
    public function update(Request $request, Descripcion $descripcion)
    {
        $request->validate([
            'acueducto_publico'             => 'nullable|array',
            'acueducto_publico.*'           => 'nullable|string',
            'acuifero'                      => 'nullable|array',
            'acuifero.*'                    => 'nullable|string',
            'almacenamiento_aguas_lluvias' => 'nullable|array',
            'almacenamiento_aguas_lluvias.*' => 'nullable|string',
            'aljibes'                       => 'nullable|array',
            'aljibes.*'                     => 'nullable|string',
            'carrotanque'                   => 'nullable|array',
            'carrotanque.*'                 => 'nullable|string',
            'nacimientos'                   => 'nullable|array',
            'nacimientos.*'                 => 'nullable|string',
            'pila_publica'                  => 'nullable|array',
            'pila_publica.*'                => 'nullable|string',
            'pozos'                         => 'nullable|array',
            'pozos.*'                       => 'nullable|string',
            'red_distribucion_comunitaria'  => 'nullable|array',
            'red_distribucion_comunitaria.*' => 'nullable|string',
            'acueducto_veredal'             => 'nullable|array',
            'acueducto_veredal.*'           => 'nullable|string',
            'rios'                          => 'nullable|array',
            'rios.*'                        => 'nullable|string',
            'quebradas'                     => 'nullable|array',
            'quebradas.*'                   => 'nullable|string',
            'otro'                          => 'nullable|array',
            'otro.*'                        => 'nullable|string',

            'herramienta_agricola'          => 'nullable|string',
            'distancia_finca_cabecera'      => 'nullable|string',
            'transporte_cabecera'           => 'nullable|string',
            'vias_acceso'                   => 'nullable|string',
            'condicion_vias'                => 'nullable|string',

            'uso_suelo_agricultura'         => 'nullable|numeric',
            'uso_suelo_ganaderia'           => 'nullable|numeric',
            'uso_suelo_conservacion'        => 'nullable|numeric',
            'uso_suelo_casa'                => 'nullable|numeric',
            'uso_suelo_rastrojo'            => 'nullable|numeric',

            'almacen_maquinaria'            => 'nullable|string',
            'almacen_insumos_quimicos'      => 'nullable|string',
            'almacen_abonos'                => 'nullable|string',

            'condicion_terreno'             => 'nullable|string',
            'sistema_riego'                 => 'nullable|string',

            'destino_produccion'            => 'nullable|array',
            'destino_produccion.*'          => 'nullable|string',

            'otros_destinos_detalle'        => 'nullable|string',
        ]);

        // ======== Procesar destino_produccion (convertir array a cadena) ========
        if ($request->has('destino_produccion')) {
            $data['destino_produccion'] = implode(',', $request->destino_produccion);
        } else {
            $data['destino_produccion'] = '';
        }

        // ======== Procesar fuentes de agua con JSON estructurado ========
        $fuenteCampos = [
            'acueducto_publico',
            'acuifero',
            'almacenamiento_aguas_lluvias',
            'aljibes',
            'carrotanque',
            'nacimientos',
            'pila_publica',
            'pozos',
            'red_distribucion_comunitaria',
            'acueducto_veredal',
            'rios',
            'quebradas',
            'otro'
        ];

        foreach ($fuenteCampos as $campo) {
            $fuenteData = [];

            // Usos seleccionados
            if ($request->has($campo)) {
                $fuenteData['usos'] = $request->$campo;
            }

            // Cantidad (si fue llenada)
            if ($request->has('cantidad_' . $campo) && !empty($request->{'cantidad_' . $campo})) {
                $fuenteData['cantidad'] = (int) $request->{'cantidad_' . $campo};
            }

            // Ubicado (si aplica y fue seleccionado)
            if ($request->has('ubicado_' . $campo) && !empty($request->{'ubicado_' . $campo})) {
                $fuenteData['ubicado'] = $request->{'ubicado_' . $campo};
            }

            // Nombre (si aplica y fue llenado)
            if ($request->has('nombre_' . $campo) && !empty($request->{'nombre_' . $campo})) {
                $fuenteData['nombre'] = $request->{'nombre_' . $campo};
            }

            // Solo guardar si hay datos
            if (!empty($fuenteData)) {
                $data[$campo] = json_encode($fuenteData);
            } else {
                $data[$campo] = '';
            }
        }

        // Campos que no necesitan procesamiento especial
        $data['herramienta_agricola'] = $request->herramienta_agricola;
        $data['distancia_finca_cabecera'] = $request->distancia_finca_cabecera;
        $data['transporte_cabecera'] = $request->transporte_cabecera;
        $data['vias_acceso'] = $request->vias_acceso;
        $data['condicion_vias'] = $request->condicion_vias;
        $data['uso_suelo_agricultura'] = $request->uso_suelo_agricultura;
        $data['uso_suelo_ganaderia'] = $request->uso_suelo_ganaderia;
        $data['uso_suelo_conservacion'] = $request->uso_suelo_conservacion;
        $data['uso_suelo_casa'] = $request->uso_suelo_casa;
        $data['uso_suelo_rastrojo'] = $request->uso_suelo_rastrojo;
        $data['almacen_maquinaria'] = $request->almacen_maquinaria;
        $data['almacen_insumos_quimicos'] = $request->almacen_insumos_quimicos;
        $data['almacen_abonos'] = $request->almacen_abonos;
        $data['condicion_terreno'] = $request->condicion_terreno;
        $data['sistema_riego'] = $request->sistema_riego;
        $data['otros_destinos_detalle'] = $request->otros_destinos_detalle;

        // Procesar fuentes de agua con JSON estructurado
        foreach ($fuenteCampos as $campo) {
            $fuenteData = [];

            // Usos seleccionados
            if ($request->has($campo)) {
                $fuenteData['usos'] = $request->$campo;
            }

            // Cantidad (si fue llenada)
            if ($request->has('cantidad_' . $campo) && !empty($request->{'cantidad_' . $campo})) {
                $fuenteData['cantidad'] = (int) $request->{'cantidad_' . $campo};
            }

            // Ubicado (si aplica y fue seleccionado)
            if ($request->has('ubicado_' . $campo) && !empty($request->{'ubicado_' . $campo})) {
                $fuenteData['ubicado'] = $request->{'ubicado_' . $campo};
            }

            // Nombre (si aplica y fue llenado)
            if ($request->has('nombre_' . $campo) && !empty($request->{'nombre_' . $campo})) {
                $fuenteData['nombre'] = $request->{'nombre_' . $campo};
            }

            // Solo guardar si hay datos
            if (!empty($fuenteData)) {
                $data[$campo] = json_encode($fuenteData);
            } else {
                $data[$campo] = '';
            }
        }

        $descripcion->update($data);

        return redirect()
            ->route('descripciones.show', $descripcion->id)
            ->with('success', 'Información de descripción actualizada correctamente.');
    }

    /**
     * Eliminar una descripción.
     */
    public function destroy(Descripcion $descripcion)
    {
        $encuesta_id = $descripcion->encuesta_id;
        $descripcion->delete();

        return redirect()
            ->route('encuestas.show', $encuesta_id)
            ->with('success', 'Registro de descripción eliminado.');
    }

    /**
     * Guardar o actualizar los datos del formulario.
     */
    public function guardarDescripcion(Request $request)
    {
        $encuesta_id = session('encuesta_id');

        if (!$encuesta_id) {
            return redirect()->route('encuestas.datos_personales')
                    ->with('error', 'Debe iniciar la encuesta primero.');
        }

        // Validación
        $data = $request->validate([
            'acueducto_publico'             => 'nullable|array',
            'acueducto_publico.*'           => 'nullable|string',
            'acuifero'                      => 'nullable|array',
            'acuifero.*'                    => 'nullable|string',
            'almacenamiento_aguas_lluvias' => 'nullable|array',
            'almacenamiento_aguas_lluvias.*' => 'nullable|string',
            'aljibes'                       => 'nullable|array',
            'aljibes.*'                     => 'nullable|string',
            'carrotanque'                   => 'nullable|array',
            'carrotanque.*'                 => 'nullable|string',
            'nacimientos'                   => 'nullable|array',
            'nacimientos.*'                 => 'nullable|string',
            'pila_publica'                  => 'nullable|array',
            'pila_publica.*'                => 'nullable|string',
            'pozos'                         => 'nullable|array',
            'pozos.*'                       => 'nullable|string',
            'red_distribucion_comunitaria'  => 'nullable|array',
            'red_distribucion_comunitaria.*' => 'nullable|string',
            'acueducto_veredal'             => 'nullable|array',
            'acueducto_veredal.*'           => 'nullable|string',
            'rios'                          => 'nullable|array',
            'rios.*'                        => 'nullable|string',
            'quebradas'                     => 'nullable|array',
            'quebradas.*'                   => 'nullable|string',
            'otro'                          => 'nullable|array',
            'otro.*'                        => 'nullable|string',

            'herramienta_agricola'          => 'nullable|string',
            'distancia_finca_cabecera'      => 'nullable|string',
            'transporte_cabecera'           => 'nullable|string',
            'vias_acceso'                   => 'nullable|string',
            'condicion_vias'                => 'nullable|string',

            'uso_suelo_agricultura'         => 'nullable|numeric',
            'uso_suelo_ganaderia'           => 'nullable|numeric',
            'uso_suelo_conservacion'        => 'nullable|numeric',
            'uso_suelo_casa'                => 'nullable|numeric',
            'uso_suelo_rastrojo'            => 'nullable|numeric',

            'almacen_maquinaria'            => 'nullable|string',
            'almacen_insumos_quimicos'      => 'nullable|string',
            'almacen_abonos'                => 'nullable|string',

            'condicion_terreno'             => 'nullable|string',
            'sistema_riego'                 => 'nullable|string',

            // Importante: ahora destino_produccion es un array
            'destino_produccion'            => 'nullable|array',
            'destino_produccion.*'          => 'nullable|string',

            'otros_destinos_detalle'        => 'nullable|string',
        ]);

        // ======== Procesar destino_produccion (convertir array a cadena) ========

        // Si no seleccionaron nada, guardar como cadena vacía
        if ($request->has('destino_produccion')) {
            $data['destino_produccion'] = implode(',', $request->destino_produccion);
        } else {
            $data['destino_produccion'] = '';
        }

        // ======== Procesar fuentes de agua con JSON estructurado ========
        $fuenteCampos = [
            'acueducto_publico',
            'acuifero',
            'almacenamiento_aguas_lluvias',
            'aljibes',
            'carrotanque',
            'nacimientos',
            'pila_publica',
            'pozos',
            'red_distribucion_comunitaria',
            'acueducto_veredal',
            'rios',
            'quebradas',
            'otro'
        ];

        foreach ($fuenteCampos as $campo) {
            $fuenteData = [];

            // Usos seleccionados
            if ($request->has($campo)) {
                $fuenteData['usos'] = $request->$campo;
            }

            // Cantidad (si fue llenada)
            if ($request->has('cantidad_' . $campo) && !empty($request->{'cantidad_' . $campo})) {
                $fuenteData['cantidad'] = (int) $request->{'cantidad_' . $campo};
            }

            // Ubicado (si aplica y fue seleccionado)
            if ($request->has('ubicado_' . $campo) && !empty($request->{'ubicado_' . $campo})) {
                $fuenteData['ubicado'] = $request->{'ubicado_' . $campo};
            }

            // Nombre (si aplica y fue llenado)
            if ($request->has('nombre_' . $campo) && !empty($request->{'nombre_' . $campo})) {
                $fuenteData['nombre'] = $request->{'nombre_' . $campo};
            }

            // Solo guardar si hay datos
            if (!empty($fuenteData)) {
                $data[$campo] = json_encode($fuenteData);
            } else {
                $data[$campo] = '';
            }
        }

        // Guardar el detalle de "otros destinos" (si lo llenaron)
        $data['otros_destinos_detalle'] = $request->otros_destinos_detalle ?? null;

        // Insertar el ID de la encuesta
        $data['encuesta_id'] = $encuesta_id;

        // Crear o actualizar
        Descripcion::updateOrCreate(
            ['encuesta_id' => $encuesta_id],
            $data
        );

        return redirect()
            ->route('encuestas.produccion')
            ->with('success', 'Descripción guardada correctamente.');
    }
}

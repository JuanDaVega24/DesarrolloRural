<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\InventarioPecuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventarioPecuarioController extends Controller
{
    /**
     * Mostrar el formulario del inventario pecuario.
     */
    public function create()
    {
        // Verificar encuesta en sesión
        $encuesta_id = session('encuesta_id');

        if (!$encuesta_id) {
            return redirect()->route('encuestas.datos_personales')
                ->with('error', 'Debe iniciar la encuesta primero.');
        }

        $encuesta = Encuesta::findOrFail($encuesta_id);

        // Si ya existe registro, cargarlo
        $pecuario = InventarioPecuario::where('encuesta_id', $encuesta_id)->first();

        // Convertir campos JSON a arrays para la vista
        if ($pecuario) {
            $pecesFields = [
                'peces_especie',
                'peces_cosechas_anio',
                'peces_animales_cosecha',
                'peces_peso_promedio',
                'peces_produccion_total_anterior',
                'peces_comercializacion'
            ];

            foreach ($pecesFields as $field) {
                if ($pecuario->$field && is_string($pecuario->$field)) {
                    $pecuario->$field = json_decode($pecuario->$field, true) ?: $pecuario->$field;
                }
            }
        }

        return view('encuestas.inventario_pecuario', compact('encuesta', 'pecuario'));
    }


    /**
     * Guardar o actualizar inventario pecuario.
     */
    public function guardarPecuario(Request $request)
    {
        $encuesta_id = session('encuesta_id');

        if (!$encuesta_id) {
            return redirect()->route('encuestas.datos_personales')
                ->with('error', 'Debe iniciar la encuesta primero.');
        }

        // Convertir valores del formulario antes de validar
        $convertedData = $this->convertFormValues($request->all());

        // Reglas de validación
        $rules = [
            // Ganado bovino
            'tiene_ganado_bovino'       => 'nullable|boolean',
            'orientacion_ganadera'     => 'nullable|string',
            'manejo_alimentacion'      => 'nullable|string',
            'vacunas_recibidas'        => 'nullable|string',
            'pago_biologico'           => 'nullable|boolean',

            'bovino_machos_menor1'     => 'nullable|integer',
            'bovino_machos_1a3'        => 'nullable|integer',
            'bovino_machos_mayor3'     => 'nullable|integer',
            'bovino_machos_reproductores' => 'nullable|integer',

            'bovino_hembras_menor1'    => 'nullable|integer',
            'bovino_hembras_1a3'       => 'nullable|integer',
            'bovino_hembras_mayor3'    => 'nullable|integer',
            'bovino_hembras_ordeño'    => 'nullable|integer',

            'produccion_leche_litros'  => 'nullable|numeric',
            'uso_leche'                => 'nullable|array',
            'uso_leche.*'              => 'nullable|string|in:Consumo,Comercialización',
            'porcentaje_uso_leche'     => 'nullable|array',
            'porcentaje_uso_leche.Consumo' => 'nullable|numeric|min:0|max:100',
            'porcentaje_uso_leche.Comercialización' => 'nullable|numeric|min:0|max:100',
            'comercializacion_leche'   => 'nullable|array',
            'comercializacion_leche.*' => 'nullable|string|in:Vecinos,Industria,Transformacion/Derivados',
            'porcentaje_comercializacion_leche' => 'nullable|array',
            'porcentaje_comercializacion_leche.Vecinos' => 'nullable|numeric|min:0|max:100',
            'porcentaje_comercializacion_leche.Industria' => 'nullable|numeric|min:0|max:100',
            'porcentaje_comercializacion_leche.Transformacion/Derivados' => 'nullable|numeric|min:0|max:100',

            // Cerdos
            'tiene_cerdos'             => 'nullable|boolean',
            'orientacion_porcicola'    => 'nullable|string',
            'vacuna_peste_clasica'     => 'nullable|boolean',
            'cerdos_machos_reproductores' => 'nullable|integer',
            'cerdos_hembras_gestantes' => 'nullable|integer',
            'cerdos_hembras_reemplazo' => 'nullable|integer',
            'cerdos_descartes'         => 'nullable|integer',
            'cerdos_destetos_anio'     => 'nullable|integer',
            'cerdos_ceba_anio'         => 'nullable|integer',

            // Aves
            'cria_gallinas_pollos_galpon' => 'nullable|boolean',
            'aves_ponedoras'              => 'nullable|integer',
            'aves_pollos_engorde'         => 'nullable|integer',
            'aves_genetica_huevo'         => 'nullable|string',
            'aves_genetica_engorde'       => 'nullable|string',
            'produccion_huevos_mes'       => 'nullable|numeric',
            'comercializacion_huevos'     => 'nullable|string',
            'pollo_comercializado_kg_mes' => 'nullable|numeric',
            'donde_comercializo_pollo'    => 'nullable|string',
            'metodo_sacrificio'           => 'nullable|string',
            'orientacion_avicola'         => 'nullable|string',
            'aves_ornamentales'           => 'nullable|integer',
            'aves_ornamentales'           => 'nullable|integer',
            'aves_ornamentales'           => 'nullable|integer',

            // Peces
            'cria_peces'                     => 'nullable|boolean',
            'peces_especie'                  => 'nullable|array',
            'peces_especie.*'                => 'nullable|string',
            'peces_cosechas_anio'            => 'nullable|array',
            'peces_cosechas_anio.*'          => 'nullable|integer',
            'peces_animales_cosecha'         => 'nullable|array',
            'peces_animales_cosecha.*'       => 'nullable|integer',
            'peces_peso_promedio'            => 'nullable|array',
            'peces_peso_promedio.*'          => 'nullable|numeric',
            'peces_produccion_total_anterior'=> 'nullable|array',
            'peces_produccion_total_anterior.*' => 'nullable|numeric',
            'peces_comercializacion'         => 'nullable|array',
            'peces_comercializacion.*'       => 'nullable|string',
            'peces_orientacion'              => 'nullable|string',

            // Otros animales
            'tiene_otros_animales'       => 'nullable|boolean',
            'caballos'                   => 'nullable|integer',
            'yeguas'                     => 'nullable|integer',
            'mulos'                      => 'nullable|integer',
            'mulas'                      => 'nullable|integer',
            'burros'                     => 'nullable|integer',
            'burras'                     => 'nullable|integer',
            'cabros'                     => 'nullable|integer',
            'cabras'                     => 'nullable|integer',
            'ovejos'                     => 'nullable|integer',
            'ovejas'                     => 'nullable|integer',
            'bufalos_machos'             => 'nullable|integer',
            'bufalos_hembras'            => 'nullable|integer',
            'vacuna_encefalitis_equina'  => 'nullable|boolean',
            'orientacion_ovino_caprina'  => 'nullable|string',

            // Traspatio
            'cerdos_traspatio'           => 'nullable|integer',
            'gallos_pollos_traspatio'    => 'nullable|integer',
            'gallos_pelea'               => 'nullable|integer',
            'pavos'                      => 'nullable|integer',
            'patos_gansos'               => 'nullable|integer',
            'codornices'                 => 'nullable|integer',
            'avestruces'                 => 'nullable|integer',
            'cuyes'                      => 'nullable|integer',
            'conejos'                    => 'nullable|integer',

            // Abejas
            'colmenas_miel'              => 'nullable|integer',
            'colmenas_polen'             => 'nullable|integer',
            'colmenas_subproductos'      => 'nullable|integer',
            'colmenas_meliponas'         => 'nullable|integer',

            // Mascotas
            'caninos_hembras'            => 'nullable|integer',
            'caninos_machos'             => 'nullable|integer',
            'felinos_hembras'            => 'nullable|integer',
            'felinos_machos'             => 'nullable|integer',
            'tortugas'                   => 'nullable|integer',

            // Otros
            'otros2'                     => 'nullable|string',
            'esterilizados'              => 'nullable|boolean',
        ];

        // Validar datos convertidos
        $validator = Validator::make($convertedData, $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Procesar uso de leche para guardarlo de forma legible
        if (isset($data['produccion_leche_litros']) && isset($data['uso_leche']) && isset($data['porcentaje_uso_leche'])) {
            $usosFormateados = [];

            foreach ($data['uso_leche'] as $uso) {
                $porcentaje = $data['porcentaje_uso_leche'][$uso] ?? null;
                if ($porcentaje !== null) {
                    $usosFormateados[] = $uso . ': ' . $porcentaje . '% de ' . $data['produccion_leche_litros'] . ' litros';
                }
            }

            // Guardar como string separado por saltos de línea
            $data['destino_leche'] = implode("\n", $usosFormateados);
        }

        // Procesar comercialización de leche para guardarlo de forma legible
        if (isset($data['comercializacion_leche']) && isset($data['porcentaje_comercializacion_leche'])) {
            $comercializacionesFormateadas = [];

            foreach ($data['comercializacion_leche'] as $comercializacion) {
                $porcentaje = $data['porcentaje_comercializacion_leche'][$comercializacion] ?? null;
                if ($porcentaje !== null) {
                    $comercializacionesFormateadas[] = $comercializacion . ': ' . $porcentaje . '%';
                }
            }

            // Guardar como string separado por comas
            $data['comercializacion_leche'] = implode(', ', $comercializacionesFormateadas);
        }

        // Procesar arrays de peces como JSON (igual que producción)
        $pecesFields = [
            'peces_especie',
            'peces_cosechas_anio',
            'peces_animales_cosecha',
            'peces_peso_promedio',
            'peces_produccion_total_anterior',
            'peces_comercializacion'
        ];

        foreach ($pecesFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode(array_filter($data[$field], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Insertar ID de encuesta
        $data['encuesta_id'] = $encuesta_id;

        // Crear o actualizar
        InventarioPecuario::updateOrCreate(
            ['encuesta_id' => $encuesta_id],
            $data
        );

        return redirect()
            ->route('encuestas.maquinaria', $encuesta_id)
            ->with('success', 'Inventario pecuario guardado correctamente.');
    }

    /**
     * Convertir valores del formulario para validación
     */
    private function convertFormValues(array $data): array
    {
        // Campos booleanos que necesitan conversión
        $booleanFields = [
            'tiene_ganado_bovino',
            'pago_biologico',
            'tiene_cerdos',
            'vacuna_peste_clasica',
            'cria_gallinas_pollos_galpon', // Este es string pero maneja si/no
            'cria_peces',
            'tiene_otros_animales',
            'vacuna_encefalitis_equina',
            'esterilizados'
        ];

        // Campos de string que pueden estar vacíos
        $stringFields = [
            'orientacion_ganadera',
            'manejo_alimentacion',
            'vacunas_recibidas',
            'orientacion_porcicola',
            'orientacion_avicola',
            'peces_orientacion',
            'orientacion_ovino_caprina'
        ];

        foreach ($booleanFields as $field) {
            if (isset($data[$field])) {
                if ($data[$field] === 'si') {
                    $data[$field] = true;
                } elseif ($data[$field] === 'no') {
                    $data[$field] = false;
                } elseif ($data[$field] === '') {
                    $data[$field] = null;
                }
            }
        }

        // Convertir strings vacías a null para campos opcionales
        foreach ($stringFields as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        // Convertir arrays de checkboxes vacíos
        if (isset($data['manejo_alimentacion']) && is_array($data['manejo_alimentacion'])) {
            $data['manejo_alimentacion'] = array_filter($data['manejo_alimentacion']);
            if (empty($data['manejo_alimentacion'])) {
                $data['manejo_alimentacion'] = null;
            } else {
                $data['manejo_alimentacion'] = implode(',', $data['manejo_alimentacion']);
            }
        }

        if (isset($data['vacunas_recibidas']) && is_array($data['vacunas_recibidas'])) {
            $data['vacunas_recibidas'] = array_filter($data['vacunas_recibidas']);
            if (empty($data['vacunas_recibidas'])) {
                $data['vacunas_recibidas'] = null;
            } else {
                $data['vacunas_recibidas'] = implode(',', $data['vacunas_recibidas']);
            }
        }

        if (isset($data['orientacion_avicola']) && is_array($data['orientacion_avicola'])) {
            $data['orientacion_avicola'] = array_filter($data['orientacion_avicola']);
            if (empty($data['orientacion_avicola'])) {
                $data['orientacion_avicola'] = null;
            } else {
                $data['orientacion_avicola'] = implode(',', $data['orientacion_avicola']);
            }
        }

        if (isset($data['peces_orientacion']) && is_array($data['peces_orientacion'])) {
            $data['peces_orientacion'] = array_filter($data['peces_orientacion']);
            if (empty($data['peces_orientacion'])) {
                $data['peces_orientacion'] = null;
            } else {
                $data['peces_orientacion'] = implode(',', $data['peces_orientacion']);
            }
        }

        if (isset($data['orientacion_ovino_caprina']) && is_array($data['orientacion_ovino_caprina'])) {
            $data['orientacion_ovino_caprina'] = array_filter($data['orientacion_ovino_caprina']);
            if (empty($data['orientacion_ovino_caprina'])) {
                $data['orientacion_ovino_caprina'] = null;
            } else {
                $data['orientacion_ovino_caprina'] = implode(',', $data['orientacion_ovino_caprina']);
            }
        }

        return $data;
    }

    /**
     * Mostrar detalles del inventario pecuario
     */
    public function show(InventarioPecuario $inventario_pecuario)
    {
        session(['encuesta_id' => $inventario_pecuario->encuesta_id]);
        $inventario_pecuario->load('encuesta');
        return view('encuestas.inventario_pecuario_show', compact('inventario_pecuario'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(InventarioPecuario $inventario_pecuario)
    {
        $inventario_pecuario->load('encuesta');

        // Convertir campos JSON a arrays para la vista
        $pecesFields = [
            'peces_especie',
            'peces_cosechas_anio',
            'peces_animales_cosecha',
            'peces_peso_promedio',
            'peces_produccion_total_anterior',
            'peces_comercializacion'
        ];

        foreach ($pecesFields as $field) {
            if ($inventario_pecuario->$field && is_string($inventario_pecuario->$field)) {
                $inventario_pecuario->$field = json_decode($inventario_pecuario->$field, true) ?: $inventario_pecuario->$field;
            }
        }

        return view('encuestas.inventario_pecuario_edit', compact('inventario_pecuario'));
    }

    /**
     * Actualizar inventario pecuario
     */
    public function update(Request $request, InventarioPecuario $inventario_pecuario)
    {
        // Convertir valores del formulario antes de validar
        $convertedData = $this->convertFormValues($request->all());

        // Reglas de validación
        $rules = [
            // Ganado bovino
            'tiene_ganado_bovino'       => 'nullable|boolean',
            'orientacion_ganadera'     => 'nullable|string',
            'manejo_alimentacion'      => 'nullable|string',
            'vacunas_recibidas'        => 'nullable|string',
            'pago_biologico'           => 'nullable|boolean',

            'bovino_machos_menor1'     => 'nullable|integer',
            'bovino_machos_1a3'        => 'nullable|integer',
            'bovino_machos_mayor3'     => 'nullable|integer',
            'bovino_machos_reproductores' => 'nullable|integer',

            'bovino_hembras_menor1'    => 'nullable|integer',
            'bovino_hembras_1a3'       => 'nullable|integer',
            'bovino_hembras_mayor3'    => 'nullable|integer',
            'bovino_hembras_ordeño'    => 'nullable|integer',

            'produccion_leche_litros'  => 'nullable|numeric',
            'uso_leche'                => 'nullable|array',
            'uso_leche.*'              => 'nullable|string|in:Consumo,Comercialización',
            'porcentaje_uso_leche'     => 'nullable|array',
            'porcentaje_uso_leche.Consumo' => 'nullable|numeric|min:0|max:100',
            'porcentaje_uso_leche.Comercialización' => 'nullable|numeric|min:0|max:100',
            'comercializacion_leche'   => 'nullable|array',
            'comercializacion_leche.*' => 'nullable|string|in:Vecinos,Industria,Transformacion/Derivados',
            'porcentaje_comercializacion_leche' => 'nullable|array',
            'porcentaje_comercializacion_leche.Vecinos' => 'nullable|numeric|min:0|max:100',
            'porcentaje_comercializacion_leche.Industria' => 'nullable|numeric|min:0|max:100',
            'porcentaje_comercializacion_leche.Transformacion/Derivados' => 'nullable|numeric|min:0|max:100',

            // Cerdos
            'tiene_cerdos'             => 'nullable|boolean',
            'orientacion_porcicola'    => 'nullable|string',
            'vacuna_peste_clasica'     => 'nullable|boolean',
            'cerdos_machos_reproductores' => 'nullable|integer',
            'cerdos_hembras_gestantes' => 'nullable|integer',
            'cerdos_hembras_reemplazo' => 'nullable|integer',
            'cerdos_descartes'         => 'nullable|integer',
            'cerdos_destetos_anio'     => 'nullable|integer',
            'cerdos_ceba_anio'         => 'nullable|integer',

            // Aves
            'cria_gallinas_pollos_galpon' => 'nullable|boolean',
            'aves_ponedoras'              => 'nullable|integer',
            'aves_pollos_engorde'         => 'nullable|integer',
            'aves_genetica_huevo'         => 'nullable|string',
            'aves_genetica_engorde'       => 'nullable|string',
            'produccion_huevos_mes'       => 'nullable|numeric',
            'comercializacion_huevos'     => 'nullable|string',
            'pollo_comercializado_kg_mes' => 'nullable|numeric',
            'donde_comercializo_pollo'    => 'nullable|string',
            'metodo_sacrificio'           => 'nullable|string',
            'orientacion_avicola'         => 'nullable|string',

            // Peces
            'cria_peces'                     => 'nullable|boolean',
            'peces_especie'                  => 'nullable|array',
            'peces_especie.*'                => 'nullable|string',
            'peces_cosechas_anio'            => 'nullable|array',
            'peces_cosechas_anio.*'          => 'nullable|integer',
            'peces_animales_cosecha'         => 'nullable|array',
            'peces_animales_cosecha.*'       => 'nullable|integer',
            'peces_peso_promedio'            => 'nullable|array',
            'peces_peso_promedio.*'          => 'nullable|numeric',
            'peces_produccion_total_anterior'=> 'nullable|array',
            'peces_produccion_total_anterior.*' => 'nullable|numeric',
            'peces_comercializacion'         => 'nullable|array',
            'peces_comercializacion.*'       => 'nullable|string',
            'peces_orientacion'              => 'nullable|string',

            // Otros animales
            'tiene_otros_animales'       => 'nullable|boolean',
            'caballos'                   => 'nullable|integer',
            'yeguas'                     => 'nullable|integer',
            'mulos'                      => 'nullable|integer',
            'mulas'                      => 'nullable|integer',
            'burros'                     => 'nullable|integer',
            'burras'                     => 'nullable|integer',
            'cabros'                     => 'nullable|integer',
            'cabras'                     => 'nullable|integer',
            'ovejos'                     => 'nullable|integer',
            'ovejas'                     => 'nullable|integer',
            'bufalos_machos'             => 'nullable|integer',
            'bufalos_hembras'            => 'nullable|integer',
            'vacuna_encefalitis_equina'  => 'nullable|boolean',
            'orientacion_ovino_caprina'  => 'nullable|string',

            // Traspatio
            'cerdos_traspatio'           => 'nullable|integer',
            'gallos_pollos_traspatio'    => 'nullable|integer',
            'gallos_pelea'               => 'nullable|integer',
            'pavos'                      => 'nullable|integer',
            'patos_gansos'               => 'nullable|integer',
            'codornices'                 => 'nullable|integer',
            'avestruces'                 => 'nullable|integer',
            'cuyes'                      => 'nullable|integer',
            'conejos'                    => 'nullable|integer',

            // Abejas
            'colmenas_miel'              => 'nullable|integer',
            'colmenas_polen'             => 'nullable|integer',
            'colmenas_subproductos'      => 'nullable|integer',
            'colmenas_meliponas'         => 'nullable|integer',

            // Mascotas
            'caninos_hembras'            => 'nullable|integer',
            'caninos_machos'             => 'nullable|integer',
            'felinos_hembras'            => 'nullable|integer',
            'felinos_machos'             => 'nullable|integer',
            'tortugas'                   => 'nullable|integer',

            // Otros
            'otros2'                     => 'nullable|string',
            'esterilizados'              => 'nullable|boolean',
        ];

        // Validar datos convertidos
        $validator = Validator::make($convertedData, $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Procesar uso de leche para guardarlo de forma legible
        if (isset($data['produccion_leche_litros']) && isset($data['uso_leche']) && isset($data['porcentaje_uso_leche'])) {
            $usosFormateados = [];

            foreach ($data['uso_leche'] as $uso) {
                $porcentaje = $data['porcentaje_uso_leche'][$uso] ?? null;
                if ($porcentaje !== null) {
                    $usosFormateados[] = $uso . ': ' . $porcentaje . '% de ' . $data['produccion_leche_litros'] . ' litros';
                }
            }

            // Guardar como string separado por saltos de línea
            $data['destino_leche'] = implode("\n", $usosFormateados);
        }

        // Procesar comercialización de leche para guardarlo de forma legible
        if (isset($data['comercializacion_leche']) && isset($data['porcentaje_comercializacion_leche'])) {
            $comercializacionesFormateadas = [];

            foreach ($data['comercializacion_leche'] as $comercializacion) {
                $porcentaje = $data['porcentaje_comercializacion_leche'][$comercializacion] ?? null;
                if ($porcentaje !== null) {
                    $comercializacionesFormateadas[] = $comercializacion . ': ' . $porcentaje . '%';
                }
            }

            // Guardar como string separado por comas
            $data['comercializacion_leche'] = implode(', ', $comercializacionesFormateadas);
        }

        // Procesar arrays de peces como JSON (igual que producción)
        $pecesFields = [
            'peces_especie',
            'peces_cosechas_anio',
            'peces_animales_cosecha',
            'peces_peso_promedio',
            'peces_produccion_total_anterior',
            'peces_comercializacion'
        ];

        foreach ($pecesFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode(array_filter($data[$field], fn($v) => $v !== null && $v !== ''));
            }
        }

        // Actualizar registro
        $inventario_pecuario->update($data);

        return redirect()
            ->route('inventario_pecuario.show', $inventario_pecuario->id)
            ->with('success', 'Inventario pecuario actualizado correctamente.');
    }
}

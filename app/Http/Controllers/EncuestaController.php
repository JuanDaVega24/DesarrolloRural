<?php

namespace App\Http\Controllers;

use App\Models\Corregimiento;
use App\Models\Encuesta;
use Illuminate\Http\Request;
use App\Models\Vereda;

class EncuestaController extends Controller
{
 public function index()
{
    return view('encuestas.index');
}


    public function show(Encuesta $encuesta)
    {
        $encuesta->load(['vereda', 'corregimiento', 'vivienda']);
        return view('encuestas.show', compact('encuesta'));
    }

    public function create()
    {
        return view('encuestas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha_encuesta' => 'nullable|date',
            'lugar_aplicacion' => 'nullable|string',
            'corregimiento_id' => 'required|exists:corregimientos,id',
            'vereda_id' => 'required|exists:veredas,id',

            'finca' => 'nullable|string',
            'area_predio' => 'nullable|numeric',
            'unidad_medida' => 'nullable|string',
            'coordenadas' => 'nullable|string',
            'area_total_disponible' => 'nullable|numeric',
            'unidad_medida2' => 'nullable|string',
            'altitud' => 'nullable|numeric',
            'nombre_identidad' => 'nullable|string',
            'primer_apellido' => 'nullable|string',
            'segundo_apellido' => 'nullable|string',
            'numero_documento' => 'nullable|string',
            'tipo_documento' => 'nullable|string',
            'fecha_expedicion' => 'nullable|date',
            'municipio_expedicion' => 'nullable|string',
            'departamento_expedicion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'municipio_nacimiento' => 'nullable|string',
            'departamento_nacimiento' => 'nullable|string',
            'genero' => 'nullable|string',
            'correo' => 'nullable|string',
            'celular_1' => 'nullable|string',
            'celular_2' => 'nullable|string',
            'nivel_educativo' => 'nullable|string',
            'que_estudio' => 'nullable|string',
            'actividades_agricolas' => 'nullable|integer',
            'actividades_pecuarias' => 'nullable|integer',
            'renta_ciudadana' => 'nullable|integer',
            'renta_joven' => 'nullable|integer',
            'colombia_mayor' => 'nullable|integer',
            'devolucion_iva' => 'nullable|integer',
            'pension' => 'nullable|integer',
            'arriendos' => 'nullable|integer',
            'empleo_formal' => 'nullable|integer',
            'actividad_comercial' => 'nullable|integer',
            'independiente' => 'nullable|integer',
            'otros' => 'nullable|integer',
            'tiempo_viviendo_finca' => 'nullable|string',
            'medio_transporte_propio' => 'nullable|string',
            'tenencia_tierra' => 'nullable|string',
            'pertenencia_poblacion_especial' => 'nullable|string',
            'le_gustaria_estudiar' => 'nullable|boolean',
            'que_le_gustaria_estudiar' => 'nullable|string',
            'trabaja_actualmente' => 'nullable|boolean',
            'tipo_empleo' => 'nullable|string',
            'tipo_contrato' => 'nullable|string',
            'tipo_tenencia' => 'nullable|string',
        ]);

        $encuesta = Encuesta::create($data);

        return redirect()
            ->route('encuestas.index')
            ->with('success', 'Encuesta creada correctamente.');
    }

    public function edit(Encuesta $encuesta)
    {
        $encuesta->load(['vereda', 'corregimiento']);
        $corregimientos = Corregimiento::orderBy('nombre')->get();
        $veredas = Vereda::where('corregimiento_id', $encuesta->corregimiento_id)
                         ->orderBy('nombre')
                         ->get();

        return view('encuestas.edit', compact('encuesta', 'corregimientos', 'veredas'));
    }

    public function update(Request $request, Encuesta $encuesta)
    {
        $data = $request->validate([
            'fecha_encuesta' => 'nullable|date',
            'lugar_aplicacion' => 'nullable|string',
            'corregimiento_id' => 'required|exists:corregimientos,id',
            'vereda_id' => 'required|exists:veredas,id',

            'finca' => 'nullable|string',
            'area_predio' => 'nullable|numeric',
            'unidad_medida' => 'nullable|string',
            'coordenadas' => 'nullable|string',
            'area_total_disponible' => 'nullable|numeric',
            'unidad_medida2' => 'nullable|string',
            'altitud' => 'nullable|numeric',
            'nombre_identidad' => 'nullable|string',
            'primer_apellido' => 'nullable|string',
            'segundo_apellido' => 'nullable|string',
            'numero_documento' => 'nullable|string',
            'tipo_documento' => 'nullable|string',
            'fecha_expedicion' => 'nullable|date',
            'municipio_expedicion' => 'nullable|string',
            'departamento_expedicion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'municipio_nacimiento' => 'nullable|string',
            'departamento_nacimiento' => 'nullable|string',
            'genero' => 'nullable|string',
            'correo' => 'nullable|string',
            'celular_1' => 'nullable|string',
            'celular_2' => 'nullable|string',
            'nivel_educativo' => 'nullable|string',
            'que_estudio' => 'nullable|string',
            'actividades_agricolas' => 'nullable|integer',
            'actividades_pecuarias' => 'nullable|integer',
            'renta_ciudadana' => 'nullable|integer',
            'renta_joven' => 'nullable|integer',
            'colombia_mayor' => 'nullable|integer',
            'devolucion_iva' => 'nullable|integer',
            'pension' => 'nullable|integer',
            'arriendos' => 'nullable|integer',
            'empleo_formal' => 'nullable|integer',
            'actividad_comercial' => 'nullable|integer',
            'independiente' => 'nullable|integer',
            'otros' => 'nullable|integer',
            'tiempo_viviendo_finca' => 'nullable|string',
            'medio_transporte_propio' => 'nullable|string',
            'tenencia_tierra' => 'nullable|string',
            'pertenencia_poblacion_especial' => 'nullable|string',
            'le_gustaria_estudiar' => 'nullable|boolean',
            'que_le_gustaria_estudiar' => 'nullable|string',
            'trabaja_actualmente' => 'nullable|boolean',
            'tipo_empleo' => 'nullable|string',
            'tipo_contrato' => 'nullable|string',
            'tipo_tenencia' => 'nullable|string',
        ]);

        $encuesta->update($data);

        return redirect()
            ->route('encuestas.index')
            ->with('success', 'Encuesta actualizada correctamente.');
    }

    public function destroy(Encuesta $encuesta)
    {
        $encuesta->delete();

        return redirect()
            ->route('encuestas.index')
            ->with('success', 'Caracterizacion eliminada correctamente.');
    }

    public function datosPersonales()
{
    $corregimientos = Corregimiento::orderBy('nombre')->get();
    $veredas = []; // inicial vacío

    return view('encuestas.datos_personales', compact('corregimientos', 'veredas'));
}


    public function guardarDatosPersonales(Request $request)
{
    $data = $request->validate([
        // Información general
        'fecha_encuesta' => 'nullable|date',
        'lugar_aplicacion' => 'nullable|string',
        'corregimiento' => 'required|exists:corregimientos,id',
        'vereda' => 'required|exists:veredas,id',
        'finca' => 'nullable|string',

        // Área del predio
        'area_predio' => 'nullable|numeric',
        'unidad_medida' => 'nullable|string',
        'coordenadas' => 'nullable|string',
        'area_total_disponible' => 'nullable|numeric',
        'unidad_medida2' => 'nullable|string',
        'altitud' => 'nullable|numeric',

        // Información del encuestado
        'nombre_identidad' => 'nullable|string',
        'primer_apellido' => 'nullable|string',
        'segundo_apellido' => 'nullable|string',
        'numero_documento' => 'nullable|string',
        'tipo_documento' => 'nullable|string',
        'fecha_expedicion' => 'nullable|date',
        'municipio_expedicion' => 'nullable|string',
        'departamento_expedicion' => 'nullable|string',
        'fecha_nacimiento' => 'nullable|date',
        'municipio_nacimiento' => 'nullable|string',
        'departamento_nacimiento' => 'nullable|string',
        'genero' => 'nullable|string',

        // Información de contacto
        'correo' => 'nullable|email',
        'celular_1' => 'nullable|string',
        'celular_2' => 'nullable|string',

        // Educación
        'nivel_educativo' => 'nullable|string',
        'que_estudio' => 'nullable|string',

        // Ingresos del hogar
        'actividades_agricolas' => 'nullable|integer',
        'actividades_pecuarias' => 'nullable|integer',
        'renta_ciudadana' => 'nullable|integer',
        'renta_joven' => 'nullable|integer',
        'colombia_mayor' => 'nullable|integer',
        'devolucion_iva' => 'nullable|integer',
        'pension' => 'nullable|integer',
        'arriendos' => 'nullable|integer',
        'empleo_formal' => 'nullable|integer',
        'actividad_comercial' => 'nullable|integer',
        'independiente' => 'nullable|integer',
        'otros' => 'nullable|integer',

        // Información adicional
        'tiempo_viviendo_finca' => 'nullable|string',
        'medio_transporte_propio' => 'nullable|string',
        'pertenencia_poblacion_especial' => 'nullable|string',
        'le_gustaria_estudiar' => 'nullable|boolean',
        'que_le_gustaria_estudiar' => 'nullable|string',
        'trabaja_actualmente' => 'nullable|boolean',
        'tipo_empleo' => 'nullable|string',
        'tenencia_tierra' => 'nullable|string',
        'tipo_tenencia' => 'nullable|string',
        'tipo_contrato' => 'nullable|string',
        'duracion_contrato' => 'nullable|string',
    ]);

    // Mapear nombres de campos para la base de datos
    $data['corregimiento_id'] = $data['corregimiento'];
    $data['vereda_id'] = $data['vereda'];
    unset($data['corregimiento'], $data['vereda']);

    $encuesta = Encuesta::create($data);

    session(['encuesta_id' => $encuesta->id]);

    return redirect()
        ->route('encuestas.vivienda')
        ->with('success', 'Datos personales guardados correctamente. Continúa con Vivienda.');
}


public function getVeredas($id)
{
    return Vereda::where('corregimiento_id', $id)
                 ->orderBy('nombre')
                 ->get();
}

public function establecerSesion($encuestaId)
{
    session(['encuesta_id' => $encuestaId]);
    return response()->json(['success' => true]);
}

}

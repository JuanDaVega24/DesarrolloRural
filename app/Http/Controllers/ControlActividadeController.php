<?php

namespace App\Http\Controllers;

use App\Models\ControlActividade;
use App\Models\Encuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ControlActividadeController extends Controller
{
    /**
     * Mostrar el formulario de control de actividades
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
        $controlActividade = ControlActividade::where('encuesta_id', $encuesta_id)->first();

        return view('encuestas.control_actividades', compact('encuesta', 'controlActividade'));
    }

    /**
     * Guardar o actualizar los datos del formulario.
     */
    public function guardarControlActividade(Request $request)
    {
        $encuesta_id = session('encuesta_id');

        if (!$encuesta_id) {
            return redirect()->route('encuestas.datos_personales')
                ->with('error', 'Debe iniciar la encuesta primero.');
        }

        // Validar datos
        $validator = Validator::make($request->all(), [
            'unidad_productiva' => 'nullable|string',
            'cuales' => 'nullable|string',
            'fertilizantes' => 'nullable|string',
            'tipo_fertilizantes' => 'nullable|string',
            'frecuencia_aplicacion' => 'nullable|string',
            'mecanismos' => 'nullable|string',
            'analisis' => 'nullable|string',
            'analisis_ayuda' => 'nullable|string',
            'fecha_analisis' => 'nullable|integer',
            'control' => 'nullable|string',
            'frecuencia' => 'nullable|string',
            'control_plagas' => 'nullable|string',
            'tipo_control' => 'nullable|string',
            'conoce_BPA' => 'nullable|string',
            'conoce_inocuidad' => 'nullable|string',
            'desinfectar' => 'nullable|string',
            'toxicidad' => 'nullable|string',
            'proteccion' => 'nullable|string',
            'cuales_proteccion' => 'nullable|string',
            'plaguicidas' => 'nullable|string',
            'tiempo_plaguicida' => 'nullable|string',
            'cultivo_plaguicida' => 'nullable|string',
            'envases_plaguicida' => 'nullable|string',
            'calidad_predio' => 'nullable|string',
            'analisis_agua' => 'nullable|string',
            'cual_analisis' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['encuesta_id'] = $encuesta_id;

        // Crear o actualizar
        ControlActividade::updateOrCreate(
            ['encuesta_id' => $encuesta_id],
            $data
        );

        return redirect()
            ->route('encuestas.familiares')
            ->with('success', 'Información de control de actividades guardada correctamente.');
    }

    public function show(ControlActividade $controlActividade)
    {
        session(['encuesta_id' => $controlActividade->encuesta_id]);
        $controlActividade->load('encuesta');
        return view('encuestas.control_actividades_show', compact('controlActividade'));
    }

    public function edit(ControlActividade $controlActividade)
    {
        $controlActividade->load('encuesta');
        return view('encuestas.control_actividades_edit', compact('controlActividade'));
    }

    public function update(Request $request, ControlActividade $controlActividade)
    {
        // Validar datos
        $validator = Validator::make($request->all(), [
            'unidad_productiva' => 'nullable|string',
            'cuales' => 'nullable|string',
            'fertilizantes' => 'nullable|string',
            'tipo_fertilizantes' => 'nullable|string',
            'frecuencia_aplicacion' => 'nullable|string',
            'mecanismos' => 'nullable|string',
            'analisis' => 'nullable|string',
            'analisis_ayuda' => 'nullable|string',
            'fecha_analisis' => 'nullable|integer',
            'control' => 'nullable|string',
            'frecuencia' => 'nullable|string',
            'control_plagas' => 'nullable|string',
            'tipo_control' => 'nullable|string',
            'conoce_BPA' => 'nullable|string',
            'conoce_inocuidad' => 'nullable|string',
            'desinfectar' => 'nullable|string',
            'toxicidad' => 'nullable|string',
            'proteccion' => 'nullable|string',
            'cuales_proteccion' => 'nullable|string',
            'plaguicidas' => 'nullable|string',
            'tiempo_plaguicida' => 'nullable|string',
            'cultivo_plaguicida' => 'nullable|string',
            'envases_plaguicida' => 'nullable|string',
            'calidad_predio' => 'nullable|string',
            'analisis_agua' => 'nullable|string',
            'cual_analisis' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $controlActividade->update($data);

        return redirect()
            ->route('control_actividades.show', $controlActividade->id)
            ->with('success', 'Información de control de actividades actualizada correctamente.');
    }

    public function destroy(ControlActividade $controlActividade)
    {
        $encuesta_id = $controlActividade->encuesta_id;
        $controlActividade->delete();

        return redirect()
            ->route('encuestas.show', $encuesta_id)
            ->with('success', 'Registro de control de actividades eliminado.');
    }
}

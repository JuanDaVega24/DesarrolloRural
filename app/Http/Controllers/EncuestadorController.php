<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Encuestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncuestadorController extends Controller
{
    /**
     * Mostrar los detalles del encuestador para una encuesta
     */
    public function show(Encuesta $encuesta)
    {
        session(['encuesta_id' => $encuesta->id]);
        $encuestador = Encuestador::where('encuesta_id', $encuesta->id)
            ->with(['encuesta', 'user'])
            ->firstOrFail();

        return view('encuestas.encuestador_show', compact('encuestador'));
    }

    /**
     * Mostrar el formulario final de la encuesta
     */
    public function create()
    {
        $encuestaId = session('encuesta_id');

        if (!$encuestaId) {
            return redirect()->route('encuestas.index')
                ->with('error', 'No se encontró la encuesta en progreso.');
        }

        $encuesta = Encuesta::findOrFail($encuestaId);

        // Verificar si ya existe información del encuestador
        $encuestador = Encuestador::where('encuesta_id', $encuestaId)->first();

        return view('encuestas.final', compact('encuesta', 'encuestador'));
    }

    /**
     * Guardar la información del encuestador y finalizar la encuesta
     */
    public function store(Request $request)
    {
        $encuestaId = session('encuesta_id');

        if (!$encuestaId) {
            return redirect()->route('encuestas.index')
                ->with('error', 'No se encontró la encuesta en progreso.');
        }

        $data = $request->validate([
    'documento_encuestador' => 'required|string|max:20',
    'telefono_encuestador' => 'required|string|max:15',
    'observaciones' => 'nullable|string|max:1000',
    'autorizacion_datos' => 'required|accepted',
], [
    'documento_encuestador.required' => 'El número de documento del encuestador es obligatorio.',
    'telefono_encuestador.required' => 'El número de teléfono del encuestador es obligatorio.',
    'autorizacion_datos.required' => 'Debe aceptar la autorización de tratamiento de datos.',
    'autorizacion_datos.accepted' => 'Debe aceptar la autorización de tratamiento de datos.',
    // Agregar otros mensajes según necesites
]);


        // Verificar si ya existe un registro para esta encuesta
        $encuestador = Encuestador::where('encuesta_id', $encuestaId)->first();

        if ($encuestador) {
            // Actualizar registro existente
            $encuestador->update([
                'documento_encuestador' => $data['documento_encuestador'],
                'telefono_encuestador' => $data['telefono_encuestador'],
                'observaciones' => $data['observaciones'],
                'autorizacion_datos' => true,
            ]);
        } else {
            // Crear nuevo registro
            Encuestador::create([
                'encuesta_id' => $encuestaId,
                'user_id' => Auth::id(),
                'nombre_encuestador' => Auth::user()->name,
                'documento_encuestador' => $data['documento_encuestador'],
                'telefono_encuestador' => $data['telefono_encuestador'],
                'observaciones' => $data['observaciones'],
                'autorizacion_datos' => true,
            ]);
        }

        // Limpiar la sesión de la encuesta
        session()->forget('encuesta_id');

        return redirect()->route('encuestas.index')
            ->with('success', 'Caracterización completada exitosamente.');
    }

    /**
     * Eliminar la información del encuestador
     */
    public function destroy(Encuesta $encuesta)
    {
        $encuestador = Encuestador::where('encuesta_id', $encuesta->id)->first();

        if ($encuestador) {
            $encuestador->delete();

            return redirect()->route('encuestador.show', $encuesta->id)
                ->with('success', 'Información del encuestador eliminada correctamente.');
        }

        return redirect()->route('encuestador.show', $encuesta->id)
            ->with('error', 'No se encontró información del encuestador para eliminar.');
    }
}

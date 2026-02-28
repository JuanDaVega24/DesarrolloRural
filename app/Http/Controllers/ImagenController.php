<?php

namespace App\Http\Controllers;

use App\Models\FormularioPregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImagenController extends Controller
{
    /**
     * Subir imágenes para una pregunta
     */
    public function upload(Request $request, FormularioPregunta $pregunta)
    {
        // Verificar que la pregunta pertenece a un proyecto manual
        if ($pregunta->proyecto->origen !== 'manual') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden subir imágenes a preguntas de proyectos manuales.'
            ], 400);
        }

        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'imagenes' => 'required|array',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Máximo 2MB por imagen
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $imagenes = $request->file('imagenes');
            $imagenesPaths = [];
            $imagenesUrls = [];

            // Crear directorio para las imágenes del proyecto
            $projectDir = 'proyectos/' . $pregunta->proyecto_id . '/imagenes';

            foreach ($imagenes as $index => $imagen) {
                // Generar nombre único para la imagen
                $originalName = $imagen->getClientOriginalName();
                $extension = $imagen->getClientOriginalExtension();
                $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '_' . $index . '.' . $extension;
                
                // Subir la imagen
                $path = $imagen->storeAs($projectDir, $fileName, 'public');
                
                // Obtener URL pública
                $url = Storage::url($path);
                
                $imagenesPaths[] = $path;
                $imagenesUrls[] = $url;
            }

            // Actualizar la pregunta con las URLs de las imágenes
            $imagenesActuales = $pregunta->imagenes ?? [];
            $imagenesActuales = array_merge($imagenesActuales, $imagenesUrls);
            
            $pregunta->update([
                'imagenes' => $imagenesActuales
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Imágenes subidas exitosamente',
                'imagenes' => $imagenesUrls,
                'pregunta_id' => $pregunta->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir las imágenes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una imagen de una pregunta
     */
    public function destroy(Request $request, FormularioPregunta $pregunta)
    {
        $request->validate([
            'imagen_url' => 'required|string'
        ]);

        try {
            $imagenUrl = $request->imagen_url;
            $imagenesActuales = $pregunta->imagenes ?? [];

            // Encontrar y eliminar la imagen
            $key = array_search($imagenUrl, $imagenesActuales);
            if ($key !== false) {
                // Eliminar del array
                unset($imagenesActuales[$key]);
                $imagenesActuales = array_values($imagenesActuales); // Reindexar array

                // Eliminar del sistema de archivos
                $path = str_replace('/storage/', 'public/', $imagenUrl);
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }

                // Actualizar la pregunta
                $pregunta->update([
                    'imagenes' => $imagenesActuales
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Imagen eliminada exitosamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Imagen no encontrada'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la imagen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener imágenes de una pregunta
     */
    public function getImages(FormularioPregunta $pregunta)
    {
        return response()->json([
            'imagenes' => $pregunta->imagenes ?? []
        ]);
    }
}
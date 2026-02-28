<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConstructorImagenController extends Controller
{
    /**
     * Subir imágenes para el constructor de formularios
     */
    public function upload(Request $request)
    {
        // Validar la solicitud - aceptar tanto 'imagen' (única) como 'imagenes' (array)
        $validator = Validator::make($request->all(), [
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Máximo 2MB por imagen
            'imagenes' => 'nullable|array',
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
            $imagenesPaths = [];
            $imagenesUrls = [];

            // Crear directorio temporal para imágenes del constructor
            $projectDir = 'constructor/temp';
            
            // Usar una ruta más segura que no dependa del enlace simbólico
            $publicDir = public_path('uploads/constructor/temp');
            if (!file_exists($publicDir)) {
                mkdir($publicDir, 0755, true);
            }

            // Manejar subida de una sola imagen (formato del JavaScript simple)
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                
                // Generar nombre único para la imagen
                $originalName = $imagen->getClientOriginalName();
                $extension = $imagen->getClientOriginalExtension();
                $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
                
                // Subir la imagen a la ruta pública directa
                $path = $imagen->move($publicDir, $fileName);
                
                // Obtener URL pública directa
                $url = asset('uploads/constructor/temp/' . $fileName);
                
                $imagenesPaths[] = 'uploads/constructor/temp/' . $fileName;
                $imagenesUrls[] = $url;
            }
            // Manejar subida de múltiples imágenes (formato array)
            elseif ($request->hasFile('imagenes')) {
                $imagenes = $request->file('imagenes');
                
                foreach ($imagenes as $index => $imagen) {
                    // Generar nombre único para la imagen
                    $originalName = $imagen->getClientOriginalName();
                    $extension = $imagen->getClientOriginalExtension();
                    $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '_' . $index . '.' . $extension;
                    
                    // Subir la imagen a la ruta pública directa
                    $path = $imagen->move($publicDir, $fileName);
                    
                    // Obtener URL pública directa
                    $url = asset('uploads/constructor/temp/' . $fileName);
                    
                    $imagenesPaths[] = 'uploads/constructor/temp/' . $fileName;
                    $imagenesUrls[] = $url;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Imágenes subidas exitosamente',
                'imagenes' => $imagenesUrls
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir las imágenes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una imagen del constructor
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'imagen_url' => 'required|string'
        ]);

        try {
            $imagenUrl = $request->imagen_url;

            // Eliminar del sistema de archivos (nueva ruta pública)
            $path = str_replace(asset('uploads/constructor/temp/'), public_path('uploads/constructor/temp/'), $imagenUrl);
            if (file_exists($path)) {
                unlink($path);
            }

            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la imagen: ' . $e->getMessage()
            ], 500);
        }
    }
}
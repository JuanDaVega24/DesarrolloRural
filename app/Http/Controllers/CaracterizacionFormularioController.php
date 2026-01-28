<?php

namespace App\Http\Controllers;

use App\Models\Caracterizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaracterizacionFormularioController extends Controller
{
    /**
     * Mostrar formulario para agregar registros a caracterización existente
     */
    public function show()
    {
        // Obtener la caracterización global (ID=1)
        $caracterizacion = Caracterizacion::find(1);

        if (!$caracterizacion || !$caracterizacion->data) {
            return redirect()->route('caracterizaciones.index')
                           ->with('error', 'No hay caracterización configurada o no tiene datos.');
        }

        // Obtener columnas de la caracterización existente
        $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
        $columnasReferencia = $data['headers'] ?? [];
        $conditionalRules = $data['conditional_rules'] ?? [];

        if (empty($columnasReferencia)) {
            return redirect()->route('caracterizaciones.index')
                           ->with('error', 'La caracterización no tiene columnas definidas.');
        }

        // Calcular el siguiente ID disponible basado en los registros existentes
        $registrosExistentes = $data['rows'] ?? [];
        $ultimoId = 0;
        $columnaId = null;

        // Buscar cuál es la columna que actúa como ID (misma lógica que en la vista)
        foreach ($columnasReferencia as $columna) {
            $columnaLower = strtolower($columna);
            if (preg_match('/(^|[\s_])id($|[\s_])/', $columnaLower) && !str_contains($columnaLower, 'cedula') && !str_contains($columnaLower, 'documento')) {
                $columnaId = $columna;
                break;
            }
        }

        if ($columnaId) {
            foreach ($registrosExistentes as $row) {
                if (isset($row[$columnaId]) && is_numeric($row[$columnaId])) {
                    $val = (int)$row[$columnaId];
                    if ($val > $ultimoId) {
                        $ultimoId = $val;
                    }
                }
            }
        } else {
            // Fallback: si no se detecta columna ID explícita, usar el conteo de filas
            $ultimoId = count($registrosExistentes);
        }

        $siguienteId = $ultimoId + 1;

  // Calcular el siguiente ID disponible basado en los registros existentes
        $registrosExistentes = $data['rows'] ?? [];
        $ultimoId = 0;
        $columnaId = null;

        // Buscar cuál es la columna que actúa como ID (misma lógica que en la vista)
        foreach ($columnasReferencia as $columna) {
            $columnaLower = strtolower($columna);
            if (preg_match('/(^|[\s_])id($|[\s_])/', $columnaLower) && !str_contains($columnaLower, 'cedula') && !str_contains($columnaLower, 'documento')) {
                $columnaId = $columna;
                break;
            }
        }

        if ($columnaId) {
            foreach ($registrosExistentes as $row) {
                if (isset($row[$columnaId]) && is_numeric($row[$columnaId])) {
                    $val = (int)$row[$columnaId];
                    if ($val > $ultimoId) {
                        $ultimoId = $val;
                    }
                }
            }
        } else {
            // Fallback: si no se detecta columna ID explícita, usar el conteo de filas
            $ultimoId = count($registrosExistentes);
        }

        $siguienteId = $ultimoId + 1;

        return view('caracterizaciones.formulario', compact('caracterizacion', 'columnasReferencia', 'conditionalRules', 'siguienteId'));    }

    /**
     * Agregar nuevos registros a la caracterización existente
     */
    public function update(Request $request)
    {
        // Obtener la caracterización global (ID=1)
        $caracterizacion = Caracterizacion::find(1);

        if (!$caracterizacion || !$caracterizacion->data) {
            return redirect()->route('caracterizaciones.index')
                           ->with('error', 'No hay caracterización configurada.');
        }

        // Validar datos básicos
        $validated = $request->validate([
            'beneficiarios_acumulados' => 'required|string',
        ]);

        // Decodificar el JSON de beneficiarios acumulados
        $beneficiariosJson = json_decode($validated['beneficiarios_acumulados'], true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($beneficiariosJson) || empty($beneficiariosJson)) {
            return back()->withInput()->with('error', 'Los datos de beneficiarios no son válidos.');
        }

        if (count($beneficiariosJson) > 100) {
            return back()->withInput()->with('error', 'No puede agregar más de 100 registros en una sola operación.');
        }

        // Obtener datos actuales de la caracterización
        $dataActual = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
        $headers = $dataActual['headers'] ?? [];
        $registrosExistentes = $dataActual['rows'] ?? [];

        if (empty($headers)) {
            return back()->withInput()->with('error', 'La caracterización no tiene columnas definidas.');
        }

        // Procesar datos de beneficiarios
        $rows = [];
        $errores = [];

        foreach ($beneficiariosJson as $beneficiarioId => $beneficiarioData) {
            $rowData = [];

            // Limpiar datos de entrada - usar solo las columnas que existen en la caracterización
            $beneficiarioData = array_intersect_key($beneficiarioData, array_flip($headers));

            // Validar que cada registro tenga al menos un campo con datos
            $tieneDatos = false;
            foreach ($headers as $header) {
                if (!empty(trim((string)($beneficiarioData[$header] ?? '')))) {
                    $tieneDatos = true;
                    break;
                }
            }

            if (!$tieneDatos) {
                $errores[] = "Registro " . ($beneficiarioId + 1) . ": Debe tener al menos un campo con datos";
                continue;
            }

            // Procesar cada campo usando los headers existentes
            foreach ($headers as $header) {
                $valor = $beneficiarioData[$header] ?? '';
                $rowData[$header] = trim((string)$valor);
            }

            $rows[] = $rowData;
        }

        // Si hay errores, devolver con errores
        if (!empty($errores)) {
            return back()->withInput()->withErrors($errores);
        }

        // Si no hay filas válidas, error
        if (empty($rows)) {
            return back()->withInput()->with('error', 'Debe ingresar al menos un registro válido.');
        }

        try {
            // Combinar registros existentes con los nuevos
            $todosLosRegistros = array_merge($registrosExistentes, $rows);

            // Preparar datos actualizados para guardar
            $dataToSave = [
                'filename' => $dataActual['filename'] ?? 'Caracterización Manual',
                'uploaded_by' => $dataActual['uploaded_by'] ?? Auth::user()->name,
                'headers' => $headers,
                'rows' => $todosLosRegistros,
                'uploaded_at' => $dataActual['uploaded_at'] ?? now()->toISOString(),
                'total_rows' => count($todosLosRegistros),
                'total_columns' => count($headers)
            ];

            // Actualizar la caracterización con los nuevos registros
            $caracterizacion->update([
                'data' => $dataToSave,
            ]);

            $mensaje = count($rows) === 1 ?
                '¡Registro agregado exitosamente!' :
                "¡" . count($rows) . " registros agregados exitosamente!";

            return redirect()->route('caracterizaciones.index')
                           ->with('success', $mensaje);

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar la caracterización: ' . $e->getMessage());
        }
    }

    /**
     * Validar si una cédula existe en la caracterización
     */
    public function validarCedula(Request $request)
    {
        $cedula = $request->input('cedula');

        if (!$cedula) {
            return response()->json(['error' => 'Cédula es requerida'], 400);
        }

        // Obtener la caracterización global
        $caracterizacion = Caracterizacion::find(1);

        if (!$caracterizacion || !$caracterizacion->data) {
            return response()->json(['error' => 'No hay caracterización configurada'], 404);
        }

        $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
        $headers = $data['headers'] ?? [];
        $rows = $data['rows'] ?? [];

        // Buscar columna de documento
        $documentColumn = null;
        foreach ($headers as $header) {
            $headerNormalized = strtolower(trim($header));
            if (str_contains($headerNormalized, 'cedula') || str_contains($headerNormalized, 'cédula') ||
                str_contains($headerNormalized, 'documento') || str_contains($headerNormalized, 'dni') ||
                str_contains($headerNormalized, 'identidad')) {
                $documentColumn = $header;
                break;
            }
        }

        $found = false;
        if ($documentColumn) {
            foreach ($rows as $row) {
                if (isset($row[$documentColumn]) && trim((string)$row[$documentColumn]) === trim((string)$cedula)) {
                    $found = true;
                    break;
                }
            }
        }

        return response()->json([
            'found_in_caracterizacion' => $found,
            'document_column' => $documentColumn
        ]);
    }
}

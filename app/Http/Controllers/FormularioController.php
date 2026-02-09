<?php

namespace App\Http\Controllers;

use App\Models\ProyectoProductivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormularioController extends Controller
{
    /**
     * Mostrar lista de proyectos manuales
     */
    public function index()
    {
        // Obtener proyectos creados manualmente
        $proyectosManuales = ProyectoProductivo::where('origen', 'manual')
            ->latest()
            ->paginate(20);

        return view('formularios.index', compact('proyectosManuales'));
    }

    /**
     * Mostrar formulario para completar proyecto manual
     */
    public function show(ProyectoProductivo $proyecto)
    {
        // Verificar que sea un proyecto manual
        if ($proyecto->origen !== 'manual') {
            abort(404, 'Proyecto no encontrado');
        }

        // Obtener columnas de referencia
        $columnasReferencia = $this->getColumnasReferencia();

        return view('formularios.show', compact('proyecto', 'columnasReferencia'));
    }

    /**
     * Actualizar proyecto manual con datos completos
     */
    public function update(Request $request, ProyectoProductivo $proyecto)
    {
        // Verificar que sea un proyecto manual
        if ($proyecto->origen !== 'manual') {
            abort(404, 'Proyecto no encontrado');
        }

        // Validar datos básicos
        $validated = $request->validate([
            'beneficiarios_acumulados' => 'required|string',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        // Decodificar el JSON de beneficiarios acumulados
        $beneficiariosJson = json_decode($validated['beneficiarios_acumulados'], true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($beneficiariosJson) || empty($beneficiariosJson)) {
            return back()->withInput()->with('error', 'Los datos de beneficiarios no son válidos.');
        }

        if (count($beneficiariosJson) > 50) {
            return back()->withInput()->with('error', 'No puede agregar más de 50 beneficiarios.');
        }

        // Para proyectos manuales, usar campos fijos del formulario estático
        $headers = [
            '# DEL SORTEO',
            'CÉDULA',
            'NOMBRE COMPLETO',
            'GENERO',
            'CONDICIÓN',
            'FECHA DE NACIMIENTO',
            'FECHA DE EXPEDICIÓN',
            'CORREGIMIENTO',
            'VEREDA',
            'SISBEN',
            'FINCA',
            'TELÉFONO',
            'LUGAR DE ENTREGA',
            'EVIDENCIA FOTOGRAFICA',
            'CONSULTA BD'
        ];

        // Procesar datos de beneficiarios
        $rows = [];
        $errores = [];

        foreach ($beneficiariosJson as $beneficiarioId => $beneficiarioData) {
            $rowData = [];

            // Limpiar datos de entrada - eliminar campos que no existen en nuestro formulario
            $beneficiarioData = array_intersect_key($beneficiarioData, array_flip($headers));

            // Validar que cada beneficiario tenga CÉDULA y NOMBRE COMPLETO (campos requeridos)
            if (empty($beneficiarioData['CÉDULA']) || empty($beneficiarioData['NOMBRE COMPLETO'])) {
                $errores[] = "Beneficiario " . ($beneficiarioId + 1) . ": Debe tener cédula y nombre completo";
                continue;
            }

            // Procesar cada campo usando los headers fijos
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
            return back()->withInput()->with('error', 'Debe ingresar al menos un beneficiario válido.');
        }

        try {
            // Preparar datos para guardar
            $dataToSave = [
                'filename' => 'Formulario Manual - ' . $proyecto->nombre,
                'uploaded_by' => Auth::user()->name,
                'headers' => array_unique($headers), // Eliminar duplicados
                'rows' => $rows,
                'uploaded_at' => now()->toISOString(),
                'total_rows' => count($rows),
                'total_columns' => count($headers)
            ];

            // Actualizar proyecto
            $proyecto->update([
                'data' => $dataToSave,
                'descripcion' => $validated['descripcion'] ?? $proyecto->descripcion,
            ]);

            // Agregar columnas automáticas de caracterización
            $this->addAutomaticColumns($dataToSave['headers'], $dataToSave['rows'], $proyecto->ano);

            // Actualizar el proyecto con las columnas automáticas
            $proyecto->update([
                'data' => $dataToSave,
                'origen' => 'excel' // Cambiar origen para que no aparezca más en la lista de formularios manuales
            ]);

            $mensaje = count($rows) === 1 ?
                '¡Proyecto completado exitosamente!' :
                "¡Proyecto completado exitosamente con " . count($rows) . " beneficiarios!";

            return redirect()->route('formularios.index', $proyecto)
                           ->with('success', $mensaje);

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Validar si una cédula existe en proyectos productivos de años anteriores o mismo año
     */
    public function validarCedula(Request $request)
    {
        $cedula = $request->input('cedula');
        $currentYear = $request->input('current_year');

        if (!$cedula || !$currentYear) {
            return response()->json(['error' => 'Cédula y año actual son requeridos'], 400);
        }

        $documentColumn = null;
        $foundInPreviousYear = false;
        $foundInCurrentYear = false;
        $previousYearProjects = [];
        $currentYearProjects = [];

        // Buscar en proyectos del año anterior
        $previousYear = $currentYear - 1;
        $proyectosAnteriores = ProyectoProductivo::where('ano', $previousYear)
            ->whereNotNull('data')
            ->where('data->total_rows', '>', 0)
            ->get();

        foreach ($proyectosAnteriores as $proyecto) {
            $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
            $headers = $data['headers'] ?? [];
            $rows = $data['rows'] ?? [];

            if (!$documentColumn) {
                $documentColumn = $this->findDocumentColumn($headers, $rows);
            }

            if ($documentColumn && $this->cedulaExistsInRows($cedula, $rows, $documentColumn)) {
                $foundInPreviousYear = true;
                $previousYearProjects[] = $proyecto->nombre;
            }
        }

        // Buscar en proyectos del mismo año (excluyendo proyectos manuales en proceso)
        $proyectosMismoAno = ProyectoProductivo::where('ano', $currentYear)
            ->whereNotNull('data')
            ->where('data->total_rows', '>', 0)
            ->where('origen', '!=', 'manual') // Excluir proyectos manuales que están en proceso
            ->get();

        foreach ($proyectosMismoAno as $proyecto) {
            $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
            $headers = $data['headers'] ?? [];
            $rows = $data['rows'] ?? [];

            if (!$documentColumn) {
                $documentColumn = $this->findDocumentColumn($headers, $rows);
            }

            if ($documentColumn && $this->cedulaExistsInRows($cedula, $rows, $documentColumn)) {
                $foundInCurrentYear = true;
                $currentYearProjects[] = $proyecto->nombre;
            }
        }

        return response()->json([
            'found_in_previous_year' => $foundInPreviousYear,
            'found_in_current_year' => $foundInCurrentYear,
            'previous_year_projects' => $previousYearProjects,
            'current_year_projects' => $currentYearProjects,
            'previous_year' => $previousYear,
            'current_year' => $currentYear
        ]);
    }

    /**
     * Verificar si una cédula existe en las filas de datos
     */
    private function cedulaExistsInRows($cedula, $rows, $documentColumn)
    {
        foreach ($rows as $row) {
            if (isset($row[$documentColumn]) && trim((string)$row[$documentColumn]) === trim((string)$cedula)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Eliminar proyecto manual
     */
    public function destroy(ProyectoProductivo $proyecto)
    {
        // Verificar que sea un proyecto manual
        if ($proyecto->origen !== 'manual') {
            abort(404, 'Proyecto no encontrado');
        }

        $proyecto->delete();

        return redirect()->route('formularios.index')
                       ->with('success', 'Proyecto eliminado exitosamente.');
    }

    /**
     * Obtener columnas de referencia para el formulario manual
     */
    private function getColumnasReferencia()
    {
        // Retornamos un array no vacío para que la vista muestre el formulario.
        // La lógica de inferencia anterior no se estaba utilizando en la vista (campos hardcoded).
        return ['ok']; 
    }

    /**
     * Agregar columnas automáticas de caracterización a los datos
     */
    protected function addAutomaticColumns(&$headers, &$rows, $currentYear)
    {
        // Verificar y agregar las nuevas columnas a los headers solo si no existen
        $automaticColumns = ['Estado_Caracterizacion', 'Corregimiento_CZ', 'Vereda_CZ', 'Proyectos_Anteriores'];

        foreach ($automaticColumns as $column) {
            if (!in_array($column, $headers)) {
                $headers[] = $column;
            }
        }

        // Encontrar columna de documento
        $documentColumn = $this->findDocumentColumn($headers, $rows);

        // OPTIMIZACIÓN: Crear mapas eficientes para búsquedas O(1)
        $caracterizacionMaps = $this->createCaracterizacionMaps();

        // Obtener proyectos del año inmediatamente anterior (optimizado)
        $proyectosAnteriores = ProyectoProductivo::where('ano', $currentYear - 1)
            ->whereNotNull('data')
            ->where('data->total_rows', '>', 0)
            ->get();

        // Crear mapa de documentos por proyecto anterior (optimizado)
        $documentosPorProyecto = [];
        foreach ($proyectosAnteriores as $proyecto) {
            $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
            $proyectoRows = $data['rows'] ?? [];

            $proyectoDocumentos = [];
            foreach ($proyectoRows as $row) {
                if (!is_array($row)) continue;

                $documento = '';
                if ($documentColumn && isset($row[$documentColumn])) {
                    $documento = trim((string)$row[$documentColumn]);
                }

                if (!empty($documento)) {
                    $proyectoDocumentos[] = $documento;
                }
            }

            $documentosPorProyecto[$proyecto->id] = [
                'nombre' => $proyecto->nombre,
                'ano' => $proyecto->ano,
                'documentos' => $proyectoDocumentos
            ];
        }

        // Procesar cada fila para agregar información automática (OPTIMIZADO)
        foreach ($rows as &$row) {
            $documento = '';
            if ($documentColumn && isset($row[$documentColumn])) {
                $documento = trim((string)$row[$documentColumn]);
            }

            if (empty($documento)) {
                // Si no hay documento, dejar columnas vacías
                $row['Estado_Caracterizacion'] = '';
                $row['Corregimiento_CZ'] = '';
                $row['Vereda_CZ'] = '';
                $row['Proyectos_Anteriores'] = '';
                continue;
            }

            // BÚSQUEDA OPTIMIZADA: Usar mapas para búsqueda O(1)
            $caracterizacionInfo = $this->findCaracterizacionInfoOptimized($documento, $caracterizacionMaps);

            if ($caracterizacionInfo) {
                // Tiene caracterización - usar la información de los mapas optimizados
                $row['Estado_Caracterizacion'] = $caracterizacionInfo['estado_caracterizacion'];
                $row['Corregimiento_CZ'] = $caracterizacionInfo['corregimiento_cz'];
                $row['Vereda_CZ'] = $caracterizacionInfo['vereda_cz'];
            } else {
                // No tiene caracterización
                $row['Estado_Caracterizacion'] = 'NO TIENE CZ';
                $row['Corregimiento_CZ'] = '';
                $row['Vereda_CZ'] = '';
            }

            // Buscar proyectos anteriores donde aparece este documento
            $proyectosEncontrados = [];
            foreach ($documentosPorProyecto as $proyectoId => $proyectoData) {
                if (in_array($documento, $proyectoData['documentos'])) {
                    $proyectosEncontrados[] = "Recibió en {$proyectoData['nombre']} el año {$proyectoData['ano']}";
                }
            }

            $row['Proyectos_Anteriores'] = !empty($proyectosEncontrados) ? implode('; ', $proyectosEncontrados) : '';
        }
    }

    /**
     * Buscar columna de documento en los headers
     */
    private function findDocumentColumn($headers, $rows)
    {
        // Estrategia 1: Buscar por nombres de columna específicos
        $documentKeywords = [
            ['cédula de ciudadanía', 'cedula de ciudadania', 'cedula ciudadanía', 'cedula ciudadania', 'número cédula', 'numero cedula'],
            ['cédula', 'cedula', 'cc', 'ced'],
            ['documento identidad', 'documento nacional', 'numero documento', 'número documento'],
            ['documento', 'doc', 'id', 'identificación', 'identificacion', 'dni'],
            ['cedula', 'numero', 'número', 'identidad']
        ];

        foreach ($documentKeywords as $priorityGroup) {
            foreach ($headers as $header) {
                $headerNormalized = $this->normalizeText($header);
                foreach ($priorityGroup as $keyword) {
                    if (str_contains($headerNormalized, $keyword)) {
                        if ($this->validateDocumentColumn($header, $rows)) {
                            return $header;
                        }
                    }
                }
            }
        }

        // Estrategia 2: Búsqueda por contenido puro
        foreach ($headers as $header) {
            if ($this->validateDocumentColumn($header, $rows)) {
                return $header;
            }
        }

        return null;
    }

    /**
     * Validar que una columna contenga números de documento válidos
     */
    private function validateDocumentColumn($columnName, $rows)
    {
        if (empty($rows)) return false;

        $validCount = 0;
        $totalChecked = min(20, count($rows));

        for ($i = 0; $i < $totalChecked; $i++) {
            if (!isset($rows[$i][$columnName])) continue;

            $value = trim((string)$rows[$i][$columnName]);
            if ($this->isValidDocumentNumber($value)) {
                $validCount++;
            }
        }

        return $validCount >= $totalChecked * 0.6; // 60% válidos
    }

    /**
     * Verificar si un valor parece ser un número de documento válido
     */
    private function isValidDocumentNumber($value)
    {
        if (!is_numeric($value)) return false;
        $length = strlen((string)$value);
        return $length >= 6 && $length <= 12 && (int)$value > 100000;
    }

    /**
     * Normalizar texto para búsqueda
     */
    private function normalizeText($text)
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $text));
    }

    /**
     * Crear mapas optimizados para búsquedas de caracterización
     */
    private function createCaracterizacionMaps()
    {
        $caracterizacion = \App\Models\Caracterizacion::find(1);
        if (!$caracterizacion || !$caracterizacion->data) {
            return ['directos' => [], 'familiares' => []];
        }

        $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
        $rows = $data['rows'] ?? [];
        $headers = $data['headers'] ?? [];

        $documentColumn = $this->findDocumentColumn($headers, $rows);
        if (!$documentColumn) {
            return ['directos' => [], 'familiares' => []];
        }

        $mapaDirectos = [];
        $mapaFamiliares = [];

        foreach ($rows as $row) {
            if (!is_array($row)) continue;

            $documento = trim((string)($row[$documentColumn] ?? ''));
            if (empty($documento)) continue;

            $corregimiento = $this->findColumnValue($row, $headers, ['corregimiento', 'corregimiento_cz']);
            $vereda = $this->findColumnValue($row, $headers, ['vereda', 'vereda_cz']);

            $familiares = $this->extractFamiliaresInfo($row, $headers);

            $estado = 'Si';
            if (!empty($familiares)) {
                $familiaresStr = implode(', ', array_map(fn($f) => "{$f['nombre']} {$f['tipo']} {$f['numero']}", $familiares));
                $estado .= ', ' . $familiaresStr;
            }

            $mapaDirectos[$documento] = [
                'estado_caracterizacion' => $estado,
                'corregimiento_cz' => $corregimiento,
                'vereda_cz' => $vereda,
            ];

            foreach ($familiares as $familiar) {
                $familiarDoc = trim($familiar['numero']);
                if (!empty($familiarDoc)) {
                    $mapaFamiliares[$familiarDoc] = [
                        'estado_caracterizacion' => 'Si, pertenece al núcleo familiar',
                        'corregimiento_cz' => $corregimiento,
                        'vereda_cz' => $vereda,
                    ];
                }
            }
        }

        return ['directos' => $mapaDirectos, 'familiares' => $mapaFamiliares];
    }

    /**
     * Buscar información de caracterización usando mapas optimizados
     */
    private function findCaracterizacionInfoOptimized($documento, $caracterizacionMaps)
    {
        return $caracterizacionMaps['directos'][$documento] ??
               $caracterizacionMaps['familiares'][$documento] ?? null;
    }

    /**
     * Buscar valor de columna usando múltiples nombres posibles
     */
    private function findColumnValue($row, $headers, $possibleNames)
    {
        foreach ($possibleNames as $name) {
            if (in_array($name, $headers) && isset($row[$name])) {
                return trim((string)$row[$name]);
            }
        }
        return '';
    }

    /**
     * Extraer información de familiares
     */
    private function extractFamiliaresInfo($row, $headers)
    {
        $familiares = [];

        foreach ($headers as $header) {
            $headerNormalized = $this->normalizeText($header);

            if (preg_match('/nombres y apellidos familiar (\d+)/i', $headerNormalized, $matches)) {
                $index = $matches[1];
                $nombre = trim((string)($row[$header] ?? ''));

                // Buscar tipo y número correspondientes
                $tipo = '';
                $numero = '';

                foreach ($headers as $h) {
                    $hNormalized = $this->normalizeText($h);
                    if (preg_match('/tipo de documento.*?' . $index . '$/i', $hNormalized)) {
                        $tipo = trim((string)($row[$h] ?? ''));
                    }
                    if (preg_match('/numero.*documento.*?' . $index . '$/i', $hNormalized)) {
                        $numero = trim((string)($row[$h] ?? ''));
                    }
                }

                if (!empty($nombre)) {
                    $familiares[] = [
                        'nombre' => $nombre,
                        'tipo' => $tipo,
                        'numero' => $numero
                    ];
                }
            }
        }

        return $familiares;
    }
}

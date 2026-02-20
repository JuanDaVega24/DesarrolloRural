<?php

namespace App\Http\Controllers;

use App\Models\ProyectoProductivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProyectoProductivoController extends Controller
{
    public function index(Request $request)
    {
        // Obtener años únicos con conteo de proyectos
        $anosConConteo = ProyectoProductivo::selectRaw('ano, COUNT(*) as total_proyectos')
            ->whereNotNull('ano')
            ->groupBy('ano')
            ->orderBy('ano', 'desc')
            ->get();

        return view('proyectos_productivos.index', compact('anosConConteo'));
    }

    public function proyectosPorAno($ano)
    {
        // Obtener proyectos del año específico
        $proyectos = ProyectoProductivo::where('ano', $ano)
            ->latest()
            ->paginate(20);

        $totalProyectos = ProyectoProductivo::where('ano', $ano)->count();

        return view('proyectos_productivos.proyectos-por-ano', compact('proyectos', 'ano', 'totalProyectos'));
    }

    public function create()
    {
        return view('proyectos_productivos.create');
    }

    public function createManual()
    {
        // Obtener columnas de referencia de un proyecto existente o usar columnas por defecto
        $columnasReferencia = $this->getColumnasReferencia();

        return view('proyectos_productivos.create_manual', compact('columnasReferencia'));
    }

    public function storeManual(Request $request)
    {
        // Validar datos básicos del proyecto
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ano' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'descripcion' => 'nullable|string|max:1000',
            'data' => 'required|array',
            'data.*' => 'nullable|string|max:255',
        ]);

        try {
            // Preparar datos para guardar
            $dataToSave = [
                'filename' => 'Formulario Manual - ' . $validated['nombre'],
                'uploaded_by' => Auth::user()->name,
                'headers' => array_keys($validated['data']),
                'rows' => [$validated['data']], // Un solo registro
                'uploaded_at' => now()->toISOString(),
                'total_rows' => 1,
                'total_columns' => count($validated['data'])
            ];

            // Crear el proyecto con origen 'manual'
            $proyecto = ProyectoProductivo::create([
                'nombre' => $validated['nombre'],
                'ano' => $validated['ano'],
                'descripcion' => $validated['descripcion'],
                'estado' => 'Activo',
                'origen' => 'manual', // Indicar que viene del formulario manual
                'data' => $dataToSave,
            ]);

            // Agregar columnas automáticas de caracterización
            $this->addAutomaticColumns($dataToSave['headers'], $dataToSave['rows'], $proyecto->ano);

            // Actualizar el proyecto con las columnas automáticas
            $proyecto->update([
                'data' => $dataToSave
            ]);

            return redirect()->route('proyectos.por-ano', $proyecto->ano)
                           ->with('success', '¡Proyecto creado exitosamente desde formulario manual!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al crear el proyecto: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ano' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'metodo_creacion' => 'required|in:manual,excel',
        ]);

        // Crear proyecto básico
        $proyecto = ProyectoProductivo::create([
            'nombre' => $data['nombre'],
            'ano' => $data['ano'],
            'estado' => 'Activo',
        ]);

        // Redirigir según el método de creación seleccionado
        if ($data['metodo_creacion'] === 'manual') {
            // Crear proyecto con origen manual y redirigir al index de formularios
            $proyecto->update(['origen' => 'manual']);
            return redirect()->route('formularios.index')
                           ->with('success', 'Proyecto creado. Ahora puedes completar el formulario.');
        } else {
            // Para Excel, redirigir al upload de Excel
            return redirect()->route('proyectos.upload-excel', $proyecto)
                           ->with('success', 'Proyecto creado. Ahora sube tu archivo Excel.');
        }
    }

    public function edit(ProyectoProductivo $proyecto)
    {
        return view('proyectos_productivos.edit', compact('proyecto'));
    }

    public function update(Request $request, ProyectoProductivo $proyecto)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ano' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'descripcion' => 'nullable|string|max:1000',
            'metodo_creacion' => 'required|in:manual,excel',
        ]);

        $origenAnterior = $proyecto->origen;

        $proyecto->update([
            'nombre' => $data['nombre'],
            'ano' => $data['ano'],
            'descripcion' => $data['descripcion'] ?? null,
            'origen' => $data['metodo_creacion'],
        ]);

        // Si el proyecto es o cambió a Manual, redirigir a la gestión de formularios
        if ($data['metodo_creacion'] === 'manual') {
            return redirect()->route('formularios.index')->with('success', 'Proyecto actualizado. Puede gestionarlo en la sección de Formularios.');
        }

        // Si cambió de Manual a Excel, redirigir a la carga del archivo
        if ($origenAnterior !== 'excel' && $data['metodo_creacion'] === 'excel') {
            return redirect()->route('proyectos.upload-excel', $proyecto)->with('success', 'Proyecto cambiado a Excel. Por favor cargue el archivo correspondiente.');
        }

        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(ProyectoProductivo $proyecto)
    {
        $proyecto->delete();
        return back()->with('success','¡Proyecto eliminado Correctamente!');
    }

    public function uploadExcel(ProyectoProductivo $proyecto)
    {
        return view('proyectos_productivos.upload_excel', compact('proyecto'));
    }

    public function processExcel(Request $request, ProyectoProductivo $proyecto)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:51200', // 50MB max
        ]);

        try {
            $file = $request->file('excel_file');

            // Leer el archivo Excel
            $data = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLSX);

            if (empty($data) || empty($data[0])) {
                return back()->with('error', 'El archivo Excel está vacío o no tiene datos válidos.');
            }

            $sheetData = $data[0]; // Primera hoja

            if (count($sheetData) < 2) {
                return back()->with('error', 'El archivo debe tener al menos una fila de encabezados y una fila de datos.');
            }

            // Primera fila como encabezados
            $headers = array_shift($sheetData);

            // Limpiar encabezados (quitar espacios, convertir a string)
            $headers = array_map(function($header) {
                return trim((string)$header);
            }, $headers);

            // Filtrar filas vacías
            $rows = array_filter($sheetData, function($row) {
                return !empty(array_filter($row, function($cell) {
                    return !is_null($cell) && trim((string)$cell) !== '';
                }));
            });

            // Convertir filas a arrays asociativos y procesar fechas
            $processedRows = [];
            foreach ($rows as $row) {
                $rowData = [];
                foreach ($headers as $index => $header) {
                    $cellValue = isset($row[$index]) ? $row[$index] : '';

                    // Procesar fechas si es un número que parece ser una fecha de Excel
                    // Solo números > 10000 para evitar convertir números pequeños que no son fechas
                    if (is_numeric($cellValue) && $cellValue > 10000 && $cellValue <= 100000) {
                        try {
                            $excelDate = Date::excelToDateTimeObject($cellValue);
                            if ($excelDate) {
                                $year = (int)$excelDate->format('Y');
                                // Solo convertir si es una fecha razonable (1900-2100)
                                if ($year >= 1900 && $year <= 2100) {
                                    $cellValue = $excelDate->format('d/m/Y');
                                }
                            }
                        } catch (\Exception $e) {
                            // Si falla la conversión, mantener el valor original
                        }
                    }

                    $rowData[$header] = trim((string)$cellValue);
                }
                $processedRows[] = $rowData;
            }

            // AGREGAR COLUMNAS AUTOMÁTICAS DE CARACTERIZACIÓN
            $this->addAutomaticColumns($headers, $processedRows, $proyecto->ano);

            // Preparar datos para guardar
            $dataToSave = [
                'filename' => $file->getClientOriginalName(),
                'uploaded_by' => Auth::user()->name,
                'headers' => $headers,
                'rows' => $processedRows,
                'uploaded_at' => now()->toISOString(),
                'total_rows' => count($processedRows),
                'total_columns' => count($headers)
            ];

            // Guardar datos en el proyecto
            $proyecto->update([
                'data' => $dataToSave
            ]);

            return redirect()->route('proyectos.por-ano', $proyecto->ano)->with('success', 'Excel subido correctamente. ' . count($processedRows) . ' filas procesadas.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    public function show(ProyectoProductivo $proyecto)
    {
        // Permitir proyectos con datos, sin importar si fueron creados desde Excel o manualmente
        if (!$proyecto->data) {
            return redirect()->route('proyectos.index')->with('error', 'Este proyecto no tiene datos cargados.');
        }

        // Preparar datos para filtros
        $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
        $rows = $data['rows'] ?? [];
        $headers = $data['headers'] ?? [];

        // Extraer valores únicos para filtros
        $filterData = $this->prepareFilterData($rows, $headers);

        // Obtener corregimientos de la base de datos
        $corregimientos = \App\Models\Corregimiento::orderBy('id')->pluck('nombre', 'id')->toArray();

        // Obtener veredas ÚNICAS del proyecto actual (solo las que aparecen en Vereda_CZ)
        $veredas = $this->getVeredasFromProject($rows, $headers);

        return view('proyectos_productivos.show', compact('proyecto', 'filterData', 'corregimientos', 'veredas'));
    }

    /**
     * Preparar datos únicos para los filtros de cada columna
     */
    private function prepareFilterData($rows, $headers)
    {
        $filterData = [];

        // Columnas que queremos filtrar (con variaciones para género)
        $filterableColumns = ['Estado_Caracterizacion', 'Corregimiento_CZ'];

        // Buscar columna de género con variaciones
        $genderColumn = $this->findGenderColumn($headers);
        if ($genderColumn) {
            $filterableColumns[] = $genderColumn;
        }

        foreach ($filterableColumns as $column) {
            if (in_array($column, $headers)) {
                $uniqueValues = [];
                foreach ($rows as $row) {
                    $value = trim($row[$column] ?? '');
                    if (!empty($value) && !in_array($value, $uniqueValues)) {
                        $uniqueValues[] = $value;
                    }
                }
                sort($uniqueValues);
                // Usar 'Genero' como clave para mantener consistencia en la vista
                $key = ($column === $genderColumn) ? 'Genero' : $column;
                $filterData[$key] = $uniqueValues;
            }
        }

        return $filterData;
    }

    /**
     * Obtener veredas únicas del proyecto actual (solo las que aparecen en Vereda_CZ)
     */
    private function getVeredasFromProject($rows, $headers)
    {
        $veredas = [];

        // Verificar si existe la columna Vereda_CZ
        if (!in_array('Vereda_CZ', $headers)) {
            return $veredas;
        }

        // Extraer valores únicos de Vereda_CZ
        foreach ($rows as $row) {
            $veredaValue = trim($row['Vereda_CZ'] ?? '');
            if (!empty($veredaValue) && !in_array($veredaValue, $veredas)) {
                $veredas[] = $veredaValue;
            }
        }

        sort($veredas);
        return $veredas;
    }

    /**
     * Buscar columna de género con variaciones de nombre
     */
    public function findGenderColumn($headers)
    {
        $genderVariations = [
            'genero', 'género', 'sexo', 'gender', 'sex',
            'gen', 'genero_persona', 'género_persona'
        ];

        foreach ($headers as $header) {
            $headerNormalized = $this->normalizeText($header);
            foreach ($genderVariations as $variation) {
                if (str_contains($headerNormalized, $variation)) {
                    return $header;
                }
            }
        }

        return null;
    }

    /**
     * Aplicar filtros a las filas de datos (para exportación)
     */
    private function applyFiltersToRows($rows, $headers, $activeFilters)
    {
        if (empty($activeFilters)) {
            return $rows;
        }

        $filteredRows = [];

        foreach ($rows as $row) {
            $shouldInclude = true;

            foreach ($activeFilters as $column => $values) {
                if (!is_array($values) || empty($values)) {
                    continue;
                }

                // Obtener el índice de la columna
                $columnIndex = array_search($column, $headers);
                if ($columnIndex === false) {
                    continue;
                }

                // Para el filtro de género, usar la columna detectada
                if ($column === 'Genero') {
                    $genderColumn = $this->findGenderColumn($headers);
                    if ($genderColumn) {
                        $columnIndex = array_search($genderColumn, $headers);
                    }
                }

                // Obtener el valor de la celda
                $cellValue = '';
                if (isset($row[$column])) {
                    $cellValue = trim((string)$row[$column]);
                }

                // Verificar si el valor está en los filtros seleccionados
                if (!in_array($cellValue, $values)) {
                    $shouldInclude = false;
                    break;
                }
            }

            if ($shouldInclude) {
                $filteredRows[] = $row;
            }
        }

        return $filteredRows;
    }

    /**
     * Aplicar filtro de búsqueda de texto a las filas de datos
     */
    private function applySearchFilter($rows, $searchTerm)
    {
        if (empty($searchTerm)) {
            return $rows;
        }

        $searchTerm = strtolower(trim($searchTerm));
        $filteredRows = [];

        foreach ($rows as $row) {
            $rowText = '';
            foreach ($row as $value) {
                $rowText .= ' ' . strtolower(trim((string)$value));
            }

            if (str_contains($rowText, $searchTerm)) {
                $filteredRows[] = $row;
            }
        }

        return $filteredRows;
    }

    public function exportExcel(Request $request, ProyectoProductivo $proyecto)
    {
        if (!$proyecto->data) {
            return redirect()->back()->with('error', 'Este proyecto no tiene datos para exportar.');
        }

        // Preparar datos para Excel
        $data = $proyecto->data;
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data) || !isset($data['headers']) || !isset($data['rows'])) {
            return redirect()->back()->with('error', 'Los datos del proyecto no están en el formato correcto.');
        }

        $headers = $data['headers'];
        $rows = $data['rows'];

        // Aplicar filtros de columna si existen
        $activeFilters = $request->get('filters', []);
        if (!empty($activeFilters)) {
            $rows = $this->applyFiltersToRows($rows, $headers, $activeFilters);
        }

        // Aplicar filtro de búsqueda de texto si existe
        $searchTerm = $request->get('search', '');
        if (!empty($searchTerm)) {
            $rows = $this->applySearchFilter($rows, $searchTerm);
        }

        // Convertir filas asociativas a arrays indexados en el mismo orden que los headers
        $processedRows = [];
        foreach ($rows as $row) {
            $processedRow = [];
            foreach ($headers as $header) {
                $processedRow[] = $row[$header] ?? '';
            }
            $processedRows[] = $processedRow;
        }

        // Crear nombre de archivo con indicador de filtros/búsqueda
        $filterSuffix = (!empty($activeFilters) || !empty($searchTerm)) ? '_filtrado' : '';
        $fileName = $proyecto->nombre . $filterSuffix . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new class($headers, $processedRows) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $headers;
            private $rows;

            public function __construct($headers, $rows)
            {
                $this->headers = $headers;
                $this->rows = $rows;
            }

            public function headings(): array
            {
                return $this->headers;
            }

            public function array(): array
            {
                return $this->rows;
            }
        }, $fileName);
    }

    public function getProyectosExcel(Request $request)
    {
        $query = ProyectoProductivo::whereNotNull('data')
            ->where('data->total_rows', '>', 0);

        // Apply year filter if specified
        if ($request->has('ano') && !empty($request->ano)) {
            $query->where('ano', $request->ano);
        }

        $proyectos = $query->select('id', 'nombre', 'ano', 'data')
            ->orderBy('ano', 'desc')
            ->orderBy('nombre')
            ->get()
            ->map(function ($proyecto) {
                $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
                return [
                    'id' => $proyecto->id,
                    'nombre' => $proyecto->nombre,
                    'ano' => $proyecto->ano,
                    'total_rows' => $data['total_rows'] ?? 0,
                ];
            });

        return response()->json($proyectos);
    }

    public function getAniosDisponibles()
    {
        $anios = ProyectoProductivo::whereNotNull('data')
            ->where('data->total_rows', '>', 0)
            ->select('ano')
            ->distinct()
            ->orderBy('ano', 'desc')
            ->pluck('ano')
            ->filter()
            ->values();

        return response()->json($anios);
    }

    public function validarProyecto(Request $request, ProyectoProductivo $proyecto)
    {
        if (!$proyecto->data) {
            return response()->json(['error' => 'Este proyecto no tiene datos Excel cargados.'], 404);
        }

        // OPTIMIZACIÓN: Crear mapas eficientes para búsquedas O(1) usando tabla dinámica
        $caracterizacionMaps = $this->createCaracterizacionMaps();

        // Obtener datos del proyecto
        $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
        $rows = $data['rows'] ?? [];
        $headers = $data['headers'] ?? [];

        // Obtener parámetros manuales de selección de columnas
        $manualDocumentColumn = $request->query('document_column');
        $manualNameColumn = $request->query('name_column');

        // Depuración: mostrar información del procesamiento
        $debug = [
            'total_rows' => count($rows),
            'headers' => $headers,
            'total_caracterizaciones_directas' => count($caracterizacionMaps['directos'] ?? []),
            'total_caracterizaciones_familiares' => count($caracterizacionMaps['familiares'] ?? []),
            'total_caracterizaciones_total' => count($caracterizacionMaps['directos'] ?? []) + count($caracterizacionMaps['familiares'] ?? []),
            'manual_document_column' => $manualDocumentColumn,
            'manual_name_column' => $manualNameColumn,
        ];

        $comparison = [];
        $stats = ['total' => 0, 'valid' => 0, 'invalid' => 0];

        // Usar columna manual si se especificó, sino detectar automáticamente
        $documentColumnName = $manualDocumentColumn ?: $this->findDocumentColumn($headers, $rows);
        $nameColumnName = $manualNameColumn ?: $this->findNameColumn($headers);

        foreach ($rows as $rowIndex => $row) {
            // IMPORTANTE: Los datos están guardados como arrays asociativos con nombres de columnas
            // row es como: ['Documento' => '123456', 'Nombre' => 'Juan Pérez', ...]

            if (!is_array($row)) continue;

            // Obtener documento usando el nombre de columna encontrado
            $documento = '';
            if ($documentColumnName && isset($row[$documentColumnName])) {
                $documento = trim((string)$row[$documentColumnName]);
            }

            // Si no encontró columna específica, intentar con la primera columna que parezca documento
            if (empty($documento)) {
                foreach ($row as $colName => $value) {
                    $colNameLower = strtolower(trim($colName));
                    $valueStr = trim((string)$value);
                    // Si la columna parece ser de documento y el valor parece un número de documento
                    if ((str_contains($colNameLower, 'doc') ||
                         str_contains($colNameLower, 'ced') ||
                         str_contains($colNameLower, 'id') ||
                         is_numeric($valueStr)) &&
                        strlen($valueStr) >= 6 && strlen($valueStr) <= 12) {
                        $documento = $valueStr;
                        break;
                    }
                }
            }

            if (empty($documento)) continue;

            $stats['total']++;

            // BÚSQUEDA OPTIMIZADA: Usar mapas para búsqueda O(1) en tabla dinámica
            $caracterizacionInfo = $this->findCaracterizacionInfoOptimized($documento, $caracterizacionMaps);
            $tieneCaracterizacion = $caracterizacionInfo !== null;

            if ($tieneCaracterizacion) {
                $stats['valid']++;
            } else {
                $stats['invalid']++;
            }

            // Obtener nombre completo
            $nombreCompleto = '';
            if ($nameColumnName && isset($row[$nameColumnName])) {
                $nombreCompleto = trim((string)$row[$nameColumnName]);
            }

            // Si no hay nombre específico, intentar encontrar una columna que contenga nombre
            if (empty($nombreCompleto)) {
                foreach ($row as $colName => $value) {
                    $colNameLower = strtolower(trim($colName));
                    if (str_contains($colNameLower, 'nombre') ||
                        str_contains($colNameLower, 'name') ||
                        str_contains($colNameLower, 'persona')) {
                        $nombreCompleto = trim((string)$value);
                        break;
                    }
                }
            }

            // Si aún no hay nombre, combinar primeras columnas no numéricas
            if (empty($nombreCompleto)) {
                $possibleNameParts = [];
                $colCount = 0;
                foreach ($row as $colName => $value) {
                    $valueStr = trim((string)$value);
                    if (!is_numeric($valueStr) && !empty($valueStr) && $colCount < 3) {
                        $possibleNameParts[] = $valueStr;
                        $colCount++;
                    }
                }
                $nombreCompleto = implode(' ', $possibleNameParts);
            }

            // Preparar información de caracterización usando datos de los mapas
            $tipoCaracterizacion = null;
            $detallesCaracterizacion = 'No tiene caracterización';

            if ($tieneCaracterizacion) {
                // Determinar si es caracterización directa o familiar
                $mapaDirectos = $caracterizacionMaps['directos'] ?? [];
                $mapaFamiliares = $caracterizacionMaps['familiares'] ?? [];

                if (isset($mapaDirectos[$documento]) || (isset($mapaDirectos[(string)(int)$documento]) && is_numeric($documento))) {
                    $tipoCaracterizacion = 'directa';
                    $info = $mapaDirectos[$documento] ?? $mapaDirectos[(string)(int)$documento];
                    $detallesCaracterizacion = $info['estado_caracterizacion'];
                } elseif (isset($mapaFamiliares[$documento]) || (isset($mapaFamiliares[(string)(int)$documento]) && is_numeric($documento))) {
                    $tipoCaracterizacion = 'familiar';
                    $info = $mapaFamiliares[$documento] ?? $mapaFamiliares[(string)(int)$documento];
                    $detallesCaracterizacion = $info['estado_caracterizacion'];
                }
            }

            $comparison[] = [
                'documento' => $documento,
                'nombre_completo' => $nombreCompleto ?: 'No disponible',
                'tiene_caracterizacion' => $tieneCaracterizacion,
                'tipo_caracterizacion' => $tipoCaracterizacion,
                'detalles_caracterizacion' => $detallesCaracterizacion,
            ];
        }

        return response()->json([
            'proyecto' => [
                'id' => $proyecto->id,
                'nombre' => $proyecto->nombre,
                'ano' => $proyecto->ano,
            ],
            'stats' => $stats,
            'comparison' => $comparison,
            'debug' => $debug, // Información de depuración
        ]);
    }

    /**,
     * Buscar columna de documento en los headers
     * Implementa detección inteligente con múltiples estrategias
     */
    private function findDocumentColumn($headers, $rows)
    {
        // Estrategia 1: Buscar por nombres de columna específicos (prioridad alta)
        $documentKeywords = [
            // Alta prioridad - términos muy específicos colombianos
            ['numero de documento de identidad', 'número de documento de identidad', 'número de documento', 'numero de documento','cédula de ciudadanía', 'cedula de ciudadania', 'cedula ciudadanía', 'cedula ciudadania', 'número cédula', 'numero cedula'],

            // Media-alta prioridad - términos específicos
            ['cédula', 'cedula', 'cc', 'ced'],

            // Media prioridad - términos genéricos pero específicos para documentos
            ['documento identidad', 'documento nacional', 'numero documento', 'número documento'],

            // Baja prioridad - términos genéricos
            ['documento', 'doc', 'id', 'identificación', 'identificacion', 'dni'],

            // Menor prioridad - otros términos posibles
            ['cedula', 'numero', 'número', 'identidad']
        ];

        // Buscar por nombre de columna primero, validando contenido
        foreach ($documentKeywords as $priorityGroup) {
            foreach ($headers as $header) {
                $headerNormalized = $this->normalizeText($header);
                foreach ($priorityGroup as $keyword) {
                    if (str_contains($headerNormalized, $keyword)) {
                        if (preg_match('/\\d\\s*$/', $headerNormalized) || str_contains($headerNormalized, 'familiar')) {
                            continue;
                        }
                        if ($this->validateDocumentColumn($header, $rows)) {
                            return $header;
                        }
                    }
                }
            }
        }

        // Estrategia 2: Buscar por patrones comunes en nombres de columna
        $patternMatches = [
            '/^(doc|documento|cedula|ced|id)\d*$/i',
            '/^(numero|número)\s*(doc|documento|cedula|ced|id)/i',
            '/^(cc|cedula)\s*(de)?\s*(ciudadania|ciudadanía)?$/i'
        ];

        foreach ($headers as $header) {
            $headerNormalized = $this->normalizeText($header);
            foreach ($patternMatches as $pattern) {
                if (preg_match($pattern, $headerNormalized)) {
                    if ($this->validateDocumentColumn($header, $rows)) {
                        return $header;
                    }
                }
            }
        }

        // Estrategia 3: Búsqueda por contenido puro (último recurso)
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
        $totalChecked = 0;
        $maxToCheck = min(20, count($rows)); // Revisar máximo 20 filas para validación

        for ($i = 0; $i < $maxToCheck; $i++) {
            if (!isset($rows[$i][$columnName])) continue;

            $value = trim((string)$rows[$i][$columnName]);
            $totalChecked++;

            // Validar que sea un número de documento válido
            if ($this->isValidDocumentNumber($value)) {
                $validCount++;
            }
        }

        if ($totalChecked === 0) return false;

        // La columna es válida si al menos 60% de los valores son documentos válidos
        $validPercentage = ($validCount / $totalChecked) * 100;
        return $validPercentage >= 60;
    }

    /**
     * Verificar si un valor parece ser un número de documento válido
     */
    private function isValidDocumentNumber($value)
    {
        // Debe ser numérico
        if (!is_numeric($value)) return false;

        // Longitud típica para documentos colombianos (6-12 dígitos)
        $length = strlen((string)$value);
        if ($length < 6 || $length > 12) return false;

        // No debe ser un número muy pequeño (probablemente no es un documento)
        $numValue = (int)$value;
        if ($numValue < 100000) return false; // Documentos suelen ser mayores

        return true;
    }

    /**
     * Normalizar texto para búsqueda (quitar acentos, convertir a minúsculas, etc.)
     */
    private function normalizeText($text)
    {
        // Convertir a minúsculas
        $text = strtolower(trim($text));

        // Reemplazar caracteres con acentos
        $replacements = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
            'â' => 'a', 'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u',
            'ã' => 'a', 'õ' => 'o', 'ñ' => 'n',
            'ç' => 'c'
        ];

        $text = str_replace(array_keys($replacements), array_values($replacements), $text);

        // Reemplazar múltiples espacios por uno solo
        $text = preg_replace('/\s+/', ' ', $text);

        // Quitar caracteres especiales pero mantener letras, números y espacios
        $text = preg_replace('/[^a-zA-Z0-9\s]/', '', $text);

        return trim($text);
    }

    private function abbreviateDocumentType($tipo)
    {
        $t = $this->normalizeText((string)$tipo);
        if (empty($t)) return '';
        if (str_contains($t, 'cedula de ciudadania') || $t === 'cc' || str_contains($t, 'cedula')) return 'CC';
        if (str_contains($t, 'tarjeta de identidad') || $t === 'ti') return 'TI';
        if (str_contains($t, 'cedula de extranjeria') || $t === 'ce') return 'CE';
        if (str_contains($t, 'pasaporte') || $t === 'pas') return 'PAS';
        return strtoupper(trim((string)$tipo));
    }

    /**
     * Buscar columna de nombre en los headers
     */
    private function findNameColumn($headers)
    {
        $nameKeywords = [
            'nombre', 'name', 'persona', 'beneficiario', 'beneficiaria',
            'primer nombre', 'primer apellido', 'segundo apellido'
        ];

        foreach ($headers as $header) {
            $headerLower = strtolower(trim($header));
            foreach ($nameKeywords as $keyword) {
                if (str_contains($headerLower, $keyword)) {
                    return $header;
                }
            }
        }

        return null;
    }

    public function getProyectosParaComparar(Request $request)
    {
        $query = ProyectoProductivo::whereNotNull('data')
            ->where('data->total_rows', '>', 0);

        // Apply year filter if specified
        if ($request->has('ano') && !empty($request->ano)) {
            $query->where('ano', $request->ano);
        }

        // Exclude base project if specified
        if ($request->has('exclude_base') && !empty($request->exclude_base)) {
            $query->where('id', '!=', $request->exclude_base);
        }

        $proyectos = $query->select('id', 'nombre', 'ano', 'data')
            ->orderBy('ano', 'desc')
            ->orderBy('nombre')
            ->get()
            ->map(function ($proyecto) {
                $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
                return [
                    'id' => $proyecto->id,
                    'nombre' => $proyecto->nombre,
                    'ano' => $proyecto->ano,
                    'total_rows' => $data['total_rows'] ?? 0,
                ];
            });

        return response()->json($proyectos);
    }

    public function compararProyecto(Request $request, ProyectoProductivo $proyecto)
    {
        if (!$proyecto->data) {
            return response()->json(['error' => 'Este proyecto no tiene datos Excel cargados.'], 404);
        }

        // Obtener parámetros
        $comparisonProjectIds = $request->query('proyectos_comparacion');
        $manualDocumentColumn = $request->query('document_column');
        $manualNameColumn = $request->query('name_column');

        // Convertir string de IDs separados por coma a array
        $selectedProjectIds = [];
        if ($comparisonProjectIds) {
            $selectedProjectIds = explode(',', $comparisonProjectIds);
            $selectedProjectIds = array_map('intval', array_filter($selectedProjectIds));
        }

        if (empty($selectedProjectIds)) {
            return response()->json(['error' => 'Debe seleccionar al menos un proyecto para comparar.'], 400);
        }

        // Obtener datos del proyecto base
        $baseData = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
        $baseRows = $baseData['rows'] ?? [];
        $baseHeaders = $baseData['headers'] ?? [];

        // Encontrar columnas del proyecto base
        $documentColumnName = $manualDocumentColumn ?: $this->findDocumentColumn($baseHeaders, $baseRows);
        $nameColumnName = $manualNameColumn ?: $this->findNameColumn($baseHeaders);

        // Extraer documentos del proyecto base
        $baseDocuments = [];
        foreach ($baseRows as $row) {
            if (!is_array($row)) continue;

            $documento = '';
            if ($documentColumnName && isset($row[$documentColumnName])) {
                $documento = trim((string)$row[$documentColumnName]);
            }

            if (empty($documento)) continue;

            $nombreCompleto = '';
            if ($nameColumnName && isset($row[$nameColumnName])) {
                $nombreCompleto = trim((string)$row[$nameColumnName]);
            }

            $baseDocuments[$documento] = [
                'documento' => $documento,
                'nombre_completo' => $nombreCompleto ?: 'No disponible',
                'base_row' => $row,
                'proyectos_encontrados' => []
            ];
        }

        // Buscar proyectos específicos para comparación
        // Nota: Permitimos comparar un proyecto consigo mismo para testing y casos donde sea necesario
        $comparisonProjects = ProyectoProductivo::whereIn('id', $selectedProjectIds)
            ->whereNotNull('data')
            ->where('data->total_rows', '>', 0)
            ->get();

        $stats = [
            'total_personas_base' => count($baseDocuments),
            'total_proyectos_comparacion' => $comparisonProjects->count(),
            'personas_con_multiples_proyectos' => 0,
            'total_coincidencias' => 0
        ];

        $results = [];

        // Para cada documento del proyecto base, buscar en otros proyectos
        foreach ($baseDocuments as $documento => $basePerson) {
            $proyectosEncontrados = [];

            foreach ($comparisonProjects as $compProject) {
                $compData = is_string($compProject->data) ? json_decode($compProject->data, true) : $compProject->data;
                $compRows = $compData['rows'] ?? [];
                $compHeaders = $compData['headers'] ?? [];

                // Buscar el documento en este proyecto de comparación usando múltiples estrategias
                $foundInProject = false;
                $matchingRow = null;

                foreach ($compRows as $compRow) {
                    if (!is_array($compRow)) continue;

                    // Estrategia 1: Buscar por la columna detectada en el proyecto base
                    $compDocumento = '';
                    if ($documentColumnName && isset($compRow[$documentColumnName])) {
                        $compDocumento = trim((string)$compRow[$documentColumnName]);
                        if ($compDocumento === $documento) {
                            $foundInProject = true;
                            $matchingRow = $compRow;
                            break;
                        }
                    }

                    // Estrategia 2: Buscar en todas las columnas por contenido que coincida
                    if (!$foundInProject) {
                        foreach ($compRow as $colName => $value) {
                            $valueStr = trim((string)$value);

                            // Si el valor coincide exactamente con el documento buscado
                            if ($valueStr === $documento) {
                                $foundInProject = true;
                                $matchingRow = $compRow;
                                break 2; // Salir del bucle interno y externo
                            }

                            // Si el valor parece ser un documento y coincide numéricamente
                            if (is_numeric($valueStr) && is_numeric($documento) &&
                                (int)$valueStr === (int)$documento) {
                                $foundInProject = true;
                                $matchingRow = $compRow;
                                break 2; // Salir del bucle interno y externo
                            }
                        }
                    }

                    // Estrategia 3: Buscar por columnas que contengan términos relacionados con documentos
                    if (!$foundInProject) {
                        foreach ($compHeaders as $header) {
                            $headerNormalized = $this->normalizeText($header);
                            $isDocumentColumn = false;

                            // Verificar si esta columna parece contener documentos
                            $documentIndicators = ['doc', 'ced', 'cedula', 'documento', 'id', 'numero'];
                            foreach ($documentIndicators as $indicator) {
                                if (str_contains($headerNormalized, $indicator)) {
                                    $isDocumentColumn = true;
                                    break;
                                }
                            }

                            if ($isDocumentColumn && isset($compRow[$header])) {
                                $valueStr = trim((string)$compRow[$header]);
                                if ($valueStr === $documento ||
                                    (is_numeric($valueStr) && is_numeric($documento) && (int)$valueStr === (int)$documento)) {
                                    $foundInProject = true;
                                    $matchingRow = $compRow;
                                    break 2; // Salir del bucle interno y externo
                                }
                            }
                        }
                    }
                }

                if ($foundInProject && $matchingRow) {
                    $proyectosEncontrados[] = [
                        'proyecto' => [
                            'id' => $compProject->id,
                            'nombre' => $compProject->nombre,
                            'ano' => $compProject->ano,
                        ],
                        'row_data' => $matchingRow,
                        'headers' => $compHeaders
                    ];
                    $stats['total_coincidencias']++;
                }
            }

            // Filtrar proyectos adicionales (excluyendo el proyecto base si está incluido)
            $proyectosAdicionales = array_filter($proyectosEncontrados, function($encontrado) use ($proyecto) {
                return $encontrado['proyecto']['id'] != $proyecto->id;
            });

            // Contar en estadísticas solo personas con 2 o más proyectos adicionales (3+ proyectos totales)
            if (count($proyectosAdicionales) >= 2) {
                $stats['personas_con_multiples_proyectos']++;
            }

            // Incluir en resultados todas las personas con al menos 1 proyecto adicional (2+ proyectos totales)
            if (!empty($proyectosAdicionales)) {
                $results[] = [
                    'documento' => $documento,
                    'nombre_completo' => $basePerson['nombre_completo'],
                    'base_proyecto' => [
                        'id' => $proyecto->id,
                        'nombre' => $proyecto->nombre,
                        'ano' => $proyecto->ano,
                    ],
                    'base_row_data' => $basePerson['base_row'],
                    'base_headers' => $baseHeaders,
                    'proyectos_encontrados' => $proyectosAdicionales,
                    'total_proyectos_adicionales' => count($proyectosAdicionales)
                ];
            }
        }

        // Ordenar resultados por número de proyectos encontrados (descendente)
        usort($results, function($a, $b) {
            return $b['total_proyectos_adicionales'] <=> $a['total_proyectos_adicionales'];
        });

        // Recopilar información de debugging adicional
        $debugInfo = [
            'proyectos_comparacion_ids' => $selectedProjectIds,
            'total_proyectos_comparados' => $comparisonProjects->count(),
            'document_column' => $documentColumnName,
            'name_column' => $nameColumnName,
            'base_headers' => $baseHeaders,
            'total_documentos_base' => count($baseDocuments),
            'documentos_base_muestra' => array_slice(array_keys($baseDocuments), 0, 5),
            'proyectos_comparados' => $comparisonProjects->map(function($p) {
                return ['id' => $p->id, 'nombre' => $p->nombre, 'filas' => count($p->data['rows'] ?? [])];
            })->toArray(),
        ];

        return response()->json([
            'proyecto_base' => [
                'id' => $proyecto->id,
                'nombre' => $proyecto->nombre,
                'ano' => $proyecto->ano,
            ],
            'stats' => $stats,
            'results' => $results,
            'debug' => $debugInfo
        ]);
    }

    /**
     * Agregar columnas automáticas de caracterización a los datos del Excel
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
     * Crear mapas optimizados para búsquedas O(1) de caracterización
     */
    private function createCaracterizacionMaps()
    {
        $caracterizacionData = $this->getCaracterizacionData();
        $rows = $caracterizacionData['rows'] ?? [];
        $headers = $caracterizacionData['headers'] ?? [];

        // Encontrar columna de documento para caracterizaciones
        $documentColumn = $this->findDocumentColumn($headers, $rows);

        if (!$documentColumn) {
            return [
                'directos' => [],
                'familiares' => []
            ];
        }

        $mapaDirectos = []; // documento => info caracterización
        $mapaFamiliares = []; // documento => info caracterización del principal

        foreach ($rows as $row) {
            if (!is_array($row)) continue;

            $documento = '';
            if (isset($row[$documentColumn])) {
                $documento = trim((string)$row[$documentColumn]);
            }

            if (empty($documento)) continue;

            // Extraer información de corregimiento y vereda
            $corregimientoValue = $this->findColumnValue($row, $headers, ['corregimiento', 'corregimiento_cz', 'Corregimiento', 'Corregimiento_CZ']);
            $veredaValue = $this->findColumnValue($row, $headers, ['vereda', 'vereda_cz', 'Vereda', 'Vereda_CZ']);

            // Extraer información de familiares
            $familiaresInfo = $this->extractFamiliaresInfo($row, $headers, $documento);

            // Obtener nombre del principal
            $principalNombre = $this->findColumnValue($row, $headers, ['Nombres y apellidos', 'nombres y apellidos', 'nombre', 'nombres', 'Nombre', 'Nombres', 'nombre completo', 'Nombre Completo']);
            if (empty($principalNombre)) {
                $primerNombre = $this->findColumnValue($row, $headers, ['primer nombre', 'Primer Nombre']);
                $primerApellido = $this->findColumnValue($row, $headers, ['primer apellido', 'Primer Apellido']);
                $segundoApellido = $this->findColumnValue($row, $headers, ['segundo apellido', 'Segundo Apellido']);
                $partesNombre = array_filter([$primerNombre, $primerApellido, $segundoApellido]);
                $principalNombre = implode(' ', $partesNombre);
            }

            // Obtener tipo de documento del principal
            $principalTipoDocumento = $this->findColumnValue($row, $headers, ['Tipo de documento1', 'tipo de documento1', 'tipo documento', 'tipo de documento', 'Tipo Documento', 'Tipo de Documento']);

            $estadoCaracterizacionDirecta = 'SI';
            $items = [];

            if (!empty($familiaresInfo)) {
                // Si hay familiares, priorizar siempre la lista de familiares
                foreach ($familiaresInfo as $familiar) {
                    $tipoAbbr = $this->abbreviateDocumentType($familiar['tipo'] ?? '');
                    $fItemParts = array_filter([
                        trim((string)($familiar['nombre'] ?? '')),
                        trim((string)($tipoAbbr ?: ($familiar['tipo'] ?? ''))),
                        trim((string)($familiar['numero'] ?? '')),
                    ]);
                    if (!empty($fItemParts)) {
                        $items[] = implode(' ', $fItemParts);
                    }
                }
            } else {
                // Si no hay familiares, usar los datos del principal
                $principalTipoAbbr = $this->abbreviateDocumentType($principalTipoDocumento);
                $principalItemParts = array_filter([
                    trim((string)$principalNombre),
                    trim((string)($principalTipoAbbr ?: $principalTipoDocumento)),
                    trim((string)$documento),
                ]);
                if (!empty($principalItemParts)) {
                    $items[] = implode(' ', $principalItemParts);
                }
            }

            if (!empty($items)) {
                $estadoCaracterizacionDirecta .= ', ' . implode(', ', $items);
            }

            // Agregar al mapa de caracterizaciones directas
            $mapaDirectos[$documento] = [
                'estado_caracterizacion' => $estadoCaracterizacionDirecta,
                'corregimiento_cz' => $corregimientoValue,
                'vereda_cz' => $veredaValue,
                'principal_nombre' => $principalNombre,
                'principal_tipo_documento' => $principalTipoDocumento,
                'principal_documento' => $documento,
                'familiares' => $familiaresInfo
            ];

            // Agregar cada familiar al mapa de familiares (indexado por documento del familiar)
            foreach ($familiaresInfo as $familiar) {
                $familiarDocumento = trim($familiar['numero']);
                if (!empty($familiarDocumento)) {
                    // Construir mensaje para familiar
                    $estadoCaracterizacionFamiliar = 'Si, P NF de ' . $principalNombre;
                    if (!empty($principalTipoDocumento)) {
                        $estadoCaracterizacionFamiliar .= ' ' . $principalTipoDocumento;
                    }
                    if (!empty($documento)) {
                        $estadoCaracterizacionFamiliar .= ' ' . $documento;
                    }

                    // Agregar información de TODOS los familiares
                    if (!empty($familiaresInfo)) {
                        $familiaresString = implode(', ', array_map(function($familiar) {
                            return "{$familiar['nombre']} {$familiar['tipo']} {$familiar['numero']}";
                        }, $familiaresInfo));
                        $estadoCaracterizacionFamiliar .= ',' . $familiaresString;
                    }

                    $mapaFamiliares[$familiarDocumento] = [
                        'estado_caracterizacion' => $estadoCaracterizacionFamiliar,
                        'corregimiento_cz' => $corregimientoValue,
                        'vereda_cz' => $veredaValue,
                    ];
                }
            }
        }

        return [
            'directos' => $mapaDirectos,
            'familiares' => $mapaFamiliares
        ];
    }

    /**
     * Buscar información de caracterización usando mapas optimizados (O(1))
     */
    private function findCaracterizacionInfoOptimized($documento, $caracterizacionMaps)
    {
        $mapaDirectos = $caracterizacionMaps['directos'] ?? [];
        $mapaFamiliares = $caracterizacionMaps['familiares'] ?? [];

        // Normalizar documento para comparación
        $documentoNormalizado = trim((string)$documento);

        // Primero buscar en caracterizaciones directas
        if (isset($mapaDirectos[$documentoNormalizado])) {
            return $mapaDirectos[$documentoNormalizado];
        }

        // Si no está en directas, buscar en familiares
        if (isset($mapaFamiliares[$documentoNormalizado])) {
            return $mapaFamiliares[$documentoNormalizado];
        }

        // Si no se encuentra, intentar con comparación numérica
        foreach ($mapaDirectos as $doc => $info) {
            if (is_numeric($doc) && is_numeric($documentoNormalizado) && (int)$doc === (int)$documentoNormalizado) {
                return $info;
            }
        }

        foreach ($mapaFamiliares as $doc => $info) {
            if (is_numeric($doc) && is_numeric($documentoNormalizado) && (int)$doc === (int)$documentoNormalizado) {
                return $info;
            }
        }

        return null; // No encontrado
    }

    /**
     * Obtener datos de caracterización dinámica (Base de Datos de Caracterizaciones - ID=1)
     */
    private function getCaracterizacionData()
    {
        $caracterizacion = \App\Models\Caracterizacion::find(1);

        if (!$caracterizacion || !$caracterizacion->data) {
            return ['rows' => [], 'headers' => []];
        }

        $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;

        return [
            'rows' => $data['rows'] ?? [],
            'headers' => $data['headers'] ?? []
        ];
    }

    /**
     * Extraer información de familiares de la fila de caracterización
     */
    private function extractFamiliaresInfo($row, $headers, $mainDocumentValue = null)
    {
        $familiares = [];
        
        // Iterar posibles índices de familiares (1 a 20)
        // Según indicación:
        // Familiar 1: "Nombres y apellidos1"
        // Familiar 2: "Nombres y apellidos2"
        for ($i = 1; $i <= 20; $i++) {
            $familiar = [];
            
            // 1. Buscar NOMBRE del familiar i
            // Patrones: "Nombres y apellidosX", "Nombres y apellidos X", "Nombre familiar X"
            $namePatterns = [
                "Nombres y apellidos{$i}", "Nombres y apellidos {$i}",
                "Nombres y apellidos familiar {$i}", "Nombre familiar {$i}",
                "Nombre{$i}", "Nombre {$i}"
            ];
            
            // Para el primer familiar, a veces no tiene el sufijo "1"
            if ($i === 1) {
                $namePatterns[] = "Nombres y apellidos familiar";
                $namePatterns[] = "Nombre familiar";
            }
            
            $nombre = $this->findColumnValue($row, $headers, $namePatterns);
            
            // Si no hay nombre, saltamos este índice (asumiendo que el nombre es obligatorio para que exista el familiar)
            if (empty($nombre)) {
                continue;
            }
            $familiar['nombre'] = $nombre;

            // 2. Buscar TIPO DE DOCUMENTO del familiar i
            // Convención:
            //   Principal: "Tipo de documento1"
            //   Familiar 1: "Tipo de documento2"
            //   Familiar 2: "Tipo de documento3"
            //   ...
            $typeIndex = $i + 1;
            $typePatterns = [
                "Tipo de documento familiar {$i}",
                "Tipo de documento{$typeIndex}", "Tipo de documento {$typeIndex}",
                "Tipo de documento de identidad{$typeIndex}", "Tipo de documento de identidad {$typeIndex}",
                // Compatibilidad con esquemas anteriores
                "Tipo de documento{$i}", "Tipo de documento {$i}",
                "Tipo de documento de identidad{$i}", "Tipo de documento de identidad {$i}"
            ];
            if ($i === 1) {
                $typePatterns[] = "Tipo de documento familiar";
                // Algunas bases usan la columna sin sufijo para el primer familiar
                $typePatterns[] = "Tipo de documento";
            }
            
            $familiar['tipo'] = $this->findColumnValue($row, $headers, $typePatterns);

            // 3. Buscar NÚMERO DE DOCUMENTO del familiar i
            // Convención:
            //   Caracterizado (principal): "Numero de documento de identidad"
            //   Familiar 1: "Numero de documento de identidad1"
            //   Familiar 2: "Numero de documento de identidad2"
            //   ...
            $numeroIndex = (string)$i;
            $numPatterns = [
                "Número de documento de identidad{$numeroIndex}", "Número de documento de identidad {$numeroIndex}",
                "Numero de documento de identidad{$numeroIndex}", "Numero de documento de identidad {$numeroIndex}",
                // Compatibilidad con variantes usadas en algunas bases
                "Número de documento familiar {$i}", "Numero de documento familiar {$i}",
            ];

            // Para el primer familiar, excluir el documento del encuestado
            // Además, excluir específicamente el campo "Numero de documento de identidad del encuestado"
            $excludeValues = $mainDocumentValue ? [$mainDocumentValue] : [];
            
            // Buscar el valor del campo del documento del encuestado (principal) y excluirlo también
            $encuestadoDocumentColumn = $this->findColumnValue($row, $headers, [
                'Numero de documento de identidad',
                'Número de documento de identidad',
                'Numero de documento de identidad del encuestado',
                'Número de documento de identidad del encuestado'
            ]);
            if (!empty($encuestadoDocumentColumn)) {
                $excludeValues[] = $encuestadoDocumentColumn;
            }
            
            // Buscar el valor del campo "Tipo de documento1" y excluirlo también (para evitar que tome el documento del encuestado)
            $tipoDocumentoMain = $this->findColumnValue($row, $headers, ['Tipo de documento1', 'tipo de documento1']);
            if (!empty($tipoDocumentoMain)) {
                $excludeValues[] = $tipoDocumentoMain;
            }
            
            // Buscar el valor del campo "Nombres y apellidos" (principal) y excluirlo también
            $principalNombre = $this->findColumnValue($row, $headers, ['Nombres y apellidos', 'nombres y apellidos', 'nombre', 'nombres', 'Nombre', 'Nombres', 'nombre completo', 'Nombre Completo']);
            if (!empty($principalNombre)) {
                $excludeValues[] = $principalNombre;
            }
            
            // Buscar el valor del campo "primer nombre" y excluirlo también
            $primerNombre = $this->findColumnValue($row, $headers, ['primer nombre', 'Primer Nombre']);
            if (!empty($primerNombre)) {
                $excludeValues[] = $primerNombre;
            }
            
            // Buscar el valor del campo "primer apellido" y excluirlo también
            $primerApellido = $this->findColumnValue($row, $headers, ['primer apellido', 'Primer Apellido']);
            if (!empty($primerApellido)) {
                $excludeValues[] = $primerApellido;
            }
            
            // Buscar el valor del campo "segundo apellido" y excluirlo también
            $segundoApellido = $this->findColumnValue($row, $headers, ['segundo apellido', 'Segundo Apellido']);
            if (!empty($segundoApellido)) {
                $excludeValues[] = $segundoApellido;
            }
            
            // Buscar el valor del campo "Nombres y apellidos1" (para el primer familiar) y excluirlo también
            if ($i === 1) {
                $nombreFamiliar1 = $this->findColumnValue($row, $headers, ['Nombres y apellidos1', 'nombres y apellidos1', 'Nombres y apellidos 1', 'nombres y apellidos 1']);
                if (!empty($nombreFamiliar1)) {
                    $excludeValues[] = $nombreFamiliar1;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos2" (para el segundo familiar) y excluirlo también
            if ($i === 2) {
                $nombreFamiliar2 = $this->findColumnValue($row, $headers, ['Nombres y apellidos2', 'nombres y apellidos2', 'Nombres y apellidos 2', 'nombres y apellidos 2']);
                if (!empty($nombreFamiliar2)) {
                    $excludeValues[] = $nombreFamiliar2;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos3" (para el tercer familiar) y excluirlo también
            if ($i === 3) {
                $nombreFamiliar3 = $this->findColumnValue($row, $headers, ['Nombres y apellidos3', 'nombres y apellidos3', 'Nombres y apellidos 3', 'nombres y apellidos 3']);
                if (!empty($nombreFamiliar3)) {
                    $excludeValues[] = $nombreFamiliar3;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos4" (para el cuarto familiar) y excluirlo también
            if ($i === 4) {
                $nombreFamiliar4 = $this->findColumnValue($row, $headers, ['Nombres y apellidos4', 'nombres y apellidos4', 'Nombres y apellidos 4', 'nombres y apellidos 4']);
                if (!empty($nombreFamiliar4)) {
                    $excludeValues[] = $nombreFamiliar4;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos5" (para el quinto familiar) y excluirlo también
            if ($i === 5) {
                $nombreFamiliar5 = $this->findColumnValue($row, $headers, ['Nombres y apellidos5', 'nombres y apellidos5', 'Nombres y apellidos 5', 'nombres y apellidos 5']);
                if (!empty($nombreFamiliar5)) {
                    $excludeValues[] = $nombreFamiliar5;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos6" (para el sexto familiar) y excluirlo también
            if ($i === 6) {
                $nombreFamiliar6 = $this->findColumnValue($row, $headers, ['Nombres y apellidos6', 'nombres y apellidos6', 'Nombres y apellidos 6', 'nombres y apellidos 6']);
                if (!empty($nombreFamiliar6)) {
                    $excludeValues[] = $nombreFamiliar6;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos7" (para el séptimo familiar) y excluirlo también
            if ($i === 7) {
                $nombreFamiliar7 = $this->findColumnValue($row, $headers, ['Nombres y apellidos7', 'nombres y apellidos7', 'Nombres y apellidos 7', 'nombres y apellidos 7']);
                if (!empty($nombreFamiliar7)) {
                    $excludeValues[] = $nombreFamiliar7;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos8" (para el octavo familiar) y excluirlo también
            if ($i === 8) {
                $nombreFamiliar8 = $this->findColumnValue($row, $headers, ['Nombres y apellidos8', 'nombres y apellidos8', 'Nombres y apellidos 8', 'nombres y apellidos 8']);
                if (!empty($nombreFamiliar8)) {
                    $excludeValues[] = $nombreFamiliar8;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos9" (para el noveno familiar) y excluirlo también
            if ($i === 9) {
                $nombreFamiliar9 = $this->findColumnValue($row, $headers, ['Nombres y apellidos9', 'nombres y apellidos9', 'Nombres y apellidos 9', 'nombres y apellidos 9']);
                if (!empty($nombreFamiliar9)) {
                    $excludeValues[] = $nombreFamiliar9;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos10" (para el décimo familiar) y excluirlo también
            if ($i === 10) {
                $nombreFamiliar10 = $this->findColumnValue($row, $headers, ['Nombres y apellidos10', 'nombres y apellidos10', 'Nombres y apellidos 10', 'nombres y apellidos 10']);
                if (!empty($nombreFamiliar10)) {
                    $excludeValues[] = $nombreFamiliar10;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos11" (para el undécimo familiar) y excluirlo también
            if ($i === 11) {
                $nombreFamiliar11 = $this->findColumnValue($row, $headers, ['Nombres y apellidos11', 'nombres y apellidos11', 'Nombres y apellidos 11', 'nombres y apellidos 11']);
                if (!empty($nombreFamiliar11)) {
                    $excludeValues[] = $nombreFamiliar11;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos12" (para el duodécimo familiar) y excluirlo también
            if ($i === 12) {
                $nombreFamiliar12 = $this->findColumnValue($row, $headers, ['Nombres y apellidos12', 'nombres y apellidos12', 'Nombres y apellidos 12', 'nombres y apellidos 12']);
                if (!empty($nombreFamiliar12)) {
                    $excludeValues[] = $nombreFamiliar12;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos13" (para el decimotercer familiar) y excluirlo también
            if ($i === 13) {
                $nombreFamiliar13 = $this->findColumnValue($row, $headers, ['Nombres y apellidos13', 'nombres y apellidos13', 'Nombres y apellidos 13', 'nombres y apellidos 13']);
                if (!empty($nombreFamiliar13)) {
                    $excludeValues[] = $nombreFamiliar13;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos14" (para el decimocuarto familiar) y excluirlo también
            if ($i === 14) {
                $nombreFamiliar14 = $this->findColumnValue($row, $headers, ['Nombres y apellidos14', 'nombres y apellidos14', 'Nombres y apellidos 14', 'nombres y apellidos 14']);
                if (!empty($nombreFamiliar14)) {
                    $excludeValues[] = $nombreFamiliar14;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos15" (para el decimoquinto familiar) y excluirlo también
            if ($i === 15) {
                $nombreFamiliar15 = $this->findColumnValue($row, $headers, ['Nombres y apellidos15', 'nombres y apellidos15', 'Nombres y apellidos 15', 'nombres y apellidos 15']);
                if (!empty($nombreFamiliar15)) {
                    $excludeValues[] = $nombreFamiliar15;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos16" (para el decimosexto familiar) y excluirlo también
            if ($i === 16) {
                $nombreFamiliar16 = $this->findColumnValue($row, $headers, ['Nombres y apellidos16', 'nombres y apellidos16', 'Nombres y apellidos 16', 'nombres y apellidos 16']);
                if (!empty($nombreFamiliar16)) {
                    $excludeValues[] = $nombreFamiliar16;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos17" (para el decimoséptimo familiar) y excluirlo también
            if ($i === 17) {
                $nombreFamiliar17 = $this->findColumnValue($row, $headers, ['Nombres y apellidos17', 'nombres y apellidos17', 'Nombres y apellidos 17', 'nombres y apellidos 17']);
                if (!empty($nombreFamiliar17)) {
                    $excludeValues[] = $nombreFamiliar17;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos18" (para el decimoctavo familiar) y excluirlo también
            if ($i === 18) {
                $nombreFamiliar18 = $this->findColumnValue($row, $headers, ['Nombres y apellidos18', 'nombres y apellidos18', 'Nombres y apellidos 18', 'nombres y apellidos 18']);
                if (!empty($nombreFamiliar18)) {
                    $excludeValues[] = $nombreFamiliar18;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos19" (para el decimonoveno familiar) y excluirlo también
            if ($i === 19) {
                $nombreFamiliar19 = $this->findColumnValue($row, $headers, ['Nombres y apellidos19', 'nombres y apellidos19', 'Nombres y apellidos 19', 'nombres y apellidos 19']);
                if (!empty($nombreFamiliar19)) {
                    $excludeValues[] = $nombreFamiliar19;
                }
            }
            
            // Buscar el valor del campo "Nombres y apellidos20" (para el vigésimo familiar) y excluirlo también
            if ($i === 20) {
                $nombreFamiliar20 = $this->findColumnValue($row, $headers, ['Nombres y apellidos20', 'nombres y apellidos20', 'Nombres y apellidos 20', 'nombres y apellidos 20']);
                if (!empty($nombreFamiliar20)) {
                    $excludeValues[] = $nombreFamiliar20;
                }
            }
            
            $familiar['numero'] = $this->findColumnValue($row, $headers, $numPatterns, $excludeValues);

            $familiares[] = $familiar;
        }

        return $familiares;
    }

    /**
     * Buscar valor de columna usando múltiples nombres posibles
     */
    private function findColumnValue($row, $headers, $possibleNames, $excludeValues = [])
    {
        foreach ($possibleNames as $name) {
            if (in_array($name, $headers) && isset($row[$name])) {
                $value = trim((string)$row[$name]);
                if (!empty($value)) {
                    // Si el valor está en la lista de excluidos, saltar
                    if (!empty($excludeValues) && in_array($value, $excludeValues)) {
                        continue;
                    }
                    return $value;
                }
            }
        }
        return '';
    }

    /**
     * Actualizar las columnas automáticas de un proyecto específico (método público para comandos)
     */
    public function updateAutomaticColumns(ProyectoProductivo $proyecto)
    {
        if (!$proyecto->data) {
            return false;
        }

        try {
            // Obtener datos actuales
            $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;

            if (!isset($data['rows']) || !isset($data['headers'])) {
                return false;
            }

            $rows = $data['rows'];
            $headers = $data['headers'];

            // Llamar al método protegido para recalcular
            $this->addAutomaticColumns($headers, $rows, $proyecto->ano);

            // Actualizar los datos en el proyecto
            $data['rows'] = $rows;
            $data['headers'] = $headers;
            $data['updated_automatic_columns_at'] = now()->toISOString();

            $proyecto->update([
                'data' => $data
            ]);

            return true;

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error actualizando columnas automáticas del proyecto {$proyecto->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Método para actualizar columnas automáticas vía POST (para botón manual)
     */
    public function updateAutomaticColumnsPost(ProyectoProductivo $proyecto)
    {
        try {
            $updated = $this->updateAutomaticColumns($proyecto);

            if ($updated) {
                return redirect()->back()->with('success', 'Columnas automáticas actualizadas correctamente.');
            } else {
                return redirect()->back()->with('error', 'Error al actualizar las columnas automáticas.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error interno al actualizar las columnas: ' . $e->getMessage());
        }
    }

    /**
     * Método para actualizar columnas automáticas de todos los proyectos
     */
    public function updateAllProjectsColumns()
    {
        try {
            $proyectos = ProyectoProductivo::whereNotNull('data')
                ->where('data->total_rows', '>', 0)
                ->get();

            $updated = 0;
            $errors = 0;

            foreach($proyectos as $proyecto) {
                $result = $this->updateAutomaticColumns($proyecto);
                if ($result) {
                    $updated++;
                } else {
                    $errors++;
                }
            }

            return response()->json([
                'message' => 'Actualización completada',
                'total_proyectos' => $proyectos->count(),
                'actualizados' => $updated,
                'errores' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener columnas de referencia para el formulario manual
     */
    private function getColumnasReferencia()
    {
        // Intentar obtener columnas de un proyecto existente
        $proyectoExistente = ProyectoProductivo::whereNotNull('data')
            ->where('data->total_rows', '>', 0)
            ->latest()
            ->first();

        if ($proyectoExistente) {
            $data = is_string($proyectoExistente->data) ? json_decode($proyectoExistente->data, true) : $proyectoExistente->data;
            $headers = $data['headers'] ?? [];

            if (!empty($headers)) {
                // Preparar información de tipos de campos para cada columna
                $columnas = [];
                foreach ($headers as $header) {
                    $columnas[] = [
                        'nombre' => $header,
                        'tipo' => $this->inferirTipoCampo($header),
                        'requerido' => $this->esCampoRequerido($header)
                    ];
                }
                return $columnas;
            }
        }

        // Columnas por defecto si no hay proyectos existentes
        return [
            ['nombre' => 'Documento', 'tipo' => 'number', 'requerido' => true],
            ['nombre' => 'Nombre Completo', 'tipo' => 'text', 'requerido' => true],
            ['nombre' => 'Fecha Nacimiento', 'tipo' => 'date', 'requerido' => false],
            ['nombre' => 'Género', 'tipo' => 'select', 'requerido' => false, 'opciones' => ['Masculino', 'Femenino']],
            ['nombre' => 'Dirección', 'tipo' => 'text', 'requerido' => false],
            ['nombre' => 'Teléfono', 'tipo' => 'tel', 'requerido' => false],
            ['nombre' => 'Correo', 'tipo' => 'email', 'requerido' => false],
        ];
    }

    /**
     * Inferir el tipo de campo basado en el nombre de la columna
     */
    private function inferirTipoCampo($nombreColumna)
    {
        $nombreLower = strtolower($nombreColumna);

        // Tipos basados en palabras clave
        if (str_contains($nombreLower, 'fecha') || str_contains($nombreLower, 'nacimiento') || str_contains($nombreLower, 'date')) {
            return 'date';
        }

        if (str_contains($nombreLower, 'documento') || str_contains($nombreLower, 'cedula') || str_contains($nombreLower, 'doc') ||
            str_contains($nombreLower, 'numero') || str_contains($nombreLower, 'id')) {
            return 'number';
        }

        if (str_contains($nombreLower, 'correo') || str_contains($nombreLower, 'email') || str_contains($nombreLower, 'mail')) {
            return 'email';
        }

        if (str_contains($nombreLower, 'telefono') || str_contains($nombreLower, 'tel') || str_contains($nombreLower, 'celular') ||
            str_contains($nombreLower, 'movil') || str_contains($nombreLower, 'phone')) {
            return 'tel';
        }

        if (str_contains($nombreLower, 'genero') || str_contains($nombreLower, 'sexo') || str_contains($nombreLower, 'gender') ||
            str_contains($nombreLower, 'tipo') || str_contains($nombreLower, 'categoria')) {
            return 'select';
        }

        // Por defecto texto
        return 'text';
    }

    /**
     * Determinar si un campo es requerido basado en el nombre
     */
    private function esCampoRequerido($nombreColumna)
    {
        $nombreLower = strtolower($nombreColumna);
        $camposRequeridos = ['documento', 'cedula', 'nombre', 'name', 'doc'];

        foreach ($camposRequeridos as $campo) {
            if (str_contains($nombreLower, $campo)) {
                return true;
            }
        }

        return false;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Caracterizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CaracterizacionController extends Controller
{
    public function index(Request $request)
    {
        // Obtener o crear la caracterización global (ID=1)
        $caracterizacion = Caracterizacion::find(1);

        if (!$caracterizacion) {
            $caracterizacion = Caracterizacion::create([
                'nombre' => 'Base de Datos de Caracterizaciones',
                'ano' => date('Y'),
            ]);
        }

        // Preparar datos para la vista
        $data = $caracterizacion->data;
        $allRows = [];
        $headers = [];
        $hasData = false;
        $paginatedRows = [];
        $pagination = null;
        $activeFilters = [];

        if ($data && is_string($data)) {
            $data = json_decode($data, true);
        }

        if ($data && isset($data['rows']) && isset($data['headers'])) {
            $allRows = $data['rows'];
            $headers = $data['headers'];
            $hasData = !empty($allRows);

            // Para optimización cliente-side: enviar todos los datos sin paginación
            // La paginación y filtrado se harán en JavaScript
            $paginatedRows = $allRows; // Enviar todos los datos para funcionalidad completa
            $pagination = null; // Sin paginación server-side
            $activeFilters = []; // Los filtros serán manejados por el cliente
        }

        // Preparar datos para filtros (usando todos los datos originales para las opciones)
        $filterData = [];
        if ($hasData) {
            $filterData = $this->prepareFilterData($allRows, $headers);
        }

        return view('caracterizaciones.index', compact(
            'caracterizacion',
            'paginatedRows',
            'headers',
            'hasData',
            'pagination',
            'filterData',
            'activeFilters'
        ));
    }

    public function caracterizacionesPorAno($ano)
    {
        // Obtener caracterizaciones del año específico
        $caracterizaciones = Caracterizacion::where('ano', $ano)
            ->latest()
            ->paginate(20);

        $totalCaracterizaciones = Caracterizacion::where('ano', $ano)->count();

        return view('caracterizaciones.caracterizaciones-por-ano', compact('caracterizaciones', 'ano', 'totalCaracterizaciones'));
    }

    public function create()
    {
        return view('caracterizaciones.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ano' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
        ]);

        $caracterizacion = Caracterizacion::create($data);

        // Redirigir a la vista del año correspondiente si se especificó un año
        if ($caracterizacion->ano) {
            return redirect()->route('caracterizaciones.por-ano', $caracterizacion->ano)->with('success','¡Caracterización registrada Correctamente!');
        }

        return redirect()->route('caracterizaciones.index')->with('success','¡Caracterización registrada Correctamente!');
    }

    public function edit(Caracterizacion $caracterizacion)
    {
        return view('caracterizaciones.edit', compact('caracterizacion'));
    }

    public function update(Request $request, Caracterizacion $caracterizacion)
    {
        $caracterizacion->update($request->all());
        return redirect()->route('caracterizaciones.index')->with('success','Caracterización actualizada');
    }

    public function destroy(Caracterizacion $caracterizacion)
    {
        $caracterizacion->delete();
        return back()->with('success','¡Caracterización eliminada Correctamente!');
    }

    public function uploadExcel(Caracterizacion $caracterizacion)
    {
        return view('caracterizaciones.upload_excel', compact('caracterizacion'));
    }

    public function processExcel(Request $request, Caracterizacion $caracterizacion)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
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

            // Guardar datos en la caracterización
            $caracterizacion->update([
                'data' => $dataToSave
            ]);

            $successMessage = 'Excel subido correctamente. ' . count($processedRows) . ' filas procesadas.';

            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }

            return redirect()->route('caracterizaciones.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            $errorMessage = 'Error al procesar el archivo: ' . $e->getMessage();

            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 400);
            }

            return back()->with('error', $errorMessage);
        }
    }

    public function show(Caracterizacion $caracterizacion)
    {
        if (!$caracterizacion->data) {
            return redirect()->route('caracterizaciones.index')->with('error', 'Esta caracterización no tiene datos cargados.');
        }

        // Preparar datos para la vista
        $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
        $rows = $data['rows'] ?? [];
        $headers = $data['headers'] ?? [];

        return view('caracterizaciones.show', compact('caracterizacion', 'rows', 'headers'));
    }

    public function exportExcel(Request $request, Caracterizacion $caracterizacion)
    {
        if (!$caracterizacion->data) {
            return redirect()->back()->with('error', 'Esta caracterización no tiene datos para exportar.');
        }

        // Preparar datos para Excel
        $data = $caracterizacion->data;
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data) || !isset($data['headers']) || !isset($data['rows'])) {
            return redirect()->back()->with('error', 'Los datos de la caracterización no están en el formato correcto.');
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
        $fileName = $caracterizacion->nombre . $filterSuffix . '_' . date('Y-m-d_H-i-s') . '.xlsx';

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

    /**
     * Aplicar filtros a las filas de datos (para paginación y exportación)
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

    /**
     * Endpoint AJAX para filtros dinámicos
     */
    public function ajaxFilterData(Request $request)
    {
        // Obtener o crear la caracterización global (ID=1)
        $caracterizacion = Caracterizacion::find(1);

        if (!$caracterizacion || !$caracterizacion->data) {
            return response()->json(['error' => 'No hay datos disponibles'], 404);
        }

        // Preparar datos para la vista
        $data = $caracterizacion->data;
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!$data || !isset($data['rows']) || !isset($data['headers'])) {
            return response()->json(['error' => 'Datos no válidos'], 400);
        }

        $allRows = $data['rows'];
        $headers = $data['headers'];

        // Obtener filtros activos del request
        $activeFilters = $request->get('filters', []);

        // Aplicar filtros del lado del servidor si existen
        $filteredRows = $this->applyFiltersToRows($allRows, $headers, $activeFilters);

        // Implementar paginación manual sobre los datos filtrados
        $perPage = 300; // filas por página
        $currentPage = $request->get('page', 1);
        $totalRows = count($filteredRows);

        // Calcular índices para slicing
        $offset = ($currentPage - 1) * $perPage;
        $paginatedRows = array_slice($filteredRows, $offset, $perPage);

        // Crear objeto de paginación manual
        $pagination = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedRows,
            $totalRows,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );

        // Preparar respuesta JSON
        return response()->json([
            'data' => $paginatedRows,
            'headers' => $headers,
            'pagination' => [
                'current_page' => $pagination->currentPage(),
                'last_page' => $pagination->lastPage(),
                'per_page' => $pagination->perPage(),
                'total' => $pagination->total(),
                'from' => $pagination->firstItem(),
                'to' => $pagination->lastItem(),
            ],
            'active_filters' => $activeFilters,
            'total_filtered' => count($filteredRows),
            'total_original' => count($allRows),
        ]);
    }

    /**
     * Preparar datos únicos para los filtros de cada columna
     */
    private function prepareFilterData($rows, $headers)
    {
        $filterData = [];

        foreach ($headers as $column) {
            $uniqueValues = [];
            foreach ($rows as $row) {
                $value = trim($row[$column] ?? '');
                if (!empty($value) && !in_array($value, $uniqueValues)) {
                    $uniqueValues[] = $value;
                }
            }
            // Ordenar alfabéticamente y mantener consistencia en la vista
            sort($uniqueValues);
            $filterData[$column] = $uniqueValues;
        }

        return $filterData;
    }
}

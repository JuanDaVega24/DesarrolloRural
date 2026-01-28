<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Caracterizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    /**
     * Mostrar vista de estadísticas de corregimientos
     */
    public function estadisticasCorregimientosView()
    {
        try {
            // Obtener datos de caracterización (ID=1 por defecto)
            $caracterizacion = Caracterizacion::find(1);

            if (!$caracterizacion || !$caracterizacion->data) {
                return redirect()->route('reportes.index')->with('error', 'No hay datos de caracterización disponibles');
            }

            // Decodificar datos
            $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
            $rows = $data['rows'] ?? [];

            // Contar TOTAL de filas en la caracterización (sin filtrar)
            $totalFilasCaracterizacion = count($rows);

            // Inicializar contadores por corregimiento
            $corregimientos = [
                1 => ['numero' => 1, 'nombre' => 'Corregimiento 1', 'count' => 0],
                2 => ['numero' => 2, 'nombre' => 'Corregimiento 2', 'count' => 0],
                3 => ['numero' => 3, 'nombre' => 'Corregimiento 3', 'count' => 0],
            ];

            $totalPersonas = 0;
            $filasSinCorregimiento = 0;

            // Contar personas por corregimiento
            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                $totalPersonas++; // Contar TODAS las filas válidas

                // Buscar columna de corregimiento (puede tener diferentes nombres)
                $corregimiento = null;

                // Buscar por posibles nombres de columna - más flexible
                $corregimientoKeys = ['corregimiento', 'Corregimiento', 'corregimiento_cz', 'Corregimiento_CZ', 'Corregimiento_cz', 'CORREGIMIENTO'];

                foreach ($corregimientoKeys as $key) {
                    if (isset($row[$key]) && !empty(trim((string)$row[$key]))) {
                        $corregimiento = trim((string)$row[$key]);
                        // Normalizar valores comunes
                        if (in_array($corregimiento, ['1', '1.0', '01', 'Corregimiento 1', 'corregimiento 1'])) {
                            $corregimiento = '1';
                        } elseif (in_array($corregimiento, ['2', '2.0', '02', 'Corregimiento 2', 'corregimiento 2'])) {
                            $corregimiento = '2';
                        } elseif (in_array($corregimiento, ['3', '3.0', '03', 'Corregimiento 3', 'corregimiento 3'])) {
                            $corregimiento = '3';
                        }
                        break;
                    }
                }

                // Si encontramos un corregimiento válido, incrementamos el contador correspondiente
                if ($corregimiento && isset($corregimientos[$corregimiento])) {
                    $corregimientos[$corregimiento]['count']++;
                } 
            }

            // Preparar datos para la vista
            $estadisticas = [
                'total' => $totalPersonas,
                'detalles' => array_values($corregimientos),
                'chartData' => [
                    'labels' => array_column($corregimientos, 'nombre'),
                    'data' => array_column($corregimientos, 'count'),
                    'colors' => ['#4A7C2F', '#3366CC', '#FF6B35'], // Colores intuitivos
                ]
            ];

            // Obtener estadísticas de género
            $generoStats = $this->getEstadisticasGeneroData();

            // Obtener estadísticas de edad
            $edadStats = $this->getEstadisticasEdadData();

            // Obtener estadísticas de área
            $areaStats = $this->getEstadisticasAreaData();

            return view('reportes.estadisticas-corregimientos', compact('estadisticas', 'generoStats', 'edadStats', 'areaStats'));

        } catch (\Exception $e) {
            return redirect()->route('reportes.index')->with('error', 'Error al cargar las estadísticas: ' . $e->getMessage());
        }
    }

    public function filtrar(Request $request)
    {
        $query = Encuesta::query();

        if ($request->filled('municipio')) {
            $query->where('municipio_nacimiento', $request->municipio);
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        $resultados = $query->get();
        return view('reportes.resultados', compact('resultados'));
    }

    public function exportarExcel()
    {
        // pendiente implementar con Laravel Excel
    }

    /**
     * Exportar estadísticas de corregimientos a PDF
     */
    public function estadisticasCorregimientosPDF()
    {
        try {
            // Obtener los mismos datos que la vista
            $caracterizacion = Caracterizacion::find(1);

            if (!$caracterizacion || !$caracterizacion->data) {
                return redirect()->back()->with('error', 'No hay datos de caracterización disponibles');
            }

            // Decodificar datos
            $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
            $rows = $data['rows'] ?? [];

            // Inicializar contadores por corregimiento
            $corregimientos = [
                1 => ['numero' => 1, 'nombre' => 'Corregimiento 1', 'count' => 0],
                2 => ['numero' => 2, 'nombre' => 'Corregimiento 2', 'count' => 0],
                3 => ['numero' => 3, 'nombre' => 'Corregimiento 3', 'count' => 0]
            ];

            $totalPersonas = 0;

            // Contar personas por corregimiento
            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar columna de corregimiento
                $corregimiento = null;
                $corregimientoKeys = ['corregimiento', 'Corregimiento', 'corregimiento_cz', 'Corregimiento_CZ'];

                foreach ($corregimientoKeys as $key) {
                    if (isset($row[$key])) {
                        $corregimiento = trim((string)$row[$key]);
                        break;
                    }
                }

                // Si encontramos un corregimiento válido, incrementamos el contador
                if ($corregimiento && isset($corregimientos[$corregimiento])) {
                    $corregimientos[$corregimiento]['count']++;
                    $totalPersonas++;
                }
            }

            // Preparar datos para el gráfico
            $labels = [];
            $chartValues = [];
            $detalles = [];
            
            foreach (array_values($corregimientos) as $corr) {
                $labels[] = (string)$corr['nombre'];
                $chartValues[] = (int)$corr['count'];
                $detalles[] = [
                    'nombre' => (string)$corr['nombre'],
                    'count' => (int)$corr['count'],
                    'numero' => (int)$corr['numero'],
                ];
            }

            // Preparar datos para el PDF
            $estadisticas = [
                'total' => (int)$totalPersonas,
                'detalles' => $detalles,
                'fecha_generacion' => (string)now()->format('d/m/Y H:i:s'),
                'chartData' => [
                    'labels' => $labels,
                    'data' => $chartValues,
                ],
            ];

            // Generar vista HTML optimizada para impresión/PDF
            return view('reportes.pdfs.estadisticas-corregimientos', compact('estadisticas'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Exportar PDF con estadísticas de género
     */
    public function estadisticasGeneroPDF()
    {
        try {
            $generoStats = $this->getEstadisticasGeneroData();
            
            return view('reportes.pdfs.estadisticas-corregimientos', compact('generoStats'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Exportar PDF con estadísticas por edad
     */
    public function estadisticasEdadPDF()
    {
        try {
            $edadStats = $this->getEstadisticasEdadData();
            
            return view('reportes.pdfs.estadisticas-corregimientos', compact('edadStats'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Exportar PDF con estadísticas por área
     */
    public function estadisticasAreaPDF()
    {
        try {
            $areaStats = $this->getEstadisticasAreaData();
            
            return view('reportes.pdfs.estadisticas-corregimientos', compact('areaStats'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas de distribución por género y corregimiento (datos para vista)
     */
    private function getEstadisticasGeneroData()
    {
        try {
            // Obtener datos de caracterización
            $caracterizacion = Caracterizacion::find(1);

            if (!$caracterizacion || !$caracterizacion->data) {
                return [
                    'chartData' => ['labels' => [], 'datasets' => []],
                    'detalles' => [],
                    'totales' => ['femenino' => 0, 'masculino' => 0, 'total' => 0]
                ];
            }

            // Decodificar datos
            $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
            $rows = $data['rows'] ?? [];
            $headers = $data['headers'] ?? [];

            // Log headers para debugging (solo en desarrollo)
            if (config('app.debug')) {
                Log::info('Headers disponibles en caracterizaciones:', $headers);
            }

            // Inicializar contadores por corregimiento y género
            $estadisticasGenero = [
                1 => ['corregimiento' => 'Corregimiento 1', 'femenino' => 0, 'masculino' => 0, 'total' => 0],
                2 => ['corregimiento' => 'Corregimiento 2', 'femenino' => 0, 'masculino' => 0, 'total' => 0],
                3 => ['corregimiento' => 'Corregimiento 3', 'femenino' => 0, 'masculino' => 0, 'total' => 0]
            ];

            $totalGeneral = ['femenino' => 0, 'masculino' => 0, 'total' => 0];

            // Contar por género y corregimiento
            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar género - búsqueda flexible por nombre de columna
                $genero = null;
                $generoKeys = ['genero', 'género', 'sexo', 'gender', 'gen', 'Genero', 'Género', 'Sexo', 'Gender', 'Gen'];

                foreach ($generoKeys as $key) {
                    if (isset($row[$key])) {
                        $generoValue = trim(strtolower((string)$row[$key]));
                        // Normalizar valores comunes
                        if (in_array($generoValue, ['femenino', 'f', 'mujer', 'female', 'feminine', 'femenina', 'mujeres'])) {
                            $genero = 'femenino';
                        } elseif (in_array($generoValue, ['masculino', 'm', 'hombre', 'male', 'masculine', 'masculina', 'hombres'])) {
                            $genero = 'masculino';
                        }
                        break;
                    }
                }

                // Si no encontró por nombres exactos, buscar por similitud en headers
                if (!$genero) {
                    foreach ($headers as $header) {
                        $headerNormalized = trim(strtolower($header));
                        if (str_contains($headerNormalized, 'genero') || str_contains($headerNormalized, 'género') ||
                            str_contains($headerNormalized, 'sexo') || str_contains($headerNormalized, 'gender')) {
                            if (isset($row[$header])) {
                                $generoValue = trim(strtolower((string)$row[$header]));
                                if (in_array($generoValue, ['femenino', 'f', 'mujer', 'female', 'feminine', 'femenina', 'mujeres'])) {
                                    $genero = 'femenino';
                                } elseif (in_array($generoValue, ['masculino', 'm', 'hombre', 'male', 'masculine', 'masculina', 'hombres'])) {
                                    $genero = 'masculino';
                                }
                                break;
                            }
                        }
                    }
                }

                // Buscar corregimiento - búsqueda flexible por nombre de columna
                $corregimiento = null;
                $corregimientoKeys = ['corregimiento', 'Corregimiento', 'corregimiento_cz', 'Corregimiento_CZ', 'Corregimiento_cz', 'CORREGIMIENTO'];

                foreach ($corregimientoKeys as $key) {
                    if (isset($row[$key])) {
                        $corregimiento = trim((string)$row[$key]);
                        break;
                    }
                }

                // Si no encontró por nombres exactos, buscar por similitud en headers
                if (!$corregimiento) {
                    foreach ($headers as $header) {
                        $headerNormalized = trim(strtolower($header));
                        if (str_contains($headerNormalized, 'corregimiento') || str_contains($headerNormalized, 'corregimiento')) {
                            if (isset($row[$header])) {
                                $corregimiento = trim((string)$row[$header]);
                                break;
                            }
                        }
                    }
                }

                // Debug: log primera fila
                static $debugCount = 0;
                if ($debugCount < 3) {
                    Log::info('Fila ' . $debugCount . ':', ['genero_encontrado' => $genero, 'corregimiento_encontrado' => $corregimiento, 'valores_row' => $row]);
                    $debugCount++;
                }

                // Si tenemos género y corregimiento válido, contar
                if ($genero && $corregimiento && isset($estadisticasGenero[$corregimiento])) {
                    $estadisticasGenero[$corregimiento][$genero]++;
                    $estadisticasGenero[$corregimiento]['total']++;
                    $totalGeneral[$genero]++;
                    $totalGeneral['total']++;
                }
            }

            // Preparar datos para gráfico de barras
            $chartData = [
                'labels' => array_column($estadisticasGenero, 'corregimiento'),
                'datasets' => [
                    [
                        'label' => 'Femenino',
                        'data' => array_column($estadisticasGenero, 'femenino'),
                        'backgroundColor' => '#FF69B4', // Rosa
                        'borderColor' => '#FF1493',
                        'borderWidth' => 2,
                        'borderRadius' => 4,
                        'borderSkipped'=> false,
                    ],
                    [
                        'label' => 'Masculino',
                        'data' => array_column($estadisticasGenero, 'masculino'),
                        'backgroundColor' => '#4169E1', // Azul real
                        'borderColor' => '#000080',
                        'borderWidth' => 2,
                        'borderRadius' => 4,
                        'borderSkipped'=> false,
                    ]
                ]
            ];

            return [
                'chartData' => $chartData,
                'detalles' => array_values($estadisticasGenero),
                'totales' => $totalGeneral
            ];

        } catch (\Exception $e) {
            return [
                'chartData' => ['labels' => [], 'datasets' => []],
                'detalles' => [],
                'totales' => ['femenino' => 0, 'masculino' => 0, 'total' => 0]
            ];
        }
    }

    /**
     * Obtener estadísticas de distribución por edad y corregimiento (datos para vista)
     */
    private function getEstadisticasEdadData()
    {
        try {
            // Obtener datos de caracterización
            $caracterizacion = Caracterizacion::find(1);

            if (!$caracterizacion || !$caracterizacion->data) {
                return [
                    'chartData' => ['labels' => [], 'datasets' => []],
                    'detalles' => [],
                    'totales' => ['total' => 0],
                    'rangos' => []
                ];
            }

            // Decodificar datos
            $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
            $rows = $data['rows'] ?? [];
            $headers = $data['headers'] ?? [];

            // Definir rangos de edad
            $rangosEdad = [
                '0-17' => ['min' => 0, 'max' => 17, 'label' => '0-17 años'],
                '18-30' => ['min' => 18, 'max' => 30, 'label' => '18-30 años'],
                '31-45' => ['min' => 31, 'max' => 45, 'label' => '31-45 años'],
                '46-60' => ['min' => 46, 'max' => 60, 'label' => '46-60 años'],
                '61+' => ['min' => 61, 'max' => 999, 'label' => '61+ años']
            ];

            // Inicializar contadores por corregimiento y rango de edad
            $estadisticasEdad = [
                1 => array_merge(['corregimiento' => 'Corregimiento 1'], array_fill_keys(array_keys($rangosEdad), 0), ['total' => 0]),
                2 => array_merge(['corregimiento' => 'Corregimiento 2'], array_fill_keys(array_keys($rangosEdad), 0), ['total' => 0]),
                3 => array_merge(['corregimiento' => 'Corregimiento 3'], array_fill_keys(array_keys($rangosEdad), 0), ['total' => 0])
            ];

            $totalGeneral = array_merge(array_fill_keys(array_keys($rangosEdad), 0), ['total' => 0]);

            // Contar por edad y corregimiento
            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar fecha de nacimiento - búsqueda flexible por nombre de columna
                $fechaNacimiento = null;
                $fechaKeys = ['fecha_nacimiento', 'fecha_nac', 'nacimiento', 'birth', 'fecha de nacimiento', 'Fecha de Nacimiento'];

                foreach ($fechaKeys as $key) {
                    if (isset($row[$key]) && !empty(trim((string)$row[$key]))) {
                        $fechaNacimiento = trim((string)$row[$key]);
                        break;
                    }
                }

                // Si no encontró por nombres exactos, buscar por similitud en headers
                if (!$fechaNacimiento) {
                    foreach ($headers as $header) {
                        $headerNormalized = trim(strtolower($header));
                        if (str_contains($headerNormalized, 'nacimiento') || str_contains($headerNormalized, 'nac') ||
                            str_contains($headerNormalized, 'birth') || str_contains($headerNormalized, 'edad')) {
                            if (isset($row[$header]) && !empty(trim((string)$row[$header]))) {
                                $fechaNacimiento = trim((string)$row[$header]);
                                break;
                            }
                        }
                    }
                }

                // Calcular edad si tenemos fecha de nacimiento
                $edad = null;
                if ($fechaNacimiento) {
                    $edad = $this->calcularEdad($fechaNacimiento);
                }

                // Buscar corregimiento - búsqueda flexible por nombre de columna
                $corregimiento = null;
                $corregimientoKeys = ['corregimiento', 'Corregimiento', 'corregimiento_cz', 'Corregimiento_CZ', 'Corregimiento_cz', 'CORREGIMIENTO'];

                foreach ($corregimientoKeys as $key) {
                    if (isset($row[$key])) {
                        $corregimiento = trim((string)$row[$key]);
                        break;
                    }
                }

                // Si no encontró por nombres exactos, buscar por similitud en headers
                if (!$corregimiento) {
                    foreach ($headers as $header) {
                        $headerNormalized = trim(strtolower($header));
                        if (str_contains($headerNormalized, 'corregimiento') || str_contains($headerNormalized, 'corregimiento')) {
                            if (isset($row[$header])) {
                                $corregimiento = trim((string)$row[$header]);
                                break;
                            }
                        }
                    }
                }

                // Si tenemos edad y corregimiento válido, contar
                if ($edad !== null && $corregimiento && isset($estadisticasEdad[$corregimiento])) {
                    // Determinar rango de edad
                    $rangoEncontrado = null;
                    foreach ($rangosEdad as $rango => $config) {
                        if ($edad >= $config['min'] && $edad <= $config['max']) {
                            $rangoEncontrado = $rango;
                            break;
                        }
                    }

                    if ($rangoEncontrado) {
                        $estadisticasEdad[$corregimiento][$rangoEncontrado]++;
                        $estadisticasEdad[$corregimiento]['total']++;
                        $totalGeneral[$rangoEncontrado]++;
                        $totalGeneral['total']++;
                    }
                }
            }

            // Preparar datos para gráfico de barras apiladas
            $chartData = [
                'labels' => array_column($estadisticasEdad, 'corregimiento'),
                'datasets' => []
            ];

            $coloresRangos = [
                '0-17' => '#3B82F6',    // Azul
                '18-30' => '#10B981',   // Verde
                '31-45' => '#F59E0B',   // Amarillo
                '46-60' => '#EF4444',   // Rojo
                '61+' => '#8B5CF6'      // Púrpura
            ];

            foreach ($rangosEdad as $rango => $config) {
                $chartData['datasets'][] = [
                    'label' => $config['label'],
                    'data' => array_column($estadisticasEdad, $rango),
                    'backgroundColor' => $coloresRangos[$rango],
                    'borderColor' => $coloresRangos[$rango],
                    'borderWidth' => 1,
                ];
            }

            return [
                'chartData' => $chartData,
                'detalles' => array_values($estadisticasEdad),
                'totales' => $totalGeneral,
                'rangos' => $rangosEdad
            ];

        } catch (\Exception $e) {
            Log::error('Error en getEstadisticasEdadData: ' . $e->getMessage());
            return [
                'chartData' => ['labels' => [], 'datasets' => []],
                'detalles' => [],
                'totales' => ['total' => 0],
                'rangos' => []
            ];
        }
    }

    /**
     * Obtener estadísticas de área por corregimiento (datos para vista)
     */
    private function getEstadisticasAreaData()
    {
        try {
            // Obtener datos de caracterización
            $caracterizacion = Caracterizacion::find(1);

            if (!$caracterizacion || !$caracterizacion->data) {
                return [
                    'chartData' => ['labels' => [], 'datasets' => []],
                    'detalles' => [],
                    'totales' => ['area_total' => 0]
                ];
            }

            // Decodificar datos
            $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
            $rows = $data['rows'] ?? [];
            $headers = $data['headers'] ?? [];

            // Inicializar contadores por corregimiento
            $estadisticasArea = [
                1 => ['corregimiento' => 'Corregimiento 1', 'area' => 0, 'total_registros' => 0],
                2 => ['corregimiento' => 'Corregimiento 2', 'area' => 0, 'total_registros' => 0],
                3 => ['corregimiento' => 'Corregimiento 3', 'area' => 0, 'total_registros' => 0]
            ];

            $totalGeneral = ['area_total' => 0, 'total_registros' => 0];

            // Sumar áreas por corregimiento
            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar área - búsqueda flexible por nombre de columna
                $area = null;
                $areaKeys = ['Área (ha)', 'area (ha)', 'area_ha', 'area', 'Area (ha)', 'Area', 'AREA (Ha)'];

                foreach ($areaKeys as $key) {
                    if (isset($row[$key]) && !empty(trim((string)$row[$key]))) {
                        $areaValue = trim((string)$row[$key]);
                        // Convertir formato europeo (coma como decimal) a formato PHP (punto como decimal)
                        $areaValue = str_replace(',', '.', $areaValue);
                        // Convertir a float si es numérico
                        if (is_numeric($areaValue)) {
                            $area = (float)$areaValue;
                            break;
                        }
                    }
                }

                // Si no encontró por nombres exactos, buscar por similitud en headers
                if ($area === null) {
                    foreach ($headers as $header) {
                        $headerNormalized = trim(strtolower($header));
                        if (str_contains($headerNormalized, 'área') || str_contains($headerNormalized, 'area')) {
                            if (isset($row[$header]) && !empty(trim((string)$row[$header]))) {
                                $areaValue = trim((string)$row[$header]);
                        // Convertir formato europeo (coma como decimal) a formato PHP (punto como decimal)
                        $areaValue = str_replace(',', '.', $areaValue);
                        // Convertir a float si es numérico
                        if (is_numeric($areaValue)) {
                            $area = (float)$areaValue;
                            break;
                        }
                            }
                        }
                    }
                }

                // Buscar corregimiento - búsqueda flexible por nombre de columna
                $corregimiento = null;
                $corregimientoKeys = ['corregimiento', 'Corregimiento', 'corregimiento_cz', 'Corregimiento_CZ', 'Corregimiento_cz', 'CORREGIMIENTO'];

                foreach ($corregimientoKeys as $key) {
                    if (isset($row[$key])) {
                        $corregimiento = trim((string)$row[$key]);
                        // Normalizar valores comunes
                        if (in_array($corregimiento, ['1', '1.0', '01', 'Corregimiento 1', 'corregimiento 1'])) {
                            $corregimiento = '1';
                        } elseif (in_array($corregimiento, ['2', '2.0', '02', 'Corregimiento 2', 'corregimiento 2'])) {
                            $corregimiento = '2';
                        } elseif (in_array($corregimiento, ['3', '3.0', '03', 'Corregimiento 3', 'corregimiento 3'])) {
                            $corregimiento = '3';
                        }
                        break;
                    }
                }

                // Si no encontró por nombres exactos, buscar por similitud en headers
                if (!$corregimiento) {
                    foreach ($headers as $header) {
                        $headerNormalized = trim(strtolower($header));
                        if (str_contains($headerNormalized, 'corregimiento') || str_contains($headerNormalized, 'corregimiento')) {
                            if (isset($row[$header])) {
                                $corregimiento = trim((string)$row[$header]);
                                // Normalizar valores comunes
                                if (in_array($corregimiento, ['1', '1.0', '01', 'Corregimiento 1', 'corregimiento 1'])) {
                                    $corregimiento = '1';
                                } elseif (in_array($corregimiento, ['2', '2.0', '02', 'Corregimiento 2', 'corregimiento 2'])) {
                                    $corregimiento = '2';
                                } elseif (in_array($corregimiento, ['3', '3.0', '03', 'Corregimiento 3', 'corregimiento 3'])) {
                                    $corregimiento = '3';
                                }
                                break;
                            }
                        }
                    }
                }

                // Si tenemos área y corregimiento válido, sumar
                if ($area !== null && $corregimiento && isset($estadisticasArea[$corregimiento])) {
                    $estadisticasArea[$corregimiento]['area'] += $area;
                    $estadisticasArea[$corregimiento]['total_registros']++;
                    $totalGeneral['area_total'] += $area;
                    $totalGeneral['total_registros']++;
                }
            }

            // Preparar datos para gráfico de barras
            $chartData = [
                'labels' => array_column($estadisticasArea, 'corregimiento'),
                'datasets' => [
                    [
                        'label' => 'Área (ha)',
                        'data' => array_column($estadisticasArea, 'area'),
                        'backgroundColor' => '#4A7C2F', // Verde
                        'borderColor' => '#3d6625',
                        'borderWidth' => 2,
                        'borderRadius' => 4,
                        'borderSkipped' => false,
                    ]
                ]
            ];

            return [
                'chartData' => $chartData,
                'detalles' => array_values($estadisticasArea),
                'totales' => $totalGeneral
            ];

        } catch (\Exception $e) {
            Log::error('Error en getEstadisticasAreaData: ' . $e->getMessage());
            return [
                'chartData' => ['labels' => [], 'datasets' => []],
                'detalles' => [],
                'totales' => ['area_total' => 0, 'total_registros' => 0]
            ];
        }
    }

    /**
     * Calcular edad a partir de fecha de nacimiento
     */
    private function calcularEdad($fechaNacimiento)
    {
        try {
            // Intentar diferentes formatos de fecha
            $formatos = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y', 'Y/m/d'];

            $fecha = null;
            foreach ($formatos as $formato) {
                $fecha = \DateTime::createFromFormat($formato, $fechaNacimiento);
                if ($fecha !== false) {
                    break;
                }
            }

            // Si no se pudo parsear con formatos conocidos, intentar crear desde string
            if ($fecha === false || $fecha === null) {
                $fecha = new \DateTime($fechaNacimiento);
            }

            if ($fecha) {
                $hoy = new \DateTime();
                $edad = $hoy->diff($fecha)->y;
                return $edad;
            }

            return null;
        } catch (\Exception $e) {
            // Log::warning('Error calculando edad para fecha: ' . $fechaNacimiento . ' - ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener estadísticas de distribución por género y corregimiento (API)
     */
    public function estadisticasGenero()
    {
        try {
            $data = $this->getEstadisticasGeneroData();

            return response()->json([
                'success' => true,
                'chartData' => $data['chartData'],
                'detalles' => $data['detalles'],
                'totales' => $data['totales']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener estadísticas de género: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportarPDF()
    {
        // pendiente con DOMPDF
    }

    /**
     * Obtener estadísticas de distribución por corregimientos desde caracterizaciones
     */
    public function estadisticasCorregimientos()
    {
        try {
            // Obtener datos de caracterización (ID=1 por defecto)
            $caracterizacion = Caracterizacion::find(1);

            if (!$caracterizacion || !$caracterizacion->data) {
                return response()->json([
                    'error' => 'No hay datos de caracterización disponibles'
                ], 404);
            }

            // Decodificar datos
            $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
            $rows = $data['rows'] ?? [];

            // Inicializar contadores por corregimiento
            $corregimientos = [
                1 => ['nombre' => 'Corregimiento 1', 'count' => 0],
                2 => ['nombre' => 'Corregimiento 2', 'count' => 0],
                3 => ['nombre' => 'Corregimiento 3', 'count' => 0]
            ];

            $totalPersonas = 0;

            // Contar personas por corregimiento
            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar columna de corregimiento (puede tener diferentes nombres)
                $corregimiento = null;

                // Buscar por posibles nombres de columna
                $corregimientoKeys = ['corregimiento', 'Corregimiento', 'corregimiento_cz', 'Corregimiento_CZ', 'CORREGIMIENTO'];

                foreach ($corregimientoKeys as $key) {
                    if (isset($row[$key])) {
                        $corregimiento = trim((string)$row[$key]);
                        break;
                    }
                }

                // Si encontramos un corregimiento válido, incrementamos el contador
                if ($corregimiento && isset($corregimientos[$corregimiento])) {
                    $corregimientos[$corregimiento]['count']++;
                    $totalPersonas++;
                }
            }

            // Preparar datos para gráfico pastel
            $chartData = [
                'labels' => array_column($corregimientos, 'nombre'),
                'data' => array_column($corregimientos, 'count'),
                'colors' => ['#A80521', '#0943B5', '#4A7C2F'], // Colores para gráfico pastel
                'total' => $totalPersonas
            ];

            return response()->json([
                'success' => true,
                'chartData' => $chartData,
                'detalles' => $corregimientos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar vista de área por corregimientos para proyectos productivos
     */
    public function areaProyectosView(Request $request)
    {
        try {
            // Obtener todos los proyectos productivos
            $proyectos = \App\Models\ProyectoProductivo::select('id', 'nombre')->orderBy('nombre')->get();

            if ($request->has('proyecto_id') && $request->proyecto_id) {
                $proyectoId = $request->proyecto_id;

                // Verificar que el proyecto existe
                $proyectoSeleccionado = \App\Models\ProyectoProductivo::find($proyectoId);
                if (!$proyectoSeleccionado) {
                    return redirect()->back()->with('error', 'Proyecto no encontrado');
                }

                // Obtener estadísticas del proyecto específico
                $proyectoStats = $this->getEstadisticasProyectoAreaData($proyectoId);
                $proyectoStatsVereda = $this->getEstadisticasProyectoVeredaData($proyectoId);

                return view('reportes.area-proyectos', compact('proyectos', 'proyectoSeleccionado', 'proyectoStats', 'proyectoStatsVereda'));
            }

            return view('reportes.area-proyectos', compact('proyectos'));

        } catch (\Exception $e) {
            return redirect()->route('reportes.index')->with('error', 'Error al cargar las estadísticas de proyectos: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas de área por corregimiento para un proyecto específico
     */
    private function getEstadisticasProyectoAreaData($proyectoId)
    {
        try {
            // Obtener el proyecto específico
            $proyecto = \App\Models\ProyectoProductivo::find($proyectoId);

            if (!$proyecto || !$proyecto->data) {
                return [
                    'chartData' => ['labels' => [], 'datasets' => []],
                    'detalles' => [],
                    'totales' => ['area_total' => 0, 'total_registros' => 0]
                ];
            }

            // Decodificar datos del proyecto
            $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
            $rows = $data['rows'] ?? [];
            $headers = $data['headers'] ?? [];

            // Inicializar contadores por corregimiento
            $estadisticasProyecto = [
                1 => ['corregimiento' => 'Corregimiento 1', 'area' => 0, 'total_registros' => 0],
                2 => ['corregimiento' => 'Corregimiento 2', 'area' => 0, 'total_registros' => 0],
                3 => ['corregimiento' => 'Corregimiento 3', 'area' => 0, 'total_registros' => 0]
            ];

            $totalGeneral = ['area_total' => 0, 'total_registros' => 0];

            // Procesar todas las filas del proyecto específico (ya están filtradas por proyecto)
            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar área - búsqueda flexible por nombre de columna
                $area = null;
                $areaKeys = ['Área (ha)', 'area (ha)', 'area_ha', 'area', 'Area (ha)', 'Area', 'AREA (Ha)'];

                foreach ($areaKeys as $key) {
                    if (isset($row[$key]) && !empty(trim((string)$row[$key]))) {
                        $areaValue = trim((string)$row[$key]);
                        // Convertir formato europeo (coma como decimal) a formato PHP (punto como decimal)
                        $areaValue = str_replace(',', '.', $areaValue);
                        // Convertir a float si es numérico
                        if (is_numeric($areaValue)) {
                            $area = (float)$areaValue;
                            break;
                        }
                    }
                }

                // Si no encontró por nombres exactos, buscar por similitud en headers
                if ($area === null) {
                    foreach ($headers as $header) {
                        $headerNormalized = trim(strtolower($header));
                        if (str_contains($headerNormalized, 'área') || str_contains($headerNormalized, 'area')) {
                            if (isset($row[$header]) && !empty(trim((string)$row[$header]))) {
                                $areaValue = trim((string)$row[$header]);
                                // Convertir a número, manejar formatos decimales
                                // Si tiene punto pero no coma, convertir punto a coma (formato europeo)
                                if (strpos($areaValue, '.') !== false && strpos($areaValue, ',') === false) {
                                    $areaValue = str_replace('.', ',', $areaValue);
                                }
                                // Ahora convertir a float (PHP entiende coma como separador decimal)
                                if (is_numeric(str_replace(',', '.', $areaValue))) {
                                    $area = (float)str_replace(',', '.', $areaValue);
                                    break;
                                }
                            }
                        }
                    }
                }

                // Buscar corregimiento - búsqueda flexible por nombre de columna
                $corregimiento = null;
                $corregimientoKeys = ['corregimiento', 'Corregimiento', 'corregimiento_cz', 'Corregimiento_CZ', 'Corregimiento_cz', 'CORREGIMIENTO'];

                foreach ($corregimientoKeys as $key) {
                    if (isset($row[$key])) {
                        $corregimiento = trim((string)$row[$key]);
                        // Normalizar valores comunes
                        if (in_array($corregimiento, ['1', '1.0', '01', 'Corregimiento 1', 'corregimiento 1'])) {
                            $corregimiento = '1';
                        } elseif (in_array($corregimiento, ['2', '2.0', '02', 'Corregimiento 2', 'corregimiento 2'])) {
                            $corregimiento = '2';
                        } elseif (in_array($corregimiento, ['3', '3.0', '03', 'Corregimiento 3', 'corregimiento 3'])) {
                            $corregimiento = '3';
                        }
                        break;
                    }
                }

                // Si no encontró por nombres exactos, buscar por similitud en headers
                if (!$corregimiento) {
                    foreach ($headers as $header) {
                        $headerNormalized = trim(strtolower($header));
                        if (str_contains($headerNormalized, 'corregimiento') || str_contains($headerNormalized, 'corregimiento')) {
                            if (isset($row[$header])) {
                                $corregimiento = trim((string)$row[$header]);
                                // Normalizar valores comunes
                                if (in_array($corregimiento, ['1', '1.0', '01', 'Corregimiento 1', 'corregimiento 1'])) {
                                    $corregimiento = '1';
                                } elseif (in_array($corregimiento, ['2', '2.0', '02', 'Corregimiento 2', 'corregimiento 2'])) {
                                    $corregimiento = '2';
                                } elseif (in_array($corregimiento, ['3', '3.0', '03', 'Corregimiento 3', 'corregimiento 3'])) {
                                    $corregimiento = '3';
                                }
                                break;
                            }
                        }
                    }
                }

                // Si tenemos área y corregimiento válido, sumar
                if ($area !== null && $corregimiento && isset($estadisticasProyecto[$corregimiento])) {
                    $estadisticasProyecto[$corregimiento]['area'] += $area;
                    $estadisticasProyecto[$corregimiento]['total_registros']++;
                    $totalGeneral['area_total'] += $area;
                    $totalGeneral['total_registros']++;
                }
            }

            // Preparar datos para gráfico de barras
            $chartData = [
                'labels' => array_column($estadisticasProyecto, 'corregimiento'),
                'datasets' => [
                    [
                        'label' => 'Área (ha)',
                        'data' => array_column($estadisticasProyecto, 'area'),
                        'backgroundColor' => '#4A7C2F', // Verde
                        'borderColor' => '#3d6625',
                        'borderWidth' => 2,
                        'borderRadius' => 4,
                        'borderSkipped' => false,
                    ]
                ]
            ];

            return [
                'chartData' => $chartData,
                'detalles' => array_values($estadisticasProyecto),
                'totales' => $totalGeneral
            ];

        } catch (\Exception $e) {
            Log::error('Error en getEstadisticasProyectoAreaData: ' . $e->getMessage());
            return [
                'chartData' => ['labels' => [], 'datasets' => []],
                'detalles' => [],
                'totales' => ['area_total' => 0, 'total_registros' => 0]
            ];
        }
    }

    /**
     * Obtener estadísticas de área por vereda para un proyecto específico
     */
    private function getEstadisticasProyectoVeredaData($proyectoId)
    {
        try {
            // Obtener el proyecto específico
            $proyecto = \App\Models\ProyectoProductivo::find($proyectoId);

            if (!$proyecto || !$proyecto->data) {
                return [
                    'chartData' => ['labels' => [], 'datasets' => []],
                    'detalles' => [],
                    'totales' => ['area_total' => 0, 'total_registros' => 0]
                ];
            }

            // Decodificar datos del proyecto
            $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
            $rows = $data['rows'] ?? [];
            $headers = $data['headers'] ?? [];

            // Inicializar array para estadísticas por vereda
            $estadisticasVereda = [];

            $totalGeneral = ['area_total' => 0, 'total_registros' => 0];

            // Procesar todas las filas del proyecto específico
            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar área - búsqueda flexible por nombre de columna
                $area = null;
                $areaKeys = ['Área (ha)', 'area (ha)', 'area_ha', 'area', 'Area (ha)', 'Area', 'AREA (Ha)'];

                foreach ($areaKeys as $key) {
                    if (isset($row[$key]) && !empty(trim((string)$row[$key]))) {
                        $areaValue = trim((string)$row[$key]);
                        // Convertir formato europeo (coma como decimal) a formato PHP (punto como decimal)
                        $areaValue = str_replace(',', '.', $areaValue);
                        // Convertir a float si es numérico
                        if (is_numeric($areaValue)) {
                            $area = (float)$areaValue;
                            break;
                        }
                    }
                }

                // Si no encontró por nombres exactos, buscar por similitud en headers
                if ($area === null) {
                    foreach ($headers as $header) {
                        $headerNormalized = trim(strtolower($header));
                        if (str_contains($headerNormalized, 'área') || str_contains($headerNormalized, 'area')) {
                            if (isset($row[$header]) && !empty(trim((string)$row[$header]))) {
                                $areaValue = trim((string)$row[$header]);
                                // Convertir a número, manejar formatos decimales
                                if (strpos($areaValue, '.') !== false && strpos($areaValue, ',') === false) {
                                    $areaValue = str_replace('.', ',', $areaValue);
                                }
                                if (is_numeric(str_replace(',', '.', $areaValue))) {
                                    $area = (float)str_replace(',', '.', $areaValue);
                                    break;
                                }
                            }
                        }
                    }
                }

                // Buscar vereda - usando la columna "VEREDA" especificada por el usuario
                $vereda = null;
                if (isset($row['VEREDA']) && !empty(trim((string)$row['VEREDA']))) {
                    $vereda = trim((string)$row['VEREDA']);
                }

                // Si no encontró por "VEREDA", buscar por otras variantes
                if (!$vereda) {
                    $veredaKeys = ['vereda', 'Vereda', 'VEREDA', 'vereda_cz', 'Vereda_cz'];
                    foreach ($veredaKeys as $key) {
                        if (isset($row[$key]) && !empty(trim((string)$row[$key]))) {
                            $vereda = trim((string)$row[$key]);
                            break;
                        }
                    }
                }

                // Si tenemos área y vereda válido, procesar
                if ($area !== null && $vereda) {
                    // Inicializar vereda si no existe
                    if (!isset($estadisticasVereda[$vereda])) {
                        $estadisticasVereda[$vereda] = [
                            'vereda' => $vereda,
                            'area' => 0,
                            'total_registros' => 0
                        ];
                    }

                    // Sumar área y contar registro
                    $estadisticasVereda[$vereda]['area'] += $area;
                    $estadisticasVereda[$vereda]['total_registros']++;
                    $totalGeneral['area_total'] += $area;
                    $totalGeneral['total_registros']++;
                }
            }

            // Convertir a array indexado y ordenar por área descendente
            $detalles = array_values($estadisticasVereda);
            usort($detalles, function($a, $b) {
                return $b['area'] <=> $a['area'];
            });

            // Preparar datos para gráfico de barras
            $chartData = [
                'labels' => array_column($detalles, 'vereda'),
                'datasets' => [
                    [
                        'label' => 'Área (ha)',
                        'data' => array_column($detalles, 'area'),
                        'backgroundColor' => '#4A7C2F', // Verde
                        'borderColor' => '#3d6625',
                        'borderWidth' => 2,
                        'borderRadius' => 4,
                        'borderSkipped' => false,
                    ]
                ]
            ];

            return [
                'chartData' => $chartData,
                'detalles' => $detalles,
                'totales' => $totalGeneral
            ];

        } catch (\Exception $e) {
            Log::error('Error en getEstadisticasProyectoVeredaData: ' . $e->getMessage());
            return [
                'chartData' => ['labels' => [], 'datasets' => []],
                'detalles' => [],
                'totales' => ['area_total' => 0, 'total_registros' => 0]
            ];
        }
    }

    /**
     * Exportar estadísticas de área por proyectos a PDF
     */
    public function areaProyectosPDF(Request $request)
    {
        try {
            $proyectoId = $request->get('proyecto_id');

            if (!$proyectoId) {
                return redirect()->back()->with('error', 'Debe seleccionar un proyecto para exportar');
            }

            // Verificar que el proyecto existe
            $proyectoSeleccionado = \App\Models\ProyectoProductivo::find($proyectoId);
            if (!$proyectoSeleccionado) {
                return redirect()->back()->with('error', 'Proyecto no encontrado');
            }

            // Obtener estadísticas del proyecto
            $proyectoStats = $this->getEstadisticasProyectoAreaData($proyectoId);
            
            // Generar vista HTML optimizada para impresión/PDF
            return view('reportes.pdfs.area-proyectos', compact('proyectoSeleccionado', 'proyectoStats'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Exportar PDF con estadísticas de área por vereda
     */
    public function areaProyectosVeredaPDF(Request $request)
    {
        try {
            $proyectoId = $request->get('proyecto_id');

            if (!$proyectoId) {
                return redirect()->back()->with('error', 'Debe seleccionar un proyecto para exportar');
            }

            // Verificar que el proyecto existe
            $proyectoSeleccionado = \App\Models\ProyectoProductivo::find($proyectoId);
            if (!$proyectoSeleccionado) {
                return redirect()->back()->with('error', 'Proyecto no encontrado');
            }

            // Obtener estadísticas del proyecto por vereda
            $proyectoStatsVereda = $this->getEstadisticasProyectoVeredaData($proyectoId);
            
            // Generar vista HTML optimizada para impresión/PDF
            return view('reportes.pdfs.area-proyectos', compact('proyectoSeleccionado', 'proyectoStatsVereda'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }
}

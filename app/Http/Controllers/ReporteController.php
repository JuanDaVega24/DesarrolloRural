<?php

namespace App\Http\Controllers;

use App\Models\Caracterizacion;
use App\Models\ProyectoProductivo;
use Illuminate\Http\Request;

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
            $headers = $data['headers'] ?? [];

            // Inicializar contadores por corregimiento
            $corregimientos = [
                1 => ['numero' => 1, 'nombre' => 'Corregimiento 1', 'count' => 0],
                2 => ['numero' => 2, 'nombre' => 'Corregimiento 2', 'count' => 0],
                3 => ['numero' => 3, 'nombre' => 'Corregimiento 3', 'count' => 0],
            ];

            $totalPersonas = 0;

            // Contar personas por corregimiento
            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                $totalPersonas++; // Contar TODAS las filas válidas

                // Buscar columna de corregimiento
                $corregimiento = $this->findColumnValue($row, $headers, ['corregimiento', 'corregimiento_cz', 'Corregimiento', 'Corregimiento_CZ']);
                $corregimiento = $this->normalizeCorregimiento($corregimiento);

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

            // Obtener estadísticas adicionales
            $generoStats = $this->getEstadisticasGeneroData();
            $edadStats = $this->getEstadisticasEdadData();
            $areaStats = $this->getEstadisticasAreaData();

            return view('reportes.estadisticas-corregimientos', compact('estadisticas', 'generoStats', 'edadStats', 'areaStats'));

        } catch (\Exception $e) {
            return redirect()->route('reportes.index')->with('error', 'Error al cargar las estadísticas: ' . $e->getMessage());
        }
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
            $headers = $data['headers'] ?? [];

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
                $corregimiento = $this->findColumnValue($row, $headers, ['corregimiento', 'corregimiento_cz', 'Corregimiento', 'Corregimiento_CZ']);
                $corregimiento = $this->normalizeCorregimiento($corregimiento);

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

    public function areaProyectosView(Request $request)
    {
        $proyectos = ProyectoProductivo::whereNotNull('data')
            ->where('data->total_rows', '>', 0)
            ->select('id', 'nombre', 'ano', 'data')
            ->orderBy('ano', 'desc')
            ->orderBy('nombre')
            ->get();

        $proyectoSeleccionado = null;
        $proyectoStats = null;
        $proyectoStatsVereda = null;

        if ($request->has('proyecto_id') && !empty($request->proyecto_id)) {
            $proyectoSeleccionado = ProyectoProductivo::find($request->proyecto_id);
            if ($proyectoSeleccionado && $proyectoSeleccionado->data) {
                $data = is_string($proyectoSeleccionado->data) ? json_decode($proyectoSeleccionado->data, true) : $proyectoSeleccionado->data;
                $rows = $data['rows'] ?? [];
                $headers = $data['headers'] ?? [];

                $areaPorCorregimiento = [
                    1 => ['corregimiento' => 'Corregimiento 1', 'area' => 0, 'total_registros' => 0],
                    2 => ['corregimiento' => 'Corregimiento 2', 'area' => 0, 'total_registros' => 0],
                    3 => ['corregimiento' => 'Corregimiento 3', 'area' => 0, 'total_registros' => 0],
                ];
                $totalGeneral = ['area_total' => 0, 'total_registros' => 0];

                $veredaStats = [];
                $totalesVereda = ['area_total' => 0, 'total_registros' => 0];

                foreach ($rows as $row) {
                    if (!is_array($row)) continue;

                    $areaValue = $this->findColumnValue($row, $headers, ['Área (ha)', 'area (ha)', 'area_ha', 'area', 'Area (ha)', 'Area', 'AREA (Ha)']);
                    $area = null;
                    if ($areaValue) {
                        $areaValue = str_replace(',', '.', $areaValue);
                        if (is_numeric($areaValue)) {
                            $area = (float)$areaValue;
                        }
                    }

                    $corregimiento = $this->findColumnValue($row, $headers, ['corregimiento', 'corregimiento_cz', 'Corregimiento', 'Corregimiento_CZ']);
                    $corregimiento = $this->normalizeCorregimiento($corregimiento);

                    $vereda = $this->findColumnValue($row, $headers, ['vereda', 'vereda_cz', 'Vereda', 'Vereda_CZ']);

                    if ($area !== null) {
                        if ($corregimiento && isset($areaPorCorregimiento[$corregimiento])) {
                            $areaPorCorregimiento[$corregimiento]['area'] += $area;
                            $areaPorCorregimiento[$corregimiento]['total_registros']++;
                            $totalGeneral['area_total'] += $area;
                            $totalGeneral['total_registros']++;
                        }

                        if ($vereda) {
                            $key = (string)$vereda;
                            if (!isset($veredaStats[$key])) {
                                $veredaStats[$key] = ['vereda' => $key, 'area' => 0, 'total_registros' => 0];
                            }
                            $veredaStats[$key]['area'] += $area;
                            $veredaStats[$key]['total_registros']++;
                            $totalesVereda['area_total'] += $area;
                            $totalesVereda['total_registros']++;
                        }
                    }
                }

                $proyectoStats = [
                    'chartData' => [
                        'labels' => array_column($areaPorCorregimiento, 'corregimiento'),
                        'datasets' => [
                            [
                                'label' => 'Área (ha)',
                                'data' => array_column($areaPorCorregimiento, 'area'),
                                'backgroundColor' => '#4A7C2F',
                                'borderColor' => '#3d6625',
                                'borderWidth' => 2,
                                'borderRadius' => 4,
                                'borderSkipped' => false,
                            ],
                        ],
                    ],
                    'detalles' => array_values($areaPorCorregimiento),
                    'totales' => $totalGeneral,
                ];

                $veredasOrdenadas = array_values($veredaStats);
                usort($veredasOrdenadas, function ($a, $b) {
                    return strcasecmp($a['vereda'], $b['vereda']);
                });

                $proyectoStatsVereda = [
                    'chartData' => [
                        'labels' => array_column($veredasOrdenadas, 'vereda'),
                        'datasets' => [
                            [
                                'label' => 'Área (ha)',
                                'data' => array_column($veredasOrdenadas, 'area'),
                                'backgroundColor' => '#0943B5',
                                'borderColor' => '#083a99',
                                'borderWidth' => 2,
                                'borderRadius' => 4,
                                'borderSkipped' => false,
                            ],
                        ],
                    ],
                    'detalles' => $veredasOrdenadas,
                    'totales' => $totalesVereda,
                ];
            }
        }

        return view('reportes.area-proyectos', compact('proyectos', 'proyectoSeleccionado', 'proyectoStats', 'proyectoStatsVereda'));
    }

    /**
     * Exportar PDF del análisis de área de proyectos (corregimientos/veredas)
     */
    public function areaProyectosPDF(Request $request)
    {
        try {
            $proyectoSeleccionado = null;
            $proyectoStats = null;
            $proyectoStatsVereda = null;

            if ($request->has('proyecto_id') && !empty($request->proyecto_id)) {
                $proyectoSeleccionado = ProyectoProductivo::find($request->proyecto_id);
                if ($proyectoSeleccionado && $proyectoSeleccionado->data) {
                    $data = is_string($proyectoSeleccionado->data) ? json_decode($proyectoSeleccionado->data, true) : $proyectoSeleccionado->data;
                    $rows = $data['rows'] ?? [];
                    $headers = $data['headers'] ?? [];

                    $areaPorCorregimiento = [
                        1 => ['corregimiento' => 'Corregimiento 1', 'area' => 0, 'total_registros' => 0],
                        2 => ['corregimiento' => 'Corregimiento 2', 'area' => 0, 'total_registros' => 0],
                        3 => ['corregimiento' => 'Corregimiento 3', 'area' => 0, 'total_registros' => 0],
                    ];
                    $totalGeneral = ['area_total' => 0, 'total_registros' => 0];

                    $veredaStats = [];
                    $totalesVereda = ['area_total' => 0, 'total_registros' => 0];

                    foreach ($rows as $row) {
                        if (!is_array($row)) continue;

                        $corregimiento = null;
                        $area = 0;
                        $vereda = null;

                        foreach ($headers as $header) {
                            if (isset($row[$header])) {
                                $hn = $this->normalizeText($header);
                                $value = $row[$header];

                                if ($corregimiento === null) {
                                    $corrCandidates = ['corregimiento', 'corregimiento cz', 'corr', 'numero de corregimiento'];
                                    foreach ($corrCandidates as $kw) {
                                        if (str_contains($hn, $kw)) {
                                            $corregimiento = $this->normalizeCorregimiento($value);
                                            break;
                                        }
                                    }
                                }

                                if ($area === 0) {
                                    $areaCandidates = ['area', 'ha', 'hectareas', 'hectáreas'];
                                    foreach ($areaCandidates as $kw) {
                                        if (str_contains($hn, $kw)) {
                                            $area = is_numeric($value) ? (float)$value : 0;
                                            break;
                                        }
                                    }
                                }

                                if ($vereda === null) {
                                    $veredaCandidates = ['vereda', 'vereda cz', 'sector', 'zona'];
                                    foreach ($veredaCandidates as $kw) {
                                        if (str_contains($hn, $kw)) {
                                            $vereda = trim((string)$value);
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        if ($corregimiento && isset($areaPorCorregimiento[$corregimiento])) {
                            $areaPorCorregimiento[$corregimiento]['area'] += $area;
                            $areaPorCorregimiento[$corregimiento]['total_registros']++;
                            $totalGeneral['area_total'] += $area;
                            $totalGeneral['total_registros']++;
                        }

                        if ($vereda) {
                            if (!isset($veredaStats[$vereda])) {
                                $veredaStats[$vereda] = ['vereda' => $vereda, 'area' => 0, 'total_registros' => 0];
                            }
                            $veredaStats[$vereda]['area'] += $area;
                            $veredaStats[$vereda]['total_registros']++;
                            $totalesVereda['area_total'] += $area;
                            $totalesVereda['total_registros']++;
                        }
                    }

                    $proyectoStats = [
                        'chartData' => [
                            'labels' => array_column(array_values($areaPorCorregimiento), 'corregimiento'),
                            'datasets' => [
                                [
                                    'label' => 'Área (ha)',
                                    'data' => array_map(function ($item) { return round($item['area'], 2); }, array_values($areaPorCorregimiento)),
                                    'backgroundColor' => ['#4A7C2F', '#0943B5', '#A80521'],
                                    'borderColor' => ['#3d6625', '#083a99', '#8f041d'],
                                    'borderWidth' => 2,
                                    'borderRadius' => 4,
                                    'borderSkipped' => false,
                                ],
                            ],
                        ],
                        'detalles' => array_values($areaPorCorregimiento),
                        'totales' => $totalGeneral,
                    ];

                    $veredasOrdenadas = array_values($veredaStats);
                    usort($veredasOrdenadas, function ($a, $b) {
                        return strcasecmp($a['vereda'], $b['vereda']);
                    });
                    $proyectoStatsVereda = [
                        'chartData' => [
                            'labels' => array_column($veredasOrdenadas, 'vereda'),
                            'datasets' => [
                                [
                                    'label' => 'Área (ha)',
                                    'data' => array_column($veredasOrdenadas, 'area'),
                                    'backgroundColor' => '#0943B5',
                                    'borderColor' => '#083a99',
                                    'borderWidth' => 2,
                                    'borderRadius' => 4,
                                    'borderSkipped' => false,
                                ],
                            ],
                        ],
                        'detalles' => $veredasOrdenadas,
                        'totales' => $totalesVereda,
                    ];
                }
            }

            return view('reportes.pdfs.area-proyectos', compact('proyectoSeleccionado', 'proyectoStats', 'proyectoStatsVereda'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    public function areaProyectosVeredaPDF(Request $request)
    {
        try {
            $proyectoSeleccionado = null;
            $proyectoStatsVereda = null;

            if ($request->has('proyecto_id') && !empty($request->proyecto_id)) {
                $proyectoSeleccionado = ProyectoProductivo::find($request->proyecto_id);
                if ($proyectoSeleccionado && $proyectoSeleccionado->data) {
                    $data = is_string($proyectoSeleccionado->data) ? json_decode($proyectoSeleccionado->data, true) : $proyectoSeleccionado->data;
                    $rows = $data['rows'] ?? [];
                    $headers = $data['headers'] ?? [];

                    $veredaStats = [];
                    $totalesVereda = ['area_total' => 0, 'total_registros' => 0];

                    foreach ($rows as $row) {
                        if (!is_array($row)) continue;

                        $area = 0;
                        $vereda = null;

                        foreach ($headers as $header) {
                            if (isset($row[$header])) {
                                $hn = $this->normalizeText($header);
                                $value = $row[$header];

                                if ($area === 0) {
                                    $areaCandidates = ['area', 'ha', 'hectareas', 'hectáreas'];
                                    foreach ($areaCandidates as $kw) {
                                        if (str_contains($hn, $kw)) {
                                            $area = is_numeric($value) ? (float)$value : 0;
                                            break;
                                        }
                                    }
                                }

                                if ($vereda === null) {
                                    $veredaCandidates = ['vereda', 'vereda cz', 'sector', 'zona'];
                                    foreach ($veredaCandidates as $kw) {
                                        if (str_contains($hn, $kw)) {
                                            $vereda = trim((string)$value);
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        if ($vereda) {
                            if (!isset($veredaStats[$vereda])) {
                                $veredaStats[$vereda] = ['vereda' => $vereda, 'area' => 0, 'total_registros' => 0];
                            }
                            $veredaStats[$vereda]['area'] += $area;
                            $veredaStats[$vereda]['total_registros']++;
                            $totalesVereda['area_total'] += $area;
                            $totalesVereda['total_registros']++;
                        }
                    }

                    $veredasOrdenadas = array_values($veredaStats);
                    usort($veredasOrdenadas, function ($a, $b) {
                        return strcasecmp($a['vereda'], $b['vereda']);
                    });
                    $proyectoStatsVereda = [
                        'chartData' => [
                            'labels' => array_column($veredasOrdenadas, 'vereda'),
                            'datasets' => [
                                [
                                    'label' => 'Área (ha)',
                                    'data' => array_column($veredasOrdenadas, 'area'),
                                    'backgroundColor' => '#0943B5',
                                    'borderColor' => '#083a99',
                                    'borderWidth' => 2,
                                    'borderRadius' => 4,
                                    'borderSkipped' => false,
                                ],
                            ],
                        ],
                        'detalles' => $veredasOrdenadas,
                        'totales' => $totalesVereda,
                    ];
                }
            }

            return view('reportes.pdfs.area-proyectos', compact('proyectoSeleccionado', 'proyectoStatsVereda'));
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
            $caracterizacion = Caracterizacion::find(1);

            if (!$caracterizacion || !$caracterizacion->data) {
                return $this->emptyStats('genero');
            }

            $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
            $rows = $data['rows'] ?? [];
            $headers = $data['headers'] ?? [];

            $estadisticasGenero = [
                1 => ['corregimiento' => 'Corregimiento 1', 'femenino' => 0, 'masculino' => 0, 'total' => 0],
                2 => ['corregimiento' => 'Corregimiento 2', 'femenino' => 0, 'masculino' => 0, 'total' => 0],
                3 => ['corregimiento' => 'Corregimiento 3', 'femenino' => 0, 'masculino' => 0, 'total' => 0]
            ];

            $totalGeneral = ['femenino' => 0, 'masculino' => 0, 'total' => 0];

            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar género
                $generoValue = $this->findColumnValue($row, $headers, ['genero', 'género', 'sexo', 'gender', 'gen']);
                $genero = $this->normalizeGenero($generoValue);

                // Buscar corregimiento
                $corregimiento = $this->findColumnValue($row, $headers, ['corregimiento', 'corregimiento_cz', 'Corregimiento', 'Corregimiento_CZ']);
                $corregimiento = $this->normalizeCorregimiento($corregimiento);

                // Si tenemos género y corregimiento válido, contar
                if ($genero && $corregimiento && isset($estadisticasGenero[$corregimiento])) {
                    $estadisticasGenero[$corregimiento][$genero]++;
                    $estadisticasGenero[$corregimiento]['total']++;
                    $totalGeneral[$genero]++;
                    $totalGeneral['total']++;
                }
            }

            return [
                'chartData' => [
                    'labels' => array_column($estadisticasGenero, 'corregimiento'),
                    'datasets' => [
                        [
                            'label' => 'Femenino',
                            'data' => array_column($estadisticasGenero, 'femenino'),
                            'backgroundColor' => '#FF69B4',
                            'borderColor' => '#FF1493',
                            'borderWidth' => 2,
                            'borderRadius' => 4,
                            'borderSkipped'=> false,
                        ],
                        [
                            'label' => 'Masculino',
                            'data' => array_column($estadisticasGenero, 'masculino'),
                            'backgroundColor' => '#4169E1',
                            'borderColor' => '#000080',
                            'borderWidth' => 2,
                            'borderRadius' => 4,
                            'borderSkipped'=> false,
                        ]
                    ]
                ],
                'detalles' => array_values($estadisticasGenero),
                'totales' => $totalGeneral
            ];

        } catch (\Exception $e) {
            return $this->emptyStats('genero');
        }
    }

    /**
     * Obtener estadísticas de distribución por edad y corregimiento (datos para vista)
     */
    private function getEstadisticasEdadData()
    {
        try {
            $caracterizacion = Caracterizacion::find(1);

            if (!$caracterizacion || !$caracterizacion->data) {
                return $this->emptyStats('edad');
            }

            $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
            $rows = $data['rows'] ?? [];
            $headers = $data['headers'] ?? [];

            $rangosEdad = [
                '0-17' => ['min' => 0, 'max' => 17, 'label' => '0-17 años'],
                '18-30' => ['min' => 18, 'max' => 30, 'label' => '18-30 años'],
                '31-45' => ['min' => 31, 'max' => 45, 'label' => '31-45 años'],
                '46-60' => ['min' => 46, 'max' => 60, 'label' => '46-60 años'],
                '61+' => ['min' => 61, 'max' => 999, 'label' => '61+ años']
            ];

            $estadisticasEdad = [
                1 => array_merge(['corregimiento' => 'Corregimiento 1'], array_fill_keys(array_keys($rangosEdad), 0), ['total' => 0]),
                2 => array_merge(['corregimiento' => 'Corregimiento 2'], array_fill_keys(array_keys($rangosEdad), 0), ['total' => 0]),
                3 => array_merge(['corregimiento' => 'Corregimiento 3'], array_fill_keys(array_keys($rangosEdad), 0), ['total' => 0])
            ];

            $totalGeneral = array_merge(array_fill_keys(array_keys($rangosEdad), 0), ['total' => 0]);

            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar fecha de nacimiento
                $fechaNacimiento = $this->findColumnValue($row, $headers, ['fecha_nacimiento', 'fecha_nac', 'nacimiento', 'birth', 'fecha de nacimiento', 'edad']);
                
                $edad = null;
                if ($fechaNacimiento) {
                    $edad = $this->calcularEdad($fechaNacimiento);
                }

                // Buscar corregimiento
                $corregimiento = $this->findColumnValue($row, $headers, ['corregimiento', 'corregimiento_cz', 'Corregimiento', 'Corregimiento_CZ']);
                $corregimiento = $this->normalizeCorregimiento($corregimiento);

                if ($edad !== null && $corregimiento && isset($estadisticasEdad[$corregimiento])) {
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

            $chartData = [
                'labels' => array_column($estadisticasEdad, 'corregimiento'),
                'datasets' => []
            ];

            $coloresRangos = [
                '0-17' => '#3B82F6', '18-30' => '#10B981', '31-45' => '#F59E0B', '46-60' => '#EF4444', '61+' => '#8B5CF6'
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
            return $this->emptyStats('edad');
        }
    }

    /**
     * Obtener estadísticas de área por corregimiento (datos para vista)
     */
    private function getEstadisticasAreaData()
    {
        try {
            $caracterizacion = Caracterizacion::find(1);

            if (!$caracterizacion || !$caracterizacion->data) {
                return $this->emptyStats('area');
            }

            $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
            $rows = $data['rows'] ?? [];
            $headers = $data['headers'] ?? [];

            $estadisticasArea = [
                1 => ['corregimiento' => 'Corregimiento 1', 'area' => 0, 'total_registros' => 0],
                2 => ['corregimiento' => 'Corregimiento 2', 'area' => 0, 'total_registros' => 0],
                3 => ['corregimiento' => 'Corregimiento 3', 'area' => 0, 'total_registros' => 0]
            ];

            $totalGeneral = ['area_total' => 0, 'total_registros' => 0];

            foreach ($rows as $row) {
                if (!is_array($row)) continue;

                // Buscar área
                $area = null;
                $areaValue = $this->findColumnValue($row, $headers, ['Área (ha)', 'area (ha)', 'area_ha', 'area', 'Area (ha)', 'Area', 'AREA (Ha)']);
                
                if ($areaValue) {
                    $areaValue = str_replace(',', '.', $areaValue);
                    if (is_numeric($areaValue)) {
                        $area = (float)$areaValue;
                    }
                }

                // Buscar corregimiento
                $corregimiento = $this->findColumnValue($row, $headers, ['corregimiento', 'corregimiento_cz', 'Corregimiento', 'Corregimiento_CZ']);
                $corregimiento = $this->normalizeCorregimiento($corregimiento);

                if ($area !== null && $corregimiento && isset($estadisticasArea[$corregimiento])) {
                    $estadisticasArea[$corregimiento]['area'] += $area;
                    $estadisticasArea[$corregimiento]['total_registros']++;
                    $totalGeneral['area_total'] += $area;
                    $totalGeneral['total_registros']++;
                }
            }

            return [
                'chartData' => [
                    'labels' => array_column($estadisticasArea, 'corregimiento'),
                    'datasets' => [
                        [
                            'label' => 'Área (ha)',
                            'data' => array_column($estadisticasArea, 'area'),
                            'backgroundColor' => '#4A7C2F',
                            'borderColor' => '#3d6625',
                            'borderWidth' => 2,
                            'borderRadius' => 4,
                            'borderSkipped' => false,
                        ]
                    ]
                ],
                'detalles' => array_values($estadisticasArea),
                'totales' => $totalGeneral
            ];

        } catch (\Exception $e) {
            return $this->emptyStats('area');
        }
    }

    /**
     * Calcular edad a partir de fecha de nacimiento
     */
    private function calcularEdad($fechaNacimiento)
    {
        try {
            // Si es solo un número (edad directa)
            if (is_numeric($fechaNacimiento) && $fechaNacimiento < 120) {
                return (int)$fechaNacimiento;
            }

            $formatos = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y', 'Y/m/d'];
            $fecha = null;
            
            foreach ($formatos as $formato) {
                $fecha = \DateTime::createFromFormat($formato, $fechaNacimiento);
                if ($fecha !== false) break;
            }

            if (!$fecha) {
                try {
                    $fecha = new \DateTime($fechaNacimiento);
                } catch (\Exception $e) {
                    return null;
                }
            }

            if ($fecha) {
                return (new \DateTime())->diff($fecha)->y;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function estadisticasCorregimientos()
    {
        try {
            $caracterizacion = Caracterizacion::find(1);
            if (!$caracterizacion || !$caracterizacion->data) {
                return response()->json(['error' => 'No hay datos'], 404);
            }
            return response()->json(['message' => 'Use la vista web para ver este reporte']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // --- Helpers ---

    private function findColumnValue($row, $headers, $possibleNames)
    {
        // 1. Buscar coincidencia exacta en las llaves del row
        foreach ($possibleNames as $name) {
            if (isset($row[$name]) && trim((string)$row[$name]) !== '') {
                return trim((string)$row[$name]);
            }
        }

        // 2. Buscar por coincidencia parcial en los headers si no se encontró por llave directa
        // (Esto es costoso, usar solo si es necesario y si los headers están disponibles)
        if (!empty($headers)) {
            foreach ($possibleNames as $name) {
                $nameLower = strtolower($name);
                foreach ($headers as $header) {
                    if (str_contains(strtolower($header), $nameLower)) {
                        if (isset($row[$header]) && trim((string)$row[$header]) !== '') {
                            return trim((string)$row[$header]);
                        }
                    }
                }
            }
        }

        return null;
    }

    private function normalizeCorregimiento($value)
    {
        if (!$value) return null;
        
        $value = strtolower(trim($value));
        
        if (in_array($value, ['1', '1.0', '01', 'corregimiento 1'])) return 1;
        if (in_array($value, ['2', '2.0', '02', 'corregimiento 2'])) return 2;
        if (in_array($value, ['3', '3.0', '03', 'corregimiento 3'])) return 3;

        return null;
    }

    private function normalizeGenero($value)
    {
        if (!$value) return null;
        
        $value = strtolower(trim($value));
        
        if (in_array($value, ['femenino', 'f', 'mujer', 'female', 'feminine', 'femenina', 'mujeres'])) return 'femenino';
        if (in_array($value, ['masculino', 'm', 'hombre', 'male', 'masculine', 'masculina', 'hombres'])) return 'masculino';

        return null;
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

    private function emptyStats($type)
    {
        if ($type === 'genero') {
            return [
                'chartData' => ['labels' => [], 'datasets' => []],
                'detalles' => [],
                'totales' => ['femenino' => 0, 'masculino' => 0, 'total' => 0]
            ];
        }
        if ($type === 'edad') {
            return [
                'chartData' => ['labels' => [], 'datasets' => []],
                'detalles' => [],
                'totales' => ['total' => 0],
                'rangos' => []
            ];
        }
        if ($type === 'area') {
            return [
                'chartData' => ['labels' => [], 'datasets' => []],
                'detalles' => [],
                'totales' => ['area_total' => 0]
            ];
        }
        return [];
    }
}

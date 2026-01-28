<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Estadísticas</title>
    <?php
        // Detectar qué tipo de estadística se está exportando
        $reportType = 'corregimientos';
        $reportTitle = 'Reporte de Estadísticas por Corregimientos';
        
        if (isset($generoStats) && $generoStats && !isset($estadisticas)) {
            $reportType = 'genero';
            $reportTitle = 'Reporte de Estadísticas por Género';
            $estadisticas = ['total' => 0, 'detalles' => [], 'fecha_generacion' => date('d/m/Y H:i:s')];
        } elseif (isset($edadStats) && $edadStats && !isset($estadisticas)) {
            $reportType = 'edad';
            $reportTitle = 'Reporte de Estadísticas por Edad';
            $estadisticas = ['total' => 0, 'detalles' => [], 'fecha_generacion' => date('d/m/Y H:i:s')];
        } elseif (isset($areaStats) && $areaStats && !isset($estadisticas)) {
            $reportType = 'area';
            $reportTitle = 'Reporte de Estadísticas por Área';
            $estadisticas = ['total' => 0, 'detalles' => [], 'fecha_generacion' => date('d/m/Y H:i:s')];
        }
    ?>
    <style>

           :root {
          
              --govcolor-cobalt: #0943B5;
  --govcolor-white: #FFFFFF;
  --govcolor-havelock-lue: #4672C8;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }
        
        html, body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #2c3e50;
            line-height: 1.6;
            background: white;
            width: 100%;
            height: 100%;
        }
        
       
        
        /* === ENCABEZADO INSTITUCIONAL === */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 20px 40px;
            border-bottom: 2px solid #ecf0f1;
            page-break-inside: avoid;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-section img {
            height: 85px;
            width: auto;
            object-fit: contain;
        }
        
        .institutional-info h2 {
            color: #4A7C2F;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.3px;
            
        }
        
        .institutional-info p {
            color: #7f8c8d;
            font-size: 12px;
            margin: 3px 0 0 0;
        }

        .no-print {
            background: linear-gradient(135deg, #4A7C2F 0%, #3d6625 100%);
            padding: 15px 20px;
            margin: 20px 40px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(74, 124, 47, 0.3);
            display: flex;
            gap: 10px;
        }

        .no-print button {
            background: white;
            color: #4A7C2F;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .no-print button:hover {
            background: #4A7C2F;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .print-container {
            background: white;
            padding: 40px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .report-header {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4A7C2F;
            page-break-inside: avoid;
        }
        
        .report-header h1 {
            color: #4A7C2F;
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .report-header .subtitle {
            color: #7f8c8d;
            font-size: 13px;
            margin-bottom: 8px;
        }
        
        .report-meta {
            color: #95a5a6;
            font-size: 11px;
            font-style: italic;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            font-size: 12px;
            gap: 20px;
        }
        
        .info-item {
            flex: 1;
        }
        
        .info-label {
            font-weight: 700;
            color: #4A7C2F;
            display: block;
            margin-bottom: 4px;
        }
        
        .info-label + span {
            color: #2c3e50;
            font-weight: 500;
        }
        
        .section {
            margin: 35px 0;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: linear-gradient(90deg, #4A7C2F 0%, #5a9c3f 100%);
            color: white;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        table th {
            background: linear-gradient(135deg, #E8F5E0 0%, #d4ead1 100%);
            border: 1px solid #4A7C2F;
            padding: 13px;
            text-align: left;
            font-weight: 700;
            color: #4A7C2F;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        table td {
            border: 1px solid #ecf0f1;
            padding: 11px 13px;
            font-size: 11px;
            color: #2c3e50;
        }
        
        table tr:nth-child(even) {
            background: #f8fbf7;
        }
        
        table tr:hover {
            background: #f1f8ee;
        }
        
        .total-row {
            background: linear-gradient(135deg, #E8F5E0 0%, #d4ead1 100%);
            font-weight: 700;
            color: #4A7C2F;
        }
        
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
            font-size: 10px;
            color: #95a5a6;
            page-break-inside: avoid;
        }
        
        .footer p {
            margin: 4px 0;
        }
        
        .footer-institution {
            font-weight: 700;
            color: #4A7C2F;
            margin: 10px 0 5px 0;
        }
        
        .stats-box {
            background: linear-gradient(135deg, #E8F5E0 0%, #f0f5f0 100%);
            border-left: 5px solid #4A7C2F;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(74, 124, 47, 0.1);
        }
        
        .stats-box .number {
            font-size: 32px;
            font-weight: 700;
            color: #4A7C2F;
            display: block;
            margin-bottom: 5px;
        }
        
        .stats-box .label {
            font-size: 12px;
            color: #7f8c8d;
            font-weight: 500;
        }

        .chart-section {
            margin: 30px 0;
            page-break-inside: avoid;
        }

        .chart-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 25px;
            border-radius: 6px;
            border: 1px solid #ecf0f1;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .chart-title {
            font-size: 15px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        canvas {
            max-width: 100% !important;
            height: 350px !important;
            display: block !important;
            margin: 0 auto !important;
        }

        @media print {
            html, body {
                background: white;
                padding: 0;
                margin: 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .gov-header {
                display: block !important;
                page-break-inside: avoid;
            }
            
            .header-top {
                display: block !important;
                page-break-inside: avoid;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                padding: 40px;
                box-shadow: none;
                max-width: 100%;
                margin: 0;
            }

            canvas {
                max-width: 100% !important;
                page-break-inside: avoid;
                display: block !important;
                height: 350px !important;
            }
            
            .chart-section {
                page-break-inside: avoid;
            }
            
            .section {
                page-break-inside: avoid;
            }
            
            table {
                page-break-inside: avoid;
            }
        }

        
.barra-superior-govco {
  background-color: var(--govcolor-cobalt);
  width: 100%;
  height: 3.5rem;
  padding-left: 3.75rem;
  display: flex;
  align-items: center;
}

.barra-superior-govco a {
  content: url('https://cdn.www.gov.co/layout-govco-v5/assets/images/logo.svg');
  height: calc(1.5rem * 1.5);
}

.barra-superior-govco a:focus-visible {
  outline: 0.125rem solid var(--govcolor-white);
  border-radius: 0.313rem;
}

.barra-superior-govco .idioma-btn-barra-superior-govco {
  height: 1.5rem;
  width: 1.5rem;
  border-radius: 0.313rem;
  background-color: var(--govcolor-white);
  cursor: pointer;
  padding: 0;
  border: 0.063rem solid var(--govcolor-white);
  font-size: 0.625rem;
  position: absolute;
  right: 5.375rem;
  top: 1rem;
}

.barra-superior-govco .idioma-btn-barra-superior-govco:hover,
.barra-superior-govco .idioma-btn-barra-superior-govco:focus-visible {
  background-color: var(--govcolor-havelock-lue);
}

.barra-superior-govco .idioma-btn-barra-superior-govco:focus-visible {
  outline: 0.063rem solid var(--govcolor-white);
  outline-offset: max(0.188rem, 0.188rem);
}

.barra-superior-govco .idioma-btn-barra-superior-govco::before {
  font-family: "Nunito_Sans-Regular";
  content: 'EN';
  color: var(--govcolor-cobalt);
  font-size: 12px;
}

.barra-superior-govco .idioma-btn-barra-superior-govco:hover::before,
.barra-superior-govco .idioma-btn-barra-superior-govco:focus-visible::before {
  color: var(--govcolor-white);
}

@media (max-width: 600px) {
  .barra-superior-govco {
    justify-content: center;
    padding: 0;
  }

  .barra-superior-govco .idioma-btn-barra-superior-govco {
    right: 1.25rem;
  }
}
    </style>
</head>
<body>
    {{-- Encabezado GOV.CO --}}
  

    <div class="barra-superior-govco">
  <a href="https://www.gov.co/" target="_blank" rel=noopener
    aria-label="Portal del Estado Colombiano - GOV.CO"><img src="{{ asset('images/logo.svg') }}" alt="logo"></a> 

</div>

    {{-- Encabezado Institucional --}}
    <div class="header-top">
        <div class="logo-section">
            <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo Desarrollo Rural">
        </div>
        <div class="institutional-info">
            <h2>Sistema de Información Rural</h2>
            <p>Reporte de Estadísticas y Caracterización</p>
        </div>
    </div>

    {{-- Botones de acción --}}
    <div class="no-print">
        <button onclick="window.print()"><i class="fas fa-print"></i> Imprimir / Guardar como PDF</button>
    </div>

    <div class="print-container">
        {{-- Encabezado del Reporte --}}
        <div class="report-header">
            <h1><?php echo $reportTitle; ?></h1>
            <p class="subtitle">Base de Datos Global de Caracterizaciones - Desarrollo Rural</p>
            <p class="report-meta">Fecha de Generación: {{ $estadisticas['fecha_generacion'] }}</p>
        </div>
        
        <div class="stats-box">
            <div class="number">
                @if($reportType === 'corregimientos')
                    {{ $estadisticas['total'] ?? 0 }}
                @elseif($reportType === 'genero' && isset($generoStats))
                    {{ $generoStats['totales']['total'] ?? 0 }}
                @elseif($reportType === 'edad' && isset($edadStats))
                    {{ $edadStats['totales']['total'] ?? 0 }}
                @elseif($reportType === 'area' && isset($areaStats))
                    {{ $areaStats['totales']['total_registros'] ?? 0 }}
                @else
                    0
                @endif
            </div>
            <div class="label">
                @if($reportType === 'corregimientos')
                    Total de Personas Registradas
                @elseif($reportType === 'genero')
                    Total de Personas por Género
                @elseif($reportType === 'edad')
                    Total de Personas por Edad
                @elseif($reportType === 'area')
                    Total de Registros por Área
                @else
                    Total de Registros
                @endif
            </div>
        </div>
        
        {{-- SECCIÓN 1: Distribución por Corregimiento --}}
        @if($reportType === 'corregimientos' || $reportType === 'all')
        <div class="section">
            <div class="section-title">Distribución por Corregimiento</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Corregimiento</th>
                        <th style="text-align: center;">Cantidad de Personas</th>
                        <th style="text-align: center;">Porcentaje (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estadisticas['detalles'] as $corregimiento)
                        <tr>
                            <td>{{ $corregimiento['nombre'] }}</td>
                            <td style="text-align: center;">{{ $corregimiento['count'] }}</td>
                            <td style="text-align: center;">
                                @if($estadisticas['total'] > 0)
                                    {{ number_format(($corregimiento['count'] / $estadisticas['total']) * 100, 2) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>TOTAL</td>
                        <td style="text-align: center;">{{ $estadisticas['total'] }}</td>
                        <td style="text-align: center;">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <div class="chart-section">
                <div class="chart-title">📊 Distribución por Corregimiento</div>
                <div class="chart-wrapper">
                    <canvas id="corregimientosChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        @endif

        {{-- SECCIÓN 2: Análisis por Género --}}
        @if(($reportType === 'genero' || $reportType === 'all') && isset($generoStats) && $generoStats && isset($generoStats['detalles']) && count($generoStats['detalles']) > 0)
        <div class="section">
            <div class="section-title">Análisis por Género</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Corregimiento</th>
                        <th style="text-align: center;">Femenino</th>
                        <th style="text-align: center;">Masculino</th>
                        <th style="text-align: center;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($generoStats['detalles'] as $genero)
                        <tr>
                            <td>{{ $genero['corregimiento'] ?? 'N/A' }}</td>
                            <td style="text-align: center;">{{ $genero['femenino'] ?? 0 }}</td>
                            <td style="text-align: center;">{{ $genero['masculino'] ?? 0 }}</td>
                            <td style="text-align: center;">{{ $genero['total'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>TOTAL</td>
                        <td style="text-align: center;">{{ $generoStats['totales']['femenino'] ?? 0 }}</td>
                        <td style="text-align: center;">{{ $generoStats['totales']['masculino'] ?? 0 }}</td>
                        <td style="text-align: center;">{{ $generoStats['totales']['total'] ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="chart-section">
                <div class="chart-title">👥 Distribución por Género</div>
                <div class="chart-wrapper">
                    <canvas id="generoChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        @endif

        {{-- SECCIÓN 3: Análisis por Edad --}}
        @if(($reportType === 'edad' || $reportType === 'all') && isset($edadStats) && $edadStats && isset($edadStats['detalles']) && count($edadStats['detalles']) > 0)
        <div class="section">
            <div class="section-title">Análisis por Rango de Edad</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Corregimiento</th>
                        @if(isset($edadStats['rangos']))
                            @foreach($edadStats['rangos'] as $rango => $config)
                                <th style="text-align: center;">{{ $config['label'] }}</th>
                            @endforeach
                        @else
                            <th style="text-align: center;">0-17 años</th>
                            <th style="text-align: center;">18-30 años</th>
                            <th style="text-align: center;">31-45 años</th>
                            <th style="text-align: center;">46-60 años</th>
                            <th style="text-align: center;">61+ años</th>
                        @endif
                        <th style="text-align: center;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($edadStats['detalles'] as $edad)
                        <tr>
                            <td>{{ $edad['corregimiento'] ?? 'N/A' }}</td>
                            @if(isset($edadStats['rangos']))
                                @foreach($edadStats['rangos'] as $rango => $config)
                                    <td style="text-align: center;">{{ $edad[$rango] ?? 0 }}</td>
                                @endforeach
                            @else
                                <td style="text-align: center;">{{ $edad['0-17'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $edad['18-30'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $edad['31-45'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $edad['46-60'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $edad['61+'] ?? 0 }}</td>
                            @endif
                            <td style="text-align: center;">{{ $edad['total'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>TOTAL</td>
                        @if(isset($edadStats['rangos']))
                            @foreach($edadStats['rangos'] as $rango => $config)
                                <td style="text-align: center;">{{ $edadStats['totales'][$rango] ?? 0 }}</td>
                            @endforeach
                        @else
                            <td style="text-align: center;">{{ $edadStats['totales']['0-17'] ?? 0 }}</td>
                            <td style="text-align: center;">{{ $edadStats['totales']['18-30'] ?? 0 }}</td>
                            <td style="text-align: center;">{{ $edadStats['totales']['31-45'] ?? 0 }}</td>
                            <td style="text-align: center;">{{ $edadStats['totales']['46-60'] ?? 0 }}</td>
                            <td style="text-align: center;">{{ $edadStats['totales']['61+'] ?? 0 }}</td>
                        @endif
                        <td style="text-align: center;">{{ $edadStats['totales']['total'] ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="chart-section">
                <div class="chart-title">📅 Distribución por Rango de Edad</div>
                <div class="chart-wrapper">
                    <canvas id="edadChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        @endif

        {{-- SECCIÓN 4: Análisis por Área --}}
        @if(($reportType === 'area' || $reportType === 'all') && isset($areaStats) && $areaStats && isset($areaStats['detalles']) && count($areaStats['detalles']) > 0)
        <div class="section">
            <div class="section-title">Análisis por Área de Interés</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Corregimiento</th>
                        <th style="text-align: center;">Área Total (ha)</th>
                        <th style="text-align: center;">Cantidad de Registros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($areaStats['detalles'] as $area)
                        <tr>
                            <td>{{ $area['corregimiento'] ?? 'N/A' }}</td>
                            <td style="text-align: center;">{{ number_format($area['area'] ?? 0, 2) }}</td>
                            <td style="text-align: center;">{{ $area['total_registros'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>TOTAL</td>
                        <td style="text-align: center;">{{ number_format($areaStats['totales']['area_total'] ?? 0, 2) }}</td>
                        <td style="text-align: center;">{{ $areaStats['totales']['total_registros'] ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="chart-section">
                <div class="chart-title">🎯 Distribución por Área de Interés</div>
                <div class="chart-wrapper">
                    <canvas id="areaChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        @endif
        
        <div class="footer">
            <p class="footer-institution">Sistema de Información - Desarrollo Rural</p>
            <p>Este reporte fue generado automáticamente por el Sistema de Información Rural</p>
            <p>Fecha: {{ now()->format('d/m/Y H:i:s') }}</p>
            <p style="margin-top: 8px; font-size: 9px;">Documento confidencial - Uso interno</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        // Datos del gráfico de corregimientos
        const corregimientosData = @json($estadisticas['chartData'] ?? null);
        
        // Datos de género - usar chartData del controlador
        @if(isset($generoStats) && $generoStats && isset($generoStats['chartData']))
            const generoData = @json($generoStats['chartData']);
        @else
            const generoData = null;
        @endif
        
        // Datos de edad - usar chartData del controlador
        @if(isset($edadStats) && $edadStats && isset($edadStats['chartData']))
            const edadData = @json($edadStats['chartData']);
        @else
            const edadData = null;
        @endif
        
        // Datos de área - usar chartData del controlador
        @if(isset($areaStats) && $areaStats && isset($areaStats['chartData']))
            const areaData = @json($areaStats['chartData']);
        @else
            const areaData = null;
        @endif
        
        // Paleta de colores corporativa
        const coloresPie = ['#4A7C2F', '#3366CC', '#FF6B35', '#F5A623', '#7B68EE', '#00CED1', '#FF1493', '#FFD700'];
        
        // Función para crear gráfica tipo pie
        function crearGraficaPie(canvasId, labels, data, titulo) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: coloresPie.slice(0, labels.length),
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: { size: 11, weight: '600' },
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const dataset = data.datasets[0];
                                            const value = dataset.data[i];
                                            const total = dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                            return {
                                                text: `${label}: ${value} (${percentage}%)`,
                                                fillStyle: dataset.backgroundColor[i],
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            cornerRadius: 6,
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${value} personas (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Inicializar gráficos cuando el documento esté listo
        function initializeCharts() {
            // Gráfica de corregimientos
            if (corregimientosData && corregimientosData.labels) {
                crearGraficaPie('corregimientosChart', corregimientosData.labels, corregimientosData.data, 'Corregimientos');
            }
            
            // Gráfica de género
            if (generoData && generoData.labels && generoData.datasets) {
                const ctx = document.getElementById('generoChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: generoData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { font: { size: 11 }, padding: 15 }
                                }
                            },
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                }
            }
            
            // Gráfica de edad
            if (edadData && edadData.labels && edadData.datasets) {
                const ctx = document.getElementById('edadChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: edadData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { font: { size: 11 }, padding: 15 }
                                }
                            },
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                }
            }
            
            // Gráfica de área
            if (areaData && areaData.labels && areaData.datasets) {
                const ctx = document.getElementById('areaChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: areaData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { font: { size: 11 }, padding: 15 }
                                }
                            },
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                }
            }
        }
        
        // Inicializar cuando el documento esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeCharts);
        } else {
            // Si el documento ya está cargado, inicializar inmediatamente
            setTimeout(initializeCharts, 100);
        }
    </script>
</body>
</html>

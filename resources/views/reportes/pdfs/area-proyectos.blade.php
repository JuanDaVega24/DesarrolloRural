<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Área - {{ $proyectoSeleccionado->nombre ?? 'Proyecto' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        .section {
            margin: 35px 0;
            page-break-inside: auto;
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
            
            .barra-superior-govco, .no-print {
                display: none !important;
            }
            
            .header-top {
                display: flex !important;
                page-break-inside: avoid;
            }

            .print-container {
                padding: 0;
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

            .section, table {
                page-break-inside: auto;
            }

            tr {
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
            <p>Reporte de Áreas y Proyectos Productivos</p>
        </div>
    </div>

    {{-- Botones de acción --}}
    <div class="no-print">
        <button onclick="window.print()"><i class="fas fa-print"></i> Imprimir / Guardar como PDF</button>
    </div>

    <div class="print-container">
        {{-- Encabezado del Reporte --}}
        <div class="report-header">
            <h1>Reporte de Áreas por Proyecto</h1>
            <p class="subtitle">Proyecto: {{ $proyectoSeleccionado->nombre ?? 'N/A' }} ({{ $proyectoSeleccionado->ano ?? 'N/A' }})</p>
            <p class="report-meta">Fecha de Generación: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
        
        <div class="stats-box">
            <div class="number">
                @php
                    $totalArea = 0;
                    if(isset($proyectoStats)) $totalArea = $proyectoStats['totales']['area_total'] ?? 0;
                    elseif(isset($proyectoStatsVereda)) $totalArea = $proyectoStatsVereda['totales']['area_total'] ?? 0;
                @endphp
                {{ number_format($totalArea, 2) }} ha
            </div>
            <div class="label">Área Total del Proyecto</div>
        </div>
        
        {{-- SECCIÓN 1: Distribución por Corregimiento --}}
        @if(isset($proyectoStats) && $proyectoStats)
        <div class="section">
            <div class="section-title">Distribución de Área por Corregimiento</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Corregimiento</th>
                        <th style="text-align: center;">Área (ha)</th>
                        <th style="text-align: center;">Registros</th>
                        <th style="text-align: center;">% Área</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proyectoStats['detalles'] as $detalle)
                        <tr>
                            <td>{{ $detalle['corregimiento'] }}</td>
                            <td style="text-align: center;">{{ number_format($detalle['area'], 2) }}</td>
                            <td style="text-align: center;">{{ $detalle['total_registros'] }}</td>
                            <td style="text-align: center;">
                                @if($proyectoStats['totales']['area_total'] > 0)
                                    {{ number_format(($detalle['area'] / $proyectoStats['totales']['area_total']) * 100, 2) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>TOTAL</td>
                        <td style="text-align: center;">{{ number_format($proyectoStats['totales']['area_total'], 2) }}</td>
                        <td style="text-align: center;">{{ $proyectoStats['totales']['total_registros'] }}</td>
                        <td style="text-align: center;">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <div class="chart-section">
                <div class="chart-title">📊 Distribución de Área por Corregimiento</div>
                <div class="chart-wrapper">
                    <canvas id="corregimientosChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        @endif

        {{-- SECCIÓN 2: Distribución por Vereda --}}
        @if(isset($proyectoStatsVereda) && $proyectoStatsVereda)
        <div class="section">
            <div class="section-title">Distribución de Área por Vereda</div>
            <table>
                <thead>
                    <tr>
                        <th>Vereda</th>
                        <th style="text-align: center;">Área (ha)</th>
                        <th style="text-align: center;">Registros</th>
                        <th style="text-align: center;">% Área</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proyectoStatsVereda['detalles'] as $detalle)
                        <tr>
                            <td>{{ $detalle['vereda'] }}</td>
                            <td style="text-align: center;">{{ number_format($detalle['area'], 2) }}</td>
                            <td style="text-align: center;">{{ $detalle['total_registros'] }}</td>
                            <td style="text-align: center;">
                                @if($proyectoStatsVereda['totales']['area_total'] > 0)
                                    {{ number_format(($detalle['area'] / $proyectoStatsVereda['totales']['area_total']) * 100, 2) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>TOTAL</td>
                        <td style="text-align: center;">{{ number_format($proyectoStatsVereda['totales']['area_total'], 2) }}</td>
                        <td style="text-align: center;">{{ $proyectoStatsVereda['totales']['total_registros'] }}</td>
                        <td style="text-align: center;">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="chart-section">
                <div class="chart-title">📍 Distribución de Área por Vereda</div>
                <div class="chart-wrapper">
                    <canvas id="veredasChart" width="400" height="300"></canvas>
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
        @if(isset($proyectoStats) && $proyectoStats && isset($proyectoStats['chartData']))
            const corregimientosData = @json($proyectoStats['chartData']);
        @else
            const corregimientosData = null;
        @endif
        
        // Datos del gráfico de veredas
        @if(isset($proyectoStatsVereda) && $proyectoStatsVereda && isset($proyectoStatsVereda['chartData']))
            const veredasData = @json($proyectoStatsVereda['chartData']);
        @else
            const veredasData = null;
        @endif
        
        // Función para crear gráfica de barras
        function crearGraficaBarras(canvasId, chartData, titulo) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: chartData,
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
                        y: { beginAtZero: true, title: { display: true, text: 'Área (ha)' } }
                    }
                }
            });
        }
        
        // Inicializar gráficos cuando el documento esté listo
        function initializeCharts() {
            // Gráfica de corregimientos
            if (corregimientosData) {
                crearGraficaBarras('corregimientosChart', corregimientosData, 'Corregimientos');
            }
            
            // Gráfica de veredas
            if (veredasData) {
                crearGraficaBarras('veredasChart', veredasData, 'Veredas');
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
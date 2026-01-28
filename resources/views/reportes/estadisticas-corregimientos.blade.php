<x-app-layout>
  @vite(['resources/css/pages/reportes/estadisticas-corregimientos.css'])

  
    <div class="estadisticas-container">
        <div class="content-wrapper">

            {{-- === HEADER PROFESIONAL === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Centro de Estadísticas</h1>
                    <p>Análisis detallado de datos de caracterización rural</p>
                    <div class="header-meta">
                        <span>Última actualización: {{ now()->format('d/m/Y H:i') }}</span>
                        <span>Total de registros: {{ number_format($estadisticas['total']) }}</span>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('reportes.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Volver a Reportes
                    </a>
                </div>
            </div>

            {{-- === SISTEMA DE PESTAÑAS === --}}
            <div class="stats-tabs">
                <div class="tabs-header">
                    <button class="tab-button active" onclick="showTab('corregimientos')">
                       
                        Distribución por Corregimientos
                    </button>
                    <button class="tab-button" onclick="showTab('genero')">
                       
                        Análisis por Género
                    </button>
                    <button class="tab-button" onclick="showTab('edad')">
                       
                        Distribución por Edad
                    </button>
                    <button class="tab-button" onclick="showTab('proyectos')">
                       
                        Área por corregimientos y veredas 
                    </button>
                </div>

             {{-- PESTAÑA: CORREGIMIENTOS --}}
                <div id="corregimientos-tab" class="tab-content active">
                  

                    {{-- CHART SECTION CON LAYOUT MEJORADO --}}
                    <div class="chart-section">
                        <div class="chart-header">
                            <h2 class="chart-title">Distribución Geográfica por Corregimiento</h2>
                            <p class="chart-subtitle">
                                Visualización comparativa de la distribución poblacional en los tres corregimientos
                            </p>
                        </div>
                        <div class="chart-container" style="display: flex; gap: 2rem; align-items: center;">
                            <div class="chart-wrapper" style="flex: 1;">
                                <canvas id="corregimientosChart"></canvas>
                            </div>
                            <div class="chart-sidebar" style="width: 280px; background: var(--surface); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-lg); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(20px);">
                                <h4 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem; text-align: center; padding-bottom: 1rem; border-bottom: 2px solid var(--primary-light);">
                                    📊 Resumen Estadístico
                                </h4>
                                @if(isset($estadisticas['chartData']['labels']) && isset($estadisticas['chartData']['data']))
                                @foreach($estadisticas['chartData']['labels'] as $index => $nombre)
                                @php
                                    $total = $estadisticas['chartData']['data'][$index] ?? 0;
                                    $porcentaje = $estadisticas['total'] > 0 ? round(($total / $estadisticas['total']) * 100, 1) : 0;
                                @endphp
                                <div style="margin-bottom: 1.25rem; padding: 1.25rem; background: var(--surface-muted); border-radius: var(--radius-md); border-left: 4px solid {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}; box-shadow: var(--shadow-sm); transition: all 0.3s ease; cursor: pointer;" 
                                     onmouseover="this.style.transform='translateX(5px)'; this.style.boxShadow='var(--shadow-md)'" 
                                     onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='var(--shadow-sm)'">
                                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                                        <div style="width: 12px; height: 12px; border-radius: 50%; background: {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}; box-shadow: 0 0 8px {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}80;"></div>
                                        <span style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">{{ $nombre }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Personas:</span>
                                        <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">{{ number_format($total) }}</span>
                                    </div>
                                    <div style="width: 100%; background: rgba(0,0,0,0.1); height: 8px; border-radius: 4px; overflow: hidden; margin-top: 0.5rem;">
                                        <div style="width: {{ $porcentaje }}%; background: linear-gradient(90deg, {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}, {{ $index === 0 ? '#3d6625' : ($index === 1 ? '#0943B5' : '#A80521') }}); height: 100%; border-radius: 4px; transition: width 1s ease;"></div>
                                    </div>
                                    <div style="text-align: right; margin-top: 0.5rem;">
                                        <span style="font-size: 0.875rem; font-weight: 700; color: {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }};">{{ $porcentaje }}%</span>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                <div style="margin-top: 1.5rem; padding: 1rem; background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%); border-radius: var(--radius-md); text-align: center; border: 1px solid rgba(30, 58, 138, 0.2);">
                                    <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 600; margin-bottom: 0.25rem;">Total General</div>
                                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">{{ number_format($estadisticas['total'] ?? 0) }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">caracterizaciones registradas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PESTAÑA: GÉNERO --}}
                <div id="genero-tab" class="tab-content">
                    {{-- TOTAL OVERVIEW --}}
                    <div class="stats-overview">
                      

                        {{-- CARDS DE GÉNERO GENERAL --}}
                        <div class="stat-card" style="--index: 1;">
                            <div class="stat-icon" style="background: var(--gradient-accent);">
                                <i class="fas fa-venus"></i>
                            </div>
                            <div class="stat-number">{{ number_format($generoStats['totales']['femenino'] ?? 0) }}</div>

                            <div class="stat-title">Femenino</div>
                            <div class="stat-percentage">
                                {{ $generoStats['totales']['total'] > 0 ? round(($generoStats['totales']['femenino'] / $generoStats['totales']['total']) * 100, 1) : 0 }}% del total
                            </div>
                        </div>

                        <div class="stat-card" style="--index: 2;">
                            <div class="stat-icon" style="background: var(--gradient-primary);">
                                <i class="fas fa-mars"></i>
                            </div>
                            <div class="stat-number">{{ number_format($generoStats['totales']['masculino'] ?? 0) }}</div>
                            <div class="stat-title">Masculino</div>
                            <div class="stat-percentage">
                                {{ $generoStats['totales']['total'] > 0 ? round(($generoStats['totales']['masculino'] / $generoStats['totales']['total']) * 100, 1) : 0 }}% del total
                            </div>
                        </div>
                    </div>

                    {{-- CHART SECTION --}}
                    <div class="chart-section">
                        <div class="chart-header">
                            <h2 class="chart-title">Distribución por Género y Corregimiento</h2>
                            <p class="chart-subtitle">
                                Comparación visual de mujeres y hombres por corregimiento
                            </p>
                        </div>
                        <div class="chart-container" style="display: flex; gap: 2rem; align-items: center;">
                            <div class="chart-wrapper" style="flex: 1;">
                                <canvas id="generoChart"></canvas>
                            </div>
                            <div class="chart-sidebar" style="width: 250px; background: var(--surface); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-lg); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(20px);">
                                <h4 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem; text-align: center;">
                                    Detalle por Corregimiento
                                </h4>
                                @if(isset($generoStats['detalles']))
                                @foreach($generoStats['detalles'] as $detalle)
                                <div style="margin-bottom: 1.5rem; padding: 1rem; background: var(--surface-muted); border-radius: var(--radius-md); border: 1px solid var(--border);">
                                    <div style="font-size: 1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.75rem;">
                                        {{ $detalle['corregimiento'] }}
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-size: 0.875rem; color: #4169E1; font-weight: 600;">Hombres:</span>
                                            <span style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">{{ number_format($detalle['masculino'] ?? 0) }}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-size: 0.875rem; color: #FF69B4; font-weight: 600;">Mujeres:</span>
                                            <span style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">{{ number_format($detalle['femenino'] ?? 0) }}</span>
                                        </div>
                                    </div>
                                    <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--border);">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 500;">Total:</span>
                                            <span style="font-size: 0.875rem; font-weight: 600; color: var(--primary);">{{ number_format($detalle['total'] ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>


                </div>

                {{-- PESTAÑA: EDAD --}}
                <div id="edad-tab" class="tab-content">
                    {{-- TOTAL OVERVIEW --}}
                    <div class="stats-overview">
                       

                        {{-- CARDS DE RANGOS DE EDAD --}}
                        @if(isset($edadStats['rangos']))
                        @foreach($edadStats['rangos'] as $rango => $config)
                        <div class="stat-card" style="--index: {{ $loop->index + 1 }};">
                            <div class="stat-icon" style="background: linear-gradient(135deg,
                                {{ $rango == '0-17' ? '#3B82F6' : ($rango == '18-30' ? '#10B981' : ($rango == '31-45' ? '#F59E0B' : ($rango == '46-60' ? '#EF4444' : '#8B5CF6'))) }},
                                {{ $rango == '0-17' ? '#1D4ED8' : ($rango == '18-30' ? '#059669' : ($rango == '31-45' ? '#D97706' : ($rango == '46-60' ? '#DC2626' : '#7C3AED'))) }});">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-number">{{ number_format($edadStats['totales'][$rango] ?? 0) }}</div>
                            <div class="stat-title">{{ $config['label'] }}</div>
                            <div class="stat-percentage">
                                {{ $edadStats['totales']['total'] > 0 ? round((($edadStats['totales'][$rango] ?? 0) / $edadStats['totales']['total']) * 100, 1) : 0 }}% del total
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>


                    {{-- DETALLES POR CORREGIMIENTO --}}
                    <div class="stats-overview" style="margin-top: 2rem;">
                        @if(isset($edadStats['detalles']))
                        @foreach($edadStats['detalles'] as $detalle)
                        <div class="stat-card">
                            <div class="stat-icon corregimiento-{{ $loop->index + 1 }}">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div style="width: 100%; text-align: center;">
                                <div style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1rem;">
                                    {{ $detalle['corregimiento'] }}
                                </div>
                                <div style="display: flex; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap;">
                                    @if(isset($edadStats['rangos']))
                                    @foreach($edadStats['rangos'] as $rango => $config)
                                    <div style="flex: 1; min-width: 120px; text-align: center;">
                                        <div style="font-size: 0.875rem; color:
                                            {{ $rango == '0-17' ? '#3B82F6' : ($rango == '18-30' ? '#3B82F6' : ($rango == '31-45' ? '#3B82F6' : ($rango == '46-60' ? '#3B82F6' : '#3B82F6'))) }};
                                            font-weight: 600; margin-bottom: 0.25rem;">{{ $config['label'] }}</div>
                                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">{{ number_format($detalle[$rango] ?? 0) }}</div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <div style="font-size: 0.875rem; color: var(--text-muted); margin-top: 1rem;">
                                    Total por corregimiento: {{ number_format($detalle['total'] ?? 0) }} personas
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>

                {{-- PESTAÑA: PROYECTOS --}}
                <div id="proyectos-tab" class="tab-content">
                    {{-- TOTAL OVERVIEW --}}
                    <div class="stats-overview">
                        {{-- TARJETA TOTAL DE ÁREA --}}
                        <div class="stat-card" style="--index: 1;">
                            <div class="stat-icon" style="background: var(--gradient-primary);">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="stat-number">{{ number_format($areaStats['totales']['area_total'] ?? 0, 2) }}</div>
                            <div class="stat-title">Área Total (ha)</div>
                            <div class="stat-percentage">
                                {{ $areaStats['totales']['total_registros'] ?? 0 }} registros
                            </div>
                        </div>
                    </div>

                    {{-- CARDS POR CORREGIMIENTO --}}
                    <div class="stats-overview">
                        @if(isset($areaStats['detalles']))
                        @foreach($areaStats['detalles'] as $detalle)
                        <div class="stat-card">
                            <div class="stat-icon corregimiento-{{ $loop->index + 1 }}">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div style="width: 100%; text-align: center;">
                                <div style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1rem;">
                                    {{ $detalle['corregimiento'] }}
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Área (ha):</span>
                                        <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">{{ number_format($detalle['area'] ?? 0, 2) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Registros:</span>
                                        <span style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">{{ number_format($detalle['total_registros'] ?? 0) }}</span>
                                    </div>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--text-muted); margin-top: 1rem;">
                                    {{ $areaStats['totales']['area_total'] > 0 ? round((($detalle['area'] ?? 0) / $areaStats['totales']['area_total']) * 100, 1) : 0 }}% del área total
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>

                    {{-- CHART SECTION --}}
                    <div class="chart-section">
                        <div class="chart-header">
                            <h2 class="chart-title">Distribución de Área por Corregimiento</h2>
                            <p class="chart-subtitle">
                                Comparación visual de la superficie agrícola por corregimiento
                            </p>
                        </div>
                        <div class="chart-container" style="display: flex; gap: 2rem; align-items: center;">
                            <div class="chart-wrapper" style="flex: 1;">
                                <canvas id="areaChart"></canvas>
                            </div>
                            <div class="chart-sidebar" style="width: 280px; background: var(--surface); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-lg); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(20px);">
                                <h4 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem; text-align: center; padding-bottom: 1rem; border-bottom: 2px solid var(--primary-light);">
                                    📊 Resumen por Corregimiento
                                </h4>
                                @if(isset($areaStats['detalles']))
                                @foreach($areaStats['detalles'] as $index => $detalle)
                                @php
                                    $totalArea = $areaStats['totales']['area_total'] ?? 0;
                                    $areaCorregimiento = $detalle['area'] ?? 0;
                                    $porcentaje = $totalArea > 0 ? round(($areaCorregimiento / $totalArea) * 100, 1) : 0;
                                @endphp
                                <div style="margin-bottom: 1.25rem; padding: 1.25rem; background: var(--surface-muted); border-radius: var(--radius-md); border-left: 4px solid {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}; box-shadow: var(--shadow-sm); transition: all 0.3s ease; cursor: pointer;"
                                     onmouseover="this.style.transform='translateX(5px)'; this.style.boxShadow='var(--shadow-md)'"
                                     onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='var(--shadow-sm)'">
                                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                                        <div style="width: 12px; height: 12px; border-radius: 50%; background: {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}; box-shadow: 0 0 8px {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}80;"></div>
                                        <span style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">{{ $detalle['corregimiento'] }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Área:</span>
                                        <span style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary);">{{ number_format($areaCorregimiento, 2) }} ha</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Registros:</span>
                                        <span style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">{{ number_format($detalle['total_registros'] ?? 0) }}</span>
                                    </div>
                                    <div style="width: 100%; background: rgba(0,0,0,0.1); height: 8px; border-radius: 4px; overflow: hidden; margin-top: 0.5rem;">
                                        <div style="width: {{ $porcentaje }}%; background: linear-gradient(90deg, {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}, {{ $index === 0 ? '#3d6625' : ($index === 1 ? '#0943B5' : '#A80521') }}); height: 100%; border-radius: 4px; transition: width 1s ease;"></div>
                                    </div>
                                    <div style="text-align: right; margin-top: 0.5rem;">
                                        <span style="font-size: 0.875rem; font-weight: 700; color: {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }};">{{ $porcentaje }}%</span>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                <div style="margin-top: 1.5rem; padding: 1rem; background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%); border-radius: var(--radius-md); text-align: center; border: 1px solid rgba(30, 58, 138, 0.2);">
                                    <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 600; margin-bottom: 0.25rem;">Área Total General</div>
                                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">{{ number_format($areaStats['totales']['area_total'] ?? 0, 2) }} ha</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">{{ number_format($areaStats['totales']['total_registros'] ?? 0) }} registros totales</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === ACTION BUTTONS === --}}
            <div class="action-buttons">
                <a id="export-pdf-btn" href="{{ route('reportes.estadisticas-corregimientos.pdf') }}" class="btn-export" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                    Exportar Reporte PDF
                </a>
            </div>

        </div>
    </div>

    {{-- === CHART.JS === --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- === JAVASCRIPT === --}}
    <script>
        let corregimientosChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar pestañas
            initializeTabs();

            // Cargar estadísticas iniciales (corregimientos)
            loadCorregimientosChart();
        });

        // Función para inicializar el sistema de pestañas
        function initializeTabs() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.getAttribute('onclick').match(/'([^']+)'/)[1];

                    // Remover clase active de todos los botones y contenidos
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Agregar clase active al botón y contenido seleccionado
                    this.classList.add('active');
                    document.getElementById(tabName + '-tab').classList.add('active');

                    // Cargar contenido específico de la pestaña
                    loadTabContent(tabName);
                });
            });
        }

        // Función para mostrar una pestaña específica
        function showTab(tabName) {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            // Remover clase active de todos
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Agregar clase active al seleccionado
            document.querySelector(`[onclick*="showTab('${tabName}')"]`).classList.add('active');
            document.getElementById(tabName + '-tab').classList.add('active');

            // Cargar contenido
            loadTabContent(tabName);
        }

        // Función para cargar contenido específico de cada pestaña
        function loadTabContent(tabName) {
            // Actualizar botón de exportación PDF según la pestaña activa
            const exportBtn = document.getElementById('export-pdf-btn');
            
            switch(tabName) {
                case 'corregimientos':
                    exportBtn.href = "{{ route('reportes.estadisticas-corregimientos.pdf') }}";
                    loadCorregimientosChart();
                    break;
                case 'genero':
                    exportBtn.href = "{{ route('reportes.estadisticas-genero.pdf') }}";
                    loadGeneroChart();
                    break;
                case 'edad':
                    exportBtn.href = "{{ route('reportes.estadisticas-edad.pdf') }}";
                    loadEdadChart();
                    break;
                case 'proyectos':
                    exportBtn.href = "{{ route('reportes.estadisticas-area.pdf') }}";
                    loadAreaChart();
                    break;
            }
        }

        // Función para cargar el gráfico de barras de género
        function loadGeneroChart() {
            // Destruir gráfico anterior si existe
            if (corregimientosChart) {
                corregimientosChart.destroy();
            }

            // Datos del gráfico de género
            const generoData = @json($generoStats['chartData']);

            // Crear gráfico de barras
            const ctx = document.getElementById('generoChart').getContext('2d');

            corregimientosChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: generoData.labels,
                    datasets: generoData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value.toLocaleString()} personas`;
                                },
                                afterLabel: function(context) {
                                    const corregimiento = context.label;
                                    const value = context.parsed.y;
                                    const totalCorregimiento = context.dataset.data[context.dataIndex] +
                                        (context.chart.data.datasets.find(d => d.label !== context.dataset.label)?.data[context.dataIndex] || 0);
                                    const percentage = totalCorregimiento > 0 ? Math.round((value / totalCorregimiento) * 100) : 0;
                                    return `Representa el ${percentage}% del corregimiento`;
                                },
                                title: function(context) {
                                    return `${context[0].dataset.label} - ${context[0].label}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                },
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeInOutQuart',
                        delay: function(context) {
                            return context.dataIndex * 200;
                        }
                    },
                    onHover: (event, activeElements) => {
                        event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
                    },
                    onClick: (event, activeElements) => {
                        if (activeElements.length > 0) {
                            const index = activeElements[0].index;
                            const datasetIndex = activeElements[0].datasetIndex;
                            const label = generoData.labels[index];
                            const dataset = generoData.datasets[datasetIndex];
                            const value = dataset.data[index];

                            console.log(`Clic en ${dataset.label} - ${label}: ${value} personas`);
                        }
                    }
                }
            });
        }

        // Función para cargar el gráfico de corregimientos
        function loadCorregimientosChart() {
            // Destruir gráfico anterior si existe
            if (corregimientosChart) {
                corregimientosChart.destroy();
            }

            // Datos del gráfico
            const chartData = @json($estadisticas['chartData']);

            // Crear gráfico pastel
            const ctx = document.getElementById('corregimientosChart').getContext('2d');

            corregimientosChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.data,
                        backgroundColor: [
                            '#4A7C2F', // Verde para corregimiento 1
                            '#0943B5', // Azul para corregimiento 2
                            '#A80521'  // Naranja para corregimiento 3
                        ],
                        borderColor: [
                            '#3d6625',
                            '#0943B5',
                            '#A80521'
                        ],
                        borderWidth: 3,
                        hoverBorderWidth: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 25,
                                usePointStyle: true,
                                font: {
                                    size: 14,
                                    weight: '600'
                                },
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
                                                strokeStyle: dataset.borderColor[i],
                                                lineWidth: dataset.borderWidth,
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
                            backgroundColor: 'rgba(0,0,0,0.9)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            titleFont: {
                                size: 15,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 14
                            },
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                title: function(context) {
                                    return `📍 ${context[0].label}`;
                                },
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `👥 Personas: ${value.toLocaleString()}`;
                                },
                                afterLabel: function(context) {
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `📊 Porcentaje: ${percentage}% del corregimiento`;
                                },
                                footer: function(context) {
                                    const totalCorregimiento = context[0].dataset.data.reduce((a, b) => a + b, 0);
                                    return `🏘️ Total corregimiento: ${totalCorregimiento.toLocaleString()} personas`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 25,
                                usePointStyle: true,
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const dataset = data.datasets[0];
                                            const value = dataset.data[i];
                                            const total = dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;

                                            return {
                                                text: `${label}: ${value.toLocaleString()} (${percentage}%)`,
                                                fillStyle: dataset.backgroundColor[i],
                                                strokeStyle: dataset.borderColor[i],
                                                lineWidth: dataset.borderWidth,
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 1200,
                        easing: 'easeInOutQuart'
                    },
                    onHover: (event, activeElements) => {
                        event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
                    }
                }
            });

            // Agregar funcionalidad de clic en las secciones del gráfico
            ctx.canvas.addEventListener('click', function(event) {
                const activeElements = corregimientosChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
                if (activeElements.length > 0) {
                    const index = activeElements[0].index;
                    const label = chartData.labels[index];
                    const value = chartData.data[index];

                    // Aquí puedes agregar funcionalidad adicional al hacer clic
                    console.log(`Clic en ${label}: ${value} personas`);

                    // Por ejemplo, mostrar un modal con más detalles o filtrar datos
                    // showCorregimientoDetails(label, value);
                }
            });
        }

        // Función para cargar el gráfico de barras apiladas de edad
        function loadEdadChart() {
            // Destruir gráfico anterior si existe
            if (corregimientosChart) {
                corregimientosChart.destroy();
            }

            // Datos del gráfico de edad
            const edadData = @json($edadStats['chartData']);

            // Crear gráfico de barras apiladas
            const ctx = document.getElementById('edadChart').getContext('2d');

            corregimientosChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: edadData.labels,
                    datasets: edadData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 14,
                                    weight: '600'
                                },
                                generateLabels: function(chart) {
                                    const datasets = chart.data.datasets;
                                    return datasets.map((dataset, i) => ({
                                        text: dataset.label,
                                        fillStyle: dataset.backgroundColor,
                                        strokeStyle: dataset.borderColor,
                                        lineWidth: dataset.borderWidth,
                                        hidden: !chart.isDatasetVisible(i),
                                        index: i
                                    }));
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y || 0;
                                    const totalCorregimiento = context.chart.data.datasets.reduce((sum, dataset) => sum + dataset.data[context.dataIndex], 0);
                                    const percentage = totalCorregimiento > 0 ? Math.round((value / totalCorregimiento) * 100) : 0;
                                    return `${label}: ${value.toLocaleString()} personas (${percentage}%)`;
                                },
                                afterLabel: function(context) {
                                    const corregimiento = context.label;
                                    const totalCorregimiento = context.chart.data.datasets.reduce((sum, dataset) => sum + dataset.data[context.dataIndex], 0);
                                    return `Total ${corregimiento}: ${totalCorregimiento.toLocaleString()} personas`;
                                },
                                title: function(context) {
                                    return `Corregimiento: ${context[0].label}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                },
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeInOutQuart',
                        delay: function(context) {
                            return context.dataIndex * 300;
                        }
                    },
                    onHover: (event, activeElements) => {
                        event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
                    },
                    onClick: (event, activeElements) => {
                        if (activeElements.length > 0) {
                            const index = activeElements[0].index;
                            const datasetIndex = activeElements[0].datasetIndex;
                            const label = edadData.labels[index];
                            const dataset = edadData.datasets[datasetIndex];
                            const value = dataset.data[index];

                            console.log(`Clic en ${dataset.label} - ${label}: ${value} personas`);
                        }
                    }
                }
            });
        }

        // Función para cargar el gráfico de barras de área
        function loadAreaChart() {
            // Destruir gráfico anterior si existe
            if (corregimientosChart) {
                corregimientosChart.destroy();
            }

            // Datos del gráfico de área
            const areaData = @json($areaStats['chartData']);

            // Crear gráfico de barras
            const ctx = document.getElementById('areaChart').getContext('2d');

            corregimientosChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: areaData.labels,
                    datasets: areaData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y || 0;
                                    return `${label}: ${value.toLocaleString()} hectáreas`;
                                },
                                afterLabel: function(context) {
                                    const corregimiento = context.label;
                                    const totalArea = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = totalArea > 0 ? Math.round((context.parsed.y / totalArea) * 100) : 0;
                                    return `Representa el ${percentage}% del área total`;
                                },
                                title: function(context) {
                                    return `Corregimiento: ${context[0].label}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' ha';
                                },
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeInOutQuart',
                        delay: function(context) {
                            return context.dataIndex * 200;
                        }
                    },
                    onHover: (event, activeElements) => {
                        event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
                    },
                    onClick: (event, activeElements) => {
                        if (activeElements.length > 0) {
                            const index = activeElements[0].index;
                            const label = areaData.labels[index];
                            const value = areaData.datasets[0].data[index];

                            console.log(`Clic en ${label}: ${value} hectáreas`);
                        }
                    }
                }
            });
        }

        // Función para mostrar detalles de un corregimiento (ejemplo de funcionalidad futura)
        function showCorregimientoDetails(label, value) {
            // Esta función se puede implementar para mostrar más detalles
            // cuando se haga clic en una sección del gráfico
            console.log(`Mostrar detalles de ${label}`);
        }

        // Función para actualizar estadísticas en tiempo real (útil para futuras implementaciones)
        function updateStatistics(data) {
            // Esta función puede ser usada para actualizar las estadísticas
            // cuando se cambien filtros o se actualicen datos
            console.log('Actualizando estadísticas:', data);
        }
    </script>

</x-app-layout>

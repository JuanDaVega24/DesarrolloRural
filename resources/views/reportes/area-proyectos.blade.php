<x-app-layout>
    @vite(['resources/css/pages/reportes/area-proyectos.css'])

    <div class="estadisticas-container">
        <div class="content-wrapper">

            {{-- === HEADER PROFESIONAL === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Área por Corregimientos - Proyectos Productivos</h1>
                    <p>Análisis detallado de superficie agrícola por proyecto productivo y corregimiento</p>
                    <div class="header-meta">
                        <span>Última actualización: {{ now()->format('d/m/Y H:i') }}</span>
                        <span>Proyectos registrados: {{ $proyectos->count() ?? 0 }}</span>
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
                        <i class="fas fa-map"></i>
                        Área por corregimiento
                    </button>
                    <button class="tab-button" onclick="showTab('genero')">
                        <i class="fas fa-route"></i>
                        Área por vereda
                    </button>

                </div>


            {{-- === SELECTOR DE PROYECTO === --}}
            <div class="project-selector">
                <div class="selector-content">
                     <div class="form-group">
                        <label class="form-label">Año del Proyecto </label>
                        <select class="form-select" id="base-year-filter">
                            <option value="">Selecciona un año...</option>
                            {{-- Años se cargarán vía AJAX --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Proyecto Productivo</label>
                        <select class="form-select" id="base-project-select">
                            <option value="">Primero selecciona un año...</option>
                            {{-- Proyectos se cargarán vía AJAX --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" id="load-project-btn" disabled>
                            <i class="fas fa-chart-bar me-2"></i>Cargar Estadísticas
                        </button>
                    </div>
                </div>
            </div>

            @if(request('proyecto_id') && isset($proyectoStats))
                {{-- TAB CONTENT CORREGIMIENTO --}}
                <div id="corregimiento-content" class="tab-content">
                {{-- PROJECT TITLE --}}
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin: 0; letter-spacing: -0.5px;">
                        📊 Proyecto: {{ $proyectoSeleccionado->nombre ?? 'Proyecto ' . $proyectoSeleccionado->id }} ({{ $proyectoSeleccionado->ano ?? 'N/A' }})
                    </h2>
                </div>

                {{-- TOTAL OVERVIEW --}}
                <div class="stats-overview">
                    {{-- TARJETA TOTAL DE ÁREA DEL PROYECTO --}}
                    <div class="stat-card" style="--index: 1;">
                        <div class="stat-icon" style="background: var(--gradient-primary);">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <div class="stat-number">{{ number_format($proyectoStats['totales']['area_total'] ?? 0, 2) }}</div>
                        <div class="stat-title">Área Total del Proyecto (ha)</div>
                        <div class="stat-percentage">
                            {{ $proyectoStats['totales']['total_registros'] ?? 0 }} registros
                        </div>
                    </div>
                </div>

                {{-- CARDS POR CORREGIMIENTO --}}
                <div class="stats-overview">
                    @if(isset($proyectoStats['detalles']))
                    @foreach($proyectoStats['detalles'] as $detalle)
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
                                {{ $proyectoStats['totales']['area_total'] > 0 ? round((($detalle['area'] ?? 0) / $proyectoStats['totales']['area_total']) * 100, 1) : 0 }}% del área total
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
                            Proyecto: {{ $proyectoSeleccionado->nombre ?? 'Proyecto ' . $proyectoSeleccionado->id }} - Superficie agrícola por corregimiento
                        </p>
                    </div>
                    <div class="chart-container" style="display: flex; gap: 2rem; align-items: center;">
                        <div class="chart-wrapper" style="flex: 1;">
                            <canvas id="proyectoAreaChart"></canvas>
                        </div>
                        <div class="chart-sidebar" style="width: 280px; background: var(--surface); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-lg); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(20px);">
                            <h4 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem; text-align: center; padding-bottom: 1rem; border-bottom: 2px solid var(--primary-light);">
                                📊 Resumen por Corregimiento
                            </h4>
                            @if(isset($proyectoStats['detalles']))
                            @foreach($proyectoStats['detalles'] as $index => $detalle)
                            @php
                                $totalArea = $proyectoStats['totales']['area_total'] ?? 0;
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
                                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 600; margin-bottom: 0.25rem;">Área Total del Proyecto</div>
                                <div style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">{{ number_format($proyectoStats['totales']['area_total'] ?? 0, 2) }} ha</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">{{ number_format($proyectoStats['totales']['total_registros'] ?? 0) }} registros totales</div>
                            </div>
                        </div>
                    </div>
                </div>
                </div> {{-- Cierre del div corregimiento-content --}}
            @else
                {{-- NO DATA STATE --}}
                <div class="no-data-section">
                    <div class="no-data-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h3 class="no-data-title">Seleccione un Proyecto Productivo</h3>
                    <p class="no-data-text">
                        Elija un proyecto del menú desplegable para visualizar las estadísticas de área por corregimiento.
                        Los datos se mostrarán una vez que seleccione un proyecto y haga clic en "Cargar Estadísticas".
                    </p>
                </div>
            @endif

            {{-- TAB CONTENT VEREDA --}}
            <div id="vereda-content" class="tab-content" style="display: none;">
                @if(request('proyecto_id') && isset($proyectoStatsVereda) && isset($proyectoSeleccionado))
                {{-- PROJECT TITLE --}}
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin: 0; letter-spacing: -0.5px;">
                        📊 Proyecto: {{ $proyectoSeleccionado->nombre ?? 'Proyecto ' . $proyectoSeleccionado->id }} ({{ $proyectoSeleccionado->ano ?? 'N/A' }})
                    </h2>
                </div>

                {{-- TOTAL OVERVIEW VEREDA --}}
                <div class="stats-overview">
                    {{-- TARJETA TOTAL DE ÁREA DEL PROYECTO --}}
                    <div class="stat-card" style="--index: 1;">
                        <div class="stat-icon" style="background: var(--gradient-primary);">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="stat-number">{{ number_format($proyectoStatsVereda['totales']['area_total'] ?? 0, 2) }}</div>
                        <div class="stat-title">Área Total del Proyecto (ha)</div>
                        <div class="stat-percentage">
                            {{ $proyectoStatsVereda['totales']['total_registros'] ?? 0 }} registros
                        </div>
                    </div>
                </div>

                {{-- SEARCH BAR FOR VEREDAS --}}
                <div style="margin-bottom: 2rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                    <div class="form-group">
                        <label class="form-label" style="text-align: center; display: block;">Buscar Vereda</label>
                        <div style="position: relative;">
                            <input type="text" id="vereda-search" class="form-control" placeholder="Escribe el nombre de la vereda..." style="padding-left: 2.5rem; padding-right: 1rem;">
                            <i class="fas fa-search" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--gris); font-size: 0.9rem;"></i>
                        </div>
                    </div>
                </div>

                {{-- CARDS POR VEREDA --}}
                <div class="stats-overview" id="veredas-container">
                    @if(isset($proyectoStatsVereda['detalles']))
                    @foreach($proyectoStatsVereda['detalles'] as $index => $detalle)
                    <div class="stat-card">
                        <div class="stat-icon corregimiento-{{ ($index % 3) + 1 }}">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div style="width: 100%; text-align: center;">
                            <div class="vereda-name" style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1rem;">
                                {{ $detalle['vereda'] }}
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
                                {{ $proyectoStatsVereda['totales']['area_total'] > 0 ? round((($detalle['area'] ?? 0) / $proyectoStatsVereda['totales']['area_total']) * 100, 1) : 0 }}% del área total
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>

                {{-- CHART SECTION VEREDA --}}
                <div class="chart-section">
                    <div class="chart-header">
                        <h2 class="chart-title">Distribución de Área por Vereda</h2>
                        <p class="chart-subtitle">
                            Proyecto: {{ $proyectoSeleccionado->nombre ?? 'Proyecto ' . $proyectoSeleccionado->id }} - Superficie agrícola por vereda
                        </p>
                    </div>
                    <div class="chart-container" style="display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
                        <div class="chart-wrapper" style="flex: 1; min-width: 500px;">
                            <canvas id="proyectoVeredaChart"></canvas>
                        </div>
                        <div class="chart-sidebar" style="flex: 0 1 350px; background: var(--surface); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-lg); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(20px); max-height: 600px; overflow-y: auto;">
                            <h4 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem; text-align: center; padding-bottom: 1rem; border-bottom: 2px solid var(--primary-light); position: sticky; top: 0; background: var(--surface); z-index: 10;">
                                📊 Resumen por Vereda
                            </h4>
                            @if(isset($proyectoStatsVereda['detalles']))
                            @foreach(array_slice($proyectoStatsVereda['detalles'], 0, 8) as $index => $detalle) {{-- Mostrar solo las primeras 8 veredas --}}
                            @php
                                $totalArea = $proyectoStatsVereda['totales']['area_total'] ?? 0;
                                $areaVereda = $detalle['area'] ?? 0;
                                $porcentaje = $totalArea > 0 ? round(($areaVereda / $totalArea) * 100, 1) : 0;
                            @endphp
                            <div style="margin-bottom: 1.25rem; padding: 1.25rem; background: var(--surface-muted); border-radius: var(--radius-md); border-left: 4px solid {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}; box-shadow: var(--shadow-sm); transition: all 0.3s ease; cursor: pointer;"
                                 onmouseover="this.style.transform='translateX(5px)'; this.style.boxShadow='var(--shadow-md)'"
                                 onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='var(--shadow-sm)'">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                                    <div style="width: 12px; height: 12px; border-radius: 50%; background: {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}; box-shadow: 0 0 8px {{ $index === 0 ? '#4A7C2F' : ($index === 1 ? '#0943B5' : '#A80521') }}80;"></div>
                                    <span style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">{{ $detalle['vereda'] }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Área:</span>
                                    <span style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary);">{{ number_format($areaVereda, 2) }} ha</span>
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
                                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 600; margin-bottom: 0.25rem;">Área Total del Proyecto</div>
                                <div style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">{{ number_format($proyectoStatsVereda['totales']['area_total'] ?? 0, 2) }} ha</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">{{ number_format($proyectoStatsVereda['totales']['total_registros'] ?? 0) }} registros totales</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- === ACTION BUTTONS === --}}
            @if(request('proyecto_id') && isset($proyectoStats))
            <div class="action-buttons">
                <a id="export-pdf-btn" href="{{ route('reportes.area-proyectos.pdf', ['proyecto_id' => request('proyecto_id')]) }}" class="btn-export" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                    Exportar Reporte PDF
                </a>
            </div>
            @endif

        </div>
    </div>

    {{-- === CHART.JS === --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- === JAVASCRIPT === --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial data on page load
            loadYears();

            // Event listeners
            document.getElementById('base-year-filter').addEventListener('change', handleBaseYearChange);
            document.getElementById('base-project-select').addEventListener('change', handleBaseProjectChange);
            document.getElementById('load-project-btn').addEventListener('click', handleLoadProject);

            @if(request('proyecto_id') && isset($proyectoStats))
            loadProyectoAreaChart();
            @endif
        });

        async function loadYears() {
            try {
                const response = await fetch('/api/anios-disponibles');
                const years = await response.json();

                const baseYearSelect = document.getElementById('base-year-filter');

                // Clear existing options except the first one
                baseYearSelect.innerHTML = '<option value="">Selecciona un año...</option>';

                years.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    baseYearSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading years:', error);
            }
        }

        async function loadProjects(selectedYear = '') {
            try {
                let url = '/api/proyectos-comparar';
                if (selectedYear) {
                    url += `?ano=${selectedYear}`;
                }

                const response = await fetch(url);
                const data = await response.json();

                const select = document.getElementById('base-project-select');
                select.innerHTML = '<option value="">Selecciona un proyecto...</option>';

                data.forEach(project => {
                    const option = document.createElement('option');
                    option.value = project.id;
                    option.textContent = `${project.nombre} (${project.ano}) - ${project.total_rows} registros`;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading projects:', error);
                showError('Error al cargar los proyectos');
            }
        }

        function handleBaseYearChange(event) {
            const selectedYear = event.target.value;

            if (selectedYear) {
                loadProjects(selectedYear);
            } else {
                const select = document.getElementById('base-project-select');
                select.innerHTML = '<option value="">Primero selecciona un año...</option>';
            }

            updateLoadButton();
        }

        function handleBaseProjectChange(event) {
            updateLoadButton();
        }

        function updateLoadButton() {
            const baseYear = document.getElementById('base-year-filter').value;
            const baseProject = document.getElementById('base-project-select').value;
            const loadBtn = document.getElementById('load-project-btn');

            if (baseYear && baseProject) {
                loadBtn.disabled = false;
            } else {
                loadBtn.disabled = true;
            }
        }

        function handleLoadProject() {
            const baseProject = document.getElementById('base-project-select').value;

            if (baseProject) {
                // Redirect with the selected project
                window.location.href = `{{ route('reportes.area-proyectos') }}?proyecto_id=${baseProject}`;
            }
        }

        @if(request('proyecto_id') && isset($proyectoStats))
        // Función para cargar el gráfico de barras de área del proyecto por corregimiento
        function loadProyectoAreaChart() {
            // Datos del gráfico de área del proyecto
            const proyectoAreaData = @json($proyectoStats['chartData']);

            // Crear gráfico de barras
            const ctx = document.getElementById('proyectoAreaChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: proyectoAreaData.labels,
                    datasets: proyectoAreaData.datasets
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
                                    return `Representa el ${percentage}% del área total del proyecto`;
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
                            const label = proyectoAreaData.labels[index];
                            const value = proyectoAreaData.datasets[0].data[index];

                            console.log(`Clic en ${label}: ${value} hectáreas`);
                        }
                    }
                }
            });
        }

        // Función para cargar el gráfico de barras de área del proyecto por vereda
        function loadProyectoVeredaChart() {
            // Datos del gráfico de área del proyecto por vereda
            const proyectoVeredaData = @json($proyectoStatsVereda['chartData']);

            // Crear gráfico de barras
            const ctx = document.getElementById('proyectoVeredaChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: proyectoVeredaData.labels,
                    datasets: proyectoVeredaData.datasets
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
                                    const vereda = context.label;
                                    const totalArea = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = totalArea > 0 ? Math.round((context.parsed.y / totalArea) * 100) : 0;
                                    return `Representa el ${percentage}% del área total del proyecto`;
                                },
                                title: function(context) {
                                    return `Vereda: ${context[0].label}`;
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
                                    size: 10,
                                    weight: '500'
                                },
                                maxRotation: 45,
                                minRotation: 45
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
                            const label = proyectoVeredaData.labels[index];
                            const value = proyectoVeredaData.datasets[0].data[index];

                            console.log(`Clic en ${label}: ${value} hectáreas`);
                        }
                    }
                }
            });
        }
        @endif

        // Función para mostrar/ocultar pestañas
        function showTab(tabName) {
            // Actualizar botón de exportación PDF según la pestaña activa
            const exportBtn = document.getElementById('export-pdf-btn');
            const projectId = new URLSearchParams(window.location.search).get('proyecto_id');
            
            // Ocultar todos los contenidos de pestañas
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.style.display = 'none';
            });

            // Remover clase active de todos los botones
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });

            // Mostrar el contenido de la pestaña seleccionada
            if (tabName === 'corregimientos') {
                document.getElementById('corregimiento-content').style.display = 'block';
                document.querySelector('.tab-button:nth-child(1)').classList.add('active');
                if (exportBtn && projectId) {
                    exportBtn.href = "{{ route('reportes.area-proyectos.pdf') }}?proyecto_id=" + projectId;
                }
            } else if (tabName === 'genero') {
                document.getElementById('vereda-content').style.display = 'block';
                document.querySelector('.tab-button:nth-child(2)').classList.add('active');
                if (exportBtn && projectId) {
                    exportBtn.href = "{{ route('reportes.area-proyectos.vereda.pdf') }}?proyecto_id=" + projectId;
                }

                // Cargar el gráfico de vereda si no está cargado
                @if(request('proyecto_id') && isset($proyectoStatsVereda))
                setTimeout(() => {
                    loadProyectoVeredaChart();
                    // Inicializar buscador de veredas
                    initializeVeredaSearch();
                }, 100);
                @endif
            }
        }

        // Función para inicializar el buscador de veredas
        function initializeVeredaSearch() {
            const searchInput = document.getElementById('vereda-search');
            const veredasContainer = document.getElementById('veredas-container');

            if (!searchInput || !veredasContainer) return;

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                const veredaCards = veredasContainer.querySelectorAll('.stat-card');

                let visibleCount = 0;

                veredaCards.forEach(card => {
                    const veredaName = card.querySelector('.vereda-name')?.textContent.toLowerCase() || '';

                    if (searchTerm === '' || veredaName.includes(searchTerm)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Actualizar mensaje si no hay resultados
                let noResultsMsg = veredasContainer.querySelector('.no-results-message');
                if (searchTerm !== '' && visibleCount === 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'no-results-message';
                        noResultsMsg.style.cssText = `
                            text-align: center;
                            padding: 3rem 2rem;
                            background: var(--surface);
                            border-radius: var(--radius-lg);
                            box-shadow: var(--shadow-md);
                            margin-top: 2rem;
                            border: 2px dashed var(--primary-light);
                        `;
                        noResultsMsg.innerHTML = `
                            <div style="font-size: 3rem; color: var(--gris-medio); margin-bottom: 1rem;">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4 style="color: var(--gris); font-weight: 700; margin-bottom: 1rem;">No se encontraron veredas</h4>
                            <p style="color: var(--gris-medio); margin: 0;">
                                No hay veredas que coincidan con "<strong>${searchTerm}</strong>".
                                Intenta con otro término de búsqueda.
                            </p>
                        `;
                        veredasContainer.appendChild(noResultsMsg);
                    } else {
                        // Actualizar el mensaje con el nuevo término de búsqueda
                        noResultsMsg.querySelector('p').innerHTML =
                            `No hay veredas que coincidan con "<strong>${searchTerm}</strong>".
                            Intenta con otro término de búsqueda.`;
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            });

            // Limpiar búsqueda al cambiar de pestaña
            searchInput.value = '';
        }

        function showError(message) {
            alert(message);
        }
    </script>

</x-app-layout>
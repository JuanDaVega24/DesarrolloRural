<x-app-layout>
    <style>
        /* === ESTILOS ESPECÍFICOS DE ESTA PÁGINA === */

        .header-content h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--negro);
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header-content p {
            color: var(--gris);
            font-size: 1rem;
            margin: 0.5rem 0 0 0;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        /* === PROJECT SELECTOR === */
        .project-selector {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            margin-bottom: 2rem;
        }

        .selector-content {
            display: grid;
            grid-template-columns: 1fr 0.5fr;
            gap: 2rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--negro);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-select, .form-control {
            border: 1px solid var(--gris-medio);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-select:focus, .form-control:focus {
            border-color: var(--azul);
            box-shadow: 0 0 0 3px var(--azul-claro);
            outline: none;
        }

        /* === STATS CARDS === */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.success { background: var(--verde-claro); color: var(--verde); }
        .stat-icon.danger { background: var(--rojo-claro); color: var(--rojo); }
        .stat-icon.info { background: var(--azul-claro); color: var(--azul); }

        .stat-content h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--negro);
            margin: 0;
            line-height: 1;
        }

        .stat-content p {
            color: var(--gris);
            font-size: 0.9rem;
            margin: 0.25rem 0 0 0;
        }

        /* === COMPARISON TABLE === */
        .comparison-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }

        .table-header {
            background: linear-gradient(to top, var(--verde) 0%, #5a9c3f 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .search-container {
            position: relative;
            margin-right: 0.1rem;
        }

        .search-container .form-control {
            width: 150px;
            padding-right: 2.5rem;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: var(--govcolor-cobalt);
        }

        .search-container .form-control:focus {
            background: white;
            border-color: white;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
        }

        .search-container .form-control::placeholder {
            color: var(--govcolor-cobalt);
        }

        .search-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--azul);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .btn-filter {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-filter:hover, .btn-filter.active {
            background: white;
            color: var(--azul);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .comparison-table {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .comparison-table thead th {
            background: var(--gris-claro);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--negro);
            padding: 1rem 1.5rem;
            border-bottom: 2px solid var(--gris-medio);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .comparison-table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gris-claro);
            font-size: 0.9rem;
        }

        /* Estilos específicos para la columna Detalles Caracterización */
        .comparison-table tbody td:nth-child(4) {
            max-width: 400px;
            min-width: 300px;
            word-wrap: break-word;
            white-space: normal;
            line-height: 1.4;
            vertical-align: top;
            padding: 0.75rem 1rem;
        }

        .comparison-table tbody td:nth-child(4) .detalles-caracterizacion {
            font-size: 0.85rem;
            color: var(--gris);
            font-weight: 500;
            margin: 0;
        }

        /* Estilos para el contenido de detalles caracterización */
        .detalles-caracterizacion-cell {
            position: relative;
        }

        .detalles-caracterizacion-content {
            font-size: 0.85rem;
            line-height: 1.5;
            color: var(--negro);
            font-weight: 500;
            word-break: break-word;
            hyphens: auto;
            margin: 0;
        }

        .detalles-caracterizacion-content:empty::before {
            content: "No tiene caracterización";
            color: var(--gris);
            font-style: italic;
        }

        .comparison-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .comparison-table tbody tr:hover {
            background-color: var(--gris-claro);
        }

        /* Status indicators */
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.75rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .status-valid {
            background: var(--verde-claro);
            color: var(--verde);
        }

        .status-invalid {
            background: var(--rojo-claro);
            color: var(--rojo);
        }

        .document-number {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: var(--azul);
        }

        /* === BUTTONS === */
        .btn-primary {
            background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 124, 47, 0.25);
        }

        .btn-secondary {
            background: white;
            border: 1px solid var(--gris-medio);
            color: var(--gris);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: var(--gris);
            color: var(--negro);
        }

        .btn-export {
            background: var(--azul);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-export:hover {
            background: var(--azul-hover);
        }

        /* === EMPTY STATE === */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--gris-medio);
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: var(--gris);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--gris-medio);
            font-size: 0.95rem;
        }

        /* === ALERTS === */
        .alert-danger {
            background: #ffe5e5;
            border: none;
            border-left: 3px solid #dc3545;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .alert-info {
            background: var(--azul-claro);
            border: none;
            border-left: 3px solid var(--azul);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: var(--azul);
        }

        /* === LOADING SPINNER === */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid var(--gris-claro);
            border-radius: 50%;
            border-top-color: var(--azul);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .selector-content {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .filter-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn-filter {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }

        /* Estilos responsive para la columna Detalles Caracterización */
        @media (max-width: 768px) {
            .comparison-table tbody td:nth-child(4) {
                max-width: 250px;
                min-width: 200px;
                font-size: 0.8rem;
                padding: 0.5rem 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .comparison-table thead th,
            .comparison-table tbody td {
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
            }

            .comparison-table tbody td:nth-child(4) {
                max-width: 180px;
                min-width: 150px;
                font-size: 0.75rem;
                padding: 0.5rem 0.5rem;
            }

            .status-indicator {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>

    <div class="validacion-container">
        <div class="content-wrapper">

            {{-- === HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Validar Proyectos</h1>
                    <p>Compara documentos de proyectos Excel con datos de caracterización</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('filtros.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>

            {{-- === ALERTS === --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- === PROJECT SELECTOR === --}}
            <div class="project-selector">
                <div class="selector-content">
                    <div class="form-group">
                        <label class="form-label">Seleccionar Proyecto</label>
                        <select class="form-select" id="project-select">
                            <option value="">Selecciona un proyecto con datos Excel...</option>
                            {{-- Proyectos se cargarán vía AJAX --}}
                        </select>
                    </div>
                     <div class="form-group">
                        <label class="form-label">Filtrar por Año</label>
                        <select class="form-select" id="year-filter">
                            <option value="">Todos los años</option>
                            {{-- Años se cargarán vía AJAX --}}
                        </select>
                    </div>
                </div>
            </div>

            {{-- === MANUAL COLUMN SELECTOR === --}}
            <div class="project-selector" id="column-selector" style="display: none;">
                <div class="selector-content">
                    <div class="form-group">
                        <label class="form-label">Columna de Documentos</label>
                        <select class="form-select" id="document-column-select">
                            <option value="auto">🔍 Detectar automáticamente</option>
                            {{-- Columnas se cargarán dinámicamente --}}
                        </select>
                        <small class="text-muted mt-1 d-block">
                            Si la detección automática no funciona, selecciona manualmente la columna que contiene los números de documento.
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Columna de Nombres</label>
                        <select class="form-select" id="name-column-select">
                            <option value="auto">🔍 Detectar automáticamente</option>
                            {{-- Columnas se cargarán dinámicamente --}}
                        </select>
                        <small class="text-muted mt-1 d-block">
                            Opcional: selecciona la columna que contiene los nombres completos.
                        </small>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" id="revalidate-btn" style="margin-top: 1.5rem;">
                            <i class="fas fa-sync-alt me-2"></i>Re-validar con selección manual
                        </button>
                    </div>
                </div>
            </div>

            {{-- === STATS CARDS === --}}
            <div class="stats-grid" id="stats-container" style="display: none;">
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="total-personas">0</h3>
                        <p>Total Personas</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="personas-validas">0</h3>
                        <p>Con Caracterización</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="personas-invalidas">0</h3>
                        <p>Sin Caracterización</p>
                    </div>
                </div>
            </div>



            {{-- === COMPARISON TABLE === --}}
            <div class="comparison-card" id="comparison-container" style="display: none;">
                <div class="table-header">
                    <h3>Resultado de Validación</h3>
                    <div class="filter-buttons">
                        
                        <button class="btn-filter active" data-filter="all">Todos</button>
                        <button class="btn-filter" data-filter="valid">Caracterizados</button>
                        <button class="btn-filter" data-filter="invalid">No Caracterizados</button>
                        <div class="search-container">
                            <input type="text" id="table-search" placeholder="Buscar.." class="form-control">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="comparison-table" id="comparison-table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Estado</th>
                                <th>Nombre Completo</th>
                                <th>Detalles Caracterización</th>
                            </tr>
                        </thead>
                        <tbody id="comparison-body">
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="loading-spinner me-2"></div>
                                    Cargando datos...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- === INITIAL EMPTY STATE === --}}
            <div class="empty-state" id="initial-state">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4>Validación de Proyectos</h4>
                <p class="text-muted">Selecciona un proyecto con datos Excel para comparar con la base de caracterización.</p>
            </div>

        </div>
    </div>

    {{-- === JAVASCRIPT === --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentProjectData = null;
            let currentFilter = 'all';

            // Load initial data on page load
            loadYears();
            loadProjects();

            // Event listeners
            document.getElementById('year-filter').addEventListener('change', handleYearChange);
            document.getElementById('project-select').addEventListener('change', handleProjectChange);
            document.getElementById('table-search').addEventListener('input', handleTableSearch);
            document.querySelectorAll('.btn-filter').forEach(btn => {
                btn.addEventListener('click', handleFilterClick);
            });
            document.getElementById('revalidate-btn').addEventListener('click', handleManualRevalidation);

            async function loadYears() {
                try {
                    const response = await fetch('/api/anios-disponibles');
                    const years = await response.json();

                    const select = document.getElementById('year-filter');

                    // Keep the "Todos los años" option and add the years
                    years.forEach(year => {
                        const option = document.createElement('option');
                        option.value = year;
                        option.textContent = year;
                        select.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading years:', error);
                }
            }

            async function loadProjects(selectedYear = '') {
                try {
                    let url = '/api/proyectos-excel';
                    if (selectedYear) {
                        url += `?ano=${selectedYear}`;
                    }

                    const response = await fetch(url);
                    const data = await response.json();

                    const select = document.getElementById('project-select');
                    select.innerHTML = '<option value="">Selecciona un proyecto con datos Excel...</option>';

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

            async function handleYearChange(event) {
                const selectedYear = event.target.value;

                // Reset project selection when year changes (only clear selection, not content)
                document.getElementById('project-select').value = '';

                // Reload projects for the selected year
                await loadProjects(selectedYear);
                
                // Do NOT call showInitialState() here - keep the dropdown visible
                // so the user can see and select the filtered projects
            }

            async function handleProjectChange(event) {
                const projectId = event.target.value;
                if (!projectId) {
                    showInitialState();
                    return;
                }

                try {
                    showLoading();
                    const response = await fetch(`/api/proyectos/${projectId}/validar`);
                    const data = await response.json();

                    currentProjectData = data;
                    renderResults(data);
                } catch (error) {
                    console.error('Error validating project:', error);
                    showError('Error al validar el proyecto');
                }
            }

            function handleTableSearch(event) {
                const searchTerm = event.target.value.toLowerCase();
                if (!currentProjectData) return;

                const filtered = currentProjectData.comparison.filter(item =>
                    item.documento.toLowerCase().includes(searchTerm) ||
                    item.nombre_completo.toLowerCase().includes(searchTerm)
                );

                renderTable(filtered);
            }

            function handleFilterClick(event) {
                document.querySelectorAll('.btn-filter').forEach(btn => btn.classList.remove('active'));
                event.target.classList.add('active');

                currentFilter = event.target.dataset.filter;
                if (currentProjectData) {
                    renderTable(currentProjectData.comparison);
                }
            }

            function renderResults(data) {
                // Update stats
                document.getElementById('total-personas').textContent = data.stats.total;
                document.getElementById('personas-validas').textContent = data.stats.valid;
                document.getElementById('personas-invalidas').textContent = data.stats.invalid;

                // Populate column selectors with available columns
                if (data.debug && data.debug.headers) {
                    populateColumnSelectors(data.debug.headers);
                    document.getElementById('column-selector').style.display = 'block';
                }

                // Show containers
                document.getElementById('stats-container').style.display = 'grid';
                document.getElementById('comparison-container').style.display = 'block';
                document.getElementById('initial-state').style.display = 'none';

                // Render table
                renderTable(data.comparison);
            }

            function populateColumnSelectors(headers) {
                const docSelect = document.getElementById('document-column-select');
                const nameSelect = document.getElementById('name-column-select');

                // Clear existing options except the first one
                docSelect.innerHTML = '<option value="auto">🔍 Detectar automáticamente</option>';
                nameSelect.innerHTML = '<option value="auto">🔍 Detectar automáticamente</option>';

                // Add all available columns
                headers.forEach(header => {
                    const docOption = document.createElement('option');
                    docOption.value = header;
                    docOption.textContent = header;
                    docSelect.appendChild(docOption);

                    const nameOption = document.createElement('option');
                    nameOption.value = header;
                    nameOption.textContent = header;
                    nameSelect.appendChild(nameOption);
                });
            }

            async function handleManualRevalidation() {
                const projectId = document.getElementById('project-select').value;
                const documentColumn = document.getElementById('document-column-select').value;
                const nameColumn = document.getElementById('name-column-select').value;

                if (!projectId) {
                    showError('Selecciona un proyecto primero');
                    return;
                }

                try {
                    showLoading();

                    // Build URL with manual column parameters
                    let url = `/api/proyectos/${projectId}/validar`;
                    const params = new URLSearchParams();

                    if (documentColumn !== 'auto') {
                        params.append('document_column', documentColumn);
                    }
                    if (nameColumn !== 'auto') {
                        params.append('name_column', nameColumn);
                    }

                    if (params.toString()) {
                        url += '?' + params.toString();
                    }

                    const response = await fetch(url);
                    const data = await response.json();

                    currentProjectData = data;
                    renderResults(data);
                } catch (error) {
                    console.error('Error revalidating project:', error);
                    showError('Error al revalidar el proyecto');
                }
            }

            function renderTable(comparisonData) {
                const tbody = document.getElementById('comparison-body');
                tbody.innerHTML = '';

                let filteredData = comparisonData;

                // Apply filter
                if (currentFilter === 'valid') {
                    filteredData = comparisonData.filter(item => item.tiene_caracterizacion);
                } else if (currentFilter === 'invalid') {
                    filteredData = comparisonData.filter(item => !item.tiene_caracterizacion);
                }

                if (filteredData.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="fas fa-search me-2"></i>
                                No se encontraron resultados para el filtro seleccionado.
                            </td>
                        </tr>
                    `;
                    return;
                }

                filteredData.forEach(item => {
                    const statusClass = item.tiene_caracterizacion ? 'status-valid' : 'status-invalid';
                    const statusIcon = item.tiene_caracterizacion ? 'check-circle' : 'times-circle';
                    let statusText = item.tiene_caracterizacion ? 'Con Caracterización' : 'Sin Caracterización';

                    if (item.tiene_caracterizacion) {
                        if (item.tipo_caracterizacion === 'directa') {
                            statusText = 'Caracterización Directa';
                        } else if (item.tipo_caracterizacion === 'familiar') {
                            statusText = 'Caracterización Familiar';
                        }
                    }

                    tbody.innerHTML += `
                        <tr>
                            <td><span class="document-number">${item.documento}</span></td>
                            <td>
                                <span class="status-indicator ${statusClass}">
                                    <i class="fas fa-${statusIcon}"></i>
                                    ${statusText}
                                </span>
                            </td>
                            <td>${item.nombre_completo || 'No disponible'}</td>
                            <td class="detalles-caracterizacion-cell">
                                <div class="detalles-caracterizacion-content">${item.detalles_caracterizacion || 'No tiene caracterización'}</div>
                            </td>
                        </tr>
                    `;
                });
            }

            function handleExport() {
                if (!currentProjectData) return;

                // Create CSV content
                let csv = 'Documento,Estado,Nombre Completo,Fecha Caracterizacion\n';

                currentProjectData.comparison.forEach(item => {
                    const status = item.tiene_caracterizacion ? 'Validado' : 'Sin validar';
                    const fecha = item.tiene_caracterizacion ? (item.encuesta.fecha_encuesta_formatted || '') : '';
                    csv += `"${item.documento}","${status}","${item.nombre_completo || ''}","${fecha}"\n`;
                });

                // Download CSV
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'validacion-proyecto.csv';
                a.click();
                window.URL.revokeObjectURL(url);
            }

            function showLoading() {
                document.getElementById('comparison-body').innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="loading-spinner me-2"></div>
                            Validando documentos...
                        </td>
                    </tr>
                `;
            }

            function showInitialState() {
                document.getElementById('stats-container').style.display = 'none';
                document.getElementById('comparison-container').style.display = 'none';
                document.getElementById('initial-state').style.display = 'block';
                currentProjectData = null;
            }

            function showError(message) {
                // You could implement a toast notification here
                alert(message);
            }
        });
    </script>
</x-app-layout>

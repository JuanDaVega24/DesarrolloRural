<x-app-layout>

    <div class="comparison-container">
        <div class="content-wrapper">

            {{-- === HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Comparar Proyectos Productivos</h1>
                    <p>Encuentra personas inscritas en múltiples proyectos productivos</p>
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
                        <label class="form-label">Año del Proyecto Base </label>
                        <select class="form-select" id="base-year-filter">
                            <option value="">Selecciona un año...</option>
                            {{-- Años se cargarán vía AJAX --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Proyecto Base</label>
                        <select class="form-select" id="base-project-select">
                            <option value="">Primero selecciona un año...</option>
                            {{-- Proyectos se cargarán vía AJAX --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Año de Proyectos a Comparar</label>
                        <select class="form-select" id="comparison-year-filter">
                            <option value="">Todos los años</option>
                            {{-- Años se cargarán vía AJAX --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Proyectos a Comparar</label>
                        <div class="multiselect-container">
                            <div class="form-control" id="comparison-projects-display" onclick="toggleMultiselect()">
                                <span id="selected-text">Selecciona proyectos para comparar...</span>
                                <i class="fas fa-chevron-down" style="float: right; margin-top: 2px;"></i>
                            </div>
                            <div class="multiselect-dropdown" id="comparison-projects-dropdown">
                                <div class="multiselect-option select-all-option">
                                    <input type="checkbox" id="select-all-checkbox" onclick="event.stopPropagation(); selectAllProjects();">
                                    <label for="select-all-checkbox" id="select-all-text">Seleccionar Todos</label>
                                </div>
                                <div id="projects-list">
                                    {{-- Proyectos se cargarán vía AJAX --}}
                                </div>
                            </div>
                        </div>
                        <div class="selected-projects" id="selected-count" style="display: none;">
                            <span class="selected-count" id="selected-number">0</span> proyectos seleccionados
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" id="compare-btn" disabled>
                            <i class="fas fa-search me-2"></i>Comparar
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
                        <h3 id="total-personas-base">0</h3>
                        <p>Personas en proyecto base</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="total-proyectos-comparacion">0</h3>
                        <p>Proyectos comparados</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="personas-multiples">0</h3>
                        <p>Personas inscritas en mas de 2 Proyectos</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon danger">
                        <i class="fas fa-link"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="total-coincidencias">0</h3>
                        <p>Total coincidencias</p>
                    </div>
                </div>
            </div>

            {{-- === RESULTS === --}}
            <div class="results-container" id="results-container" style="display: none;">
                <div id="results-content">
                    <div class="loading-spinner me-2"></div>
                    Procesando comparación...
                </div>
            </div>

            {{-- === INITIAL EMPTY STATE === --}}
            <div class="empty-state" id="initial-state">
                <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                <h4>Comparación de Proyectos</h4>
                <p class="text-muted">Selecciona un proyecto base y los proyectos específicos con los que deseas comparar para encontrar personas inscritas en múltiples proyectos.</p>
            </div>

        </div>
    </div>

    {{-- === DATA TABLE MODAL === --}}
    <div class="modal-container-govco" id="tableModal" tabindex="-1" role="dialog" aria-labelledby="tableModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-govco">
            <div class="modal-content modal-content-govco">
                <div class="modal-header modal-header-govco">
                    <h3 class="modal-title-govco" id="tableModalTitle">Datos del Proyecto</h3>
                    <button type="button" class="btn-modal-govco btn-secondary" onclick="closeTableModal()" aria-label="Cerrar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body modal-body-govco">
                    <div class="modal-table-preview" id="modalTableContent">
                        <!-- Table content will be inserted here -->
                    </div>
                </div>
                <div class="modal-footer modal-footer-govco">
                    <button type="button" class="btn-modal-govco btn-secondary" onclick="closeTableModal()">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- === JAVASCRIPT === --}}
    <script>
        // Variables globales (fuera de DOMContentLoaded para acceso desde funciones globales)
        let selectedComparisonProjects = [];
        let allProjects = [];

        document.addEventListener('DOMContentLoaded', function() {

            // Load initial data on page load
            loadYears();
            loadProjects();
            loadComparisonProjects();

            // Event listeners
            document.getElementById('base-year-filter').addEventListener('change', handleBaseYearChange);
            document.getElementById('comparison-year-filter').addEventListener('change', handleYearChange);
            document.getElementById('base-project-select').addEventListener('change', handleBaseProjectChange);
            document.getElementById('compare-btn').addEventListener('click', handleCompare);

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('comparison-projects-dropdown');
                const display = document.getElementById('comparison-projects-display');

                if (!display.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            });

            async function loadYears() {
                try {
                    const response = await fetch('/api/anios-disponibles');
                    const years = await response.json();

                    // Load years for both filters
                    const baseYearSelect = document.getElementById('base-year-filter');
                    const comparisonYearSelect = document.getElementById('comparison-year-filter');

                    // Clear existing options except the first one
                    baseYearSelect.innerHTML = '<option value="">Selecciona un año...</option>';
                    comparisonYearSelect.innerHTML = '<option value="">Todos los años</option>';

                    years.forEach(year => {
                        // Add to base year filter
                        const baseOption = document.createElement('option');
                        baseOption.value = year;
                        baseOption.textContent = year;
                        baseYearSelect.appendChild(baseOption);

                        // Add to comparison year filter
                        const comparisonOption = document.createElement('option');
                        comparisonOption.value = year;
                        comparisonOption.textContent = year;
                        comparisonYearSelect.appendChild(comparisonOption);
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
                    select.innerHTML = '<option value="">Selecciona un proyecto base...</option>';

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

            async function loadComparisonProjects(baseProjectId = '', selectedYear = '') {
                try {
                    let url = '/api/proyectos-comparar';
                    const params = [];

                    if (selectedYear && selectedYear !== '') {
                        params.push(`ano=${selectedYear}`);
                    }

                    if (baseProjectId) {
                        params.push(`exclude_base=${baseProjectId}`);
                    }

                    if (params.length > 0) {
                        url += '?' + params.join('&');
                    }

                    const response = await fetch(url);
                    allProjects = await response.json();

                    // Reset selection when reloading
                    selectedComparisonProjects = [];

                    renderComparisonProjects();
                } catch (error) {
                    console.error('Error loading comparison projects:', error);
                    allProjects = [];
                    renderComparisonProjects();
                }
            }

            function renderComparisonProjects() {
                const projectsList = document.getElementById('projects-list');
                const selectAllText = document.getElementById('select-all-text');
                const selectAllCheckbox = document.getElementById('select-all-checkbox');

                projectsList.innerHTML = '';

                // Convertir todos los IDs a números para comparación consistente
                const allIds = allProjects.map(p => Number(p.id));
                const selectedIds = selectedComparisonProjects.map(id => Number(id));

                // Verificar si todos están seleccionados
                const allSelected = allIds.length > 0 && allIds.every(id => selectedIds.includes(id));

                // Sincronizar checkbox "Seleccionar Todos"
                selectAllCheckbox.checked = allSelected;
                selectAllText.textContent = allSelected ? 'Desmarcar Todos' : 'Seleccionar Todos';

                allProjects.forEach(proyecto => {
                    const projectId = Number(proyecto.id);

                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'multiselect-option';
                    if (selectedIds.includes(projectId)) {
                        optionDiv.classList.add('selected');
                    }

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.checked = selectedIds.includes(projectId);
                    checkbox.id = `project-${projectId}`;

                    checkbox.addEventListener('change', (e) => {
                        e.stopPropagation();
                        toggleProjectSelection(projectId);
                    });

                    const label = document.createElement('label');
                    label.htmlFor = `project-${projectId}`;
                    label.textContent = `${proyecto.nombre} (${proyecto.ano}) - ${proyecto.total_rows} registros`;

                    optionDiv.addEventListener('click', (e) => {
                        if (e.target !== checkbox) {
                            e.stopPropagation();
                            toggleProjectSelection(projectId);
                        }
                    });

                    optionDiv.appendChild(checkbox);
                    optionDiv.appendChild(label);
                    projectsList.appendChild(optionDiv);
                });

                updateSelectedDisplay();
            }

            function toggleProjectSelection(projectId) {
                projectId = Number(projectId);

                if (selectedComparisonProjects.includes(projectId)) {
                    selectedComparisonProjects = selectedComparisonProjects.filter(id => id !== projectId);
                } else {
                    selectedComparisonProjects.push(projectId);
                }

                renderComparisonProjects();
                updateCompareButton();
            }

            function updateSelectedDisplay() {
                const display = document.getElementById('selected-text');
                const countDiv = document.getElementById('selected-count');
                const countSpan = document.getElementById('selected-number');

                if (selectedComparisonProjects.length === 0) {
                    display.textContent = 'Selecciona proyectos para comparar...';
                    countDiv.style.display = 'none';
                } else if (selectedComparisonProjects.length === 1) {
                    const project = allProjects.find(p => p.id == selectedComparisonProjects[0]);
                    display.textContent = project ? `${project.nombre} (${project.ano})` : '1 proyecto seleccionado';
                    countDiv.style.display = 'none';
                } else if (selectedComparisonProjects.length <= 3) {
                    const projectNames = selectedComparisonProjects.map(id => {
                        const project = allProjects.find(p => p.id == id);
                        return project ? project.nombre : 'Proyecto';
                    });
                    display.textContent = projectNames.join(', ');
                    countDiv.style.display = 'none';
                } else {
                    display.textContent = `${selectedComparisonProjects.length} proyectos seleccionados`;
                    countSpan.textContent = selectedComparisonProjects.length;
                    countDiv.style.display = 'block';
                }
            }

            async function handleYearChange(event) {
                const selectedYear = event.target.value;
                const baseProjectId = document.getElementById('base-project-select').value;

                // Reset project selection when year changes
                selectedComparisonProjects = [];

                if (selectedYear === 'todos') {
                    await loadComparisonProjects(baseProjectId, '');
                } else {
                    await loadComparisonProjects(baseProjectId, selectedYear);
                }

                updateCompareButton();
                updateSelectedDisplay();
            }

            async function handleBaseYearChange(event) {
                const selectedYear = event.target.value;

                // Reset base project selection when year changes
                document.getElementById('base-project-select').value = '';

                if (selectedYear) {
                    // Load base projects for the selected year
                    await loadProjects(selectedYear);
                } else {
                    // Clear base projects if no year is selected
                    const select = document.getElementById('base-project-select');
                    select.innerHTML = '<option value="">Primero selecciona un año...</option>';
                }

                updateCompareButton();
            }

            async function handleBaseProjectChange(event) {
                const baseProjectId = event.target.value;

                if (baseProjectId) {
                    const currentYear = document.getElementById('comparison-year-filter').value;
                    await loadComparisonProjects(baseProjectId, currentYear);
                }

                updateCompareButton();
            }

            function updateCompareButton() {
                const baseProject = document.getElementById('base-project-select').value;
                const compareBtn = document.getElementById('compare-btn');

                if (baseProject && selectedComparisonProjects.length > 0) {
                    compareBtn.disabled = false;
                } else {
                    compareBtn.disabled = true;
                }
            }

            async function handleCompare() {
                const baseProjectId = document.getElementById('base-project-select').value;

                if (!baseProjectId) {
                    alert('Selecciona un proyecto base');
                    return;
                }

                if (selectedComparisonProjects.length === 0) {
                    alert('Selecciona al menos un proyecto para comparar');
                    return;
                }

                try {
                    showLoading();

                    let url = `/api/proyectos/${baseProjectId}/comparar`;
                    const params = new URLSearchParams();

                    params.append('proyectos_comparacion', selectedComparisonProjects.join(','));

                    if (params.toString()) {
                        url += '?' + params.toString();
                    }

                    const response = await fetch(url);
                    const data = await response.json();

                    renderResults(data);
                } catch (error) {
                    console.error('Error comparing projects:', error);
                    showError('Error al comparar proyectos');
                }
            }



            function renderResults(data) {
                // Store results globally for modal access
                window.currentResults = data.results;

                document.getElementById('total-personas-base').textContent = data.stats.total_personas_base;
                document.getElementById('total-proyectos-comparacion').textContent = data.stats.total_proyectos_comparacion;
                document.getElementById('personas-multiples').textContent = data.stats.personas_con_multiples_proyectos;
                document.getElementById('total-coincidencias').textContent = data.stats.total_coincidencias;

                document.getElementById('stats-container').style.display = 'grid';
                document.getElementById('results-container').style.display = 'grid';
                document.getElementById('initial-state').style.display = 'none';

                renderResultsContent(data.results);
            }

            function renderResultsContent(results) {
                const container = document.getElementById('results-content');

                if (results.length === 0) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <h4>No se encontraron coincidencias</h4>
                            <p>No se encontraron personas inscritas en múltiples proyectos con los criterios seleccionados.</p>
                        </div>
                    `;
                    return;
                }

                let html = '';

                results.forEach((result, index) => {
                    html += `
                        <div class="person-card">
                            <div class="person-header">
                                <div class="person-info">
                                    <h4>${result.nombre_completo}</h4>
                                    <p>Documento: ${result.documento}</p>
                                </div>
                                <div class="projects-count">
                                    ${result.total_proyectos_adicionales} proyecto(s) adicional(es)
                                </div>
                            </div>
                            <div class="projects-list">
                    `;

                    html += `
                        <div class="project-item">
                            <div class="project-name">${result.base_proyecto.nombre} (Base)</div>
                            <div class="project-year">Año: ${result.base_proyecto.ano}</div>
                            <button class="expand-btn" onclick="toggleTable(${index}, 'base')">
                                <i class="fas fa-eye me-1"></i>Ver datos
                            </button>
                        </div>
                    `;

                    result.proyectos_encontrados.forEach((proyectoEncontrado, projIndex) => {
                        html += `
                            <div class="project-item">
                                <div class="project-name">${proyectoEncontrado.proyecto.nombre}</div>
                                <div class="project-year">Año: ${proyectoEncontrado.proyecto.ano}</div>
                                <button class="expand-btn" onclick="toggleTable(${index}, 'proj-${projIndex}')">
                                    <i class="fas fa-eye me-1"></i>Ver datos
                                </button>
                            </div>
                        `;
                    });

                    html += `
                            </div>
                        </div>
                    `;
                });

                container.innerHTML = html;
            }

            function renderTable(headers, rows) {
                if (!headers || !rows || rows.length === 0) {
                    return '<p>No hay datos disponibles</p>';
                }

                let html = '<table><thead><tr>';

                headers.forEach(header => {
                    html += `<th>${header}</th>`;
                });

                html += '</tr></thead><tbody>';

                rows.forEach(row => {
                    html += '<tr>';
                    headers.forEach(header => {
                        const value = row[header] || '';
                        html += `<td>${value}</td>`;
                    });
                    html += '</tr>';
                });

                html += '</tbody></table>';

                return html;
            }

            function showLoading() {
                document.getElementById('results-content').innerHTML = `
                    <div style="text-align: center; padding: 2rem;">
                        <div class="loading-spinner me-2"></div>
                        Comparando proyectos...
                    </div>
                `;
            }

            function showError(message) {
                alert(message);
            }

            // Hacer funciones accesibles globalmente
            window.renderComparisonProjects = renderComparisonProjects;
            window.updateCompareButton = updateCompareButton;
        });

        // Función global para toggle de multiselect
        function toggleMultiselect() {
            const dropdown = document.getElementById('comparison-projects-dropdown');
            dropdown.classList.toggle('show');
        }

        // Función global para seleccionar/deseleccionar todos
        function selectAllProjects() {
            const checkbox = document.getElementById('select-all-checkbox');
            const allIds = allProjects.map(p => Number(p.id));
            
            if (checkbox.checked) {
                selectedComparisonProjects = [...allIds];
            } else {
                selectedComparisonProjects = [];
            }

            renderComparisonProjects();
            updateCompareButton();
        }

        // Función global para mostrar tabla en modal
        function toggleTable(personIndex, tableType) {
            // Obtener datos del resultado correspondiente
            const results = window.currentResults;
            if (!results || !results[personIndex]) {
                console.error('No se encontraron datos para el índice:', personIndex);
                return;
            }

            const result = results[personIndex];
            let tableData = null;
            let projectName = '';
            let projectYear = '';

            if (tableType === 'base') {
                tableData = {
                    headers: result.base_headers,
                    rows: [result.base_row_data]
                };
                projectName = result.base_proyecto.nombre;
                projectYear = result.base_proyecto.ano;
            } else if (tableType.startsWith('proj-')) {
                const projIndex = parseInt(tableType.split('-')[1]);
                const proyectoEncontrado = result.proyectos_encontrados[projIndex];
                if (proyectoEncontrado) {
                    tableData = {
                        headers: proyectoEncontrado.headers,
                        rows: [proyectoEncontrado.row_data]
                    };
                    projectName = proyectoEncontrado.proyecto.nombre;
                    projectYear = proyectoEncontrado.proyecto.ano;
                }
            }

            if (tableData) {
                openTableModal(projectName, projectYear, tableData);
            }
        }

        // Función para abrir el modal con la tabla
        function openTableModal(projectName, projectYear, tableData) {
            const modal = document.getElementById('tableModal');
            const titleElement = document.getElementById('tableModalTitle');
            const contentElement = document.getElementById('modalTableContent');

            // Actualizar título del modal
            titleElement.textContent = `${projectName} (${projectYear})`;

            // Generar contenido de la tabla
            contentElement.innerHTML = renderModalTable(tableData.headers, tableData.rows);

            // Mostrar modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Agregar event listener para cerrar con ESC
            document.addEventListener('keydown', handleModalKeydown);
        }

        // Función para cerrar el modal
        function closeTableModal() {
            const modal = document.getElementById('tableModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';

            // Remover event listener
            document.removeEventListener('keydown', handleModalKeydown);
        }

        // Función para manejar teclas en el modal
        function handleModalKeydown(event) {
            if (event.key === 'Escape') {
                closeTableModal();
            }
        }

        // Función para renderizar tabla en el modal
        function renderModalTable(headers, rows) {
            if (!headers || !rows || rows.length === 0) {
                return '<p>No hay datos disponibles</p>';
            }

            let html = '<table><thead><tr>';

            headers.forEach(header => {
                html += `<th>${header}</th>`;
            });

            html += '</tr></thead><tbody>';

            rows.forEach(row => {
                html += '<tr>';
                headers.forEach(header => {
                    const value = row[header] || '';
                    html += `<td>${value}</td>`;
                });
                html += '</tr>';
            });

            html += '</tbody></table>';

            return html;
        }
    </script>
</x-app-layout>

<x-app-layout>

    @vite(['resources/css/pages/proyectos-productivos/show.css'])

    <div class="py-3">
        <div class="container-fluid px-4">

            {{-- Header con información del proyecto --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="mb-2" style="color: #2d5f3f; font-weight: 600;">
                            <i class="bi bi-table me-2"></i>{{ $proyecto->nombre }}
                        </h3>
                        <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.9rem;">
                            <span><strong class="text-dark">Columnas:</strong> {{ $proyecto->data['total_columns'] ?? 0 }}</span>
                            <span><strong class="text-dark">Filas:</strong> {{ $proyecto->data['total_rows'] ?? 0 }}</span>
                            @if(isset($proyecto->data['uploaded_at']))
                                <span> <strong class="text-dark">Actualizado:</strong> {{ \Carbon\Carbon::parse($proyecto->data['uploaded_at'])->format('d/m/Y H:i:s') }}</span>
                            @endif
                            @if(isset($proyecto->data['uploaded_by']))
                                <span><i class="bi bi-person-circle text-dark"></i> <strong class="text-dark">Por:</strong> {{ $proyecto->data['uploaded_by'] }}</span>
                            @endif
                        </div>
                        @if($proyecto->descripcion)
                            <p class="text-muted mb-0 mt-2" style="font-size: 0.9rem;">
                                <i class="bi bi-info-circle"></i> {{ $proyecto->descripcion }}
                            </p>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('proyectos.por-ano', $proyecto->ano) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>

            {{-- Tabla principal con controles integrados --}}
            <div class="card shadow-sm border-0" id="tableCard">
                
                {{-- Header de la tabla con controles --}}
                <div class="card-header bg-gradient text-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 justify-content-end">
                                <div class="input-group" style="max-width: 300px;">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="searchInput"
                                           placeholder="Buscar en tabla...">
                                </div>
                                <form action="{{ route('proyectos.update-automatic-columns', $proyecto) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" title="Actualizar columnas automáticas">
                                        <i class="bi bi-arrow-clockwise"></i> Actualizar
                                    </button>
                                </form>
                                <button class="btn btn-light" id="exportBtn" title="Exportar a Excel">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button class="btn btn-light" id="toggleFullscreen" title="Pantalla completa">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                </div>

                {{-- Contenedor de la tabla --}}
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover mb-0" id="dataTable">
                            <thead class="table-header sticky-header">
                                {{-- Fila de filtros --}}
                                <tr class="filter-row">
                                    <th class="text-center column-index filter-header">
                                        <i class="bi bi-funnel text-white"></i>
                                    </th>
                                    @if(isset($proyecto->data['headers']))
                                        @foreach($proyecto->data['headers'] as $header)
                                            <th class="text-center filter-header">
                                                @php
                                                    $genderColumn = isset($filterData['Genero']) ? app('App\Http\Controllers\ProyectoProductivoController')->findGenderColumn($proyecto->data['headers'] ?? []) : null;
                                                @endphp
                                                @if($header === $genderColumn && isset($filterData['Genero']) && !empty($filterData['Genero']))
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-white p-0 dropdown-toggle" type="button"
                                                                id="generoFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-funnel-fill filter-icon" data-column="{{ $genderColumn }}"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="generoFilter">
                                                            <li><h6 class="dropdown-header">Filtrar por {{ $header }}</h6></li>
                                                            @foreach($filterData['Genero'] as $genero)
                                                            <li>
                                                                <label class="dropdown-item">
                                                                    <input type="checkbox" class="form-check-input me-2 column-filter"
                                                                           data-column="{{ $genderColumn }}" value="{{ $genero }}">
                                                                    {{ $genero }}
                                                                </label>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @elseif($header === 'Corregimiento_CZ')
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-white p-0 dropdown-toggle" type="button"
                                                                id="corregimientoFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-funnel-fill filter-icon" data-column="Corregimiento_CZ"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="corregimientoFilter">
                                                            <li><h6 class="dropdown-header">Filtrar por Corregimiento</h6></li>
                                                            @foreach($corregimientos as $id => $nombre)
                                                            <li>
                                                                <label class="dropdown-item">
                                                                    <input type="checkbox" class="form-check-input me-2 column-filter"
                                                                           data-column="Corregimiento_CZ" value="{{ $id }}">
                                                                    {{ $nombre }}
                                                                </label>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @elseif($header === 'Vereda_CZ')
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-white p-0 dropdown-toggle" type="button"
                                                                id="veredaFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-funnel-fill filter-icon" data-column="Vereda_CZ"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="veredaFilter">
                                                            <li><h6 class="dropdown-header">Filtrar por Vereda</h6></li>
                                                            @foreach($veredas as $vereda)
                                                            <li>
                                                                <label class="dropdown-item">
                                                                    <input type="checkbox" class="form-check-input me-2 column-filter"
                                                                           data-column="Vereda_CZ" value="{{ $vereda }}">
                                                                    {{ $vereda }}
                                                                </label>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @elseif($header === 'Estado_Caracterizacion')
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-white p-0 dropdown-toggle" type="button"
                                                                id="estadoFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-funnel-fill filter-icon" data-column="Estado_Caracterizacion"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="estadoFilter">
                                                            <li><h6 class="dropdown-header">Filtrar por Estado CZ</h6></li>
                                                            @if(isset($filterData['Estado_Caracterizacion']) && !empty($filterData['Estado_Caracterizacion']))
                                                                @foreach($filterData['Estado_Caracterizacion'] as $estado)
                                                                <li>
                                                                    <label class="dropdown-item">
                                                                        <input type="checkbox" class="form-check-input me-2 column-filter"
                                                                               data-column="Estado_Caracterizacion" value="{{ $estado }}">
                                                                        {{ $estado }}
                                                                    </label>
                                                                </li>
                                                                @endforeach
                                                            @else
                                                                <li><span class="dropdown-item-text text-muted">No hay datos para filtrar</span></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif
                                            </th>
                                        @endforeach
                                    @endif
                                </tr>
                                {{-- Fila de headers --}}
                                <tr>
                                    <th class="text-center column-index">#</th>
                                    @if(isset($proyecto->data['headers']))
                                        @foreach($proyecto->data['headers'] as $header)
                                            <th class="text-center">{{ $header }}</th>
                                        @endforeach
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($proyecto->data['rows']) && count($proyecto->data['rows']) > 0)
                                    @foreach($proyecto->data['rows'] as $index => $row)
                                        <tr class="data-row">
                                            <td class="text-center column-index">{{ $index + 1 }}</td>
                                            @if(isset($proyecto->data['headers']))
                                                @foreach($proyecto->data['headers'] as $header)
                                                    <td class="text-center">
                                                        {{ $row[$header] ?? '-' }}
                                                    </td>
                                                @endforeach
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="{{ (isset($proyecto->data['headers']) ? count($proyecto->data['headers']) : 0) + 1 }}"
                                            class="text-center py-5">
                                            <i class="bi bi-exclamation-triangle text-warning display-4 d-block mb-3"></i>
                                            <span class="text-muted fs-5">No hay datos para mostrar</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer con información --}}
                <div class="card-footer bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-eye"></i> Mostrando 
                            <strong id="showingCount">{{ count($proyecto->data['rows'] ?? []) }}</strong> 
                            de <strong>{{ $proyecto->data['total_rows'] ?? 0 }}</strong> registros
                        </small>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Usa <kbd>Ctrl</kbd> + <kbd>F</kbd> para buscar
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Estilos optimizados --}}
    

    {{-- JavaScript para funcionalidades --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleFullscreen = document.getElementById('toggleFullscreen');
            const tableCard = document.getElementById('tableCard');
            const searchInput = document.getElementById('searchInput');
            const dataRows = document.querySelectorAll('.data-row');
            const exportBtn = document.getElementById('exportBtn');
            const showingCount = document.getElementById('showingCount');

            // Pantalla completa
            if (toggleFullscreen) {
                toggleFullscreen.addEventListener('click', function() {
                    const container = tableCard.closest('.container-fluid');
                    container.classList.toggle('fullscreen-mode');
                    
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('bi-arrows-fullscreen')) {
                        icon.classList.remove('bi-arrows-fullscreen');
                        icon.classList.add('bi-fullscreen-exit');
                        this.setAttribute('title', 'Salir de pantalla completa');
                    } else {
                        icon.classList.remove('bi-fullscreen-exit');
                        icon.classList.add('bi-arrows-fullscreen');
                        this.setAttribute('title', 'Pantalla completa');
                    }
                });
            }

            // Búsqueda en tabla con highlight
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    let visibleCount = 0;

                    dataRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (searchTerm === '' || text.includes(searchTerm)) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (showingCount) {
                        showingCount.textContent = visibleCount;
                    }
                });
            }

            // Exportar a Excel
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    // Recopilar filtros activos
                    const activeFilters = {};
                    const columnFilters = document.querySelectorAll('.column-filter');

                    columnFilters.forEach(checkbox => {
                        if (checkbox.checked) {
                            const column = checkbox.getAttribute('data-column');
                            const value = checkbox.value;

                            if (!activeFilters[column]) {
                                activeFilters[column] = [];
                            }
                            activeFilters[column].push(value);
                        }
                    });

                    // Obtener término de búsqueda actual
                    const searchTerm = searchInput ? searchInput.value.trim() : '';

                    // Construir URL con parámetros de filtros y búsqueda
                    let exportUrl = '{{ route("proyectos.export-excel", $proyecto) }}';
                    const params = new URLSearchParams();

                    // Agregar filtros de columna
                    for (const [column, values] of Object.entries(activeFilters)) {
                        values.forEach(value => {
                            params.append(`filters[${column}][]`, value);
                        });
                    }

                    // Agregar término de búsqueda si existe
                    if (searchTerm) {
                        params.append('search', searchTerm);
                    }

                    if (params.toString()) {
                        exportUrl += '?' + params.toString();
                    }

                    // Redireccionar a la URL de exportación con filtros y búsqueda
                    window.location.href = exportUrl;

                    // Feedback visual
                    const originalHTML = exportBtn.innerHTML;
                    exportBtn.innerHTML = '<i class="bi bi-check-circle"></i>';
                    exportBtn.classList.add('btn-success');
                    exportBtn.classList.remove('btn-light');

                    setTimeout(() => {
                        exportBtn.innerHTML = originalHTML;
                        exportBtn.classList.remove('btn-success');
                        exportBtn.classList.add('btn-light');
                    }, 2000);
                });
            }

            // Atajos de teclado
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + F para buscar
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                }
                
                // Esc para salir de pantalla completa
                if (e.key === 'Escape') {
                    const container = tableCard.closest('.container-fluid');
                    if (container.classList.contains('fullscreen-mode')) {
                        toggleFullscreen.click();
                    }
                }

                // F11 para pantalla completa
                if (e.key === 'F11') {
                    e.preventDefault();
                    toggleFullscreen.click();
                }
            });

            // Tooltip en botones
            const buttons = document.querySelectorAll('[title]');
            buttons.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.cursor = 'pointer';
                });
            });

            // Auto-focus en búsqueda con un pequeño delay
            setTimeout(() => {
                if (searchInput && dataRows.length > 0) {
                    searchInput.focus();
                }
            }, 500);

            // Sistema de filtros por columna
            const columnFilters = document.querySelectorAll('.column-filter');
            const dataTableRows = document.querySelectorAll('.data-row');

            // Función para actualizar iconos de filtro activos
            function updateFilterIcons() {
                const filterIcons = document.querySelectorAll('.filter-icon');

                filterIcons.forEach(icon => {
                    const column = icon.getAttribute('data-column');
                    const columnCheckboxes = document.querySelectorAll(`.column-filter[data-column="${column}"]`);
                    const hasActiveFilters = Array.from(columnCheckboxes).some(checkbox => checkbox.checked);

                    if (hasActiveFilters) {
                        icon.classList.add('filter-active');
                    } else {
                        icon.classList.remove('filter-active');
                    }
                });
            }

            // Función para aplicar filtros
            function applyColumnFilters() {
                const activeFilters = {};

                // Recopilar filtros activos
                columnFilters.forEach(checkbox => {
                    if (checkbox.checked) {
                        const column = checkbox.getAttribute('data-column');
                        const value = checkbox.value;

                        if (!activeFilters[column]) {
                            activeFilters[column] = [];
                        }
                        activeFilters[column].push(value);
                    }
                });

                // Aplicar filtros a las filas
                let visibleCount = 0;
                dataTableRows.forEach(row => {
                    let shouldShow = true;

                    // Verificar cada filtro activo
                    for (const [column, values] of Object.entries(activeFilters)) {
                        const cellIndex = getColumnIndex(column);
                        if (cellIndex !== -1) {
                            const cell = row.cells[cellIndex + 1]; // +1 porque la primera columna es el índice
                            const cellValue = cell ? cell.textContent.trim() : '';

                            let compareValue = cellValue;

                            if (!values.includes(compareValue)) {
                                shouldShow = false;
                                break;
                            }
                        }
                    }

                    // También aplicar filtro de búsqueda si existe
                    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
                    if (searchTerm && shouldShow) {
                        const text = row.textContent.toLowerCase();
                        shouldShow = text.includes(searchTerm);
                    }

                    row.style.display = shouldShow ? '' : 'none';
                    if (shouldShow) visibleCount++;
                });

                // Actualizar contador
                if (showingCount) {
                    showingCount.textContent = visibleCount;
                }

                // Actualizar iconos de filtro
                updateFilterIcons();
            }

            // Función para obtener índice de columna
            function getColumnIndex(columnName) {
                const headers = @json($proyecto->data['headers'] ?? []);
                return headers.indexOf(columnName);
            }

            // Event listeners para filtros
            columnFilters.forEach(checkbox => {
                checkbox.addEventListener('change', applyColumnFilters);
            });

            // También actualizar cuando cambie la búsqueda
            if (searchInput) {
                const originalSearchHandler = searchInput.oninput;
                searchInput.addEventListener('input', function() {
                    // Primero aplicar búsqueda original si existe
                    if (originalSearchHandler) {
                        originalSearchHandler.call(this);
                    }
                    // Luego aplicar filtros de columna
                    applyColumnFilters();
                });
            }

            // Inicializar iconos de filtro al cargar la página
            updateFilterIcons();
        });
    </script>

</x-app-layout>

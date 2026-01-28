@props([
    'caracterizacion',
    'headers' => [],
    'rows' => [],
    'filterData' => [],
    'hasData' => false,
    'pagination' => null
])

@if($hasData)
<div class="data-card">
    {{-- Header de la tabla con controles --}}
    <div class="card-header text-black py-2">
        <div class="row align-items-center">
            
            <div class="col-md-12">
                <div class="d-flex gap-2 justify-content-end">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" id="searchInput"
                               placeholder="Buscar en tabla...">
                    </div>
                    <button class="btn btn-light" id="toggleFilters" title="Activar filtros">
                        <i class="bi bi-funnel"></i> Activar filtros
                    </button>
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

    <div class="table-responsive">
        <table class="table">
            <thead>
                {{-- Fila de filtros --}}
                <tr class="filter-row d-none">
                    @if(!empty($headers))
                        @foreach($headers as $header)
                            <th class="text-center filter-header">
                                @if(isset($filterData[$header]) && !empty($filterData[$header]))
                                    <div class="dropdown">
                                        <button class=" text-white p-0 " type="button"
                                                id="{{ $header }}Filter" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-caret-down-fill filter-icon" data-column="{{ $header }}"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="{{ $header }}Filter">
                                            <li><h6 class="dropdown-header">Filtrar por {{ $header }}</h6></li>
                                            @foreach($filterData[$header] as $value)
                                            <li>
                                                <label class="dropdown-item">
                                                    <input type="checkbox" class="form-check-input me-2 column-filter"
                                                           data-column="{{ $header }}" value="{{ $value }}">
                                                    {{ $value }}
                                                </label>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </th>
                        @endforeach
                    @endif
                </tr>
                {{-- Fila de headers --}}
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="table-body">
                {{-- Render inicial: primeras 25 filas para carga inmediata --}}
                @php
                    $initialRows = array_slice($rows, 0, 25);
                    $remainingCount = count($rows) - count($initialRows);
                @endphp

                @foreach($initialRows as $row)
                    <tr>
                        @foreach($headers as $header)
                            <td>{{ $row[$header] ?? '' }}</td>
                        @endforeach
                    </tr>
                @endforeach

                {{-- Placeholder para filas restantes --}}
                @if($remainingCount > 0)
                <tr class="table-loading-placeholder">
                    <td colspan="{{ count($headers) }}" class="text-center py-3">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <small class="text-muted">Cargando {{ $remainingCount }} registros adicionales...</small>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- REGISTROS COUNTER --}}
<div class="records-counter mt-3">
    <div class="d-flex justify-content-between align-items-center">
        <div id="records-info" class="text-muted" style="font-size: 0.9rem;">
            <!-- Se actualizará con JavaScript -->
        </div>
        <div class="pagination-wrapper">
            {{-- PAGINATION --}}
            @if($pagination && $pagination->hasPages())
                <div class="pagination-container">
                    {{ $pagination->links() }}
                </div>
            @else
                <div class="pagination-container" id="client-pagination">
                    <!-- Paginación cliente-side se insertará aquí -->
                </div>
            @endif
        </div>
    </div>
</div>

@else
{{-- UPLOAD FORM --}}
<div class="upload-card">
    <div class="upload-icon">
        <i class="fas fa-upload"></i>
    </div>
    <h2 class="upload-title">Subir Base de Datos de Caracterizaciones</h2>
    <p class="upload-description">
        Carga un archivo Excel con los datos de caracterización rural. La primera fila debe contener los encabezados de las columnas.
    </p>

    <form action="{{ route('caracterizaciones.process-excel', $caracterizacion) }}" method="POST" enctype="multipart/form-data" class="upload-form">
               class="form-control"
               accept=".xlsx,.xls"
               required>
        <button type="submit" class="upload-btn">
            <i class="fas fa-upload me-2"></i>Subir Excel
        </button>
    </form>
</div>
@endif

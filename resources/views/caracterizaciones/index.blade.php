<x-app-layout>
   
    @vite(['resources/css/pages/caracterizaciones/index.css'])

    <div class="caracterizacion-container">
        <div class="content-wrapper">

            {{-- === HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>{{ $caracterizacion->nombre }}</h1>
                    <p>Base de datos global de caracterizaciones rurales</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                    @if($hasData)
                        <a href="{{ route('caracterizaciones.upload-excel', $caracterizacion) }}" class="btn btn-primary">
                            <i class="fas fa-upload me-1"></i>Subir Caracterización
                        </a>
                        <a href="{{ route('caracterizaciones.formulario.show') }}" class="btn btn-secondary">
                            <i class="fas fa-plus me-1"></i>Crear CZ Manual
                        </a>
                    @endif
                </div>
            </div>

            {{-- === INFO CARD === --}}
            @if($hasData)
            <div class="info-card">
                <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.9rem;">
                    <span><strong class="text-dark">Total de Caracterizaciones:</strong> {{ $caracterizacion->data['total_rows'] ?? 0 }}</span>
                    <span><strong class="text-dark">Página Actual:</strong> {{ $pagination ? $pagination->currentPage() : 1 }} de {{ $pagination ? $pagination->lastPage() : 1 }}</span>
                    <span><strong class="text-dark">Registros por Página:</strong> {{ $pagination ? $pagination->perPage() : 50 }}</span>
                    <span><strong class="text-dark">Archivo:</strong> {{ $caracterizacion->data['filename'] ?? 'N/A' }}</span>
                    @if(isset($caracterizacion->data['uploaded_at']))
                        <span><strong class="text-dark">Última actualización:</strong> {{ \Carbon\Carbon::parse($caracterizacion->data['uploaded_at'])->format('d/m/Y H:i') }}</span>
                    @endif
                    @if(isset($caracterizacion->data['uploaded_by']))
                        <span><i class="bi bi-person-circle text-dark"></i> <strong class="text-dark">Por:</strong> {{ $caracterizacion->data['uploaded_by'] }}</span>
                    @endif
                </div>
            </div>
            @endif

            {{-- === CONTENT === --}}
            <x-tabla-caracterizaciones
                :caracterizacion="$caracterizacion"
                :headers="$headers"
                :rows="$paginatedRows"
                :filterData="$filterData"
                :hasData="$hasData"
                :pagination="$pagination"
            />

        </div>
    </div>

    {{-- === MODAL GOV.CO - Confirmación === --}}
    <div class="container-modal-govco" id="modalConfirmacionContainer">
        <div class="modal-backdrop-govco"></div>
        <div class="modal-container-govco" id="exampleModalConfirmacion" tabindex="-1" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-govco">
                <div class="modal-content modal-content-govco">
                    <div class="modal-header modal-header-govco">
                        <button type="button" class="btn-close" aria-label="cerrar"></button>
                    </div>
                    <div class="modal-body modal-body-govco center-elements-govco">
                        <div class="modal-icon">
                            <span class="govco-icon govco-info-circle"></span>
                        </div>
                        <h3 class="modal-title-govco confirmation-govco">
                            ¿Eliminar Caracterización?
                        </h3>
                        <p class="modal-text-govco modal-text-center-govco">
                            ¿Está seguro de que desea eliminar esta caracterización? Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="modal-footer-govco">
                        <div class="modal-buttons-govco">
                            <button type="button" class="btn-modal-govco btn-eliminar-confirmar">
                                Eliminar
                            </button>
                            <button type="button" class="btn-modal-govco btn-contorno btn-eliminar-cancelar">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === MODAL GOV.CO - Éxito === --}}
    <div class="container-modal-govco" id="modalExitoContainer">
        <div class="modal-backdrop-govco"></div>
        <div class="modal-container-govco" id="exampleModalExito" tabindex="-1" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-govco">
                <div class="modal-content modal-content-govco">
                    <div class="modal-header modal-header-govco">
                        <button type="button" class="btn-close" aria-label="cerrar"></button>
                    </div>
                    <div class="modal-body modal-body-govco center-elements-govco">
                        <div class="modal-icon">
                            <span class="govco-icon govco-check-circle"></span>
                        </div>
                        <h3 class="modal-title-govco success-govco">
                            ¡Operación Exitosa!
                        </h3>
                        <p class="modal-text-govco modal-text-center-govco">
                            La operación se realizó correctamente.
                        </p>
                    </div>
                    <div class="modal-footer-govco">
                        <div class="modal-buttons-govco" style="justify-content: center;">
                            <button type="button" class="btn-modal-govco btn-exito-aceptar">
                                Aceptar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === JAVASCRIPT CONFIG === --}}
    <script>
        // Configuración global para el JavaScript de la tabla
        window.CaracterizacionesConfig = {
            ajaxUrl: '{{ route("caracterizaciones.ajax.filter-data") }}',
            exportUrl: '{{ route("caracterizaciones.export-excel", $caracterizacion) }}',
            headers: @json($headers ?? []),
            headersCount: @json(count($headers ?? [])),
            caracterizacionId: '{{ $caracterizacion->id }}'
        };
    </script>

    {{-- Datos de tabla para JavaScript cliente-side --}}
    @if($hasData)
    <script data-table-data type="application/json">
        @json([
            'rows' => $paginatedRows,
            'headers' => $headers,
            'total_rows' => count($paginatedRows)
        ])
    </script>
    @endif

    {{-- Cargar JavaScript de tabla de forma asíncrona --}}
    @vite(['resources/js/caracterizaciones-table.js', 'resources/js/caracterizaciones-upload.js'])
</x-app-layout>
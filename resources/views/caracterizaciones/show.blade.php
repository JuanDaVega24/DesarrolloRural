<x-app-layout>

    <div class="caracterizacion-container">
        <div class="content-wrapper">

            {{-- === HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>{{ $caracterizacion->nombre }}</h1>
                    <p>Datos de caracterización rural - {{ $caracterizacion->data['total_rows'] ?? 0 }} registros</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('caracterizaciones.export-excel', $caracterizacion) }}" class="btn btn-secondary">
                        <i class="fas fa-download me-1"></i>Exportar Excel
                    </a>
                    <a href="{{ route('caracterizaciones.upload-excel', $caracterizacion) }}" class="btn btn-secondary">
                        <i class="fas fa-upload me-1"></i>Actualizar Datos
                    </a>
                    <a href="{{ route('caracterizaciones.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>

            {{-- === INFO CARD === --}}
            <div class="info-card">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">ID de Caracterización</div>
                        <div class="info-value">#{{ $caracterizacion->id }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Año</div>
                        <div class="info-value">
                            @if($caracterizacion->ano)
                                <span class="badge bg-success">{{ $caracterizacion->ano }}</span>
                            @else
                                <span class="badge bg-warning">Sin año</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Total de Registros</div>
                        <div class="info-value">{{ $caracterizacion->data['total_rows'] ?? 0 }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Total de Columnas</div>
                        <div class="info-value">{{ $caracterizacion->data['total_columns'] ?? 0 }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Archivo Original</div>
                        <div class="info-value">{{ $caracterizacion->data['filename'] ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Subido por</div>
                        <div class="info-value">{{ $caracterizacion->data['uploaded_by'] ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Fecha de subida</div>
                        <div class="info-value">
                            @if(isset($caracterizacion->data['uploaded_at']))
                                {{ \Carbon\Carbon::parse($caracterizacion->data['uploaded_at'])->format('d/m/Y H:i') }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- === DATA TABLE === --}}
            <div class="data-card">
                <div class="data-header">
                    <h4><i class="fas fa-table me-2"></i>Datos de la Caracterización</h4>
                    <span>{{ count($rows) }} filas × {{ count($headers) }} columnas</span>
                </div>

                <div class="table-responsive">
                    @if(!empty($rows))
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach($headers as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        @foreach($headers as $header)
                                            @php
                                                $val = $row[$header] ?? '';
                                                $isUrl = is_string($val) && filter_var($val, FILTER_VALIDATE_URL);
                                                $isImage = $isUrl && preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)(\?.*)?$/i', $val);
                                            @endphp
                                            <td>
                                                @if($isUrl)
                                                    <a href="{{ $val }}" target="_blank" rel="noopener" @if($isImage) download @endif>
                                                        {{ $isImage ? 'Descargar imagen' : $val }}
                                                    </a>
                                                @else
                                                    {{ $val }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <h4>No hay datos disponibles</h4>
                            <p>Esta caracterización aún no tiene datos cargados desde Excel.</p>
                            <a href="{{ route('caracterizaciones.upload-excel', $caracterizacion) }}" class="btn btn-primary mt-3">
                                <i class="fas fa-upload me-1"></i>Subir Excel
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</x-app-layout>

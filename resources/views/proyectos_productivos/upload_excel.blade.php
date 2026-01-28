<x-app-layout>

    @vite(['resources/css/pages/proyectos-productivos/upload_excel.css'])

    <div class="d-flex justify-content-center mt-4 mb-5">
        <div class="login-box" style="max-width:600px; width:100%;">

            {{-- Logo --}}
            <div class="escudo">
                <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo">
            </div>

            <h2 class="login-title">Subir Excel - {{ $proyecto->nombre }}</h2>
            <p class="login-subtitle">Cargue un archivo Excel para este proyecto productivo</p>

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-2">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Información del proyecto --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title text-success">
                        <i class="bi bi-info-circle me-2"></i>Información del Proyecto
                    </h6>
                    <p class="mb-1"><strong>ID:</strong> {{ $proyecto->id }}</p>
                    <p class="mb-1"><strong>Nombre:</strong> {{ $proyecto->nombre }}</p>
                    <p class="mb-1"><strong>Estado:</strong>
                        <span class="badge"
                              style="background-color: {{ match($proyecto->estado) {
                                  'Activo' => '#28a745',
                                  'Inactivo' => '#6c757d',
                                  'Finalizado' => '#007bff',
                                  default => '#6c757d'
                              } }};">
                            {{ $proyecto->estado ?? 'Sin estado' }}
                        </span>
                    </p>
                    @if($proyecto->descripcion)
                        <p class="mb-0"><strong>Descripción:</strong> {{ $proyecto->descripcion }}</p>
                    @endif
                </div>
            </div>

            {{-- Formulario de subida --}}
            <form action="{{ route('proyectos.process-excel', $proyecto) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Archivo Excel --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold text-success">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Seleccionar archivo Excel
                    </label>
                    <input type="file"
                           name="excel_file"
                           class="form-control"
                           accept=".xlsx,.xls"
                           required>
                    <small class="text-muted d-block mt-1">
                        Formatos permitidos: .xlsx, .xls (máximo 10MB)
                    </small>
                </div>

                {{-- Información importante --}}
                <div class="alert alert-info">
                    <h6><i class="bi bi-lightbulb me-1"></i> Instrucciones importantes:</h6>
                    <ul class="mb-0 small">
                        <li>La primera fila del Excel debe contener los encabezados de las columnas</li>
                        <li>Las filas vacías serán ignoradas automáticamente</li>
                        <li>Los datos se almacenarán de forma dinámica para mostrar en tabla</li>
                        <li>Si ya existen datos, serán reemplazados por los nuevos</li>
                    </ul>
                </div>

                {{-- Botones --}}
                <div class="d-flex gap-2">
                    <button class="btn-login flex-fill">
                        <i class="bi bi-upload me-2"></i> Subir Excel
                    </button>

                    <a href="{{ route('proyectos.index') }}"
                       class="btn btn-secondary flex-fill d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-left me-2"></i> Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- ESTILOS REUTILIZADOS --}}
    <style>
       
    </style>

</x-app-layout>
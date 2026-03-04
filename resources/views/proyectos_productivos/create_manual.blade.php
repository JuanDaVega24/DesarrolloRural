<x-app-layout>


    <div class="form-container">
        <div class="form-card">
            {{-- Información del proyecto --}}
            <div class="project-info">
                <i class="fas fa-seedling"></i>
                <div class="project-info-content">
                    <h4>Formulario Manual de Proyecto Productivo</h4>
                    <p>Complete los campos para crear un nuevo proyecto. Los campos están basados en las columnas del Excel de referencia.</p>
                </div>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('proyectos.store-manual') }}" method="POST" id="proyectoForm">
                @csrf

                {{-- Información básica del proyecto --}}
                <div class="form-header">
                    <h2 class="form-title">Información del Proyecto</h2>
                    <p class="form-subtitle">Datos generales del proyecto productivo</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            <i class="fas fa-tag"></i>
                            Nombre del Proyecto
                            <span class="required-indicator">*</span>
                        </label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required
                               placeholder="Ej: Proyecto de agricultura sostenible">
                    </div>

                    <div class="form-group">
                        <label for="ano" class="form-label">
                            <i class="fas fa-calendar"></i>
                            Año
                            <span class="required-indicator">*</span>
                        </label>
                        <input type="number" id="ano" name="ano" class="form-control" required
                               min="2020" max="2030" placeholder="2024">
                    </div>
                </div>

                {{-- Campos dinámicos --}}
                @if(count($columnasReferencia) > 0)
                    <div class="form-header">
                        <h2 class="form-title">Datos del Beneficiario</h2>
                        <p class="form-subtitle">Complete la información del beneficiario usando las columnas del Excel</p>
                    </div>

                    <div class="form-grid">
                        @foreach($columnasReferencia as $columna)
                            <div class="form-group">
                                <label for="campo_{{ md5($columna['nombre']) }}" class="form-label">
                                    <i class="fas fa-user-edit"></i>
                                    {{ $columna['nombre'] }}
                                    @if($columna['requerido'])
                                        <span class="required-indicator">*</span>
                                    @endif
                                </label>

                                @if($columna['tipo'] === 'select')
                                    <select id="campo_{{ md5($columna['nombre']) }}"
                                            name="data[{{ $columna['nombre'] }}]"
                                            class="form-control form-select"
                                            {{ $columna['requerido'] ? 'required' : '' }}>
                                        <option value="">Seleccione una opción</option>
                                        @if(isset($columna['opciones']))
                                            @foreach($columna['opciones'] as $opcion)
                                                <option value="{{ $opcion }}">{{ $opcion }}</option>
                                            @endforeach
                                        @else
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                            <option value="Otro">Otro</option>
                                        @endif
                                    </select>
                                @elseif($columna['tipo'] === 'date')
                                    <input type="date" id="campo_{{ md5($columna['nombre']) }}"
                                           name="data[{{ $columna['nombre'] }}]"
                                           class="form-control"
                                           {{ $columna['requerido'] ? 'required' : '' }}>
                                @else
                                    <input type="{{ $columna['tipo'] }}" id="campo_{{ md5($columna['nombre']) }}"
                                           name="data[{{ $columna['nombre'] }}]"
                                           class="form-control"
                                           {{ $columna['requerido'] ? 'required' : '' }}
                                           placeholder="Ingrese {{ strtolower($columna['nombre']) }}">
                                @endif

                                @if(str_contains(strtolower($columna['nombre']), 'documento'))
                                    <div class="field-info">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Ingrese solo números, sin puntos ni comas</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No se encontraron columnas de referencia. Complete al menos los campos básicos.
                    </div>
                @endif

                {{-- Información adicional --}}
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="descripcion" class="form-label">
                        <i class="fas fa-align-left"></i>
                        Descripción (Opcional)
                    </label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                              placeholder="Describa brevemente el proyecto productivo..."></textarea>
                </div>

                {{-- Acciones --}}
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Crear Proyecto
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('proyectoForm');

            // Validación básica del formulario
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('error');
                        isValid = false;
                    } else {
                        field.classList.remove('error');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor complete todos los campos requeridos.');
                    return false;
                }

                // Mostrar indicador de carga
                const submitBtn = form.querySelector('.btn-submit');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
                submitBtn.disabled = true;

                // Revertir después de 10 segundos (por si algo falla)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            });

            // Remover clase error al escribir
            form.addEventListener('input', function(e) {
                if (e.target.classList.contains('error') && e.target.value.trim()) {
                    e.target.classList.remove('error');
                }
            });
        });
    </script>
</x-app-layout>

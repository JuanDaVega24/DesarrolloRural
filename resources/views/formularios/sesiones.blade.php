<x-app-layout>

    @vite(['resources/css/pages/formularios/show.css', 'resources/css/pages/formularios/imagenes.css', 'resources/js/formularios-imagenes.js', 'resources/js/formularios-sesiones.js'])

    <div class="form-container">
        <div class="form-card">
            {{-- Información de la sesión --}}
            <div id="info-sesion" class="session-info-container">
                <!-- Se llenará con JavaScript -->
            </div>

            {{-- Usuarios activos --}}
            <div class="users-active-container">
                <div class="users-active-header">
                    <i class="fas fa-users"></i>
                    <h4>Usuarios Activos</h4>
                </div>
                <div id="usuarios-activos-list" class="users-list">
                    <!-- Se llenará con JavaScript -->
                </div>
            </div>

            {{-- Formulario --}}
            <form action="#" method="POST" id="sesionForm">
                @csrf

                {{-- Información adicional --}}
                <div class="form-group" style="grid-column: 1 / -1; margin-bottom: 2rem;">
                    <label for="descripcion" class="form-label">
                        <i class="fas fa-align-left"></i>
                        Observaciones del Proyecto (Opcional)
                    </label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                              placeholder="Describe brevemente el proyecto productivo...">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                </div>

                {{-- Sistema de beneficiarios acumulativo --}}
                @if($preguntasPersonalizadas->count() > 0 || $proyecto->preguntas->count() > 0)
                    <div class="form-header">
                        <h2 class="form-title">Datos de los Inscritos</h2>
                        <p class="form-subtitle">Complete el formulario para cada beneficiario. Los datos se guardan automáticamente.</p>
                    </div>

                    {{-- Mensaje de advertencia --}}
                    <div id="mensaje-advertencia" class="mensaje-advertencia" style="display: none;">
                        <div class="mensaje-advertencia-content">
                           
                            <div class="mensaje-advertencia-text">
                                <h4>⚠️ Cédula Duplicada</h4>
                                <div id="mensaje-advertencia-detalles"></div>
                                <p><strong>No se puede agregar este beneficiario porque su cédula ya está siendo utilizada por otro usuario.</strong></p>
                            </div>
                            <div class="mensaje-advertencia-actions">
                                <button type="button" id="btn-aceptar" class="btn-aceptar">
                                    <i class="fas fa-check"></i>
                                    Aceptar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Información del beneficiario actual --}}
                    <div class="beneficiario-info">
                        <div class="beneficiario-current">
                            <i class="fas fa-user-edit"></i>
                            <span>Formulario actual</span>
                        </div>
                        <div class="beneficiarios-total">
                            <span id="total-beneficiarios">0</span> beneficiario(s) agregado(s)
                        </div>
                    </div>

                    {{-- Formulario dinámico para beneficiario actual --}}
                    <div id="beneficiario-form" class="beneficiario-form-section">
                        <div class="form-grid">
                            {{-- CAMPOS ESTÁTICOS --}}
                            
                            {{-- # DEL SORTEO --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hashtag"></i>
                                    # NUMERO
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="number" id="numero_sorteo" class="form-control"
                                       placeholder="Ingrese número del sorteo">
                                <div id="error-numero_sorteo" class="field-error"></div>
                            </div>

                            {{-- CÉDULA --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    CÉDULA
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="number" id="cedula" class="form-control"
                                       placeholder="Ingrese número de cédula">
                                <div id="error-cedula" class="field-error"></div>
                                <div class="field-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Solo números, sin puntos ni comas</span>
                                </div>
                            </div>

                            {{-- NOMBRE COMPLETO --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    NOMBRE COMPLETO
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="text" id="nombre_completo" class="form-control"
                                       placeholder="Ingrese nombre completo">
                                <div id="error-nombre_completo" class="field-error"></div>
                            </div>

                            {{-- GENERO --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-venus-mars"></i>
                                    GENERO
                                    <span class="required-indicator">*</span>
                                </label>
                                <select id="genero" class="form-control form-select">
                                    <option value="">Seleccione género</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <div id="error-genero" class="field-error"></div>
                            </div>

                            {{-- CORREGIMIENTO --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    CORREGIMIENTO
                                    <span class="required-indicator">*</span>
                                </label>
                                <select id="corregimiento" class="form-control form-select">
                                    <option value="">Seleccione corregimiento</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                                <div id="error-corregimiento" class="field-error"></div>
                            </div>

                            {{-- VEREDA --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-road"></i>
                                    VEREDA
                                    <span class="required-indicator">*</span>
                                </label>
                                <select id="vereda" class="form-control form-select">
                                    <option value="">Seleccione vereda</option>
                                </select>
                                <div id="error-vereda" class="field-error"></div>
                            </div>

                            {{-- TELÉFONO --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i>
                                    TELÉFONO
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="number" id="telefono" class="form-control"
                                       placeholder="Ingrese número de teléfono">
                                <div id="error-telefono" class="field-error"></div>
                            </div>

                            {{-- CAMPOS DINÁMICOS --}}
                            @foreach($preguntasPersonalizadas as $index => $pregunta)
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-question-circle"></i>
                                        {{ $pregunta->pregunta }}
                                        @if($pregunta->es_obligatorio)
                                            <span class="required-indicator">*</span>
                                        @endif
                                    </label>

                                    @if($pregunta->subtitulo)
                                        <p class="field-subtitle" style="margin-top: -0.5rem; margin-bottom: 0.75rem; font-size: 0.95rem; color: #666; font-style: italic;">
                                            {{ $pregunta->subtitulo }}
                                        </p>
                                    @endif
                                    
                                    @if($pregunta->tipo_campo === 'texto')
                                        <input type="text" 
                                               name="pregunta_{{ $pregunta->id }}" 
                                               id="pregunta_{{ $pregunta->id }}"
                                               class="form-control"
                                               placeholder="Ingrese texto">
                                        <div id="error-pregunta_{{ $pregunta->id }}" class="field-error"></div>
                                        
                                    @elseif($pregunta->tipo_campo === 'numero')
                                        <input type="number" 
                                               name="pregunta_{{ $pregunta->id }}" 
                                               id="pregunta_{{ $pregunta->id }}"
                                               class="form-control"
                                               placeholder="Ingrese número">
                                        <div id="error-pregunta_{{ $pregunta->id }}" class="field-error"></div>
                                        
                                    @elseif($pregunta->tipo_campo === 'fecha')
                                        <input type="date" 
                                               name="pregunta_{{ $pregunta->id }}" 
                                               id="pregunta_{{ $pregunta->id }}"
                                               class="form-control">
                                        <div id="error-pregunta_{{ $pregunta->id }}" class="field-error"></div>
                                        
                                    @elseif($pregunta->tipo_campo === 'select')
                                        <select name="pregunta_{{ $pregunta->id }}" 
                                                id="pregunta_{{ $pregunta->id }}"
                                                class="form-control form-select">
                                            <option value="">Seleccione una opción</option>
                                            @if($pregunta->opciones)
                                                @foreach($pregunta->opciones as $opcion)
                                                    <option value="{{ $opcion['texto'] ?? $opcion }}" data-imagen="{{ $opcion['imagen'] ?? '' }}">
                                                        @if($opcion['imagen'] ?? false)
                                                            <span class="option-with-image">
                                                                <img src="{{ $opcion['imagen'] }}" alt="Imagen" class="option-image">
                                                                <span class="option-text">{{ $opcion['texto'] ?? $opcion }}</span>
                                                            </span>
                                                        @else
                                                            {{ $opcion['texto'] ?? $opcion }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div id="error-pregunta_{{ $pregunta->id }}" class="field-error"></div>
                                        
                                    @elseif($pregunta->tipo_campo === 'checkbox')
                                        <div class="checkbox-options">
                                            @if($pregunta->opciones)
                                                @foreach($pregunta->opciones as $opcion)
                                                    <label class="checkbox-label">
                                                        <input type="checkbox" 
                                                               name="pregunta_{{ $pregunta->id }}[]" 
                                                               value="{{ $opcion['texto'] ?? $opcion }}"
                                                               data-imagen="{{ $opcion['imagen'] ?? '' }}">
                                                        @if($opcion['imagen'] ?? false)
                                                            <span class="option-with-image">
                                                                <img src="{{ $opcion['imagen'] }}" alt="Imagen" class="option-image">
                                                                <span class="option-text">{{ $opcion['texto'] ?? $opcion }}</span>
                                                            </span>
                                                        @else
                                                            {{ $opcion['texto'] ?? $opcion }}
                                                        @endif
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div id="error-pregunta_{{ $pregunta->id }}" class="field-error"></div>
                                        
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="beneficiario-actions-section">
                        <div class="action-buttons">
                            <button type="button" id="btn-agregar-beneficiario" class="btn-add-beneficiario">
                                <i class="fas fa-plus"></i>
                                Agregar Beneficiario
                            </button>
                            <button type="button" id="btn-limpiar-formulario" class="btn-clean-form">
                                <i class="fas fa-eraser"></i>
                                Limpiar Formulario
                            </button>
                            <button type="button" id="btn-completar-sesion" class="btn-complete-session">
                                <i class="fas fa-check-circle"></i>
                                Completar Sesión
                            </button>
                        </div>
                    </div>

                    {{-- Lista de beneficiarios agregados --}}
                    <div id="beneficiarios-agregados" class="beneficiarios-agregados">
                        <h4 class="agregados-title">
                            <i class="fas fa-users"></i>
                            Beneficiarios Agregados
                        </h4>
                        <div id="lista-beneficiarios" class="lista-beneficiarios">
                            <!-- Los beneficiarios agregados se mostrarán aquí -->
                        </div>
                    </div>

                    {{-- Campo oculto para datos acumulados --}}
                    <input type="hidden" name="beneficiarios_acumulados" id="beneficiarios_acumulados" value="[]">
                    <input type="hidden" id="proyecto-id" value="{{ $proyecto->id }}">

                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Este proyecto no tiene preguntas configuradas. Por favor, configure las preguntas del formulario primero.
                    </div>
                    <div class="form-actions">
                        <a href="{{ route('formularios.index') }}" class="btn-cancel">
                            <i class="fas fa-arrow-left"></i>
                            Volver a proyectos
                        </a>
                    </div>
                @endif

                {{-- Acciones --}}
                <div class="form-actions">
                    <button type="button" id="btn-fusionar-sesiones" class="btn-fusionar-sesiones" style="display: none;">
                        <i class="fas fa-sync-alt"></i>
                        Fusionar Sesiones
                    </button>
                    <a href="{{ route('formularios.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar el sistema de sesiones
        const proyectoId = document.getElementById('proyecto-id')?.value;
        if (proyectoId) {
            window.formularioSesiones = new FormularioSesiones(proyectoId);
        }

        // Eventos para el formulario
        const btnAgregar = document.getElementById('btn-agregar-beneficiario');
        const btnLimpiar = document.getElementById('btn-limpiar-formulario');
        const btnCompletar = document.getElementById('btn-completar-sesion');
        const btnFusionar = document.getElementById('btn-fusionar-sesiones');

        if (btnAgregar) {
            btnAgregar.addEventListener('click', async function() {
                if (!window.formularioSesiones) return;

                // Validar formulario
                if (!validarFormulario()) return;

                // Obtener datos
                const datos = obtenerDatosFormulario();
                
                // Agregar beneficiario
                const exito = await window.formularioSesiones.agregarBeneficiario(datos);
                
                if (exito) {
                    limpiarFormulario();
                }
            });
        }

        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', function() {
                limpiarFormulario();
            });
        }

        if (btnCompletar) {
            btnCompletar.addEventListener('click', async function() {
                if (!window.formularioSesiones) return;

                const exito = await window.formularioSesiones.completarSesion();
                
                if (exito) {
                    // Mostrar botón de fusión si es administrador
                    if (btnFusionar) {
                        btnFusionar.style.display = 'inline-block';
                    }
                }
            });
        }

        if (btnFusionar) {
            btnFusionar.addEventListener('click', async function() {
                if (!window.formularioSesiones) return;

                const exito = await window.formularioSesiones.fusionarSesiones();
                
                if (exito) {
                    // Redirigir al listado de proyectos
                    window.location.href = '{{ route("formularios.index") }}';
                }
            });
        }

        // --- LÓGICA DINÁMICA DE VEREDAS ---
        const veredasMap = @json(json_decode(file_get_contents(resource_path('js/veredas.json')), true));
        const corregimientoSelect = document.getElementById('corregimiento');
        const veredaSelect = document.getElementById('vereda');

        function poblarVeredas(idCorregimiento) {
            if (!veredaSelect) return;

            // Limpiar opciones actuales
            while (veredaSelect.options.length > 0) {
                veredaSelect.remove(0);
            }
            
            // Opción por defecto
            const defaultOption = document.createElement('option');
            defaultOption.value = "";
            defaultOption.textContent = "Seleccione vereda";
            veredaSelect.add(defaultOption);

            if (idCorregimiento && veredasMap[idCorregimiento]) {
                veredasMap[idCorregimiento].forEach(vereda => {
                    const option = document.createElement('option');
                    option.value = vereda;
                    option.textContent = vereda;
                    veredaSelect.add(option);
                });
            }
        }

        if (corregimientoSelect) {
            corregimientoSelect.addEventListener('change', function() {
                poblarVeredas(this.value);
            });
        }

        // --- FUNCIONES DE VALIDACIÓN Y MANEJO DE FORMULARIO ---
        function validarFormulario() {
            limpiarTodosErrores();
            let valido = true;

            // Validar campos estáticos requeridos
            const requeridos = [
                'numero_sorteo',
                'cedula',
                'nombre_completo',
                'genero',
                'corregimiento',
                'vereda',
                'telefono'
            ];

            requeridos.forEach(id => {
                const campo = document.getElementById(id);
                if (!campo || !campo.value.trim()) {
                    mostrarErrorCampo(id, 'Este campo es obligatorio');
                    valido = false;
                }
            });

            // Validar campos dinámicos requeridos
            @foreach($preguntasPersonalizadas as $pregunta)
                const campoId = 'pregunta_{{ $pregunta->id }}';
                const campo = document.getElementById(campoId);
                
                if ({{ $pregunta->es_obligatorio ? 'true' : 'false' }}) {
                    if ('{{ $pregunta->tipo_campo }}' === 'checkbox') {
                        const checkboxes = document.querySelectorAll('input[name="pregunta_{{ $pregunta->id }}[]"]:checked');
                        if (checkboxes.length === 0) {
                            mostrarErrorCampo(campoId, 'Este campo es obligatorio');
                            valido = false;
                        }
                    } else {
                        if (!campo || !campo.value.trim()) {
                            mostrarErrorCampo(campoId, 'Este campo es obligatorio');
                            valido = false;
                        }
                    }
                }
            @endforeach

            return valido;
        }

        function obtenerDatosFormulario() {
            const datos = {
                '# NUMERO': document.getElementById('numero_sorteo').value.trim(),
                'CÉDULA': document.getElementById('cedula').value.trim(),
                'NOMBRE COMPLETO': document.getElementById('nombre_completo').value.trim(),
                'GENERO': document.getElementById('genero').value.trim(),
                'CORREGIMIENTO': document.getElementById('corregimiento').value.trim(),
                'VEREDA': document.getElementById('vereda').value.trim(),
                'TELÉFONO': document.getElementById('telefono').value.trim()
            };

            @foreach($preguntasPersonalizadas as $pregunta)
                const campoId = 'pregunta_{{ $pregunta->id }}';
                
                if ('{{ $pregunta->tipo_campo }}' === 'checkbox') {
                    const checkboxes = document.querySelectorAll('input[name="pregunta_{{ $pregunta->id }}[]"]:checked');
                    const valores = Array.from(checkboxes).map(cb => cb.value.trim());
                    datos['{{ $pregunta->pregunta }}'] = valores.join(', ');
                } else {
                    const campo = document.getElementById(campoId);
                    if (campo) {
                        datos['{{ $pregunta->pregunta }}'] = campo.value.trim();
                    }
                }
            @endforeach

            return datos;
        }

        function limpiarFormulario() {
            // Limpiar campos estáticos
            const campos = [
                'numero_sorteo', 'cedula', 'nombre_completo', 
                'genero', 'corregimiento', 'vereda', 'telefono'
            ];
            
            campos.forEach(id => {
                const campo = document.getElementById(id);
                if (campo) campo.value = '';
            });
            
            // Limpiar checkboxes
            document.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
            
            // Limpiar inputs dinámicos
            @foreach($preguntasPersonalizadas as $pregunta)
                const campo = document.getElementById('pregunta_{{ $pregunta->id }}');
                if (campo) campo.value = '';
            @endforeach

            // Resetear lista de veredas
            poblarVeredas('');
            
            limpiarTodosErrores();
        }

        function mostrarErrorCampo(id, mensaje) {
            const input = document.getElementById(id);
            const error = document.getElementById(`error-${id}`);
            if (input && error) {
                input.classList.add('error');
                error.textContent = mensaje;
                error.classList.add('show');
            }
        }

        function limpiarTodosErrores() {
            document.querySelectorAll('.field-error').forEach(e => {
                e.textContent = '';
                e.classList.remove('show');
            });
            document.querySelectorAll('.form-control').forEach(i => {
                i.classList.remove('error');
            });
        }

        // --- VALIDACIÓN EN TIEMPO REAL CÉDULA ---
        const cedulaInput = document.getElementById('cedula');
        const cedulaError = document.getElementById('error-cedula');
        let cedulaDebounce;

        if (cedulaInput) {
            cedulaInput.addEventListener('input', function() {
                const val = this.value.trim();
                
                // Limpiar estado inicial
                cedulaError.textContent = '';
                cedulaError.style.color = '';
                cedulaError.style.display = 'none';
                
                clearTimeout(cedulaDebounce);
                
                if (!val) return;
                
                cedulaDebounce = setTimeout(async () => {
                    if (!window.formularioSesiones) return;

                    const res = await window.formularioSesiones.validarCedulaConcurrente(val);
                    
                    if (res.success) {
                        if (res.cedula_encontrada) {
                            cedulaError.innerHTML = '<i class="fas fa-times-circle"></i> ⛔ Esta cédula ya está siendo utilizada por: ' + res.usuarios.map(u => u.usuario).join(', ');
                            cedulaError.style.color = '#dc3545';
                            cedulaError.style.display = 'block';
                        }
                    }
                }, 500);
            });
        }

        // --- EVENTOS DEL SISTEMA DE SESIONES ---
        // Ocultar mensaje de advertencia al limpiar formulario
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', function() {
                const mensajeAdvertencia = document.getElementById('mensaje-advertencia');
                if (mensajeAdvertencia) mensajeAdvertencia.style.display = 'none';
            });
        }

        // Botón de aceptar en mensaje de advertencia
        const btnAceptar = document.getElementById('btn-aceptar');
        if (btnAceptar) {
            btnAceptar.addEventListener('click', function() {
                const mensajeAdvertencia = document.getElementById('mensaje-advertencia');
                if (mensajeAdvertencia) mensajeAdvertencia.style.display = 'none';
                limpiarFormulario();
            });
        }
    });
    </script>

    <!-- Script para manejar imágenes en opciones -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Función para manejar errores de carga de imágenes
        function handleImageError(img) {
            img.style.display = 'none';
        }

        // Añadir manejadores de error a todas las imágenes en opciones
        const optionImages = document.querySelectorAll('.option-image');
        optionImages.forEach(img => {
            img.addEventListener('error', function() {
                handleImageError(this);
            });
        });

        // Mejorar la accesibilidad de las opciones con imágenes
        const checkboxLabels = document.querySelectorAll('.checkbox-options .checkbox-label');
        checkboxLabels.forEach(label => {
            const checkbox = label.querySelector('input[type="checkbox"]');
            const image = label.querySelector('.option-image');
            
            if (checkbox && image) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        label.style.backgroundColor = '#f0f9ff';
                        label.style.borderColor = '#bfdbfe';
                    } else {
                        label.style.backgroundColor = '';
                        label.style.borderColor = '';
                    }
                });
            }
        });
    });
    </script>

</x-app-layout>
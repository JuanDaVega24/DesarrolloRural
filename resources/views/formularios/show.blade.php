<x-app-layout>

        

    <div class="form-container">
        {{-- Barra de información colaborativa --}}
        <div id="info-sesion" class="mb-3"></div>
        
        <div class="form-card">
            {{-- Información del proyecto --}}
            <div class="project-info">
                <i class="fas fa-edit"></i>
                <div class="project-info-content">
                    <h4>Completar Proyecto: {{ $proyecto->nombre }}</h4>
                    <p>Llena el formulario con los datos para completar este proyecto productivo.</p>
                </div>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('formularios.update', $proyecto) }}" method="POST" id="completarForm">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger" style="grid-column: 1 / -1; margin-bottom: 2rem; border-radius: 8px; padding: 1rem; background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c;">
                        <h5 style="margin-top: 0; margin-bottom: 0.5rem; font-weight: 600;">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Por favor corrija los siguientes errores:
                        </h5>
                        <ul style="margin-bottom: 0; padding-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Información adicional --}}
                <div class="form-group" style="grid-column: 1 / -1; margin-bottom: 2rem;">
                    <label for="descripcion" class="form-label">
                        <i class="fas fa-align-left"></i>
                        Observaciones del Proyecto (Opcional)
                    </label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                              placeholder="Describe brevemente el proyecto productivo...">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                </div>

                {{-- Filtros de comparación (Nuevo) --}}
                <div class="comparison-filters-section" style="grid-column: 1 / -1;">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calendar-alt"></i>
                            Año de Proyectos a Comparar
                        </label>
                        <select class="form-control form-select" id="comparison-year-filter">
                            <option value="">Todos los años</option>
                            @foreach($anos as $ano)
                                <option value="{{ $ano }}">{{ $ano }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-search-plus"></i>
                            Proyectos a Comparar
                        </label>
                        <div class="multiselect-container">
                            <div class="multiselect-display" id="multiselect-display">
                                <span id="multiselect-text">Todos los proyectos</span>
                                <span class="selected-count-badge" id="selected-count" style="display: none;">0</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="multiselect-dropdown" id="multiselect-dropdown">
                                <div class="multiselect-option select-all-option">
                                    <input type="checkbox" id="select-all-projects" checked>
                                    <label for="select-all-projects">Seleccionar Todos</label>
                                </div>
                                <div id="projects-list-container">
                                    @foreach($proyectosParaComparar as $pComp)
                                        <div class="multiselect-option project-option" data-year="{{ $pComp->ano }}">
                                            <input type="checkbox" id="proj-{{ $pComp->id }}" value="{{ $pComp->id }}" checked>
                                            <label for="proj-{{ $pComp->id }}">{{ $pComp->nombre }} ({{ $pComp->ano }})</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sistema de beneficiarios acumulativo --}}
                <div class="collaborative-dashboard mt-4 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users-cog me-2"></i>Usuarios Activos</span>
                                    <button type="button" class="btn btn-sm btn-outline-light" onclick="window.formularioSesiones?.obtenerUsuariosActivos()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <div class="card-body p-0" style="max-height: 250px; overflow-y: auto;">
                                    <div id="usuarios-activos-list" class="list-group list-group-flush">
                                        {{-- Se poblará por JS --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-chart-line me-2"></i>Progreso Global</span>
                                    <span class="badge bg-light text-success fs-6" id="total-beneficiarios-todos">0</span>
                                </div>
                                <div class="card-body p-3">
                                    <p class="small text-muted mb-2">Beneficiarios registrados por todos los usuarios en sesiones activas.</p>
                                    <div id="all-beneficiarios-container">
                                        {{-- Se poblará por JS --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="proyecto-id" value="{{ $proyecto->id }}">

                @if($preguntasPersonalizadas->count() > 0 || $proyecto->preguntas->count() > 0)
                    <div class="form-header">
                        <h2 class="form-title">Datos de los Inscritos</h2>
                        <p class="form-subtitle">Complete el formulario para cada beneficiario. Use "Agregar Beneficiario" para incluir múltiples personas.</p>
                    </div>

                    {{-- Mensaje de advertencia --}}
                    <div id="mensaje-advertencia" class="mensaje-advertencia" style="display: none;">
                        <div class="mensaje-advertencia-content">
                           
                            <div class="mensaje-advertencia-text">
                                <h4>⚠️ Cédula Duplicada</h4>
                                <div id="mensaje-advertencia-detalles"></div>
                                <p><strong>No se puede agregar este beneficiario porque su cédula ya existe en otros proyectos.</strong></p>
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
                            <span>Completando beneficiario <span id="beneficiario-actual">1</span></span>
                        </div>
                        <div class="beneficiarios-total">
                            <span id="total-beneficiarios">0</span> beneficiario(s) agregado(s)
                        </div>
                    </div>

                    {{-- Formulario dinámico para beneficiario actual --}}
                    <div id="beneficiario-form" class="beneficiario-form-section">
                        <div class="form-grid">
                            {{-- CAMPOS ESTÁTICOS (siempre presentes) --}}
                            
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

                            {{-- TELÉFONO (Nuevo campo estático para coincidir con tabla) --}}
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

                            {{-- CAMPOS DINÁMICOS (personalizados por el usuario) --}}
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
                        </div>
                    </div>

                    {{-- Lista de beneficiarios agregados --}}
                    <div id="beneficiarios-agregados" class="beneficiarios-agregados" style="display: none;">
                        <h4 class="agregados-title">
                            <i class="fas fa-users"></i>
                            Beneficiarios Agregados
                        </h4>
                        <div id="lista-beneficiarios" class="lista-beneficiarios">
                            <!-- Los beneficiarios agregados se mostrarán aquí -->
                        </div>
                    </div>

                    {{-- Campo oculto para datos acumulados --}}
                    <input type="hidden" name="beneficiarios_acumulados" id="beneficiarios_acumulados" value="{{ old('beneficiarios_acumulados', '[]') }}">

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
                <div class="form-actions d-flex flex-wrap gap-2">
                    @if(auth()->user()->hasRole('Administrador'))
                        <button type="button" class="btn btn-success" onclick="window.formularioSesiones?.fusionarSesiones()">
                            <i class="fas fa-file-import me-2"></i>Finalizar Proyecto y Fusionar Datos
                        </button>
                    @endif
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Completar mi parte
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

    const form = document.getElementById('completarForm');
    const btnAgregar = document.getElementById('btn-agregar-beneficiario');
    const btnLimpiar = document.getElementById('btn-limpiar-formulario');
    const inputAcumulados = document.getElementById('beneficiarios_acumulados');
    const lista = document.getElementById('lista-beneficiarios');
    const total = document.getElementById('total-beneficiarios');
    const actual = document.getElementById('beneficiario-actual');
    const contenedorAgregados = document.getElementById('beneficiarios-agregados');

    let beneficiarios = [];
    window.beneficiarios = beneficiarios; // Hacerlo global para sincronización

    window.setBeneficiariosLocales = function(datos) {
        beneficiarios = datos;
        window.beneficiarios = beneficiarios;
        inputAcumulados.value = JSON.stringify(beneficiarios);
        actualizarLista();
        actualizarEstado();
    };

    try {
        beneficiarios = JSON.parse(inputAcumulados.value || '[]');
        window.beneficiarios = beneficiarios;
    } catch (e) {
        console.error('Error al parsear beneficiarios:', e);
        beneficiarios = [];
        window.beneficiarios = beneficiarios;
    }
    
    let contador = beneficiarios.length + 1;

    // Inicializar lista si hay beneficiarios previos (ej. después de un error de validación)
    if (beneficiarios.length > 0) {
        actualizarLista();
        actualizarEstado();
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

    const evidenciaFileInput = document.getElementById('evidencia_file');
    const evidenciaNombreInput = document.getElementById('evidencia_fotografica');
    if (evidenciaFileInput && evidenciaNombreInput) {
        evidenciaFileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                evidenciaNombreInput.value = this.files[0].name;
                limpiarErrorCampo('evidencia_fotografica');
            } else {
                evidenciaNombreInput.value = '';
            }
        });
    }

    // --- PREGUNTAS PERSONALIZADAS ---
    const preguntasPersonalizadas = @json($preguntasPersonalizadas);
    
    /* =========================
       UTILIDADES DE ERRORES
    ========================== */
    function mostrarErrorCampo(id, mensaje) {
        const input = document.getElementById(id);
        const error = document.getElementById(`error-${id}`);
        if (input && error) {
            input.classList.add('error');
            error.textContent = mensaje;
            error.classList.add('show');
        }
    }

    function limpiarErrorCampo(id) {
        const input = document.getElementById(id);
        const error = document.getElementById(`error-${id}`);
        if (input && error) {
            input.classList.remove('error');
            error.textContent = '';
            error.classList.remove('show');
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

    /* =========================
       RECOPILAR DATOS
    ========================== */
    function obtenerDatos() {
    const numero_sorteo = document.getElementById('numero_sorteo');
    const cedula = document.getElementById('cedula');
    const nombre_completo = document.getElementById('nombre_completo');
    const genero = document.getElementById('genero');
    const corregimiento = document.getElementById('corregimiento');
    const vereda = document.getElementById('vereda');
    const telefono = document.getElementById('telefono');
    
    // Contenedor del formulario dinámico
    const container = document.getElementById('beneficiario-form');

    const datos = {
        '# NUMERO': numero_sorteo.value.trim(),
        'CÉDULA': cedula.value.trim(),
        'NOMBRE COMPLETO': nombre_completo.value.trim(),
        'GENERO': genero.value.trim(),
        'CORREGIMIENTO': corregimiento.value.trim(),
        'VEREDA': vereda.value.trim(),
        'TELÉFONO': telefono ? telefono.value.trim() : ''
    };

    preguntasPersonalizadas.forEach(pregunta => {
        const campoId = `pregunta_${pregunta.id}`;
        
        if (pregunta.tipo_campo === 'checkbox') {
            // Buscar checkboxes marcados solo dentro del contenedor del formulario
            const checkboxes = container.querySelectorAll(`input[name="${campoId}[]"]:checked`);
            const valores = Array.from(checkboxes).map(cb => 
                cb.value.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim()
            );

            // 🔥 Guardar como texto separado por coma con la llave de la pregunta
            datos[pregunta.pregunta] = valores.join(', ');

        } else {
            const campo = document.getElementById(campoId);
            if (campo) {
                datos[pregunta.pregunta] = campo.value.trim();
            }
        }
    });

    return datos;
}


    /* =========================
       VALIDACIÓN
    ========================== */
    function validar() {
        limpiarTodosErrores();
        let valido = true;

        // Validar campos estáticos requeridos (solo los que existen en el formulario)
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
        preguntasPersonalizadas.forEach(pregunta => {
            const campoId = `pregunta_${pregunta.id}`;
            const campo = document.getElementById(campoId);
            
            if (pregunta.es_obligatorio) {
                if (pregunta.tipo_campo === 'checkbox') {
                    const checkboxes = document.querySelectorAll(`input[name="${campoId}[]"]:checked`);
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
        });

        return valido;
    }

    /* =========================
       LIMPIAR FORMULARIO
    ========================== */
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
        document.querySelectorAll('#beneficiario-form input[type="checkbox"]')
            .forEach(c => c.checked = false);
        
        // Limpiar inputs dinámicos (texto, número, fecha, select)
        preguntasPersonalizadas.forEach(p => {
            const campo = document.getElementById(`pregunta_${p.id}`);
            if (campo) campo.value = '';
        });

        const evidenciaFile = document.getElementById('evidencia_file');
        if (evidenciaFile) evidenciaFile.value = '';
        
        // Resetear lista de veredas
        poblarVeredas('');
        
        limpiarTodosErrores();
    }

    /* =========================
       AGREGAR BENEFICIARIO
    ========================== */
    function agregarBeneficiario() {
        if (!validar()) return;

        // Validar cédula antes de agregar
        const cedulaElement = document.getElementById('cedula');
        const cedula = cedulaElement.value.trim();
        if (cedula) {
            validarCedulaExistente(cedula, function(res) {
                if (res.foundRecent) {
                    // Mostrar mensaje de advertencia grande y bloquear
                    mostrarMensajeAdvertencia(res);
                } else if (res.foundOld) {
                    // Mostrar mensaje de advertencia pero permitir
                    if(confirm('⚠️ ADVERTENCIA:\nEl beneficiario participó en proyectos antiguos:\n' + res.oldProjects.join(', ') + '\n\n¿Desea agregarlo de todas formas?')) {
                        agregarBeneficiarioConfirmado();
                    }
                } else {
                    // No hay duplicados, agregar directamente
                    agregarBeneficiarioConfirmado();
                }
            });
        } else {
            // Si no hay cédula, agregar directamente
            agregarBeneficiarioConfirmado();
        }
    }

    /* =========================
       MOSTRAR MENSAJE DE ADVERTENCIA
    ========================== */
    function mostrarMensajeAdvertencia(res) {
        const mensajeAdvertencia = document.getElementById('mensaje-advertencia');
        const detallesDiv = document.getElementById('mensaje-advertencia-detalles');

        let detalles = '';

        if (res.recentProjects && res.recentProjects.length > 0) {
            detalles = '• Inscrito en: ' + res.recentProjects.join(', ');
        }

        detallesDiv.textContent = detalles;
        mensajeAdvertencia.style.display = 'block';

        // Hacer scroll al mensaje
        mensajeAdvertencia.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /* =========================
       OCULTAR MENSAJE DE ADVERTENCIA
    ========================== */
    function ocultarMensajeAdvertencia() {
        const mensajeAdvertencia = document.getElementById('mensaje-advertencia');
        if(mensajeAdvertencia) mensajeAdvertencia.style.display = 'none';
    }

    /* =========================
       AGREGAR BENEFICIARIO CONFIRMADO
    ========================== */
    function agregarBeneficiarioConfirmado() {
        // Ocultar mensaje de advertencia si está visible
        ocultarMensajeAdvertencia();

        // Proceder a agregar el beneficiario
        beneficiarios.push(obtenerDatos());
        window.beneficiarios = beneficiarios; // Sincronizar global
        inputAcumulados.value = JSON.stringify(beneficiarios);
        
        // Guardar en el servidor inmediatamente si estamos en modo colaborativo
        if (window.formularioSesiones) {
            window.formularioSesiones.guardarDatos(beneficiarios);
        }

        actualizarLista();
        limpiarFormulario();
        contador++;
        actualizarEstado();
    }

    /* =========================
       VALIDAR CÉDULA EXISTENTE
    ========================== */
    function validarCedulaExistente(cedula, callback) {
        // Obtener el año del proyecto actual
        const currentYear = {{ $proyecto->ano }};
        
        // Obtener proyectos seleccionados en el multiselect
        const selectedCheckboxes = document.querySelectorAll('.project-option input[type="checkbox"]:checked');
        const proyectosIds = Array.from(selectedCheckboxes).map(cb => cb.value);

        // Primero validar contra otros usuarios en tiempo real si estamos en modo colaborativo
        let pConcurrente = Promise.resolve({ success: true, cedula_encontrada: false });
        if (window.formularioSesiones) {
            pConcurrente = fetch(`/sesiones/${document.getElementById('proyecto-id').value}/validar-cedula`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    cedula: cedula,
                    session_token: window.formularioSesiones.sesion?.session_token
                })
            }).then(r => r.json());
        }

        // Luego validar contra base de datos histórica
        const pHistorica = fetch('{{ route("formularios.validar-cedula") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                cedula: cedula,
                current_year: currentYear,
                proyectos_ids: proyectosIds
            })
        }).then(r => r.json());

        Promise.all([pConcurrente, pHistorica])
        .then(([resConcurrente, resHistorica]) => {
            const projects = resHistorica.projects || [];
            let foundRecent = false;
            let foundOld = false;
            let recentProjects = [];
            let oldProjects = [];
            
            // Si se encontró en otra sesión activa
            if (resConcurrente.cedula_encontrada) {
                foundRecent = true;
                resConcurrente.usuarios.forEach(u => {
                    recentProjects.push(`Usuario: ${u.usuario} (Sesión activa)`);
                });
            }

            projects.forEach(p => {
                if (p.ano >= currentYear - 1) {
                    foundRecent = true;
                    recentProjects.push(`${p.nombre} (${p.ano})`);
                } else {
                    foundOld = true;
                    oldProjects.push(`${p.nombre} (${p.ano})`);
                }
            });
            
            callback({
                foundRecent: foundRecent,
                foundOld: foundOld,
                recentProjects: recentProjects,
                oldProjects: oldProjects,
                projects: projects
            });
        })
        .catch(error => {
            console.error('Error:', error);
            callback({foundRecent: false, foundOld: false});
        });
    }

    /* =========================
       LISTA
    ========================== */
    function actualizarLista() {
        lista.innerHTML = '';
        beneficiarios.forEach((b, i) => {
            const div = document.createElement('div');
            div.className = 'beneficiario-item';
            
            div.innerHTML = `
                <div class="beneficiario-content">
                    <div class="beneficiario-main">
                        <div class="beneficiario-nombre">${b['NOMBRE COMPLETO']}</div>
                        <div class="beneficiario-detalles">Cédula: ${b['CÉDULA']}</div>
                    </div>
                </div>
                <button type="button" class="btn-remove-item" onclick="eliminar(${i})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            lista.appendChild(div);
        });
        contenedorAgregados.style.display = beneficiarios.length ? 'block' : 'none';
    }

    window.eliminar = function (i) {
        beneficiarios.splice(i, 1);
        window.beneficiarios = beneficiarios; // Sincronizar global
        inputAcumulados.value = JSON.stringify(beneficiarios);
        
        // Guardar en el servidor inmediatamente si estamos en modo colaborativo
        if (window.formularioSesiones) {
            window.formularioSesiones.guardarDatos(beneficiarios);
        }

        contador = beneficiarios.length + 1;
        actualizarLista();
        actualizarEstado();
    };

    function actualizarEstado() {
        total.textContent = beneficiarios.length;
        actual.textContent = contador;
    }

    /* =========================
       EVENTOS
    ========================== */
    btnAgregar.addEventListener('click', agregarBeneficiario);
    btnLimpiar.addEventListener('click', limpiarFormulario);

    // Event listeners para los botones del mensaje de advertencia
    const btnAceptar = document.getElementById('btn-aceptar');

    if (btnAceptar) {
        btnAceptar.addEventListener('click', function() {
            ocultarMensajeAdvertencia();
            limpiarFormulario();
        });
    }

    // --- LÓGICA DE MULTISELECT PARA COMPARACIÓN ---
    const msDisplay = document.getElementById('multiselect-display');
    const msDropdown = document.getElementById('multiselect-dropdown');
    const msText = document.getElementById('multiselect-text');
    const msBadge = document.getElementById('selected-count');
    const selectAllCb = document.getElementById('select-all-projects');
    const projectCheckboxes = document.querySelectorAll('.project-option input[type="checkbox"]');
    const yearFilter = document.getElementById('comparison-year-filter');

    // Abrir/Cerrar dropdown
    msDisplay.addEventListener('click', (e) => {
        e.stopPropagation();
        msDropdown.classList.toggle('show');
    });

    document.addEventListener('click', () => msDropdown.classList.remove('show'));
    msDropdown.addEventListener('click', (e) => e.stopPropagation());

    // Filtrar proyectos por año
    yearFilter.addEventListener('change', function() {
        const year = this.value;
        const options = document.querySelectorAll('.project-option');
        
        options.forEach(opt => {
            if (!year || opt.dataset.year === year) {
                opt.style.display = 'flex';
            } else {
                opt.style.display = 'none';
            }
        });
    });

    // Seleccionar todos
    selectAllCb.addEventListener('change', function() {
        const isChecked = this.checked;
        const visibleOptions = document.querySelectorAll('.project-option:not([style*="display: none"]) input[type="checkbox"]');
        visibleOptions.forEach(cb => cb.checked = isChecked);
        updateMultiselectText();
    });

    // Cambio individual
    projectCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateMultiselectText);
    });

    function updateMultiselectText() {
        const total = projectCheckboxes.length;
        const selected = document.querySelectorAll('.project-option input[type="checkbox"]:checked').length;

        if (selected === 0) {
            msText.textContent = "Ningún proyecto seleccionado";
            msBadge.style.display = 'none';
        } else if (selected === total) {
            msText.textContent = "Todos los proyectos";
            msBadge.style.display = 'none';
        } else {
            msText.textContent = "Proyectos seleccionados";
            msBadge.textContent = selected;
            msBadge.style.display = 'inline-block';
        }
        
        // Actualizar checkbox de "seleccionar todos"
        selectAllCb.checked = (selected === total);
        selectAllCb.indeterminate = (selected > 0 && selected < total);
    }

    form.addEventListener('submit', async function (e) {
        if (beneficiarios.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un beneficiario.');
            return;
        }

        if (window.formularioSesiones) {
            e.preventDefault();
            if (confirm('¿Está seguro de que ha terminado de registrar sus beneficiarios? Esta acción guardará su parte del trabajo.')) {
                await window.formularioSesiones.completarSesion();
            }
        }
    });

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
            ocultarMensajeAdvertencia(); // Ocultar mensaje grande si se corrige la cédula
            
            clearTimeout(cedulaDebounce);
            
            if (!val) return;
            
            cedulaDebounce = setTimeout(() => {
                validarCedulaExistente(val, function(res) {
                    if (res.foundRecent) {
                        cedulaError.innerHTML = '<i class="fas fa-times-circle"></i> ⛔ Esta persona ya está inscrita en proyectos recientes: ' + res.recentProjects.join(', ');
                        cedulaError.style.color = '#dc3545'; // Rojo
                        cedulaError.style.display = 'block';
                        
                        // Mostrar mensaje grande de bloqueo inmediatamente al escribir
                        mostrarMensajeAdvertencia(res);
                        
                    } else if (res.foundOld) {
                        cedulaError.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ⚠️ Esta persona participó en proyectos anteriores: ' + res.oldProjects.join(', ');
                        cedulaError.style.color = '#e67e22'; // Naranja oscuro
                        cedulaError.style.display = 'block';
                    }
                });
            }, 500);
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
            // Podrías reemplazar con una imagen por defecto si lo deseas
            // img.src = '/path/to/default-image.png';
        }

        // Añadir manejadores de error a todas las imágenes en opciones
        const optionImages = document.querySelectorAll('.option-image');
        optionImages.forEach(img => {
            img.addEventListener('error', function() {
                handleImageError(this);
            });
        });

        // Mejorar la visualización de imágenes en selects (para navegadores que lo soporten)
        const selectElements = document.querySelectorAll('.form-control.form-select');
        selectElements.forEach(select => {
            // Añadir estilos para mejorar la visualización en el select abierto
            select.addEventListener('focus', function() {
                this.style.backgroundColor = '#f8fafc';
            });
            
            select.addEventListener('blur', function() {
                this.style.backgroundColor = '';
            });
        });

        // Mejorar la accesibilidad de las opciones con imágenes
        const checkboxLabels = document.querySelectorAll('.checkbox-options .checkbox-label');
        checkboxLabels.forEach(label => {
            const checkbox = label.querySelector('input[type="checkbox"]');
            const image = label.querySelector('.option-image');
            
            if (checkbox && image) {
                // Sincronizar el estado visual del checkbox con el label
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

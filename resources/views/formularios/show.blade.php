<x-app-layout>

        
@vite(['resources/css/pages/formularios/show.css'])

    <div class="form-container">
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
                @if(count($columnasReferencia) > 0)
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

                    {{-- Formulario estático simple para beneficiario actual --}}
                    <div id="beneficiario-form" class="beneficiario-form-section">
                        <div class="form-grid">
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

                            {{-- CONDICIÓN --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user-tag"></i>
                                    CONDICIÓN
                                    <span class="required-indicator">*</span>
                                </label>
                                <select id="condicion" class="form-control form-select">
                                    <option value="">Seleccione condición</option>
                                    <option value="Ninguno" selected>Ninguno</option>
                                    <option value="Afrocolombiano">Afrocolombiano</option>
                                    <option value="Campesino">Campesino</option>
                                    <option value="Indígena">Indígena</option>
                                    <option value="LGBTIQ+">LGBTIQ+</option>
                                    <option value="Persona mayor">Persona mayor</option>
                                    <option value="Cabeza de familia">Cabeza de familia</option>
                                    <option value="Mujer rural">Mujer rural</option>
                                    <option value="Desmovilizado">Desmovilizado</option>
                                    <option value="Reinsertado">Reinsertado</option>
                                    <option value="joven rural">Joven rural</option>
                                    <option value="persona con discapacidad">Persona con discapacidad</option>
                                    <option value="victima del conflicto (RUV)">Victima del conflicto (RUV)</option>
                                    <option value="cuidador/a">Cuidador/a</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <div id="error-condicion" class="field-error"></div>

                            </div>

                            {{-- FECHA DE NACIMIENTO --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-birthday-cake"></i>
                                    FECHA DE NACIMIENTO
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="date" id="fecha_nacimiento" class="form-control">
                           <div id="error-fecha_nacimiento" class="field-error"></div>

                            </div>

                            {{-- FECHA DE EXPEDICIÓN --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    FECHA DE EXPEDICIÓN
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="date" id="fecha_expedicion" class="form-control">
                          <div id="error-fecha_expedicion" class="field-error"></div>

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

                            {{-- SISBEN --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-chart-line"></i>
                                    SISBEN (Consultado por la Alcaldía)
                                </label>
                                <input type="text" id="sisben" class="form-control"
                                       placeholder="Ingrese SISBEN" readonly>
                            </div>

                            {{-- FINCA --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-home"></i>
                                    FINCA
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="text" id="finca" class="form-control"
                                       placeholder="Ingrese finca">
                            <div id="error-finca" class="field-error"></div>

                                    </div>

                            {{-- TELÉFONO --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i>
                                    TELÉFONO
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="tel" id="telefono" class="form-control"
                                       placeholder="Ingrese teléfono">
                           <div id="error-telefono" class="field-error"></div>

                                    </div>

                            {{-- LUGAR DE ENTREGA --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-truck"></i>
                                    LUGAR DE ENTREGA
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="text" id="lugar_entrega" class="form-control"
                                       placeholder="Ingrese lugar de entrega">
                            <div id="error-lugar_entrega" class="field-error"></div>

                                    </div>

                            {{-- EVIDENCIA FOTOGRAFICA --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-camera"></i>
                                    EVIDENCIA FOTOGRAFICA (Consultado por la Alcaldía)
                                </label>
                                <div style="display: flex; gap: 0.75rem; align-items: center;">
                                    <input type="text" id="evidencia_fotografica" class="form-control"
                                           placeholder="Evidencia Fotográfica" readonly>
                                </div>
                                <div id="error-evidencia_fotografica" class="field-error"></div>
                            </div>

                            {{-- CONSULTA BD --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-database"></i>
                                    CONSULTA BD (Consultado por la Alcaldía)
                                </label>
                                <input type="text" id="consulta_bd" class="form-control"
                                       placeholder="Ingrese consulta BD" readonly>
                            </div>
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

                    {{-- Campo oculto para datos acumulados --}}
                    <input type="hidden" name="beneficiarios_acumulados" id="beneficiarios_acumulados" value="[]">

                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No se encontraron columnas de referencia. Complete al menos la descripción.
                    </div>
                @endif

                {{-- Acciones --}}
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Terminar y Guardar Proyecto
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
    let contador = 1;

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
        const condicion = document.getElementById('condicion');
        const fecha_nacimiento = document.getElementById('fecha_nacimiento');
        const fecha_expedicion = document.getElementById('fecha_expedicion');
        const corregimiento = document.getElementById('corregimiento');
        const vereda = document.getElementById('vereda');
        const sisben = document.getElementById('sisben');
        const finca = document.getElementById('finca');
        const telefono = document.getElementById('telefono');
        const lugar_entrega = document.getElementById('lugar_entrega');
        const evidencia_fotografica = document.getElementById('evidencia_fotografica');
        const evidencia_file = document.getElementById('evidencia_file');
        const consulta_bd = document.getElementById('consulta_bd');

        return {
            '# NUMERO': numero_sorteo.value.trim(),
            'CÉDULA': cedula.value.trim(),
            'NOMBRE COMPLETO': nombre_completo.value.trim(),
            'GENERO': genero.value.trim(),
            'CONDICIÓN': condicion.value.trim(),
            'FECHA DE NACIMIENTO': fecha_nacimiento.value.trim(),
            'FECHA DE EXPEDICIÓN': fecha_expedicion.value.trim(),
            'CORREGIMIENTO': corregimiento.value.trim(),
            'VEREDA': vereda.value.trim(),
            'SISBEN': sisben.value.trim(),
            'FINCA': finca.value.trim(),
            'TELÉFONO': telefono.value.trim(),
            'LUGAR DE ENTREGA': lugar_entrega.value.trim(),
            'EVIDENCIA FOTOGRAFICA': (evidencia_file && evidencia_file.files && evidencia_file.files[0])
                ? evidencia_file.files[0].name
                : evidencia_fotografica.value.trim(),
            'CONSULTA BD': consulta_bd.value.trim()
        };
    }

    /* =========================
       VALIDACIÓN
    ========================== */
    function validar() {
        limpiarTodosErrores();
        let valido = true;

        const requeridos = [
            'numero_sorteo',
            'cedula',
            'nombre_completo',
            'genero',
            'condicion',
            'fecha_nacimiento',
            'fecha_expedicion',
            'corregimiento',
            'vereda',
            'finca',
            'telefono',
            'lugar_entrega'
        ];

        requeridos.forEach(id => {
            const campo = document.getElementById(id);
            if (!campo.value.trim()) {
                mostrarErrorCampo(id, 'Este campo es obligatorio');
                valido = false;
            }
        });

        const evidenciaFile = document.getElementById('evidencia_file');
        const evidenciaNombre = document.getElementById('evidencia_fotografica');
        if (evidenciaFile && evidenciaFile.files && evidenciaFile.files[0]) {
            if (evidenciaNombre) {
                evidenciaNombre.value = evidenciaFile.files[0].name;
                limpiarErrorCampo('evidencia_fotografica');
            }
        }

        return valido;
    }

    /* =========================
       LIMPIAR FORMULARIO
    ========================== */
    function limpiarFormulario() {
        document.querySelectorAll('#beneficiario-form input, #beneficiario-form select')
            .forEach(c => c.value = '');
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
            validarCedulaExistente(cedula, function(duplicadoInfo) {
                if (duplicadoInfo.found_in_previous_year || duplicadoInfo.found_in_current_year) {
                    // Mostrar mensaje de advertencia en la página
                    mostrarMensajeAdvertencia(duplicadoInfo);
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
    function mostrarMensajeAdvertencia(duplicadoInfo) {
        const mensajeAdvertencia = document.getElementById('mensaje-advertencia');
        const detallesDiv = document.getElementById('mensaje-advertencia-detalles');

        let detalles = '';

        if (duplicadoInfo.found_in_previous_year) {
            detalles += `• En el año ${duplicadoInfo.previous_year}: ${duplicadoInfo.previous_year_projects.join(', ')}\n`;
        }

        if (duplicadoInfo.found_in_current_year) {
            detalles += `• En el año ${duplicadoInfo.current_year}: ${duplicadoInfo.current_year_projects.join(', ')}\n`;
        }

        detallesDiv.textContent = detalles.trim();
        mensajeAdvertencia.style.display = 'block';

        // Hacer scroll al mensaje
        mensajeAdvertencia.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /* =========================
       OCULTAR MENSAJE DE ADVERTENCIA
    ========================== */
    function ocultarMensajeAdvertencia() {
        const mensajeAdvertencia = document.getElementById('mensaje-advertencia');
        mensajeAdvertencia.style.display = 'none';
    }

    /* =========================
       AGREGAR BENEFICIARIO CONFIRMADO
    ========================== */
    function agregarBeneficiarioConfirmado() {
        // Ocultar mensaje de advertencia si está visible
        ocultarMensajeAdvertencia();

        // Proceder a agregar el beneficiario
        beneficiarios.push(obtenerDatos());
        inputAcumulados.value = JSON.stringify(beneficiarios);
        actualizarLista();
        limpiarFormulario();
        contador++;
        actualizarEstado();
    }

    /* =========================
       VALIDAR CÉDULA EXISTENTE
    ========================== */
    function validarCedulaExistente(cedula, callback) {
        // Obtener el año del proyecto actual desde la URL o variable global
        const currentYear = {{ $proyecto->ano }};

        fetch('{{ route("formularios.validar-cedula") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                cedula: cedula,
                current_year: currentYear
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error al validar cédula: ' + data.error);
                return;
            }
            callback(data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al validar cédula. Continuando...');
            callback({found_in_previous_year: false, found_in_current_year: false});
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
                <div>
                    <div class="beneficiario-nombre">${b['NOMBRE COMPLETO']}</div>
                    <div class="beneficiario-detalles">Cédula: ${b['CÉDULA']}</div>
                </div>
                <button class="btn-remove-item" onclick="eliminar(${i})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            lista.appendChild(div);
        });
        contenedorAgregados.style.display = beneficiarios.length ? 'block' : 'none';
    }

    window.eliminar = function (i) {
        beneficiarios.splice(i, 1);
        inputAcumulados.value = JSON.stringify(beneficiarios);
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

    form.addEventListener('submit', function (e) {
        if (beneficiarios.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un beneficiario.');
        }
    });

});
</script>

</x-app-layout>

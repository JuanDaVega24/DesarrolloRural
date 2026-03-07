<x-app-layout>
    @vite(['resources/css/pages/proyectos-productivos/constructor.css', 'resources/css/pages/formularios/imagenes.css', 'resources/css/pages/formularios/opciones-imagenes.css', 'resources/js/constructor-simple.js'])

    <div class="constructor-container">
        <div class="content-wrapper">
            {{-- === HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Constructor de Formulario</h1>
                    <p>Configura las preguntas para el proyecto: <strong>{{ $proyecto->nombre }}</strong></p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>

            {{-- === PASOS === --}}
            <div class="steps-container">
                <div class="step completed">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Datos del Proyecto</h4>
                        <p>{{ $proyecto->nombre }} - {{ $proyecto->ano }}</p>
                    </div>
                </div>
                <div class="step active">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Constructor de Formulario</h4>
                        <p>Agrega tus preguntas personalizadas</p>
                    </div>
                </div>
            </div>

            {{-- === FORMULARIO === --}}
            <form action="{{ route('proyectos.guardar-preguntas', $proyecto) }}" method="POST" id="main-form">
                @csrf
                <input type="hidden" name="preguntas_json" id="preguntas_json">

                @if ($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: 2rem; border-radius: 8px; padding: 1rem; background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c;">
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

                {{-- === CAMPOS ESTÁTICOS === --}}
                <div class="static-fields-section">
                    <h3>Campos Estáticos (siempre incluidos)</h3>
                    <p class="section-subtitle">Estos campos son fundamentales y siempre aparecerán en el formulario</p>
                    
                    <div class="static-fields-grid">
                        <div class="static-field-item">
                            <i class="fas fa-id-card"></i>
                            <div>
                                <strong>CÉDULA</strong>
                                <span class="field-type">Número</span>
                                <span class="field-required">Obligatorio</span>
                            </div>
                        </div>
                        <div class="static-field-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <strong>NOMBRE COMPLETO</strong>
                                <span class="field-type">Texto</span>
                                <span class="field-required">Obligatorio</span>
                            </div>
                        </div>
                        <div class="static-field-item">
                            <i class="fas fa-venus-mars"></i>
                            <div>
                                <strong>GÉNERO</strong>
                                <span class="field-type">Select</span>
                                <span class="field-required">Obligatorio</span>
                            </div>
                        </div>
                        <div class="static-field-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>CORREGIMIENTO</strong>
                                <span class="field-type">Select</span>
                                <span class="field-required">Obligatorio</span>
                            </div>
                        </div>
                        <div class="static-field-item">
                            <i class="fas fa-road"></i>
                            <div>
                                <strong>VEREDA</strong>
                                <span class="field-type">Select</span>
                                <span class="field-required">Obligatorio</span>
                            </div>
                        </div>
                        <div class="static-field-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>TELEFONO</strong>
                                <span class="field-type">Numero</span>
                                <span class="field-required">Obligatorio</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- === CAMPOS DINÁMICOS === --}}
                <div class="dynamic-fields-section">
                    <div class="section-header">
                        <h3>Preguntas Personalizadas</h3>
                        <p class="section-subtitle">Agrega las preguntas que deseas incluir en tu formulario</p>
                    </div>

                    <div id="questions-container" class="questions-container">
                        {{-- Las preguntas se agregarán aquí dinámicamente --}}
                    </div>

                    <div class="add-question-container" style="margin-top: 2rem; display: flex; justify-content: center;">
                        <button type="button" class="btn-add-question" id="btn-add-question">
                            <i class="fas fa-plus"></i>
                            Agregar Pregunta
                        </button>
                    </div>
                </div>

                {{-- === ACCIONES === --}}
                <div class="form-actions">
                    <button type="submit" class="btn-submit" id="btn-guardar-formulario">
                        <i class="fas fa-save"></i>
                        Guardar Formulario y Continuar
                    </button>
                    <a href="{{ route('proyectos.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- === TEMPLATE PARA PREGUNTAS === --}}
    <template id="question-template">
        <div class="question-item" data-question-index="__INDEX__">
            <div class="question-header">
                <div class="question-number">Pregunta <span class="number-display">__INDEX__</span></div>
                <button type="button" class="btn-remove-question" onclick="removeQuestion(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="question-form">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-question-circle"></i>
                        Texto de la Pregunta
                        <span class="required-indicator">*</span>
                    </label>
                    <input type="text" 
                           class="form-control question-text"
                           placeholder="Escribe tu pregunta aquí..."
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-info-circle"></i>
                        Subtítulo / Instrucciones (Opcional)
                    </label>
                    <textarea class="form-control question-subtitle" 
                              placeholder="Información adicional o instrucciones para esta pregunta..."
                              rows="2"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-cog"></i>
                            Tipo de Campo
                            <span class="required-indicator">*</span>
                        </label>
                        <select class="form-control question-type"
                                onchange="toggleOptions(this)">
                            <option value="">Selecciona un tipo</option>
                            <option value="texto">Texto</option>
                            <option value="numero">Número</option>
                            <option value="fecha">Fecha</option>
                            <option value="select">Select (Lista desplegable)</option>
                            <option value="checkbox">Checkbox (Opciones múltiples)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-asterisk"></i>
                            Obligatorio
                        </label>
                        <select class="form-control question-required">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                    </div>
                </div>

                <div class="options-container" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-list"></i>
                            Opciones
                            <span class="required-indicator">*</span>
                            <span class="field-info">(Una opción por línea)</span>
                        </label>
                        <div class="options-list" id="options-list-__INDEX__">
                            <!-- Las opciones se agregarán aquí dinámicamente -->
                        </div>
                        <div class="add-option-container">
                            <button type="button" class="btn-add-option" onclick="agregarOpcion(__INDEX__)">
                                <i class="fas fa-plus"></i>
                                Agregar Opción
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questionIndex = 0;
            const container = document.getElementById('questions-container');
            const template = document.getElementById('question-template');
            const btnAdd = document.getElementById('btn-add-question');
            const btnGuardar = document.getElementById('btn-guardar-formulario');

            // Cargar preguntas existentes o viejas (si hubo error)
            @php
                $preguntasBase = old('preguntas', $proyecto->preguntas->map(function($p) {
                    return [
                        'pregunta' => $p->pregunta,
                        'subtitulo' => $p->subtitulo,
                        'tipo_campo' => $p->tipo_campo,
                        'es_obligatorio' => $p->es_obligatorio,
                        'opciones' => $p->opciones
                    ];
                })->all());
            @endphp
            const oldPreguntas = @json($preguntasBase);

            if (oldPreguntas && oldPreguntas.length > 0) {
                oldPreguntas.forEach(p => {
                    addQuestion(p);
                });
            } else {
                // Agregar pregunta inicial si no hay ninguna
                addQuestion();
            }

            btnAdd.addEventListener('click', () => addQuestion());

            function addQuestion(data = null) {
                const clone = template.content.cloneNode(true);
                const questionItem = clone.querySelector('.question-item');
                
                // Actualizar índices
                questionItem.setAttribute('data-question-index', questionIndex);
                updateQuestionIndex(questionItem, questionIndex);
                
                container.appendChild(clone);
                
                // Si hay datos, poblarlos
                if (data) {
                    const currentItem = container.lastElementChild;
                    currentItem.querySelector('.question-text').value = data.pregunta || '';
                    currentItem.querySelector('.question-subtitle').value = data.subtitulo || '';
                    currentItem.querySelector('.question-type').value = data.tipo_campo || '';
                    currentItem.querySelector('.question-required').value = data.es_obligatorio ? '1' : '0';
                    
                    if (data.tipo_campo === 'select' || data.tipo_campo === 'checkbox') {
                        const optionsContainer = currentItem.querySelector('.options-container');
                        optionsContainer.style.display = 'block';
                        
                        if (data.opciones && Array.isArray(data.opciones)) {
                            data.opciones.forEach(opt => {
                                if (typeof window.agregarOpcion === 'function') {
                                    // agregarOpcion ya sabe manejar el index globalmente
                                    // Pero necesitamos pasarle los datos de la opción
                                    // Vamos a modificar agregarOpcion para aceptar datos opcionales
                                    window.agregarOpcion(questionIndex, opt);
                                }
                            });
                        }
                    }
                }

                // Hacer scroll a la nueva pregunta solo si no es carga inicial
                if (!data) {
                    const addedQuestion = container.lastElementChild;
                    if (addedQuestion) {
                        addedQuestion.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                questionIndex++;
                updateQuestionNumbers();
            }

            window.removeQuestion = function(button) {
                const questionItem = button.closest('.question-item');
                questionItem.remove();
                updateQuestionNumbers();
            }

            function updateQuestionIndex(element, newIndex) {
                const html = element.innerHTML
                    .replace(/__INDEX__/g, newIndex)
                    .replace(/data-question-index="__INDEX__"/g, `data-question-index="${newIndex}"`);
                element.innerHTML = html;
            }

            function updateQuestionNumbers() {
                const questions = document.querySelectorAll('.question-item');
                questions.forEach((question, index) => {
                    const numberDisplay = question.querySelector('.number-display');
                    if (numberDisplay) {
                        numberDisplay.textContent = index + 1;
                    }
                });
            }

            window.toggleOptions = function(select) {
                const questionItem = select.closest('.question-item');
                const optionsContainer = questionItem.querySelector('.options-container');
                const preguntaIndex = questionItem.getAttribute('data-question-index');
                
                if (select.value === 'select' || select.value === 'checkbox') {
                    optionsContainer.style.display = 'block';
                    
                    // Asegurar que los inputs tengan required si están visibles
                    const inputs = optionsContainer.querySelectorAll('.opcion-texto');
                    inputs.forEach(input => input.setAttribute('required', 'required'));
                    
                    // Si no hay opciones, agregar una por defecto
                    const optionsList = document.getElementById(`options-list-${preguntaIndex}`);
                    if (optionsList && optionsList.children.length === 0) {
                        if (typeof window.agregarOpcion === 'function') {
                            window.agregarOpcion(preguntaIndex);
                        }
                    }
                } else {
                    optionsContainer.style.display = 'none';
                    
                    // Quitar required si están ocultos para evitar errores de "not focusable"
                    const inputs = optionsContainer.querySelectorAll('.opcion-texto');
                    inputs.forEach(input => input.removeAttribute('required'));
                }
            }

            // Validación antes de enviar
            document.getElementById('main-form').addEventListener('submit', function(e) {
                const questions = document.querySelectorAll('.question-item');
                let hasQuestions = questions.length > 0;
                let validQuestions = true;
                let mensajeError = '';
                const preguntasData = [];

                questions.forEach((question, idx) => {
                    const text = question.querySelector('.question-text').value.trim();
                    const subtitle = question.querySelector('.question-subtitle').value.trim();
                    const type = question.querySelector('.question-type').value;
                    const esObligatorio = question.querySelector('.question-required').value;
                    const questionNum = idx + 1;
                    
                    if (!text) {
                        validQuestions = false;
                        mensajeError += `- Pregunta ${questionNum}: Falta el texto de la pregunta.\n`;
                    }
                    if (!type) {
                        validQuestions = false;
                        mensajeError += `- Pregunta ${questionNum}: Debes seleccionar un tipo de campo.\n`;
                    }

                    const opciones = [];
                    if (type === 'select' || type === 'checkbox') {
                        const optionsList = question.querySelector('.options-list');
                        const optionInputs = optionsList.querySelectorAll('.opcion-item');
                        
                        if (optionInputs.length === 0) {
                            validQuestions = false;
                            mensajeError += `- Pregunta ${questionNum}: Debe tener al menos una opción.\n`;
                        } else {
                            let hasFilledOption = false;
                            optionInputs.forEach(optDiv => {
                                const texto = optDiv.querySelector('.opcion-texto').value.trim();
                                const imagen = optDiv.querySelector('.input-url-imagen-simple').value;
                                
                                if (texto !== '') {
                                    hasFilledOption = true;
                                    opciones.push({ texto, imagen });
                                }
                            });
                            
                            if (!hasFilledOption) {
                                validQuestions = false;
                                mensajeError += `- Pregunta ${questionNum}: Debe completar al menos una opción.\n`;
                            }
                        }
                    }

                    preguntasData.push({
                        pregunta: text,
                        subtitulo: subtitle,
                        tipo_campo: type,
                        es_obligatorio: esObligatorio,
                        opciones: opciones
                    });
                });

                if (!hasQuestions) {
                    e.preventDefault();
                    alert('Debes agregar al menos una pregunta personalizada.');
                    return;
                }

                if (!validQuestions) {
                    e.preventDefault();
                    alert('Por favor, completa todos los campos requeridos:\n' + mensajeError);
                    return;
                }

                // Guardar en el campo JSON y DESACTIVAR los nombres individuales para evitar saturar max_input_vars
                document.getElementById('preguntas_json').value = JSON.stringify(preguntasData);
                
                // Opcional: remover nombres para que no se envíen por duplicado
                // questions.forEach(q => {
                //     q.querySelectorAll('input, select').forEach(i => i.removeAttribute('name'));
                // });
            });
        });
    </script>
</x-app-layout>
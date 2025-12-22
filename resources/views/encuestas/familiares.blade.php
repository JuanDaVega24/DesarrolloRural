<x-app-layout>
<x-steps :progress="100" :current="11" :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Gestión Agropecuaria','Predio','Control Actividades','Familiares','Final']" />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <form method="POST" action="{{ route('familiares.guardarFamiliares') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
                    @csrf

                    {{-- ERRORES --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Faltan datos por llenar:</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- SECCIÓN FAMILIARES --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people-fill me-2"></i>Información de Familiares
                            </h5>
                            <small class="text-muted">Complete la información de todos los miembros del hogar</small>
                        </div>
                        <div class="card-body">
                            {{-- Contenedor de familiares --}}
                            <div id="familiares-container">
                                {{-- Familiar inicial --}}
                                <div class="familiar-item border rounded p-4 mb-4" style="background-color: #ffffff;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-semibold text-primary">
                                            <i class="bi bi-person-circle me-2"></i>Familiar 1
                                        </h6>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Nombre Completo *</label>
                                            <input type="text" name="familiares[0][nombre_completo]"
                                                   class="form-control border-primary"
                                                   value="{{ old('familiares.0.nombre_completo') }}"
                                                   required>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Fecha de Nacimiento</label>
                                            <input type="date" name="familiares[0][fecha_nacimiento]"
                                                   class="form-control border-primary"
                                                   value="{{ old('familiares.0.fecha_nacimiento') }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Tipo Documento</label>
                                            <select name="familiares[0][tipo_documento]" class="form-select border-primary">
                                                <option value="">Seleccione</option>
                                                <option value="CC" {{ old('familiares.0.tipo_documento') == 'CC' ? 'selected' : '' }}>CC</option>
                                                <option value="TI" {{ old('familiares.0.tipo_documento') == 'TI' ? 'selected' : '' }}>TI</option>
                                                <option value="RC" {{ old('familiares.0.tipo_documento') == 'RC' ? 'selected' : '' }}>RC</option>
                                                <option value="PEP" {{ old('familiares.0.tipo_documento') == 'PEP' ? 'selected' : '' }}>PEP</option>
                                                <option value="PAS" {{ old('familiares.0.tipo_documento') == 'PAS' ? 'selected' : '' }}>PAS</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Número Documento</label>
                                            <input type="text" name="familiares[0][documento]"
                                                   class="form-control border-primary"
                                                   value="{{ old('familiares.0.documento') }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Fecha Expedición</label>
                                            <input type="date" name="familiares[0][fecha_expedicion]"
                                                   class="form-control border-primary"
                                                   value="{{ old('familiares.0.fecha_expedicion') }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Lugar Expedición</label>
                                            <input type="text" name="familiares[0][lugar_expedicion]"
                                                   class="form-control border-primary"
                                                   value="{{ old('familiares.0.lugar_expedicion') }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Parentesco</label>
                                            <select name="familiares[0][parentesco]" class="form-select border-primary">
                                                <option value="">Seleccione</option>
                                                <option value="Cabeza del hogar" {{ old('familiares.0.parentesco') == 'Cabeza del hogar' ? 'selected' : '' }}>Cabeza del hogar</option>
                                                <option value="Pareja" {{ old('familiares.0.parentesco') == 'Pareja' ? 'selected' : '' }}>Pareja</option>
                                                <option value="Hijo" {{ old('familiares.0.parentesco') == 'Hijo' ? 'selected' : '' }}>Hijo(a)</option>
                                                <option value="Yerno/Nuera" {{ old('familiares.0.parentesco') == 'Yerno/Nuera' ? 'selected' : '' }}>Yerno/Nuera</option>
                                                <option value="Nieto" {{ old('familiares.0.parentesco') == 'Nieto' ? 'selected' : '' }}>Nieto(a)</option>
                                                <option value="Hermano (a) hermanastro (a)" {{ old('familiares.0.parentesco') == 'Hermano (a) hermanastro (a)' ? 'selected' : '' }}>Hermano (a) hermanastro (a)</option>
                                                <option value="Otro pariente" {{ old('familiares.0.parentesco') == 'Otro pariente' ? 'selected' : '' }}>Otro pariente</option>
                                                <option value="Empleado (a) domestico (a)" {{ old('familiares.0.parentesco') == 'Empleado (a) domestico (a)' ? 'selected' : '' }}>Empleado (a) domestico (a)</option>
                                                <option value="Otro no pariente" {{ old('familiares.0.parentesco') == 'Otro no pariente' ? 'selected' : '' }}>Otro no pariente</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Género</label>
                                            <select name="familiares[0][genero]" class="form-select border-primary">
                                                <option value="">Seleccione</option>
                                                <option value="Masculino" {{ old('familiares.0.genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                                <option value="Femenino" {{ old('familiares.0.genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                                <option value="No binario" {{ old('familiares.0.genero') == 'No binario' ? 'selected' : '' }}>No binario</option>
                                                <option value="Otro" {{ old('familiares.0.genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Población</label>
                                            <select name="familiares[0][poblacion]" class="form-select border-primary">
                                                <option value="">Seleccione</option>
                                                <option value="Indígena" {{ old('familiares.0.poblacion') == 'Indígena' ? 'selected' : '' }}>Indígena</option>
                                                <option value="Gitano" {{ old('familiares.0.poblacion') == 'Gitano' ? 'selected' : '' }}>Gitano (ROM)</option>
                                                <option value="Raizal" {{ old('familiares.0.poblacion') == 'Raizal' ? 'selected' : '' }}>Raizal</option>
                                                <option value="Negro" {{ old('familiares.0.poblacion') == 'Negro' ? 'selected' : '' }}>Negro</option>
                                                <option value="Palenquero" {{ old('familiares.0.poblacion') == 'Palenquero' ? 'selected' : '' }}>Palenquero</option>
                                                <option value="Ninguna" {{ old('familiares.0.poblacion') == 'Ninguna' ? 'selected' : '' }}>Ninguna</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Condición</label>
                                            <select name="familiares[0][condicion]" id="condicion0" class="form-select border-primary" onchange="toggleCondicionOtro(0)">
                                                <option value="">Seleccione</option>
                                                <option value="Afrocolombiano" {{ old('familiares.0.condicion') == 'Afrocolombiano' ? 'selected' : '' }}>Afrocolombiano</option>
                                                <option value="Campesino" {{ old('familiares.0.condicion') == 'Campesino' ? 'selected' : '' }}>Campesino</option>
                                                <option value="Indígena" {{ old('familiares.0.condicion') == 'Indígena' ? 'selected' : '' }}>Indígena</option>
                                                <option value="LGBTIQ+" {{ old('familiares.0.condicion') == 'LGBTIQ+' ? 'selected' : '' }}>LGBTIQ+</option>
                                                <option value="Persona mayor" {{ old('familiares.0.condicion') == 'Persona mayor' ? 'selected' : '' }}>Persona mayor</option>
                                                <option value="Cabeza de familia" {{ old('familiares.0.condicion') == 'Cabeza de familia' ? 'selected' : '' }}>Cabeza de familia</option>
                                                <option value="Mujer rural" {{ old('familiares.0.condicion') == 'Mujer rural' ? 'selected' : '' }}>Mujer rural</option>
                                                <option value="Desmovilizado" {{ old('familiares.0.condicion') == 'Desmovilizado' ? 'selected' : '' }}>Desmovilizado</option>
                                                <option value="Reinsertado" {{ old('familiares.0.condicion') == 'Reinsertado' ? 'selected' : '' }}>Reinsertado</option>
                                                <option value="joven rural" {{ old('familiares.0.condicion') == 'joven rural' ? 'selected' : '' }}>joven rural</option>
                                                <option value="persona con discapacidad" {{ old('familiares.0.condicion') == 'persona con discapacidad' ? 'selected' : '' }}>persona con discapacidad</option>
                                                <option value="victima del conflicto (RUV)" {{ old('familiares.0.condicion') == 'victima del conflicto (RUV)' ? 'selected' : '' }}>victima del conflicto (RUV)</option>
                                                <option value="cuidador/a" {{ old('familiares.0.condicion') == 'cuidador/a' ? 'selected' : '' }}>cuidador/a</option>
                                                <option value="Otro" {{ old('familiares.0.condicion') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2 mt-2" id="condicionOtroDiv0" style="display: none;">
                                            <label class="form-label fw-semibold">Especifique condición</label>
                                            <input type="text" name="familiares[0][condicion_otro]"
                                                   class="form-control border-primary"
                                                   value="{{ old('familiares.0.condicion_otro') }}"
                                                   placeholder="Especifique cuál">
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label fw-semibold">¿Sabe leer?</label>
                                            <select name="familiares[0][sabe_leer]" class="form-select border-primary">
                                                <option value="">Seleccione</option>
                                                <option value="1" {{ old('familiares.0.sabe_leer') == '1' ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ old('familiares.0.sabe_leer') == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label fw-semibold">¿Estudia?</label>
                                            <select name="familiares[0][estudia]" class="form-select border-primary">
                                                <option value="">Seleccione</option>
                                                <option value="1" {{ old('familiares.0.estudia') == '1' ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ old('familiares.0.estudia') == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Nivel Educativo</label>
                                            <select name="familiares[0][nivel_educativo]" class="form-select border-primary">
                                                <option value="">Seleccione</option>
                                                <option value="Ninguno" {{ old('familiares.0.nivel_educativo') == 'Ninguno' ? 'selected' : '' }}>Ninguno</option>
                                                <option value="Preescolar" {{ old('familiares.0.nivel_educativo') == 'Preescolar' ? 'selected' : '' }}>Preescolar</option>
                                                <option value="Básica primaria" {{ old('familiares.0.nivel_educativo') == 'Básica primaria' ? 'selected' : '' }}>Básica primaria</option>
                                                <option value="Básica secundaria" {{ old('familiares.0.nivel_educativo') == 'Básica secundaria' ? 'selected' : '' }}>Básica secundaria</option>
                                                <option value="Media" {{ old('familiares.0.nivel_educativo') == 'Media' ? 'selected' : '' }}>Media</option>
                                                <option value="Técnico" {{ old('familiares.0.nivel_educativo') == 'Técnico' ? 'selected' : '' }}>Técnico</option>
                                                <option value="Tecnológico" {{ old('familiares.0.nivel_educativo') == 'Tecnológico' ? 'selected' : '' }}>Tecnológico</option>
                                                <option value="Universitario" {{ old('familiares.0.nivel_educativo') == 'Universitario' ? 'selected' : '' }}>Universitario</option>
                                                <option value="Postgrado" {{ old('familiares.0.nivel_educativo') == 'Postgrado' ? 'selected' : '' }}>Postgrado</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Celular</label>
                                            <input type="text" name="familiares[0][celular]"
                                                   class="form-control border-primary"
                                                   value="{{ old('familiares.0.celular') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botón para añadir familiar --}}
                            <div class="text-end mb-4">
                                <button type="button" class="btn btn-primary" id="add-familiar">
                                    <i class="bi bi-plus-circle me-2"></i>Añadir Familiar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('encuestas.control_actividades') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-arrow-left-circle me-2"></i> Volver
                        </a>

                        <button type="submit" class="btn btn-success btn-lg" onclick="console.log('Enviando formulario...'); console.log(document.querySelector('form').elements);">
                            <i class="bi bi-check-circle-fill me-2"></i> Continuar a Afectaciones
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let familiarIndex = 1;

            // Función para añadir un nuevo familiar
            function addFamiliar() {
                const container = document.getElementById('familiares-container');
                const newIndex = familiarIndex;

                const familiarHtml = `
                    <div class="familiar-item border rounded p-4 mb-4" style="background-color: #ffffff;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold text-primary">
                                <i class="bi bi-person-circle me-2"></i>Familiar ${newIndex + 1}
                            </h6>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-familiar">
                                <i class="bi bi-trash"></i> Remover
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nombre Completo *</label>
                                <input type="text" name="familiares[${newIndex}][nombre_completo]"
                                       class="form-control border-primary" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Fecha de Nacimiento</label>
                                <input type="date" name="familiares[${newIndex}][fecha_nacimiento]"
                                       class="form-control border-primary">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Tipo Documento</label>
                                <select name="familiares[${newIndex}][tipo_documento]" class="form-select border-primary">
                                    <option value="">Seleccione</option>
                                    <option value="CC">CC</option>
                                    <option value="TI">TI</option>
                                    <option value="RC">RC</option>
                                    <option value="PEP">PEP</option>
                                    <option value="PAS">PAS</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Número Documento</label>
                                <input type="text" name="familiares[${newIndex}][documento]"
                                       class="form-control border-primary">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Fecha Expedición</label>
                                <input type="date" name="familiares[${newIndex}][fecha_expedicion]"
                                       class="form-control border-primary">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Lugar Expedición</label>
                                <input type="text" name="familiares[${newIndex}][lugar_expedicion]"
                                       class="form-control border-primary">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Parentesco</label>
                                <select name="familiares[${newIndex}][parentesco]" class="form-select border-primary">
                                    <option value="">Seleccione</option>
                                    <option value="Cabeza del hogar">Cabeza del hogar</option>
                                    <option value="Pareja">Pareja</option>
                                    <option value="Hijo">Hijo(a)</option>
                                    <option value="Yerno/Nuera">Yerno/Nuera</option>
                                    <option value="Nieto">Nieto(a)</option>
                                    <option value="Hermano (a) hermanastro (a)">Hermano (a) hermanastro (a)</option>
                                    <option value="Otro pariente">Otro pariente</option>
                                    <option value="Empleado (a) domestico (a)">Empleado (a) domestico (a)</option>
                                    <option value="Otro no pariente">Otro no pariente</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Género</label>
                                <select name="familiares[${newIndex}][genero]" class="form-select border-primary">
                                    <option value="">Seleccione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="No binario">No binario</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Población</label>
                                <select name="familiares[${newIndex}][poblacion]" class="form-select border-primary">
                                    <option value="">Seleccione</option>
                                    <option value="Indígena">Indígena</option>
                                    <option value="Gitano">Gitano (ROM)</option>
                                    <option value="Raizal">Raizal</option>
                                    <option value="Negro">Negro</option>
                                    <option value="Palenquero">Palenquero</option>
                                    <option value="Ninguna">Ninguna</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Condición</label>
                                <select name="familiares[${newIndex}][condicion]" id="condicion${newIndex}" class="form-select border-primary" onchange="toggleCondicionOtro(${newIndex})">
                                    <option value="">Seleccione</option>
                                    <option value="Afrocolombiano">Afrocolombiano</option>
                                    <option value="Campesino">Campesino</option>
                                    <option value="Indígena">Indígena</option>
                                    <option value="LGBTIQ+">LGBTIQ+</option>
                                    <option value="Persona mayor">Persona mayor</option>
                                    <option value="Cabeza de familia">Cabeza de familia</option>
                                    <option value="Mujer rural">Mujer rural</option>
                                    <option value="Desmovilizado">Desmovilizado</option>
                                    <option value="Reinsertado">Reinsertado</option>
                                    <option value="joven rural">joven rural</option>
                                    <option value="persona con discapacidad">persona con discapacidad</option>
                                    <option value="victima del conflicto (RUV)">victima del conflicto (RUV)</option>
                                    <option value="cuidador/a">cuidador/a</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <div class="col-md-2 mt-2" id="condicionOtroDiv${newIndex}" style="display: none;">
                                <label class="form-label fw-semibold">Especifique condición</label>
                                <input type="text" name="familiares[${newIndex}][condicion_otro]"
                                       class="form-control border-primary"
                                       placeholder="Especifique cuál">
                            </div>

                            <div class="col-md-1">
                                <label class="form-label fw-semibold">¿Sabe leer?</label>
                                <select name="familiares[${newIndex}][sabe_leer]" class="form-select border-primary">
                                    <option value="">Seleccione</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-md-1">
                                <label class="form-label fw-semibold">¿Estudia?</label>
                                <select name="familiares[${newIndex}][estudia]" class="form-select border-primary">
                                    <option value="">Seleccione</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Nivel Educativo</label>
                                <select name="familiares[${newIndex}][nivel_educativo]" class="form-select border-primary">
                                    <option value="">Seleccione</option>
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Preescolar">Preescolar</option>
                                    <option value="Básica primaria">Básica primaria</option>
                                    <option value="Básica secundaria">Básica secundaria</option>
                                    <option value="Media">Media</option>
                                    <option value="Técnico">Técnico</option>
                                    <option value="Tecnológico">Tecnológico</option>
                                    <option value="Universitario">Universitario</option>
                                    <option value="Postgrado">Postgrado</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Celular</label>
                                <input type="text" name="familiares[${newIndex}][celular]"
                                       class="form-control border-primary">
                            </div>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', familiarHtml);
                familiarIndex++;
            }

            // Función para remover familiar
            function removeFamiliar(event) {
                if (event.target.classList.contains('remove-familiar') || event.target.closest('.remove-familiar')) {
                    event.target.closest('.familiar-item').remove();
                }
            }

            // Función para mostrar/ocultar input de condición otro
            function toggleCondicionOtro(index) {
                const select = document.getElementById(`condicion${index}`);
                const otroDiv = document.getElementById(`condicionOtroDiv${index}`);

                if (!select || !otroDiv) return;

                otroDiv.style.display = select.value === 'Otro' ? 'block' : 'none';
            }

            // Inicializar el estado del input "Otro" al cargar la página
            toggleCondicionOtro(0);

            // Event listeners
            const addFamiliarBtn = document.getElementById('add-familiar');
            if (addFamiliarBtn) {
                addFamiliarBtn.addEventListener('click', addFamiliar);
            }

            const container = document.getElementById('familiares-container');
            if (container) {
                container.addEventListener('click', removeFamiliar);
            }
        });
    </script>

    <style>
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(45, 95, 63, 0.25);
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn:hover {
            background-color: #1e4430 !important;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background-color: #0f5132 !important;
        }
        .familiar-item {
            transition: all 0.3s ease;
        }
        .familiar-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .remove-familiar {
            transition: all 0.2s ease;
        }
        .remove-familiar:hover {
            transform: scale(1.1);
        }
    </style>

</x-app-layout>

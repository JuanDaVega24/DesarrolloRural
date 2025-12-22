<x-app-layout>

    <style>
        :root {
            --verde: #4A7C2F;
            --verde-hover: #3d6625;
            --verde-claro: #E8F5E0;
        }
    </style>

    <div class="py-4">
        <div class="container">

            {{-- Header con gradiente --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h2 class="mb-1" style="color:#2d5f3f; font-weight:700;">
                            <i class="bi bi-pencil-square me-2"></i>Editar Familiares
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $encuesta->nombre_identidad }} {{ $encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $encuesta->id }}
                        </p>
                    </div>

                    <a href="{{ route('familiares.show', $encuesta->id) }}"
                       class="btn btn-outline-secondary px-4 py-2"
                       style="border-radius:8px; font-weight:500;">
                       <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('familiares.guardarFamiliares') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
                @csrf
                <input type="hidden" name="modo" value="edit">
                <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">

                <div class="row g-4">

                    {{-- Columna Principal --}}
                    <div class="col-lg-12">

                        {{-- MENSAJE DE ERRORES --}}
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
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-people me-2"></i>Información de Familiares
                                </h5>
                            </div>
                            <div class="card-body">

                                <div id="familiares-container">
                                    @if($familiares->isNotEmpty())
                                        @foreach($familiares as $index => $familiar)
                                            <div class="familiar-item border rounded p-3 mb-3" data-index="{{ $index }}">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="mb-0 fw-semibold">
                                                        <i class="bi bi-person-circle me-2"></i>Familiar {{ $index + 1 }}
                                                    </h6>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-familiar" {{ $familiares->count() <= 1 ? 'disabled' : '' }}>
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                <div class="row g-3">
                                                    {{-- Nombre Completo --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Nombre Completo *</label>
                                                        <input type="text" name="familiares[{{ $index }}][nombre_completo]"
                                                               class="form-control border-primary"
                                                               value="{{ old('familiares.' . $index . '.nombre_completo', $familiar->nombre_completo) }}"
                                                               required>
                                                    </div>

                                                    {{-- Fecha de Nacimiento --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Fecha de Nacimiento</label>
                                                        <input type="date" name="familiares[{{ $index }}][fecha_nacimiento]"
                                                               class="form-control border-primary"
                                                               value="{{ old('familiares.' . $index . '.fecha_nacimiento', $familiar->fecha_nacimiento ? $familiar->fecha_nacimiento->format('Y-m-d') : '') }}">
                                                    </div>

                                                    {{-- Tipo de Documento --}}
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Tipo de Documento</label>
                                                        <select name="familiares[{{ $index }}][tipo_documento]" class="form-select border-primary">
                                                            <option value="" {{ old('familiares.' . $index . '.tipo_documento', $familiar->tipo_documento) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="CC" {{ old('familiares.' . $index . '.tipo_documento', $familiar->tipo_documento) == 'CC' ? 'selected' : '' }}>CC</option>
                                                            <option value="TI" {{ old('familiares.' . $index . '.tipo_documento', $familiar->tipo_documento) == 'TI' ? 'selected' : '' }}>TI</option>
                                                            <option value="RC" {{ old('familiares.' . $index . '.tipo_documento', $familiar->tipo_documento) == 'RC' ? 'selected' : '' }}>RC</option>
                                                            <option value="PEP" {{ old('familiares.' . $index . '.tipo_documento', $familiar->tipo_documento) == 'PEP' ? 'selected' : '' }}>PEP</option>
                                                            <option value="PAS" {{ old('familiares.' . $index . '.tipo_documento', $familiar->tipo_documento) == 'PAS' ? 'selected' : '' }}>PAS</option>
                                                        </select>
                                                    </div>

                                                    {{-- Documento --}}
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Número de Documento</label>
                                                        <input type="text" name="familiares[{{ $index }}][documento]"
                                                               class="form-control border-primary"
                                                               value="{{ old('familiares.' . $index . '.documento', $familiar->documento) }}">
                                                    </div>

                                                    {{-- Fecha de Expedición --}}
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Fecha de Expedición</label>
                                                        <input type="date" name="familiares[{{ $index }}][fecha_expedicion]"
                                                               class="form-control border-primary"
                                                               value="{{ old('familiares.' . $index . '.fecha_expedicion', $familiar->fecha_expedicion ? $familiar->fecha_expedicion->format('Y-m-d') : '') }}">
                                                    </div>

                                                    {{-- Lugar de Expedición --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Lugar de Expedición</label>
                                                        <input type="text" name="familiares[{{ $index }}][lugar_expedicion]"
                                                               class="form-control border-primary"
                                                               value="{{ old('familiares.' . $index . '.lugar_expedicion', $familiar->lugar_expedicion) }}">
                                                    </div>

                                                    {{-- Parentesco --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Parentesco</label>
                                                        <select name="familiares[{{ $index }}][parentesco]" class="form-select border-primary">
                                                            <option value="" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="Cabeza del hogar" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) == 'Cabeza del hogar' ? 'selected' : '' }}>Cabeza del hogar</option>
                                                            <option value="Pareja" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) == 'Pareja' ? 'selected' : '' }}>Pareja</option>
                                                            <option value="Hijo" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) == 'Hijo' ? 'selected' : '' }}>Hijo(a)</option>
                                                            <option value="Yerno/Nuera" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) == 'Yerno/Nuera' ? 'selected' : '' }}>Yerno/Nuera</option>
                                                            <option value="Nieto" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) == 'Nieto' ? 'selected' : '' }}>Nieto(a)</option>
                                                            <option value="Hermano (a) hermanastro (a)" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) == 'Hermano (a) hermanastro (a)' ? 'selected' : '' }}>Hermano (a) hermanastro (a)</option>
                                                            <option value="Otro pariente" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) == 'Otro pariente' ? 'selected' : '' }}>Otro pariente</option>
                                                            <option value="Empleado (a) domestico (a)" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) == 'Empleado (a) domestico (a)' ? 'selected' : '' }}>Empleado (a) domestico (a)</option>
                                                            <option value="Otro no pariente" {{ old('familiares.' . $index . '.parentesco', $familiar->parentesco) == 'Otro no pariente' ? 'selected' : '' }}>Otro no pariente</option>
                                                        </select>
                                                    </div>

                                                    {{-- Género --}}
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Género</label>
                                                        <select name="familiares[{{ $index }}][genero]" class="form-select border-primary">
                                                            <option value="" {{ old('familiares.' . $index . '.genero', $familiar->genero) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="Masculino" {{ old('familiares.' . $index . '.genero', $familiar->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                                            <option value="Femenino" {{ old('familiares.' . $index . '.genero', $familiar->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                                            <option value="No binario" {{ old('familiares.' . $index . '.genero', $familiar->genero) == 'No binario' ? 'selected' : '' }}>No binario</option>
                                                            <option value="Otro" {{ old('familiares.' . $index . '.genero', $familiar->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                                        </select>
                                                    </div>

                                                    {{-- Población --}}
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Población</label>
                                                        <select name="familiares[{{ $index }}][poblacion]" class="form-select border-primary">
                                                            <option value="" {{ old('familiares.' . $index . '.poblacion', $familiar->poblacion) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="Indígena" {{ old('familiares.' . $index . '.poblacion', $familiar->poblacion) == 'Indígena' ? 'selected' : '' }}>Indígena</option>
                                                            <option value="Gitano" {{ old('familiares.' . $index . '.poblacion', $familiar->poblacion) == 'Gitano' ? 'selected' : '' }}>Gitano (ROM)</option>
                                                            <option value="Raizal" {{ old('familiares.' . $index . '.poblacion', $familiar->poblacion) == 'Raizal' ? 'selected' : '' }}>Raizal</option>
                                                            <option value="Negro" {{ old('familiares.' . $index . '.poblacion', $familiar->poblacion) == 'Negro' ? 'selected' : '' }}>Negro</option>
                                                            <option value="Palenquero" {{ old('familiares.' . $index . '.poblacion', $familiar->poblacion) == 'Palenquero' ? 'selected' : '' }}>Palenquero</option>
                                                            <option value="Ninguna" {{ old('familiares.' . $index . '.poblacion', $familiar->poblacion) == 'Ninguna' ? 'selected' : '' }}>Ninguna</option>
                                                        </select>
                                                    </div>

                                                    {{-- Condición --}}
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Condición</label>
                                                        <select name="familiares[{{ $index }}][condicion]" id="condicion{{ $index }}" class="form-select border-primary" onchange="toggleCondicionOtro({{ $index }})">
                                                            <option value="" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="Afrocolombiano" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'Afrocolombiano' ? 'selected' : '' }}>Afrocolombiano</option>
                                                            <option value="Campesino" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'Campesino' ? 'selected' : '' }}>Campesino</option>
                                                            <option value="Indígena" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'Indígena' ? 'selected' : '' }}>Indígena</option>
                                                            <option value="LGBTIQ+" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'LGBTIQ+' ? 'selected' : '' }}>LGBTIQ+</option>
                                                            <option value="Persona mayor" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'Persona mayor' ? 'selected' : '' }}>Persona mayor</option>
                                                            <option value="Cabeza de familia" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'Cabeza de familia' ? 'selected' : '' }}>Cabeza de familia</option>
                                                            <option value="Mujer rural" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'Mujer rural' ? 'selected' : '' }}>Mujer rural</option>
                                                            <option value="Desmovilizado" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'Desmovilizado' ? 'selected' : '' }}>Desmovilizado</option>
                                                            <option value="Reinsertado" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'Reinsertado' ? 'selected' : '' }}>Reinsertado</option>
                                                            <option value="joven rural" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'joven rural' ? 'selected' : '' }}>joven rural</option>
                                                            <option value="persona con discapacidad" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'persona con discapacidad' ? 'selected' : '' }}>persona con discapacidad</option>
                                                            <option value="victima del conflicto (RUV)" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'victima del conflicto (RUV)' ? 'selected' : '' }}>victima del conflicto (RUV)</option>
                                                            <option value="cuidador/a" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'cuidador/a' ? 'selected' : '' }}>cuidador/a</option>
                                                            <option value="Otro" {{ old('familiares.' . $index . '.condicion', $familiar->condicion) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 mt-2" id="condicionOtroDiv{{ $index }}" style="display: none;">
                                                        <label class="form-label fw-semibold">Especifique condición</label>
                                                        <input type="text" name="familiares[{{ $index }}][condicion_otro]"
                                                               class="form-control border-primary"
                                                               value="{{ old('familiares.' . $index . '.condicion_otro', $familiar->condicion_otro ?? '') }}"
                                                               placeholder="Especifique cuál">
                                                    </div>

                                                    {{-- Sabe Leer --}}
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">¿Sabe leer?</label>
                                                        <select name="familiares[{{ $index }}][sabe_leer]" class="form-select border-primary">
                                                            <option value="" {{ old('familiares.' . $index . '.sabe_leer', $familiar->sabe_leer) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="1" {{ old('familiares.' . $index . '.sabe_leer', $familiar->sabe_leer) == '1' ? 'selected' : '' }}>Sí</option>
                                                            <option value="0" {{ old('familiares.' . $index . '.sabe_leer', $familiar->sabe_leer) == '0' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>

                                                    {{-- Estudia --}}
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">¿Estudia?</label>
                                                        <select name="familiares[{{ $index }}][estudia]" class="form-select border-primary">
                                                            <option value="" {{ old('familiares.' . $index . '.estudia', $familiar->estudia) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="1" {{ old('familiares.' . $index . '.estudia', $familiar->estudia) == '1' ? 'selected' : '' }}>Sí</option>
                                                            <option value="0" {{ old('familiares.' . $index . '.estudia', $familiar->estudia) == '0' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>

                                                    {{-- Nivel Educativo --}}
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Nivel Educativo</label>
                                                        <select name="familiares[{{ $index }}][nivel_educativo]" class="form-select border-primary">
                                                            <option value="" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="Ninguno" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) == 'Ninguno' ? 'selected' : '' }}>Ninguno</option>
                                                            <option value="Preescolar" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) == 'Preescolar' ? 'selected' : '' }}>Preescolar</option>
                                                            <option value="Básica primaria" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) == 'Básica primaria' ? 'selected' : '' }}>Básica primaria</option>
                                                            <option value="Básica secundaria" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) == 'Básica secundaria' ? 'selected' : '' }}>Básica secundaria</option>
                                                            <option value="Media" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) == 'Media' ? 'selected' : '' }}>Media</option>
                                                            <option value="Técnico" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) == 'Técnico' ? 'selected' : '' }}>Técnico</option>
                                                            <option value="Tecnológico" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) == 'Tecnológico' ? 'selected' : '' }}>Tecnológico</option>
                                                            <option value="Universitario" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) == 'Universitario' ? 'selected' : '' }}>Universitario</option>
                                                            <option value="Postgrado" {{ old('familiares.' . $index . '.nivel_educativo', $familiar->nivel_educativo) == 'Postgrado' ? 'selected' : '' }}>Postgrado</option>
                                                        </select>
                                                    </div>

                                                    {{-- Celular --}}
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Celular</label>
                                                        <input type="text" name="familiares[{{ $index }}][celular]"
                                                               class="form-control border-primary"
                                                               value="{{ old('familiares.' . $index . '.celular', $familiar->celular) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                {{-- Botón Agregar Familiar --}}
                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-success" id="add-familiar">
                                        <i class="bi bi-plus-circle me-2"></i>Agregar Familiar
                                    </button>
                                </div>

                            </div>
                        </div>

                        {{-- CARD: Botones de Acción --}}
                        <div class="card shadow-sm border-0" style="border-radius:12px; overflow:hidden; border:2px solid #2d5f3f;">
                            <div class="card-body p-3">
                                <h6 class="mb-3 fw-semibold" style="color:#2d5f3f;">
                                   Guardar Cambios
                                </h6>

                                <div class="d-grid gap-2">
                                    <button type="submit"
                                            class="btn text-white fw-semibold py-2"
                                            style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); border-radius:8px;">
                                        <i class="bi bi-check-circle me-2"></i>Actualizar Familiares
                                    </button>

                                    <a href="{{ route('familiares.show', $encuesta->id) }}"
                                       class="btn btn-outline-secondary py-2"
                                       style="border-radius:8px;">
                                        <i class="bi bi-x-circle me-2"></i>Cancelar
                                    </a>
                                </div>

                                <div class="alert alert-info mt-3 mb-0 small" style="border-radius:8px;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Los cambios se guardarán automáticamente
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <style>
         .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(45, 95, 63, 0.25);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .familiar-item {
            background-color: #f8f9fa;
        }
    </style>

    <script>
    let familiarIndex = {{ $familiares->count() }};

    document.getElementById('add-familiar').addEventListener('click', function() {
        addFamiliar();
    });

    function addFamiliar() {
        const container = document.getElementById('familiares-container');
        const familiarHtml = createFamiliarHtml(familiarIndex);
        container.insertAdjacentHTML('beforeend', familiarHtml);
        familiarIndex++;
        updateRemoveButtons();
    }

    function createFamiliarHtml(index) {
        return `
            <div class="familiar-item border rounded p-3 mb-3" data-index="${index}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-person-circle me-2"></i>Familiar ${index + 1}
                    </h6>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-familiar">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre Completo *</label>
                        <input type="text" name="familiares[${index}][nombre_completo]" class="form-control border-primary" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha de Nacimiento</label>
                        <input type="date" name="familiares[${index}][fecha_nacimiento]" class="form-control border-primary">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tipo de Documento</label>
                        <select name="familiares[${index}][tipo_documento]" class="form-select border-primary">
                            <option value="">Seleccione</option>
                            <option value="CC">CC</option>
                            <option value="TI">TI</option>
                            <option value="RC">RC</option>
                            <option value="PEP">PEP</option>
                            <option value="PAS">PAS</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Número de Documento</label>
                        <input type="text" name="familiares[${index}][documento]" class="form-control border-primary">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Fecha de Expedición</label>
                        <input type="date" name="familiares[${index}][fecha_expedicion]" class="form-control border-primary">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lugar de Expedición</label>
                        <input type="text" name="familiares[${index}][lugar_expedicion]" class="form-control border-primary">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Parentesco</label>
                        <select name="familiares[${index}][parentesco]" class="form-select border-primary">
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

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Género</label>
                        <select name="familiares[${index}][genero]" class="form-select border-primary">
                            <option value="">Seleccione</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="No binario">No binario</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Población</label>
                        <select name="familiares[${index}][poblacion]" class="form-select border-primary">
                            <option value="">Seleccione</option>
                            <option value="Indígena">Indígena</option>
                            <option value="Gitano">Gitano (ROM)</option>
                            <option value="Raizal">Raizal</option>
                            <option value="Negro">Negro</option>
                            <option value="Palenquero">Palenquero</option>
                            <option value="Ninguna">Ninguna</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Condición</label>
                        <select name="familiares[${index}][condicion]" class="form-select border-primary">
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

                    <div class="col-md-4 mt-2" id="condicionOtroDiv${index}" style="display: none;">
                        <label class="form-label fw-semibold">Especifique condición</label>
                        <input type="text" name="familiares[${index}][condicion_otro]"
                               class="form-control border-primary"
                               placeholder="Especifique cuál">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">¿Sabe leer?</label>
                        <select name="familiares[${index}][sabe_leer]" class="form-select border-primary">
                            <option value="">Seleccione</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">¿Estudia?</label>
                        <select name="familiares[${index}][estudia]" class="form-select border-primary">
                            <option value="">Seleccione</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Nivel Educativo</label>
                        <select name="familiares[${index}][nivel_educativo]" class="form-select border-primary">
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

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Celular</label>
                        <input type="text" name="familiares[${index}][celular]" class="form-control border-primary">
                    </div>
                </div>
            </div>
        `;
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.familiar-item');
        const removeButtons = document.querySelectorAll('.remove-familiar');

        removeButtons.forEach(button => {
            button.disabled = items.length <= 1;
        });
    }

    // Función para mostrar/ocultar input de condición otro
    function toggleCondicionOtro(index) {
        const select = document.getElementById(`condicion${index}`);
        const otroDiv = document.getElementById(`condicionOtroDiv${index}`);

        if (!select || !otroDiv) return;

        otroDiv.style.display = select.value === 'Otro' ? 'block' : 'none';
    }

    // Inicializar el estado del input "Otro" para familiares existentes
    @if($familiares->isNotEmpty())
        @foreach($familiares as $index => $familiar)
            @if($familiar->condicion === 'Otro')
                <script>toggleCondicionOtro({{ $index }});</script>
            @endif
        @endforeach
    @endif

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-familiar') || e.target.closest('.remove-familiar')) {
            const item = e.target.closest('.familiar-item');
            if (item) {
                item.remove();
                updateRemoveButtons();
            }
        }
    });
    </script>

</x-app-layout>

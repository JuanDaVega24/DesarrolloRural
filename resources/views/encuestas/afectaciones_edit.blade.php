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
                            <i class="bi bi-pencil-square me-2"></i>Editar Afectaciones
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $encuesta->nombre_identidad }} {{ $encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $encuesta->id }}
                        </p>
                    </div>

                    <a href="{{ route('afectaciones.show', $encuesta->id) }}"
                       class="btn btn-outline-secondary px-4 py-2"
                       style="border-radius:8px; font-weight:500;">
                       <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('afectaciones.guardarAfectaciones') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
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

                        {{-- SECCIÓN AFECTACIONES --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Información de Afectaciones
                                </h5>
                            </div>
                            <div class="card-body">

                                <div id="afectaciones-container">
                                    @if($afectaciones->isNotEmpty())
                                        @foreach($afectaciones as $index => $afectacion)
                                            <div class="afectacion-item border rounded p-3 mb-3" data-index="{{ $index }}">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="mb-0 fw-semibold">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>Afectación {{ $index + 1 }}
                                                    </h6>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-afectacion" {{ $afectaciones->count() <= 1 ? 'disabled' : '' }}>
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                <div class="row g-3">
                                                    {{-- Actividad Productiva --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Actividad Productiva *</label>

                                                        @php
                                                            $actividadesEdit = [
                                                                'inundacion' => 'Inundación',
                                                                'exceso de lluvias' => 'Exceso de lluvias',
                                                                'lluvias a destiempo' => 'Lluvias a destiempo',
                                                                'granizada' => 'Granizada',
                                                                'helada' => 'Helada',
                                                                'sequia' => 'Sequía',
                                                                'vientos fuertes' => 'Vientos fuertes',
                                                                'deslizamiento de tierra' => 'Deslizamiento de tierra',
                                                                'incendio o quema' => 'Incendio o quema',
                                                                'plaga' => 'Plaga',
                                                                'enfermedad' => 'Enfermedad',
                                                                'no fue afectado' => 'No fue afectado',
                                                            ];

                                                            $oldActividadesEdit = old('afectaciones.' . $index . '.actividad_productiva', $afectacion->actividad_productiva ?? []);
                                                        @endphp

                                                        <div class="row">
                                                            @foreach ($actividadesEdit as $value => $label)
                                                                <div class="col-md-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                               type="checkbox"
                                                                               name="afectaciones[{{ $index }}][actividad_productiva][]"
                                                                               value="{{ $value }}"
                                                                               id="actividad_edit_{{ $index }}_{{ $loop->index }}"
                                                                               {{ is_array($oldActividadesEdit) && in_array($value, $oldActividadesEdit) ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="actividad_edit_{{ $index }}_{{ $loop->index }}">
                                                                            {{ $label }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    {{-- Fenómeno --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Fenómeno</label>
                                                        <select name="afectaciones[{{ $index }}][fenomeno]" class="form-select border-primary">
                                                            <option value="" {{ old('afectaciones.' . $index . '.fenomeno', $afectacion->fenomeno) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="Sequía" {{ old('afectaciones.' . $index . '.fenomeno', $afectacion->fenomeno) == 'Sequía' ? 'selected' : '' }}>Sequía</option>
                                                            <option value="Lluvias intensas" {{ old('afectaciones.' . $index . '.fenomeno', $afectacion->fenomeno) == 'Lluvias intensas' ? 'selected' : '' }}>Lluvias intensas</option>
                                                            <option value="Heladas" {{ old('afectaciones.' . $index . '.fenomeno', $afectacion->fenomeno) == 'Heladas' ? 'selected' : '' }}>Heladas</option>
                                                            <option value="Granizadas" {{ old('afectaciones.' . $index . '.fenomeno', $afectacion->fenomeno) == 'Granizadas' ? 'selected' : '' }}>Granizadas</option>
                                                            <option value="Incendios" {{ old('afectaciones.' . $index . '.fenomeno', $afectacion->fenomeno) == 'Incendios' ? 'selected' : '' }}>Incendios</option>
                                                            <option value="Plagas" {{ old('afectaciones.' . $index . '.fenomeno', $afectacion->fenomeno) == 'Plagas' ? 'selected' : '' }}>Plagas</option>
                                                            <option value="Enfermedades" {{ old('afectaciones.' . $index . '.fenomeno', $afectacion->fenomeno) == 'Enfermedades' ? 'selected' : '' }}>Enfermedades</option>
                                                            <option value="Otro" {{ old('afectaciones.' . $index . '.fenomeno', $afectacion->fenomeno) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                                        </select>
                                                    </div>

                                                    {{-- Año --}}
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Año</label>
                                                        <input type="number" name="afectaciones[{{ $index }}][anio]"
                                                               class="form-control border-primary"
                                                               value="{{ old('afectaciones.' . $index . '.anio', $afectacion->anio) }}"
                                                               min="2000" max="2030">
                                                    </div>

                                                    {{-- Semestre --}}
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Semestre</label>
                                                        <select name="afectaciones[{{ $index }}][semestre]" class="form-select border-primary">
                                                            <option value="" {{ old('afectaciones.' . $index . '.semestre', $afectacion->semestre) ? '' : 'selected' }}>Seleccione</option>
                                                            <option value="1" {{ old('afectaciones.' . $index . '.semestre', $afectacion->semestre) == '1' ? 'selected' : '' }}>1</option>
                                                            <option value="2" {{ old('afectaciones.' . $index . '.semestre', $afectacion->semestre) == '2' ? 'selected' : '' }}>2</option>
                                                        </select>
                                                    </div>

                                                    {{-- Hectáreas --}}
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Hectáreas Afectadas</label>
                                                        <input type="number" name="afectaciones[{{ $index }}][hectareas]"
                                                               class="form-control border-primary"
                                                               value="{{ old('afectaciones.' . $index . '.hectareas', $afectacion->hectareas) }}"
                                                               step="0.01" min="0">
                                                    </div>

                                                    {{-- Unidades --}}
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Unidades Afectadas</label>
                                                        <input type="number" name="afectaciones[{{ $index }}][unidades_afectadas]"
                                                               class="form-control border-primary"
                                                               value="{{ old('afectaciones.' . $index . '.unidades_afectadas', $afectacion->unidades_afectadas) }}"
                                                               min="0">
                                                    </div>

                                                    {{-- Soluciones --}}
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-semibold">Soluciones Implementadas</label>
                                                        <textarea name="afectaciones[{{ $index }}][soluciones]"
                                                                  class="form-control border-primary"
                                                                  rows="3"
                                                                  placeholder="Describa las soluciones o medidas tomadas para mitigar la afectación">{{ old('afectaciones.' . $index . '.soluciones', $afectacion->soluciones) }}</textarea>
                                                    </div>

                                                    {{-- Actividades --}}
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-semibold">Actividades de Recuperación</label>
                                                        <textarea name="afectaciones[{{ $index }}][actividades]"
                                                                  class="form-control border-primary"
                                                                  rows="3"
                                                                  placeholder="Describa las actividades realizadas para recuperar la producción">{{ old('afectaciones.' . $index . '.actividades', $afectacion->actividades) }}</textarea>
                                                    </div>

                                                    {{-- Afectación --}}
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-semibold">Tipo de Afectación</label>
                                                        <textarea name="afectaciones[{{ $index }}][afectacion]"
                                                                  class="form-control border-primary"
                                                                  rows="3"
                                                                  placeholder="Describa el tipo y alcance de la afectación">{{ old('afectaciones.' . $index . '.afectacion', $afectacion->afectacion) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                {{-- Botón Agregar Afectación --}}
                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-success" id="add-afectacion">
                                        <i class="bi bi-plus-circle me-2"></i>Agregar Afectación
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
                                        <i class="bi bi-check-circle me-2"></i>Actualizar Afectaciones
                                    </button>

                                    <a href="{{ route('afectaciones.show', $encuesta->id) }}"
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

        .afectacion-item {
            background-color: #f8f9fa;
        }
    </style>

    <script>
    // Datos de actividades para generar checkboxes dinámicamente
    const actividadesEditData = @json($actividadesEdit);

    // Función para generar checkboxes para una afectación
    function generateCheckboxesForEditAfectacion(index) {
        const container = document.getElementById(`actividades-container-edit-${index}`);
        if (!container) return;

        let html = '';
        let counter = 0;
        Object.entries(actividadesEditData).forEach(([value, label]) => {
            html += `
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="afectaciones[${index}][actividad_productiva][]"
                               value="${value}"
                               id="actividad_edit_dynamic_${index}_${counter}">
                        <label class="form-check-label" for="actividad_edit_dynamic_${index}_${counter}">
                            ${label}
                        </label>
                    </div>
                </div>
            `;
            counter++;
        });

        container.innerHTML = html;
    }

    let afectacionIndex = {{ $afectaciones->count() }};

    document.getElementById('add-afectacion').addEventListener('click', function() {
        addAfectacion();
    });

    function addAfectacion() {
        const container = document.getElementById('afectaciones-container');
        const afectacionHtml = createAfectacionHtml(afectacionIndex);
        container.insertAdjacentHTML('beforeend', afectacionHtml);

        // Generar checkboxes para la nueva afectación
        generateCheckboxesForEditAfectacion(afectacionIndex);

        afectacionIndex++;
        updateRemoveButtons();
    }

    function createAfectacionHtml(index) {
        return `
            <div class="afectacion-item border rounded p-3 mb-3" data-index="${index}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-exclamation-triangle me-2"></i>Afectación ${index + 1}
                    </h6>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-afectacion">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Actividad Productiva *</label>
                        <div class="row" id="actividades-container-edit-${index}">
                            <!-- Checkboxes se generan dinámicamente -->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fenómeno</label>
                        <select name="afectaciones[${index}][fenomeno]" class="form-select border-primary">
                            <option value="">Seleccione</option>
                            <option value="Sequía">Sequía</option>
                            <option value="Lluvias intensas">Lluvias intensas</option>
                            <option value="Heladas">Heladas</option>
                            <option value="Granizadas">Granizadas</option>
                            <option value="Incendios">Incendios</option>
                            <option value="Plagas">Plagas</option>
                            <option value="Enfermedades">Enfermedades</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Año</label>
                        <input type="number" name="afectaciones[${index}][anio]" class="form-control border-primary" min="2000" max="2030">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Semestre</label>
                        <select name="afectaciones[${index}][semestre]" class="form-select border-primary">
                            <option value="">Seleccione</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Hectáreas Afectadas</label>
                        <input type="number" name="afectaciones[${index}][hectareas]" class="form-control border-primary" step="0.01" min="0">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Unidades Afectadas</label>
                        <input type="number" name="afectaciones[${index}][unidades_afectadas]" class="form-control border-primary" min="0">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Soluciones Implementadas</label>
                        <textarea name="afectaciones[${index}][soluciones]" class="form-control border-primary" rows="3" placeholder="Describa las soluciones o medidas tomadas para mitigar la afectación"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Actividades de Recuperación</label>
                        <textarea name="afectaciones[${index}][actividades]" class="form-control border-primary" rows="3" placeholder="Describa las actividades realizadas para recuperar la producción"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Tipo de Afectación</label>
                        <textarea name="afectaciones[${index}][afectacion]" class="form-control border-primary" rows="3" placeholder="Describa el tipo y alcance de la afectación"></textarea>
                    </div>
                </div>
            </div>
        `;
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.afectacion-item');
        const removeButtons = document.querySelectorAll('.remove-afectacion');

        removeButtons.forEach(button => {
            button.disabled = items.length <= 1;
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-afectacion') || e.target.closest('.remove-afectacion')) {
            const item = e.target.closest('.afectacion-item');
            if (item) {
                item.remove();
                updateRemoveButtons();
            }
        }
    });
    </script>

</x-app-layout>

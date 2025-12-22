<x-app-layout>
<x-steps :progress="90" :current="10" :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Gestión Agropecuaria','Predio','Control Actividades','Afectaciones','Familiares','Final']" />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <form method="POST" action="{{ route('afectaciones.guardarAfectaciones') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
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

                    {{-- SECCIÓN AFECTACIONES --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Información de Afectaciones
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Contenedor de afectaciones --}}
                            <div id="afectaciones-container">
                                {{-- Afectación inicial --}}
                                <div class="afectacion-item border rounded p-4 mb-4" style="background-color: #ffffff;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-semibold text-primary">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Afectación 1
                                        </h6>
                                    </div>

                                    <div class="row g-3">
                                     <div class="col-md-6">
    <label class="form-label fw-semibold">Actividad Productiva *</label>

    @php
        $actividades = [
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

        $oldActividades = old('afectaciones.0.actividad_productiva', []);
    @endphp

    <div class="row">
        @foreach ($actividades as $value => $label)
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           name="afectaciones[0][actividad_productiva][]"
                           value="{{ $value }}"
                           id="actividad_0_{{ $loop->index }}"
                           {{ in_array($value, $oldActividades) ? 'checked' : '' }}>
                    <label class="form-check-label" for="actividad_0_{{ $loop->index }}">
                        {{ $label }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>


                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Año</label>
                                            <input type="number" name="afectaciones[0][anio]"
                                                   class="form-control border-primary"
                                                   value="{{ old('afectaciones.0.anio') }}"
                                                   min="2000" max="2030">
                                        </div>
                                          <div class="col-md-3">
                                            <label class="form-label fw-semibold">Semestre</label>
                                            <select name="afectaciones[0][semestre]" class="form-select border-primary">
                                                <option value="">Seleccione</option>
                                                <option value="1" {{ old('afectaciones.0.semestre') == '1' ? 'selected' : '' }}>1</option>
                                                <option value="2" {{ old('afectaciones.0.semestre') == '2' ? 'selected' : '' }}>2</option>
                                            </select>
                                        </div>

                                       <div class="col-md-6">
    <label class="form-label fw-semibold">Fenómeno</label>

    <select name="afectaciones[0][fenomeno]"
            id="fenomeno_0"
            class="form-select border-primary"
            onchange="toggleOtroFenomeno(0)">
        <option value="">Seleccione</option>

        <option value="fenomeno del niño"
            {{ old('afectaciones.0.fenomeno') == 'fenomeno del niño' ? 'selected' : '' }}>
            Fenómeno del Niño
        </option>

        <option value="fenomeno de la niña"
            {{ old('afectaciones.0.fenomeno') == 'fenomeno de la niña' ? 'selected' : '' }}>
            Fenómeno de La Niña
        </option>

        <option value="otro"
            {{ old('afectaciones.0.fenomeno') == 'otro' ? 'selected' : '' }}>
            Otro
        </option>
    </select>
</div>

<!-- Input que aparece solo si elige "Otro" -->
<div class="col-md-6 mt-2"
     id="fenomeno_otro_container_0"
     style="{{ old('afectaciones.0.fenomeno') == 'otro' ? '' : 'display:none;' }}">
    <label class="form-label fw-semibold">¿Cuál fenómeno?</label>
    <input type="text"
           name="afectaciones[0][fenomeno_otro]"
           class="form-control border-primary"
           value="{{ old('afectaciones.0.fenomeno_otro') }}"
           placeholder="Especifique el fenómeno">
</div>


                                        

                                      

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Hectáreas Afectadas</label>
                                            <input type="number" name="afectaciones[0][hectareas]"
                                                   class="form-control border-primary"
                                                   value="{{ old('afectaciones.0.hectareas') }}"
                                                   step="0.01" min="0">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Unidades Afectadas</label>
                                            <input type="number" name="afectaciones[0][unidades_afectadas]"
                                                   class="form-control border-primary"
                                                   value="{{ old('afectaciones.0.unidades_afectadas') }}"
                                                   min="0">
                                        </div>

                                        <div class="col-md-12">
    <label class="form-label fw-semibold">Soluciones Implementadas</label>

    @php
        $soluciones = [
            'implementacion de sistemas de riego por goteo' => 'Implementación de sistemas de riego por goteo',
            'instalacion de tanques para el almacenamiento de agua' => 'Instalación de tanques para el almacenamiento de agua',
            'granjas integrales' => 'Granjas integrales',
            'capacitaciones en el uso eficiente de recursos hidricos' => 'Capacitaciones en el uso eficiente de recursos hídricos',
            'programa de proteccion de fuentes hidricas' => 'Programa de protección de fuentes hídricas',
            'complementos nutricionales' => 'Complementos nutricionales',
            'reconversion de cultivos con variedades mejoradas por seleccion genetica' => 'Reconversión de cultivos con variedades mejoradas por selección genética',
            'planes de reforestacion' => 'Planes de reforestación',
            'implementacion de sistemas silvopastoriles' => 'Implementación de sistemas silvopastoriles',
            'entrega de insumos y/o materiales para la siembra de cultivos' => 'Entrega de insumos y/o materiales para la siembra de cultivos',
            'otros' => 'Otros',
        ];

        $oldSoluciones = old('afectaciones.0.soluciones', []);
    @endphp

    <div class="row">
        @foreach ($soluciones as $value => $label)
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           name="afectaciones[0][soluciones][]"
                           value="{{ $value }}"
                           id="solucion_0_{{ $loop->index }}"
                           {{ in_array($value, $oldSoluciones) ? 'checked' : '' }}>
                    <label class="form-check-label" for="solucion_0_{{ $loop->index }}">
                        {{ $label }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>


                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">Actividades de Recuperación</label>
                                            <textarea name="afectaciones[0][actividades]"
                                                      class="form-control border-primary"
                                                      rows="3"
                                                      placeholder="Describa las actividades realizadas para recuperar la producción">{{ old('afectaciones.0.actividades') }}</textarea>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">Tipo de Afectación</label>
                                            <textarea name="afectaciones[0][afectacion]"
                                                      class="form-control border-primary"
                                                      rows="3"
                                                      placeholder="Describa el tipo y alcance de la afectación">{{ old('afectaciones.0.afectacion') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botón para añadir afectación --}}
                            <div class="text-end mb-4">
                                <button type="button" class="btn btn-primary" id="add-afectacion">
                                    <i class="bi bi-plus-circle me-2"></i>Añadir Afectación
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
                            <i class="bi bi-check-circle-fill me-2"></i> Continuar a Familiares
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Datos de actividades para generar checkboxes dinámicamente
        const actividadesData = @json($actividades);

        // Función para generar checkboxes para una afectación
        function generateCheckboxesForAfectacion(index) {
            const container = document.getElementById(`actividades-container-${index}`);
            if (!container) return;

            let html = '';
            let counter = 0;
            Object.entries(actividadesData).forEach(([value, label]) => {
                html += `
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="afectaciones[${index}][actividad_productiva][]"
                                   value="${value}"
                                   id="actividad_${index}_${counter}">
                            <label class="form-check-label" for="actividad_${index}_${counter}">
                                ${label}
                            </label>
                        </div>
                    </div>
                `;
                counter++;
            });

            container.innerHTML = html;
        }

        document.addEventListener('DOMContentLoaded', function () {
            let afectacionIndex = 1;

            // Función para añadir una nueva afectación
            function addAfectacion() {
                const container = document.getElementById('afectaciones-container');
                const newIndex = afectacionIndex;

                const afectacionHtml = `
                    <div class="afectacion-item border rounded p-4 mb-4" style="background-color: #ffffff;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold text-primary">
                                <i class="bi bi-exclamation-triangle me-2"></i>Afectación ${newIndex + 1}
                            </h6>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-afectacion">
                                <i class="bi bi-trash"></i> Remover
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Actividad Productiva *</label>
                                <div class="row" id="actividades-container-${newIndex}">
                                    <!-- Checkboxes se generan dinámicamente -->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fenómeno</label>
                                <select name="afectaciones[${newIndex}][fenomeno]" class="form-select border-primary">
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
                                <input type="number" name="afectaciones[${newIndex}][anio]"
                                       class="form-control border-primary"
                                       min="2000" max="2030">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Semestre</label>
                                <select name="afectaciones[${newIndex}][semestre]" class="form-select border-primary">
                                    <option value="">Seleccione</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Hectáreas Afectadas</label>
                                <input type="number" name="afectaciones[${newIndex}][hectareas]"
                                       class="form-control border-primary"
                                       step="0.01" min="0">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Unidades Afectadas</label>
                                <input type="number" name="afectaciones[${newIndex}][unidades_afectadas]"
                                       class="form-control border-primary"
                                       min="0">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Soluciones Implementadas</label>
                                <textarea name="afectaciones[${newIndex}][soluciones]"
                                          class="form-control border-primary"
                                          rows="3"
                                          placeholder="Describa las soluciones o medidas tomadas para mitigar la afectación"></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Actividades de Recuperación</label>
                                <textarea name="afectaciones[${newIndex}][actividades]"
                                          class="form-control border-primary"
                                          rows="3"
                                          placeholder="Describa las actividades realizadas para recuperar la producción"></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Tipo de Afectación</label>
                                <textarea name="afectaciones[${newIndex}][afectacion]"
                                          class="form-control border-primary"
                                          rows="3"
                                          placeholder="Describa el tipo y alcance de la afectación"></textarea>
                            </div>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', afectacionHtml);

                // Generar checkboxes para la nueva afectación
                generateCheckboxesForAfectacion(newIndex);

                afectacionIndex++;
            }

            // Función para remover afectación
            function removeAfectacion(event) {
                if (event.target.classList.contains('remove-afectacion') || event.target.closest('.remove-afectacion')) {
                    event.target.closest('.afectacion-item').remove();
                }
            }

            // Event listeners
            const addAfectacionBtn = document.getElementById('add-afectacion');
            if (addAfectacionBtn) {
                addAfectacionBtn.addEventListener('click', addAfectacion);
            }

            const container = document.getElementById('afectaciones-container');
            if (container) {
                container.addEventListener('click', removeAfectacion);
            }
      

        });
    </script>
          <script>
    function toggleOtroFenomeno(index) {
        const select = document.getElementById(`fenomeno_${index}`);
        const container = document.getElementById(`fenomeno_otro_container_${index}`);

        if (select.value === 'otro') {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    }
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
        .afectacion-item {
            transition: all 0.3s ease;
        }
        .afectacion-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .remove-afectacion {
            transition: all 0.2s ease;
        }
        .remove-afectacion:hover {
            transform: scale(1.1);
        }
    </style>

</x-app-layout>

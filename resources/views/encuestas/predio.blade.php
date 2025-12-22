<x-app-layout>
    <x-steps :progress="100" :current="8" :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Gestión Agropecuaria','Predio','Control Actividades','Final']" />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="POST" action="{{ route('predio.guardarPredio') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
                    @csrf
                    <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">

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

                    {{-- SECCIÓN USO DEL SUELO --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-tree-fill me-2"></i>Área en usos y cobertura de la tierra en la unidad productiva (Ha)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Uso Agropecuario</label>
                                    <input type="number" step="0.01" min="0" max="100" name="uso_agropecuario"
                                           class="form-control border-primary"
                                           value="{{ old('uso_agropecuario', $predio->uso_agropecuario ?? '') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Barbecho</label>
                                    <input type="number" step="0.01" min="0" max="100" name="barbecho"
                                           class="form-control border-primary"
                                           value="{{ old('barbecho', $predio->barbecho ?? '') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Descanso</label>
                                    <input type="number" step="0.01" min="0" max="100" name="descanso"
                                           class="form-control border-primary"
                                           value="{{ old('descanso', $predio->descanso ?? '') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Rastrojos</label>
                                    <input type="number" step="0.01" min="0" max="100" name="rastrojos"
                                           class="form-control border-primary"
                                           value="{{ old('rastrojos', $predio->rastrojos ?? '') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Bosques Naturales</label>
                                    <input type="number" step="0.01" min="0" max="100" name="bosques_naturales"
                                           class="form-control border-primary"
                                           value="{{ old('bosques_naturales', $predio->bosques_naturales ?? '') }}">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Construcciones o Infraestructura Agropecuaria</label>
                                    <input type="number" step="0.01" min="0" max="100" name="construcciones_infraestructura_agropecuaria"
                                           class="form-control border-primary"
                                           value="{{ old('construcciones_infraestructura_agropecuaria', $predio->construcciones_infraestructura_agropecuaria ?? '') }}">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Construcciones o Infraestructura No Agropecuaria</label>
                                    <input type="number" step="0.01" min="0" max="100" name="construcciones_infraestructura_no_agropecuaria"
                                           class="form-control border-primary"
                                           value="{{ old('construcciones_infraestructura_no_agropecuaria', $predio->construcciones_infraestructura_no_agropecuaria ?? '') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Otros Usos</label>
                                    <input type="number" step="0.01" min="0" max="100" name="otros_usos"
                                           class="form-control border-primary"
                                           value="{{ old('otros_usos', $predio->otros_usos ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN INFORMACIÓN DEL PREDIO --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-house-fill me-2"></i>Identificacion de otros Predios
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
    <label class="form-label fw-semibold">Predio No Continuo</label>
    <select name="predio_no_continuo" class="form-select border-primary" id="predio_no_continuo">
        <option value="" disabled
            {{ old('predio_no_continuo', $predio->predio_no_continuo ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('predio_no_continuo', $predio->predio_no_continuo ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('predio_no_continuo', $predio->predio_no_continuo ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                                {{-- Contenedor de predios dinámicos --}}
                                <div class="predio-campos" style="display: none;">
                                    <div id="contenedor-predios">
                                        {{-- Predio inicial --}}
                                        <div class="predio-item border rounded p-3 mb-3" style="background-color: #f8f9fa;">
                                            <h6 class="fw-semibold mb-3 text-primary">Predio 1</h6>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold">Nombre del Predio</label>
                                                    <input type="text" name="nombre_predio[0]" class="form-control border-primary"
                                                           value="{{ old('nombre_predio.0', isset($predio->nombre_predio[0]) ? $predio->nombre_predio[0] : '') }}">
                                                </div>
                                               <div class="col-md-2">
                                                    <label class="form-label fw-semibold">Área total (ha)</label>
                                                    <input type="text" name="area[0]" class="form-control border-primary"
                                                           value="{{ old('area.0', isset($predio->area[0]) ? $predio->area[0] : '') }}">
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Vereda</label>
                                                    <input type="text" name="vereda[0]" class="form-control border-primary"
                                                           value="{{ old('vereda.0', isset($predio->vereda[0]) ? $predio->vereda[0] : '') }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold">Corregimiento</label>
                                                    <input type="text" name="corregimiento[0]" class="form-control border-primary"
                                                           value="{{ old('corregimiento.0', isset($predio->corregimiento[0]) ? $predio->corregimiento[0] : '') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Municipio</label>
                                                    <input type="text" name="municipio[0]" class="form-control border-primary"
                                                           value="{{ old('municipio.0', isset($predio->municipio[0]) ? $predio->municipio[0] : '') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Departamento</label>
                                                    <input type="text" name="departamento[0]" class="form-control border-primary"
                                                           value="{{ old('departamento.0', isset($predio->departamento[0]) ? $predio->departamento[0] : '') }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label fw-semibold">Tipo de Actividad</label>
                                                    <input type="text" name="tipo_actividad[0]" class="form-control border-primary"
                                                           value="{{ old('tipo_actividad.0', isset($predio->tipo_actividad[0]) ? $predio->tipo_actividad[0] : '') }}">
                                                </div>
                                                 <div class="col-md-2">
                                                    <label class="form-label fw-semibold">Área (ha)</label>
                                                    <input type="text" name="area2[0]" class="form-control border-primary"
                                                           value="{{ old('area2.0', isset($predio->area2[0]) ? $predio->area2[0] : '') }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label fw-semibold">Cantidad</label>
                                                    <input type="text" name="cantidad[0]" class="form-control border-primary"
                                                           value="{{ old('cantidad.0', isset($predio->cantidad[0]) ? $predio->cantidad[0] : '') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Botón para añadir predio --}}
                                    <div class="text-end mb-3">
                                        <button type="button" class="btn btn-primary" id="btn-add-predio">
                                            <i class="fas fa-plus me-1"></i>Añadir Predio
                                        </button>
                                    </div>
                                </div>
                                    </div>
                                </div>
                            </div>

{{-- SECCIÓN ACTIVIDADES --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-briefcase-fill me-2"></i>Actividades no agropecuarias
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                              <div class="col-md-4">
    <label class="form-label fw-semibold">Actividades No Agropecuarias</label>
    <select name="actividades_no_agropecuarias" class="form-select border-primary">
        <option value="" disabled
            {{ old('actividades_no_agropecuarias', $predio->actividades_no_agropecuarias ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('actividades_no_agropecuarias', $predio->actividades_no_agropecuarias ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('actividades_no_agropecuarias', $predio->actividades_no_agropecuarias ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

<div class="col-md-8">
    <label class="form-label fw-semibold">Actividades</label>
    <select name="actividades" class="form-select border-primary">
        <option value="" disabled
            {{ old('actividades', $predio->actividades ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Sacrificio de animales"
            {{ old('actividades', $predio->actividades ?? '') == 'Sacrificio de animales' ? 'selected' : '' }}>
            Sacrificio de animales
        </option>

        <option value="Procesamiento de leche"
            {{ old('actividades', $predio->actividades ?? '') == 'Procesamiento de leche' ? 'selected' : '' }}>
            Procesamiento de leche
        </option>

        <option value="Produccion de alimentos para consumo humano"
            {{ old('actividades', $predio->actividades ?? '') == 'Produccion de alimentos para consumo humano' ? 'selected' : '' }}>
            Producción de alimentos para consumo humano
        </option>

        <option value="Produccion de alimentos para consumo animal"
            {{ old('actividades', $predio->actividades ?? '') == 'Produccion de alimentos para consumo animal' ? 'selected' : '' }}>
            Producción de alimentos para consumo animal
        </option>

        <option value="Destilacion de bebidas alcoholicas o fermentadas"
            {{ old('actividades', $predio->actividades ?? '') == 'Destilacion de bebidas alcoholicas o fermentadas' ? 'selected' : '' }}>
            Destilación de bebidas alcohólicas o fermentadas
        </option>

        <option value="Elaboracion de artesanias"
            {{ old('actividades', $predio->actividades ?? '') == 'Elaboracion de artesanias' ? 'selected' : '' }}>
            Elaboración de artesanías
        </option>

        <option value="Aserrado cepillado e impregnacion de la madera"
            {{ old('actividades', $predio->actividades ?? '') == 'Aserrado cepillado e impregnacion de la madera' ? 'selected' : '' }}>
            Aserrado, cepillado e impregnación de la madera
        </option>

        <option value="Fabrica de muebles"
            {{ old('actividades', $predio->actividades ?? '') == 'Fabrica de muebles' ? 'selected' : '' }}>
            Fábrica de muebles
        </option>

        <option value="Comercio o venta de productos alimenticios y bebidas alcoholicas o de diversa naturaleza"
            {{ old('actividades', $predio->actividades ?? '') == 'Comercio o venta de productos alimenticios y bebidas alcoholicas o de diversa naturaleza' ? 'selected' : '' }}>
            Comercio o venta de productos alimenticios y bebidas alcohólicas o de diversa naturaleza
        </option>

        <option value="Servicios turisticos"
            {{ old('actividades', $predio->actividades ?? '') == 'Servicios turisticos' ? 'selected' : '' }}>
            Servicios turísticos
        </option>

        <option value="No desarrolla actividades no agropecuarias"
            {{ old('actividades', $predio->actividades ?? '') == 'No desarrolla actividades no agropecuarias' ? 'selected' : '' }}>
            No desarrolla actividades no agropecuarias
        </option>
    </select>
</div>

                            </div>
                        </div>
                    </div>

                     {{-- BOTONES --}}
                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('encuestas.gestion_agropecuaria') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-arrow-left-circle me-2"></i> Volver
                        </a>

                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle-fill me-2"></i> Siguiente
                        </button>
                    </div>
                        </div>
                        
                    </div>

                    

                   
                </form>
            </div>
        </div>
    </div>

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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let predioIndex = 1;

            // Función para mostrar/ocultar campos del predio
            function togglePredioCampos() {
                const select = document.getElementById('predio_no_continuo');
                const campos = document.querySelector('.predio-campos');

                if (!select || !campos) return;

                if (select.value === 'Si') {
                    campos.style.display = 'block';
                } else {
                    campos.style.display = 'none';
                }
            }

            // Función para añadir un nuevo predio
            function addPredio() {
                const contenedor = document.getElementById('contenedor-predios');
                const original = document.querySelector('.predio-item');

                if (!contenedor || !original) return;

                // Clonar el elemento
                const nuevo = original.cloneNode(true);

                // Actualizar el título
                const titulo = nuevo.querySelector('h6');
                if (titulo) {
                    titulo.textContent = `Predio ${predioIndex + 1}`;
                }

                // Actualizar los nombres de los campos
                const inputs = nuevo.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.name) {
                        // Cambiar nombre_predio[0] por nombre_predio[1], etc.
                        input.name = input.name.replace(/\[\d+\]$/, `[${predioIndex}]`);
                        input.value = ''; // Limpiar valor
                    }
                });

                // Agregar al contenedor
                contenedor.appendChild(nuevo);
                predioIndex++;
            }

            // Event listeners
            const predioSelect = document.getElementById('predio_no_continuo');
            if (predioSelect) {
                predioSelect.addEventListener('change', togglePredioCampos);
            }

            const btnAddPredio = document.getElementById('btn-add-predio');
            if (btnAddPredio) {
                btnAddPredio.addEventListener('click', addPredio);
            }

            // Inicializar el estado al cargar la página
            togglePredioCampos();
        });
    </script>

</x-app-layout>

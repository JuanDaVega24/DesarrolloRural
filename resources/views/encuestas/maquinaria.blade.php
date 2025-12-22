<x-app-layout>
    <x-steps :progress="80" :current="6" :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Final']" />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="POST" action="{{ route('maquinaria.guardarMaquinaria') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
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

                    {{-- SECCIÓN MAQUINARIA --}}
                    
                            <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0" >
                                Maquinaria
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">¿Cuenta con maquinaria para el desarrollo de las actividades agropecuarias?</label>
                                    <select name="maquinaria" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Campos de maquinaria --}}
                            <div id="contenedor-maquinaria">
                                <div class="maquinaria-item">
                                    <div class="row g-4 mb-3 maquinaria-campos" style="display: none;">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Tipo</label>
                                            <input type="text" name="tipo_maquinaria[]" class="form-control border-primary"
                                                   value="{{ old('tipo_maquinaria.0', isset($maquinaria->tipo_maquinaria[0]) ? $maquinaria->tipo_maquinaria[0] : '') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Cantidad</label>
                                            <input type="number" name="cantidad_maquinaria[]" class="form-control border-primary"
                                                   value="{{ old('cantidad_maquinaria.0', isset($maquinaria->cantidad_maquinaria[0]) ? $maquinaria->cantidad_maquinaria[0] : '') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Antigüedad (años)</label>
                                            <input type="number" name="antiguedad_maquinaria[]" class="form-control border-primary"
                                                   value="{{ old('antiguedad_maquinaria.0', isset($maquinaria->antiguedad_maquinaria[0]) ? $maquinaria->antiguedad_maquinaria[0] : '') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Estado</label>
                                            <input type="text" name="estado_maquinaria[]" class="form-control border-primary"
                                                   value="{{ old('estado_maquinaria.0', isset($maquinaria->estado_maquinaria[0]) ? $maquinaria->estado_maquinaria[0] : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botón añadir maquinaria --}}
                            <div class="text-end mb-2">
                                <button type="button" class="btn btn-primary" id="btn-add-maquinaria">
                                    <i class="fas fa-plus me-1"></i>Añadir maquinaria
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN CONSTRUCCIÓN --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa;">
                        <div class="card-body" >
                            <h5 class="card-title mb-0">
                               Datos de Construcción
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Pregunta principal --}}
                            <div class="row g-4 mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">¿Cuenta con construcción para el desarrollo de actividades agropecuarias?</label>
                                    <select name="tiene_construccion" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Campos de construcción --}}
                            <div id="contenedor-construccion">
                                <div class="construccion-item">
                                    <div class="row g-4 mb-3 construccion-campos" style="display: none;">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Tipo</label>
                                            <input type="text" name="tipo_construccion[]" class="form-control border-primary"
                                                   value="{{ old('tipo_construccion.0', isset($maquinaria->tipo_construccion[0]) ? $maquinaria->tipo_construccion[0] : '') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Antigüedad (años)</label>
                                            <input type="number" name="antiguedad_construccion[]" class="form-control border-primary"
                                                   value="{{ old('antiguedad_construccion.0', isset($maquinaria->antiguedad_construccion[0]) ? $maquinaria->antiguedad_construccion[0] : '') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Cantidad</label>
                                            <input type="number" name="cantidad_construccion[]" class="form-control border-primary"
                                                   value="{{ old('cantidad_construccion.0', isset($maquinaria->cantidad_construccion[0]) ? $maquinaria->cantidad_construccion[0] : '') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Área</label>
                                            <div class="input-group">
                                                <input type="number" name="area_construccion[]" class="form-control border-primary"
                                                       value="{{ old('area_construccion.0', isset($maquinaria->area_construccion[0]) ? $maquinaria->area_construccion[0] : '') }}">
                                                <span class="input-group-text">MTS²</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botón añadir construcción --}}
                            <div class="text-end">
                                <button type="button" class="btn btn-primary" id="btn-add-construccion">
                                    <i class="fas fa-plus me-1"></i>Añadir construcción
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN ASOCIACIONES --}}
                    <div class="card mb-4 border-0 shadow-sm" style="background-color: #f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people me-2"></i>Asociaciones
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">¿Pertenece a asociación?</label>
                                    <select name="pertenece_asociacion" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="cooperativas">Cooperativas</option>
                                        <option value="gremios">Gremios</option>
                                        <option value="asociaciones_comunitarias">Asociación de organizaciones comunitarias</option>
                                        <option value="jac">JAC</option>
                                        <option value="no_pertenece">No pertenece a ninguna asociación</option>
                                        <option value="ns_nr">No sabe / No responde</option>
                                    </select>
                                </div>
                                <div class="col-md-6 asociacion-campos" style="display: none;">
                                    <label class="form-label fw-semibold">Nombre de la asociación</label>
                                    <input type="text" name="nombre_asociacion" class="form-control border-primary" value="{{ old('nombre_asociacion') }}">
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Entidad que brinda asesoría</label>
                                    <select name="entidad_asesoria" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="Gremio" {{ old('entidad_asesoria') == 'Gremio' ? 'selected' : '' }}>Gremio</option>
                                        <option value="Alcaldía/Umata/Epsea" {{ old('entidad_asesoria') == 'Alcaldía/Umata/Epsea' ? 'selected' : '' }}>Alcaldía/Umata/Epsea</option>
                                        <option value="Universidad" {{ old('entidad_asesoria') == 'Universidad' ? 'selected' : '' }}>Universidad</option>
                                        <option value="Almacen agropecuario" {{ old('entidad_asesoria') == 'Almacen agropecuario' ? 'selected' : '' }}>Almacén agropecuario</option>
                                        <option value="Cooperativa/asociacion" {{ old('entidad_asesoria') == 'Cooperativa/asociacion' ? 'selected' : '' }}>Cooperativa/Asociación</option>
                                        <option value="Particular" {{ old('entidad_asesoria') == 'Particular' ? 'selected' : '' }}>Particular</option>
                                        <option value="Otro" {{ old('entidad_asesoria') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-6 entidad-campos" style="display: none;">
                                    <label class="form-label fw-semibold">Nombre de la entidad</label>
                                    <input type="text" name="entidad_asesoria_nombre" class="form-control border-primary" value="{{ old('entidad_asesoria_nombre') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN ASESORÍA --}}
                    <div class="card mb-4 border-0 shadow-sm" style="background-color: #f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-book me-2"></i>Asesoría Técnica
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Pregunta principal --}}
                            <div class="row g-4 mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">¿Recibió asesoría técnica el último año?</label>
                                    <select name="recibio_asesoria_ultimo_anio" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Temas de asesoría --}}
                            <div class="asesoria-campos" style="display: none;">
                                {{-- FILA BPA / BPP --}}
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Buenas Prácticas Agrícolas?</label>
                                        <select name="tema_buenas_practicas_agricolas" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó Buenas Practicas Agricolas?</label>
                                        <select name="pago_bpa" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Buenas Prácticas Pecuarias?</label>
                                        <select name="tema_buenas_practicas_pecuarias" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó Buenas Prácticas Pecuarias?</label>
                                        <select name="pago_bpp" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- FILA MA / MS --}}
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Practicas de manejo ambiental?</label>
                                        <select name="tema_manejo_ambiental" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó Practicas de manejo ambiental?</label>
                                        <select name="pago_ma" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Manejo de Suelos?</label>
                                        <select name="tema_manejo_suelos" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó Manejo de Suelos?</label>
                                        <select name="pago_ms" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- FILA MPC / COMERCIALIZACION --}}
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Manejo de Postcosecha?</label>
                                        <select name="tema_manejo_postcosecha" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó Manejo de Postcosecha?</label>
                                        <select name="pago_mpc" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Comercializacion?</label>
                                        <select name="tema_comercializacion" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó comercializacion?</label>
                                        <select name="pago_comercializacion" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- FILA ASOCIATIVIDAD / CREDITO --}}
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Asociatividad?</label>
                                        <select name="tema_asociatividad" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó Asociatividad?</label>
                                        <select name="pago_asociatividad" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Crédito y financimiento?</label>
                                        <select name="tema_credito" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó Crédito y financimiento?</label>
                                        <select name="pago_credito" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- FILA EMPRESARIAL / TRADICIONAL --}}
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Gestión empresarial?</label>
                                        <select name="tema_empresarial" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó Gestión empresarial?</label>
                                        <select name="pago_empresarial" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Recibió Conocimiento tradicional o ancestral?</label>
                                        <select name="tema_tradicional" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">¿Pagó conocimiento tradicional o ancestral?</label>
                                        <select name="pago_tradicional" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="d-flex justify-content-between pt-3">

                        <a href="{{ route('encuestas.inventario_pecuarios') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-arrow-left-circle me-2"></i> Volver
                        </a>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right-circle me-2"></i> Siguiente                        </button>

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

     
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let maquinariaIndex = 1;
            let construccionIndex = 1;

            // Función helper para mostrar/ocultar campos
            function toggleCampos(selector, mostrar) {
                const elementos = document.querySelectorAll(selector);
                elementos.forEach(elemento => {
                    if (mostrar) {
                        elemento.style.display = 'flex';
                        elemento.style.flexWrap = 'wrap';
                        // Habilitar campos cuando se muestran
                        elemento.querySelectorAll('select, input').forEach(field => {
                            field.disabled = false;
                        });
                    } else {
                        elemento.style.display = 'none';
                        // Deshabilitar campos cuando se ocultan
                        elemento.querySelectorAll('select, input').forEach(field => {
                            field.disabled = true;
                        });
                    }
                });
            }

            // Función para manejar cambios en maquinaria
            function handleMaquinariaChange() {
                const select = document.querySelector('select[name="maquinaria"]');
                const mostrar = select.value === '1';
                toggleCampos('.maquinaria-campos', mostrar);
            }

            // Función para manejar cambios en construcción
            function handleConstruccionChange() {
                const select = document.querySelector('select[name="tiene_construccion"]');
                const mostrar = select.value === '1';
                toggleCampos('.construccion-campos', mostrar);
            }

            // Función para manejar cambios en asociaciones
            function handleAsociacionChange() {
                const select = document.querySelector('select[name="pertenece_asociacion"]');
                const mostrar = select.value !== '' && select.value !== 'no_pertenece' && select.value !== 'ns_nr';
                toggleCampos('.asociacion-campos', mostrar);
            }

             function handleEntidadChange() {
                const select = document.querySelector('select[name="entidad_asesoria"]');
                const mostrar = select.value !== '';
                toggleCampos('.entidad-campos', mostrar);
            }

            // Función para manejar cambios en asesoría
            function handleAsesoriaChange() {
                const select = document.querySelector('select[name="recibio_asesoria_ultimo_anio"]');
                const mostrar = select.value === '1';
                toggleCampos('.asesoria-campos', mostrar);
            }

            // Event listeners para campos condicionales
            document.querySelector('select[name="maquinaria"]').addEventListener('change', handleMaquinariaChange);
            document.querySelector('select[name="tiene_construccion"]').addEventListener('change', handleConstruccionChange);
            document.querySelector('select[name="entidad_asesoria"]').addEventListener('change', handleEntidadChange);
            document.querySelector('select[name="pertenece_asociacion"]').addEventListener('change', handleAsociacionChange);
            document.querySelector('select[name="recibio_asesoria_ultimo_anio"]').addEventListener('change', handleAsesoriaChange);

            // Inicializar estado (por si hay valores pre-seleccionados)
            handleMaquinariaChange();
            handleConstruccionChange();
            handleAsociacionChange();
            handleAsesoriaChange();
            handleEntidadChange();


            // Función para añadir maquinaria
            document.getElementById('btn-add-maquinaria').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-maquinaria');
                let original = document.querySelector('.maquinaria-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

                // Actualizar índices en los nombres
                nuevo.querySelectorAll('input, select').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d*\]$/, '[' + maquinariaIndex + ']');
                    }
                });

                contenedor.appendChild(nuevo);
                maquinariaIndex++;
            });

            // Función para añadir construcción
            document.getElementById('btn-add-construccion').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-construccion');
                let original = document.querySelector('.construccion-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

                // Actualizar índices en los nombres
                nuevo.querySelectorAll('input, select').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d*\]$/, '[' + construccionIndex + ']');
                    }
                });

                contenedor.appendChild(nuevo);
                construccionIndex++;
            });
        });
    </script>

</x-app-layout>

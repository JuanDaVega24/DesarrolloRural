<x-app-layout>

    <x-steps
        :progress="70"
        :current="4"
        :steps="['Personales','Vivienda','Descripcion','Producción','Pecuario','Maquinaria','Final']"
    />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <form method="POST" action="{{ route('produccion.guardarProduccion') }}"
                      class="bg-white shadow-lg rounded p-4 p-md-5">
                    @csrf

                    <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">

                    {{-- MENSAJE DE ERRORES --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Debe completar los campos requeridos:</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- =======================================================
                        ACTIVIDADES AGRÍCOLAS
                    ========================================================--}}
                    <div id="contenedor-cultivos">
                        <div class="card mb-4 border-0 cultivo-item" style="background-color: #f8f9fa;">
                            <div class="card-body">
                                <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                    <i class="bi bi-tree-fill me-2"></i> Actividades Agrícolas
                                </h5>

                                <div class="row g-4 mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Tipo de cultivo</label>
                                        <input type="text" name="tipo_cultivo[]" class="form-control border-success" value="{{ old('tipo_cultivo.0') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Área cultivada</label>
                                        <input type="number" step="0.01" name="area_cultivo[]" class="form-control border-success" value="{{ old('area_cultivo.0') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Unidad</label>
                                        <select name="unidad_area_cultivo[]" class="form-select border-primary">
                                            <option value="">Seleccionar</option>
                                            <option value="HA" {{ old('unidad_area_cultivo.0') == 'HA' ? 'selected' : '' }}>Hectáreas</option>
                                            <option value="MTS2" {{ old('unidad_area_cultivo.0') == 'MTS2' ? 'selected' : '' }}>m²</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-4 mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Cantidad de plantas / árboles</label>
                                        <input type="number" name="cantidad_arboles_plantas[]" class="form-control border-success" value="{{ old('cantidad_arboles_plantas.0') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Nivel de producción anual (Kg)</label>
                                        <input type="text" name="nivel_produccion[]" class="form-control border-success" value="{{ old('nivel_produccion.0') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Edades del cultivo</label>
                                        <input type="text" name="edades_cultivo[]" class="form-control border-success" value="{{ old('edades_cultivo.0') }}">
                                    </div>
                                </div>

                                <div class="row g-4 mb-3">
                                    @php
                                        $radios = [
                                            'seguridad_alimentaria' => '¿Es para seguridad alimentaria?',
                                            'uso_comercial'         => '¿Es para venta?',
                                            'bajo_cubierta'         => '¿Se cultiva bajo cubierta?',
                                            'cielo_abierto'         => '¿Se cultiva a cielo abierto?',
                                            'hidroponia'            => '¿Es hidroponía?',
                                        ];
                                    @endphp
                                    @foreach ($radios as $campo => $label)
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">{{ $label }}</label>
                                            <div class="d-flex gap-4 mt-2">
                                                <label class="form-check">
                                                    <input type="radio" class="form-check-input" name="{{ $campo }}[]" value="1" {{ old($campo . '.0') == '1' ? 'checked' : '' }}>
                                                    SI
                                                </label>
                                                <label class="form-check">
                                                    <input type="radio" class="form-check-input" name="{{ $campo }}[]" value="0" {{ old($campo . '.0') === '0' ? 'checked' : '' }}>
                                                    NO
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= BOTÓN PARA AÑADIR OTRO CULTIVO ================= -->
                    <div class="text-end mb-4">
                        <button type="button" class="btn btn-primary" id="btn-add-cultivo">
                             <i class="fas fa-plus me-1"></i> Añadir cultivo
                        </button>
                    </div>

                    {{-- =======================================================
                        ACTIVIDADES AGROINDUSTRIALES
                    ========================================================--}}
                    <div id="contenedor-productos">
                        <div class="card mb-4 border-0 bg-light producto-item">
                            <div class="card-body">
                                <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                    <i class="bi bi-box-seam me-2"></i> Actividades Agroindustriales
                                </h5>

                                <div class="row g-4 mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Producto elaborado</label>
                                        <input type="text" name="producto_nombre[]" class="form-control" value="{{ old('producto_nombre.0') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Cantidad de Presentacion</label>
                                        <input type="number" name="producto_presentacion[]" class="form-control" value="{{ old('producto_presentacion.0') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Precio</label>
                                        <input type="number" step="0.01" name="producto_precio[]" class="form-control" value="{{ old('producto_precio.0') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Capacidad de producción</label>
                                        <input type="number" step="0.01" name="producto_capacidad[]" class="form-control" value="{{ old('producto_capacidad.0') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Unidad</label>
                                        <select name="producto_unidad_capacidad[]" class="form-select">
                                            <option value="">Seleccionar</option>
                                            <option value="kg" {{ old('producto_unidad_capacidad.0') == 'kg' ? 'selected' : '' }}>Kg</option>
                                            <option value="g" {{ old('producto_unidad_capacidad.0') == 'g' ? 'selected' : '' }}>g</option>                                           
                                            <option value="lts" {{ old('producto_unidad_capacidad.0') == 'lts' ? 'selected' : '' }}>Lts</option>
                                            <option value="cm3" {{ old('producto_unidad_capacidad.0') == 'cm3' ? 'selected' : '' }}>Cm cubicos</option>

                                        </select>
                                    </div>

                                    @php
                                        $agroRadios = [
                                            'producto_alimentario'     => 'Alimentario',
                                            'producto_no_alimentario'  => 'No Alimentario',
                                            'producto_tiene_etiqueta'  => 'Etiqueta y envase',
                                            'producto_tiene_registro'  => 'Registro',
                                        ];
                                    @endphp

                                    @foreach ($agroRadios as $campo => $label)
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">{{ $label }}</label>
                                            <div class="d-flex gap-4 mt-2">
                                                <label class="form-check">
                                                    <input type="radio" class="form-check-input" name="{{ $campo }}[]" value="1" {{ old($campo . '.0') == '1' ? 'checked' : '' }}>
                                                    SI
                                                </label>
                                                <label class="form-check">
                                                    <input type="radio" class="form-check-input" name="{{ $campo }}[]" value="0" {{ old($campo . '.0') === '0' ? 'checked' : '' }}>
                                                    NO
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= BOTÓN PARA AÑADIR OTRO PRODUCTO ================= -->
                    <div class="text-end mb-4">
                        <button type="button" class="btn btn-primary" id="btn-add-producto">
                            <i class="fas fa-plus me-1"></i> Añadir producto
                        </button>
                    </div>

                    {{-- =======================================================
                        ACTIVIDADES FORESTALES
                    ========================================================--}}
                    <div id="contenedor-forestal">
                        <div class="card mb-4 border-0 bg-light forestal-item">
                            <div class="card-body">
                                <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                    <i class="bi bi-tree me-2"></i> Actividades Forestales
                                </h5>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Forestal disperso sembrado o plantado</label>
                                        <input type="text" name="forestal_modalidad[]" class="form-control" value="{{ old('forestal_modalidad.0') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Cantidad</label>
                                        <input type="number" name="forestal_cantidad[]" class="form-control" value="{{ old('forestal_cantidad.0') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= BOTÓN PARA AÑADIR ACTIVIDAD FORESTAL ================= -->
                    <div class="text-end mb-4">
                        <button type="button" class="btn btn-primary" id="btn-add-forestal">
                             <i class="fas fa-plus me-1"></i> Añadir actividad forestal
                        </button>
                    </div>

                    {{-- =======================================================
                        ACTIVIDAD VIVERO
                    ========================================================--}}
                    <div id="contenedor-vivero">
                        <div class="card mb-4 border-0 bg-light vivero-item">
                            <div class="card-body">
                                <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                    <i class="bi bi-flower1 me-2"></i> Vivero
                                </h5>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Especies en el vivero (ornamentales, artesanales y semilleros)</label>
                                        <input type="text" name="vivero_especies[]" class="form-control" value="{{ old('vivero_especies.0') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Cantidad</label>
                                        <input type="number" name="vivero_cantidad[]" class="form-control" value="{{ old('vivero_cantidad.0') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= BOTÓN PARA AÑADIR VIVERO ================= -->
                    <div class="text-end mb-4">
                        <button type="button" class="btn btn-primary" id="btn-add-vivero">
                              <i class="fas fa-plus me-1"></i> Añadir vivero
                        </button>
                    </div>

                    {{-- =======================================================
                        PASTOS NATURALES
                    ========================================================--}}
                    <div id="contenedor-pastos">
                        <div class="card mb-4 border-0 bg-light pastos-item">
                            <div class="card-body">
                                <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                    <i class="bi bi-grass me-2"></i> Pastos Naturales
                                </h5>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Especies de pastos o sabanas naturales</label>
                                        <input type="text" name="pastos_especies[]" class="form-control" value="{{ old('pastos_especies.0') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Hectáreas</label>
                                        <input type="number" step="0.01" name="pastos_hectareas[]" class="form-control" value="{{ old('pastos_hectareas.0') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Productos obtenidos</label>
                                        <input type="text" name="pastos_productos[]" class="form-control" value="{{ old('pastos_productos.0') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= BOTÓN PARA AÑADIR PASTOS ================= -->
                    <div class="text-end mb-4">
                        <button type="button" class="btn btn-primary" id="btn-add-pastos">
                             <i class="fas fa-plus me-1"></i>Añadir pastos
                        </button>
                    </div>

                    {{-- =======================================================
                        BOTONES
                    ========================================================--}}
                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('encuestas.descripcion') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-arrow-left-circle me-2"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right-circle me-2"></i>Siguiente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let cultivoIndex = 1;
            let productoIndex = 1;
            let forestalIndex = 1;
            let viveroIndex = 1;
            let pastosIndex = 1;

            // Función para añadir cultivo
            document.getElementById('btn-add-cultivo').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-cultivos');
                let original = document.querySelector('.cultivo-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
                nuevo.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);

                // Actualizar índices en los nombres
                nuevo.querySelectorAll('input, select').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d*\]$/, '[' + cultivoIndex + ']');
                    }
                });

                contenedor.appendChild(nuevo);
                cultivoIndex++;
            });

            // Función para añadir producto
            document.getElementById('btn-add-producto').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-productos');
                let original = document.querySelector('.producto-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
                nuevo.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);

                // Actualizar índices en los nombres
                nuevo.querySelectorAll('input, select').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d*\]$/, '[' + productoIndex + ']');
                    }
                });

                contenedor.appendChild(nuevo);
                productoIndex++;
            });

            // Función para añadir actividad forestal
            document.getElementById('btn-add-forestal').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-forestal');
                let original = document.querySelector('.forestal-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

                // Actualizar índices en los nombres
                nuevo.querySelectorAll('input, select').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d*\]$/, '[' + forestalIndex + ']');
                    }
                });

                contenedor.appendChild(nuevo);
                forestalIndex++;
            });

            // Función para añadir vivero
            document.getElementById('btn-add-vivero').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-vivero');
                let original = document.querySelector('.vivero-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

                // Actualizar índices en los nombres
                nuevo.querySelectorAll('input, select').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d*\]$/, '[' + viveroIndex + ']');
                    }
                });

                contenedor.appendChild(nuevo);
                viveroIndex++;
            });

            // Función para añadir pastos
            document.getElementById('btn-add-pastos').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-pastos');
                let original = document.querySelector('.pastos-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

                // Actualizar índices en los nombres
                nuevo.querySelectorAll('input, select').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d*\]$/, '[' + pastosIndex + ']');
                    }
                });

                contenedor.appendChild(nuevo);
                pastosIndex++;
            });
        });
    </script>

    <style>
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(45, 95, 63, 0.25);
        }
        .card {
            transition: all 0.3s ease;
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

</x-app-layout>

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
                            <i class="bi bi-tree-fill me-2"></i>Editar Producción
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $produccion->encuesta->nombre_identidad }} {{ $produccion->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $produccion->encuesta_id }}
                        </p>
                    </div>

                    <a href="{{ route('producciones.show', $produccion->id) }}"
                       class="btn btn-outline-secondary px-4 py-2"
                       style="border-radius:8px; font-weight:500;">
                       <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <form action="{{ route('producciones.update', $produccion->id) }}" method="POST">
                @csrf
                @method('PUT')

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

                        {{-- CARD: Actividades Agrícolas --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-tree-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Actividades Agrícolas</h5>
                            </div>
                            <div class="card-body p-4">

                                @php
                                    $tipoCultivo = is_string($produccion->tipo_cultivo) ? json_decode($produccion->tipo_cultivo, true) : $produccion->tipo_cultivo;
                                    $areaCultivo = is_string($produccion->area_cultivo) ? json_decode($produccion->area_cultivo, true) : $produccion->area_cultivo;
                                    $unidadArea = is_string($produccion->unidad_area_cultivo) ? json_decode($produccion->unidad_area_cultivo, true) : $produccion->unidad_area_cultivo;
                                    $cantidadPlantas = is_string($produccion->cantidad_arboles_plantas) ? json_decode($produccion->cantidad_arboles_plantas, true) : $produccion->cantidad_arboles_plantas;
                                    $nivelProduccion = is_string($produccion->nivel_produccion) ? json_decode($produccion->nivel_produccion, true) : $produccion->nivel_produccion;
                                    $edadesCultivo = is_string($produccion->edades_cultivo) ? json_decode($produccion->edades_cultivo, true) : $produccion->edades_cultivo;
                                    $seguridadAlimentaria = is_string($produccion->seguridad_alimentaria) ? json_decode($produccion->seguridad_alimentaria, true) : $produccion->seguridad_alimentaria;
                                    $usoComercial = is_string($produccion->uso_comercial) ? json_decode($produccion->uso_comercial, true) : $produccion->uso_comercial;
                                    $bajoCubierta = is_string($produccion->bajo_cubierta) ? json_decode($produccion->bajo_cubierta, true) : $produccion->bajo_cubierta;
                                    $cieloAbierto = is_string($produccion->cielo_abierto) ? json_decode($produccion->cielo_abierto, true) : $produccion->cielo_abierto;
                                    $hidroponia = is_string($produccion->hidroponia) ? json_decode($produccion->hidroponia, true) : $produccion->hidroponia;
                                @endphp

                            @php
                                $tipoCultivoCount = is_array($tipoCultivo) ? count($tipoCultivo) : 0;
                                $maxItems = max($tipoCultivoCount, 1); // Al menos 1 formulario
                            @endphp

                            <div id="contenedor-cultivos">
                            @for($index = 0; $index < $maxItems; $index++)
                                <div class="cultivo-item border p-3 mb-3 rounded" style="background-color: #f8f9fa;">
                                    <h6 class="text-muted mb-3">Cultivo {{ $index + 1 }}</h6>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Tipo de cultivo</label>
                                            <input type="text" name="tipo_cultivo[]" class="form-control" style="border-radius:8px;" value="{{ old('tipo_cultivo.' . $index, $tipoCultivo[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Área cultivada</label>
                                            <input type="number" step="0.01" name="area_cultivo[]" class="form-control" style="border-radius:8px;" value="{{ old('area_cultivo.' . $index, $areaCultivo[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Unidad</label>
                                            <select name="unidad_area_cultivo[]" class="form-select" style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="HA" {{ ($unidadArea[$index] ?? '') == 'HA' ? 'selected' : '' }}>Hectáreas</option>
                                                <option value="MTS2" {{ ($unidadArea[$index] ?? '') == 'MTS2' ? 'selected' : '' }}>m²</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Cantidad de plantas / árboles</label>
                                            <input type="number" name="cantidad_arboles_plantas[]" class="form-control" style="border-radius:8px;" value="{{ old('cantidad_arboles_plantas.' . $index, $cantidadPlantas[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Nivel de producción anual (Kg)</label>
                                            <input type="text" name="nivel_produccion[]" class="form-control" style="border-radius:8px;" value="{{ old('nivel_produccion.' . $index, $nivelProduccion[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Edades del cultivo</label>
                                            <input type="text" name="edades_cultivo[]" class="form-control" style="border-radius:8px;" value="{{ old('edades_cultivo.' . $index, $edadesCultivo[$index] ?? '') }}">
                                        </div>

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
                                                <label class="form-label text-muted small text-uppercase fw-semibold">{{ $label }}</label>
                                                <select name="{{ $campo }}[]" class="form-select" style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="1" {{ (${$campo}[$index] ?? null) == 1 ? 'selected' : '' }}>Sí</option>
                                                    <option value="0" {{ (${$campo}[$index] ?? null) === 0 ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endfor
                            </div>

                            <!-- ================= BOTÓN PARA AÑADIR OTRO CULTIVO ================= -->
                            <div class="text-end mb-4">
                                <button type="button" class="btn btn-primary" id="btn-add-cultivo">
                                     <i class="fas fa-plus me-1"></i> Añadir cultivo
                                </button>
                            </div>

                            </div>
                        </div>

                        {{-- CARD: Actividades Agroindustriales --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-box-seam fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Actividades Agroindustriales</h5>
                            </div>
                            <div class="card-body p-4">

                                @php
                                    $productoNombre = is_string($produccion->producto_nombre) ? json_decode($produccion->producto_nombre, true) : $produccion->producto_nombre;
                                    $productoPresentacion = is_string($produccion->producto_presentacion) ? json_decode($produccion->producto_presentacion, true) : $produccion->producto_presentacion;
                                    $productoPrecio = is_string($produccion->producto_precio) ? json_decode($produccion->producto_precio, true) : $produccion->producto_precio;
                                    $productoCapacidad = is_string($produccion->producto_capacidad) ? json_decode($produccion->producto_capacidad, true) : $produccion->producto_capacidad;
                                    $productoUnidad = is_string($produccion->producto_unidad_capacidad) ? json_decode($produccion->producto_unidad_capacidad, true) : $produccion->producto_unidad_capacidad;
                                    $productoAlimentario = is_string($produccion->producto_alimentario) ? json_decode($produccion->producto_alimentario, true) : $produccion->producto_alimentario;
                                    $productoNoAlimentario = is_string($produccion->producto_no_alimentario) ? json_decode($produccion->producto_no_alimentario, true) : $produccion->producto_no_alimentario;
                                    $productoEtiqueta = is_string($produccion->producto_tiene_etiqueta) ? json_decode($produccion->producto_tiene_etiqueta, true) : $produccion->producto_tiene_etiqueta;
                                    $productoRegistro = is_string($produccion->producto_tiene_registro) ? json_decode($produccion->producto_tiene_registro, true) : $produccion->producto_tiene_registro;
                                @endphp

                            @php
                                $productoNombreCount = is_array($productoNombre) ? count($productoNombre) : 0;
                                $maxItemsProductos = max($productoNombreCount, 1); // Al menos 1 formulario
                            @endphp

                            <div id="contenedor-productos">
                            @for($index = 0; $index < $maxItemsProductos; $index++)
                                <div class="producto-item border p-3 mb-3 rounded" style="background-color: #f8f9fa;">
                                    <h6 class="text-muted mb-3">Producto {{ $index + 1 }}</h6>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Producto elaborado</label>
                                            <input type="text" name="producto_nombre[]" class="form-control" style="border-radius:8px;" value="{{ old('producto_nombre.' . $index, $productoNombre[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Cantidad de Presentacion</label>
                                            <input type="number" name="producto_presentacion[]" class="form-control" style="border-radius:8px;" value="{{ old('producto_presentacion.' . $index, $productoPresentacion[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Precio</label>
                                            <input type="number" step="0.01" name="producto_precio[]" class="form-control" style="border-radius:8px;" value="{{ old('producto_precio.' . $index, $productoPrecio[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Capacidad de producción</label>
                                            <input type="number" step="0.01" name="producto_capacidad[]" class="form-control" style="border-radius:8px;" value="{{ old('producto_capacidad.' . $index, $productoCapacidad[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Unidad</label>
                                            <select name="producto_unidad_capacidad[]" class="form-select" style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="kg" {{ ($productoUnidad[$index] ?? '') == 'kg' ? 'selected' : '' }}>kg</option>
                                                <option value="g" {{ ($productoUnidad[$index] ?? '') == 'g' ? 'selected' : '' }}>g</option>
                                                <option value="lts" {{ ($productoUnidad[$index] ?? '') == 'lts' ? 'selected' : '' }}>lts</option>
                                                <option value="cm3" {{ ($productoUnidad[$index] ?? '') == 'cm3' ? 'selected' : '' }}>cm³</option>
                                            </select>
                                        </div>

                                        @php
                                            $radioCampos = [
                                                'producto_alimentario' => 'Alimentario',
                                                'producto_no_alimentario' => 'No Alimentario',
                                                'producto_tiene_etiqueta' => 'Etiqueta y envase',
                                                'producto_tiene_registro' => 'Registro sanitario',
                                            ];
                                        @endphp

                                        @foreach ($radioCampos as $campo => $label)
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">{{ $label }}</label>
                                                <select name="{{ $campo }}[]" class="form-select" style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="1" {{ (${$campo}[$index] ?? null) == 1 ? 'selected' : '' }}>Sí</option>
                                                    <option value="0" {{ (${$campo}[$index] ?? null) === 0 ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endfor
                            </div>

                            <!-- ================= BOTÓN PARA AÑADIR OTRO PRODUCTO ================= -->
                            <div class="text-end mb-4">
                                <button type="button" class="btn btn-primary" id="btn-add-producto">
                                    <i class="fas fa-plus me-1"></i> Añadir producto
                                </button>
                            </div>

                            </div>
                        </div>

                        {{-- CARD: Actividades Forestales --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-tree fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Actividades Forestales</h5>
                            </div>
                            <div class="card-body p-4">

                                @php
                                    $forestalModalidad = is_string($produccion->forestal_modalidad) ? json_decode($produccion->forestal_modalidad, true) : $produccion->forestal_modalidad;
                                    $forestalCantidad = is_string($produccion->forestal_cantidad) ? json_decode($produccion->forestal_cantidad, true) : $produccion->forestal_cantidad;
                                @endphp

                            @php
                                $forestalModalidadCount = is_array($forestalModalidad) ? count($forestalModalidad) : 0;
                                $maxItemsForestal = max($forestalModalidadCount, 1); // Al menos 1 formulario
                            @endphp

                            <div id="contenedor-forestal">
                            @for($index = 0; $index < $maxItemsForestal; $index++)
                                <div class="forestal-item border p-3 mb-3 rounded" style="background-color: #f8f9fa;">
                                    <h6 class="text-muted mb-3">Actividad Forestal {{ $index + 1 }}</h6>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Forestal disperso sembrado o plantado</label>
                                            <input type="text" name="forestal_modalidad[]" class="form-control" style="border-radius:8px;" value="{{ old('forestal_modalidad.' . $index, $forestalModalidad[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Cantidad</label>
                                            <input type="number" name="forestal_cantidad[]" class="form-control" style="border-radius:8px;" value="{{ old('forestal_cantidad.' . $index, $forestalCantidad[$index] ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                            </div>

                            <!-- ================= BOTÓN PARA AÑADIR ACTIVIDAD FORESTAL ================= -->
                            <div class="text-end mb-4">
                                <button type="button" class="btn btn-primary" id="btn-add-forestal">
                                     <i class="fas fa-plus me-1"></i> Añadir actividad forestal
                                </button>
                            </div>

                            </div>
                        </div>

                        {{-- CARD: Vivero --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-flower1 fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Especies en el vivero (ornamentales, artesanales y semilleros)</h5>
                            </div>
                            <div class="card-body p-4">

                                @php
                                    $viveroEspecies = is_string($produccion->vivero_especies) ? json_decode($produccion->vivero_especies, true) : $produccion->vivero_especies;
                                    $viveroCantidad = is_string($produccion->vivero_cantidad) ? json_decode($produccion->vivero_cantidad, true) : $produccion->vivero_cantidad;
                                @endphp

                            @php
                                $viveroEspeciesCount = is_array($viveroEspecies) ? count($viveroEspecies) : 0;
                                $maxItemsVivero = max($viveroEspeciesCount, 1); // Al menos 1 formulario
                            @endphp

                            <div id="contenedor-vivero">
                            @for($index = 0; $index < $maxItemsVivero; $index++)
                                <div class="vivero-item border p-3 mb-3 rounded" style="background-color: #f8f9fa;">
                                    <h6 class="text-muted mb-3">Vivero {{ $index + 1 }}</h6>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Especies</label>
                                            <input type="text" name="vivero_especies[]" class="form-control" style="border-radius:8px;" value="{{ old('vivero_especies.' . $index, $viveroEspecies[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Cantidad</label>
                                            <input type="number" name="vivero_cantidad[]" class="form-control" style="border-radius:8px;" value="{{ old('vivero_cantidad.' . $index, $viveroCantidad[$index] ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                            </div>

                            <!-- ================= BOTÓN PARA AÑADIR VIVERO ================= -->
                            <div class="text-end mb-4">
                                <button type="button" class="btn btn-primary" id="btn-add-vivero">
                                      <i class="fas fa-plus me-1"></i> Añadir vivero
                                </button>
                            </div>

                            </div>
                        </div>

                        {{-- CARD: Pastos Naturales --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-grass fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Pastos Naturales</h5>
                            </div>
                            <div class="card-body p-4">

                                @php
                                    $pastosEspecies = is_string($produccion->pastos_especies) ? json_decode($produccion->pastos_especies, true) : $produccion->pastos_especies;
                                    $pastosHectareas = is_string($produccion->pastos_hectareas) ? json_decode($produccion->pastos_hectareas, true) : $produccion->pastos_hectareas;
                                    $pastosProductos = is_string($produccion->pastos_productos) ? json_decode($produccion->pastos_productos, true) : $produccion->pastos_productos;
                                @endphp

                            @php
                                $pastosEspeciesCount = is_array($pastosEspecies) ? count($pastosEspecies) : 0;
                                $maxItemsPastos = max($pastosEspeciesCount, 1); // Al menos 1 formulario
                            @endphp

                            <div id="contenedor-pastos">
                            @for($index = 0; $index < $maxItemsPastos; $index++)
                                <div class="pastos-item border p-3 mb-3 rounded" style="background-color: #f8f9fa;">
                                    <h6 class="text-muted mb-3">Pastos {{ $index + 1 }}</h6>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Especies de pastos o sabanas naturales</label>
                                            <input type="text" name="pastos_especies[]" class="form-control" style="border-radius:8px;" value="{{ old('pastos_especies.' . $index, $pastosEspecies[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Hectáreas</label>
                                            <input type="number" step="0.01" name="pastos_hectareas[]" class="form-control" style="border-radius:8px;" value="{{ old('pastos_hectareas.' . $index, $pastosHectareas[$index] ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">Productos obtenidos</label>
                                            <input type="text" name="pastos_productos[]" class="form-control" style="border-radius:8px;" value="{{ old('pastos_productos.' . $index, $pastosProductos[$index] ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                            </div>

                            <!-- ================= BOTÓN PARA AÑADIR PASTOS ================= -->
                            <div class="text-end mb-4">
                                <button type="button" class="btn btn-primary" id="btn-add-pastos">
                                     <i class="fas fa-plus me-1"></i>Añadir pastos
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
                                        <i class="bi bi-check-circle me-2"></i>Actualizar Producción
                                    </button>

                                    <a href="{{ route('producciones.show', $produccion->id) }}"
                                       class="btn btn-outline-secondary py-2"
                                       style="border-radius:8px;">
                                        <i class="bi bi-x-circle me-2"></i>Cancelar
                                    </a>
                                </div>

                                <div class="alert alert-info mt-3 mb-0 small" style="border-radius:8px;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Los campos marcados son obligatorios
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #2d5f3f;
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

        .input-group {
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%) !important;
            border: none !important;
            color: white !important;
            padding: 0.5rem 1.25rem !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
        }

        .btn-primary:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(74, 124, 47, 0.25) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let cultivoIndex = {{ $maxItems }};
            let productoIndex = {{ $maxItemsProductos }};
            let forestalIndex = {{ $maxItemsForestal }};
            let viveroIndex = {{ $maxItemsVivero }};
            let pastosIndex = {{ $maxItemsPastos }};

            // Función para añadir cultivo
            document.getElementById('btn-add-cultivo').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-cultivos');
                let original = document.querySelector('.cultivo-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

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

</x-app-layout>

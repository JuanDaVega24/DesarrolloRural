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
                            <i class="bi bi-tree-fill me-2"></i>Detalles de Producción
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $produccion->encuesta->nombre_identidad }} {{ $produccion->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $produccion->encuesta_id }}
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                         <a href="{{ route('descripciones.show', $produccion->encuesta->descripcion->id) }}"
                           class="btn  px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Descripción
                        </a>
                        <a href="{{ route('encuestas.show', $produccion->encuesta_id) }}"
                           class="btn px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Datos personales
                        </a>
                        
                    </div>
                </div>
            </div>

            <div class="row g-4">

                {{-- Columna Principal --}}
                <div class="col-lg-8">

                    {{-- CARD: Actividades Agrícolas --}}
                    @php
                        $tipoCultivoDecoded = is_string($produccion->tipo_cultivo) ? json_decode($produccion->tipo_cultivo, true) : $produccion->tipo_cultivo;
                        $hasAgriData = is_array($tipoCultivoDecoded) && count(array_filter($tipoCultivoDecoded)) > 0;
                    @endphp
                    @if($hasAgriData)
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

                                // Convertir valores booleanos para display
                                $seguridadAlimentaria = is_string($produccion->seguridad_alimentaria) ? json_decode($produccion->seguridad_alimentaria, true) : $produccion->seguridad_alimentaria;
                                $usoComercial = is_string($produccion->uso_comercial) ? json_decode($produccion->uso_comercial, true) : $produccion->uso_comercial;
                                $bajoCubierta = is_string($produccion->bajo_cubierta) ? json_decode($produccion->bajo_cubierta, true) : $produccion->bajo_cubierta;
                                $cieloAbierto = is_string($produccion->cielo_abierto) ? json_decode($produccion->cielo_abierto, true) : $produccion->cielo_abierto;
                                $hidroponia = is_string($produccion->hidroponia) ? json_decode($produccion->hidroponia, true) : $produccion->hidroponia;
                            @endphp

                            @if(is_array($tipoCultivo) && count($tipoCultivo) > 0)
                                <div class="mb-3">
                                    <h6 class="text-muted small text-uppercase fw-semibold mb-2">Tipos de Cultivo</h6>
                                    @foreach($tipoCultivo as $index => $cultivo)
                                        @if($cultivo)
                                            <div class="border-start border-success border-4 ps-3 mb-2" >
                                                <strong>{{ $cultivo }}</strong>
                                                @if(isset($areaCultivo[$index]) && $areaCultivo[$index])
                                                    <br><small class="text-muted">Área: {{ $areaCultivo[$index] }} {{ $unidadArea[$index] ?? 'HA' }}</small>
                                                @endif
                                                @if(isset($cantidadPlantas[$index]) && $cantidadPlantas[$index])
                                                    <br><small class="text-muted">Cantidad: {{ $cantidadPlantas[$index] }} plantas/árboles</small>
                                                @endif
                                                @if(isset($nivelProduccion[$index]) && $nivelProduccion[$index])
                                                    <br><small class="text-muted">Nivel producción: {{ $nivelProduccion[$index] }} Kg</small>
                                                @endif
                                                @if(isset($edadesCultivo[$index]) && $edadesCultivo[$index])
                                                    <br><small class="text-muted">Edades: {{ $edadesCultivo[$index] }}</small>
                                                @endif
                                                @if(isset($seguridadAlimentaria[$index]) && $seguridadAlimentaria[$index] === 'si')
                                                    <br><small class="text-muted">Seguridad alimentaria: Sí</small>
                                                @endif
                                                @if(isset($usoComercial[$index]) && $usoComercial[$index] === 'si')
                                                    <br><small class="text-muted">Uso comercial: Sí</small>
                                                @endif
                                                @if(isset($bajoCubierta[$index]) && $bajoCubierta[$index] === 'si')
                                                    <br><small class="text-muted">Bajo cubierta: Sí</small>
                                                @endif
                                                @if(isset($cieloAbierto[$index]) && $cieloAbierto[$index] === 'si')
                                                    <br><small class="text-muted">Cielo abierto: Sí</small>
                                                @endif
                                                @if(isset($hidroponia[$index]) && $hidroponia[$index] === 'si')
                                                    <br><small class="text-muted">Hidroponía: Sí</small>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                    @else
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-tree-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Actividades Agrícolas</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center py-4">
                                <i class="bi bi-tree-fill text-muted fs-1 mb-2"></i>
                                <p class="text-muted">No hay informacion</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- CARD: Actividades Agroindustriales --}}
                    @php
                        $productoNombreDecoded = is_string($produccion->producto_nombre) ? json_decode($produccion->producto_nombre, true) : $produccion->producto_nombre;
                        $hasAgroData = is_array($productoNombreDecoded) && count(array_filter($productoNombreDecoded)) > 0;
                    @endphp
                    @if($hasAgroData)
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
                            @endphp

                            @if(is_array($productoNombre) && count($productoNombre) > 0)
                                <div class="mb-3">
                                    <h6 class="text-muted small text-uppercase fw-semibold mb-2">Productos Elaborados</h6>
                                    @foreach($productoNombre as $index => $producto)
                                        @if($producto)
                                            <div class="border-start border-primary border-4 ps-3 mb-3">
                                                <strong>{{ $producto }}</strong>
                                                @if(isset($productoPresentacion[$index]) && $productoPresentacion[$index])
                                                    <br><small class="text-muted">Presentación: {{ $productoPresentacion[$index] }}</small>
                                                @endif
                                                @if(isset($productoPrecio[$index]) && $productoPrecio[$index])
                                                    <br><small class="text-muted">Precio: ${{ $productoPrecio[$index] }}</small>
                                                @endif
                                                @if(isset($productoCapacidad[$index]) && $productoCapacidad[$index])
                                                    <br><small class="text-muted">Capacidad: {{ $productoCapacidad[$index] }} {{ $productoUnidad[$index] }}</small>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                    @else
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-box-seam fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Actividades Agroindustriales</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center py-4">
                                <i class="bi bi-box-seam text-muted fs-1 mb-2"></i>
                                <p class="text-muted">No hay informacion</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- CARD: Actividades Forestales --}}
                    @php
                        $forestalModalidadDecoded = is_string($produccion->forestal_modalidad) ? json_decode($produccion->forestal_modalidad, true) : $produccion->forestal_modalidad;
                        $hasForestalData = is_array($forestalModalidadDecoded) && count(array_filter($forestalModalidadDecoded)) > 0;
                    @endphp
                    @if($hasForestalData)
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

                            @if(is_array($forestalModalidad) && count($forestalModalidad) > 0)
                                @foreach($forestalModalidad as $index => $modalidad)
                                    @if($modalidad)
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">Modalidad</label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $modalidad }}</p>
                                            </div>
                                            @if(isset($forestalCantidad[$index]) && $forestalCantidad[$index])
                                            <div class="col-md-6">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">Cantidad</label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $forestalCantidad[$index] }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif

                        </div>
                    </div>
                    @else
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-tree fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Actividades Forestales</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center py-4">
                                <i class="bi bi-tree text-muted fs-1 mb-2"></i>
                                <p class="text-muted">No hay informacion</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- CARD: Vivero --}}
                    @php
                        $viveroEspeciesDecoded = is_string($produccion->vivero_especies) ? json_decode($produccion->vivero_especies, true) : $produccion->vivero_especies;
                        $hasViveroData = is_array($viveroEspeciesDecoded) && count(array_filter($viveroEspeciesDecoded)) > 0;
                    @endphp
                    @if($hasViveroData)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-flower1 fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Vivero</h5>
                        </div>
                        <div class="card-body p-4">

                            @php
                                $viveroEspecies = is_string($produccion->vivero_especies) ? json_decode($produccion->vivero_especies, true) : $produccion->vivero_especies;
                                $viveroCantidad = is_string($produccion->vivero_cantidad) ? json_decode($produccion->vivero_cantidad, true) : $produccion->vivero_cantidad;
                            @endphp

                            @if(is_array($viveroEspecies) && count($viveroEspecies) > 0)
                                @foreach($viveroEspecies as $index => $especie)
                                    @if($especie)
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">Especies</label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $especie }}</p>
                                            </div>
                                            @if(isset($viveroCantidad[$index]) && $viveroCantidad[$index])
                                            <div class="col-md-6">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">Cantidad</label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $viveroCantidad[$index] }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif

                        </div>
                    </div>
                    @else
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-flower1 fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Vivero</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center py-4">
                                <i class="bi bi-flower1 text-muted fs-1 mb-2"></i>
                                <p class="text-muted">No hay informacion</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- CARD: Pastos Naturales --}}
                    @php
                        $pastosEspeciesDecoded = is_string($produccion->pastos_especies) ? json_decode($produccion->pastos_especies, true) : $produccion->pastos_especies;
                        $hasPastosData = is_array($pastosEspeciesDecoded) && count(array_filter($pastosEspeciesDecoded)) > 0;
                    @endphp
                    @if($hasPastosData)
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

                            @if(is_array($pastosEspecies) && count($pastosEspecies) > 0)
                                @foreach($pastosEspecies as $index => $especie)
                                    @if($especie)
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">Especies</label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $especie }}</p>
                                            </div>
                                            @if(isset($pastosHectareas[$index]) && $pastosHectareas[$index])
                                            <div class="col-md-4">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">Hectáreas</label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $pastosHectareas[$index] }}</p>
                                            </div>
                                            @endif
                                            @if(isset($pastosProductos[$index]) && $pastosProductos[$index])
                                            <div class="col-md-4">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">Productos</label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $pastosProductos[$index] }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif

                        </div>
                    </div>
                    @else
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-grass fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Pastos Naturales</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center py-4">
                                <i class="bi bi-grass text-muted fs-1 mb-2"></i>
                                <p class="text-muted">No hay informacion</p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Columna Derecha --}}
                <div class="col-lg-4">

                    {{-- CARD: Acciones rápidas --}}
                    <div class="card shadow-sm border-0" style="border-radius:12px; overflow:hidden; border:2px solid #2d5f3f;">
                        <div class="card-body p-3">
                            <h6 class="mb-3 fw-semibold" style="color:#2d5f3f;">
                               Acciones
                            </h6>

                         <div class="row g-3">

    <div class="col-6">
        <a class="btn card-btn edit" href="{{ route('producciones.edit', $produccion->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar Producción</span>
        </a>
    </div>

    <div class="col-6">
        @if($produccion->encuesta->inventario_pecuario)
            <a class="btn card-btn next" href="{{ route('inventario_pecuario.show', $produccion->encuesta->inventario_pecuario->id) }}">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>PECUARIO</span>
            </a>
        @else
            <a class="btn card-btn next" onclick="continuarPecuario({{ $produccion->encuesta_id }})">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>Siguiente: Pecuario</span>
            </a>
        @endif
    </div>

    <div class="col-6">
        <button class="btn card-btn print">
            <i class="bi bi-printer icon"></i>
            <span>Exportar</span>
        </button>
    </div>

    <div class="col-6">
        <button class="btn card-btn delete" onclick="eliminarProduccion({{ $produccion->id }})">
            <i class="bi bi-trash icon"></i>
            <span>Eliminar</span>
        </button>
    </div>

</div>


                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <style>
        .info-item {
            transition: transform 0.2s ease;
        }

        .info-item:hover {
            transform: translateX(3px);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
        }

        .badge {
            font-weight: 500;
        }

    .card-btn {
    width: 100%;
    background: #fff;
    border-radius: 14px;
    padding: 18px;
    font-weight: 600;
    border: none;
    box-shadow: 0 4px 10px rgba(0,0,0,.08);
    transition: .2s ease-in-out;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.card-btn .icon {
    font-size: 24px;
    margin-bottom: 6px;
}

.card-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,.12);
}

/* Colores */
.card-btn.edit { color: #000000; }
.card-btn.next { color: #ffffff; background: #3d6625}
.card-btn.print { color: #ffc107; }
.card-btn.delete { background: #a51e1e; color: #ffffff; }
    </style>

    <script>
    function continuarPecuario(encuestaId) {
        // Hacer una petición AJAX para establecer el encuesta_id en la sesión
        fetch('/encuestas/establecer-sesion/' + encuestaId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            if (response.ok) {
                // Redirigir a la página de inventario pecuario
                window.location.href = '{{ route("encuestas.inventario_pecuarios") }}';
            } else {
                alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
        });
    }

    function eliminarProduccion(id) {
        if (confirm('¿Estás seguro de que quieres eliminar esta información de producción?')) {
            // Crear formulario para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/encuestas/produccion/${id}`;

            // Agregar método DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Agregar CSRF token
            const csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '_token';
            csrfField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfField);

            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>

</x-app-layout>

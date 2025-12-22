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
                            <i class="bi bi-house-fill me-2"></i>Detalles de Descripción
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $descripcion->encuesta->nombre_identidad }} {{ $descripcion->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $descripcion->encuesta_id }}
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                         <a href="{{ route('viviendas.show', $descripcion->encuesta->vivienda->id) }}"
                           class="btn  px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Vivienda
                        </a>
                        <a href="{{ route('encuestas.show', $descripcion->encuesta_id) }}"
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

                    {{-- CARD: Fuentes de Agua --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); padding:1.25rem;">
                            <i class="bi bi-droplet-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Fuentes de Agua</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                @php
                                    $fuentes = [
                                        'acueducto_publico' => 'Acueducto Público',
                                        'acuifero' => 'Acuífero',
                                        'almacenamiento_aguas_lluvias' => 'Almacenamiento de Aguas Lluvias',
                                        'aljibes' => 'Aljibes',
                                        'carrotanque' => 'Carrotanque',
                                        'nacimientos' => 'Nacimientos',
                                        'pila_publica' => 'Pila Pública',
                                        'pozos' => 'Pozos',
                                        'red_distribucion_comunitaria' => 'Red Comunitaria',
                                        'acueducto_veredal' => 'Acueducto Veredal',
                                        'rios' => 'Río',
                                        'quebradas' => 'Quebradas',
                                        'otro' => 'Otro'
                                    ];
                                @endphp

                                @foreach($fuentes as $campo => $label)
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label class="text-muted small text-uppercase fw-semibold mb-1">
                                               {{ $label }}
                                            </label>
                                            <p class="mb-0 fs-6 fw-medium">{{ $descripcion->$campo ? str_replace(',', ', ', $descripcion->$campo) : '—' }}</p>
                                            @if($descripcion->{'cantidad_'.$campo})
                                                <small class="text-muted">Cantidad: {{ $descripcion->{'cantidad_'.$campo} }}</small>
                                            @endif
                                            @if($descripcion->{'ubicado_'.$campo})
                                                <small class="text-muted">Ubicado en predio: {{ $descripcion->{'ubicado_'.$campo} }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Acceso al Predio --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-signpost-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Acceso al Predio</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Herramienta Agrícola
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->herramienta_agricola ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Distancia a Cabecera
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->distancia_finca_cabecera ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Tipo de Transporte
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->transporte_cabecera ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Vías de Acceso
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->vias_acceso ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Condición de la Vía
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->condicion_vias ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Uso del Suelo --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-tree-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Uso del Suelo</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Agricultura
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->uso_suelo_agricultura ? $descripcion->uso_suelo_agricultura . ' hectáreas' : '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Ganadería
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->uso_suelo_ganaderia ? $descripcion->uso_suelo_ganaderia . ' hectáreas' : '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Conservación
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->uso_suelo_conservacion ? $descripcion->uso_suelo_conservacion . ' hectáreas' : '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Casa
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->uso_suelo_casa ? $descripcion->uso_suelo_casa . ' hectáreas' : '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Rastrojo
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->uso_suelo_rastrojo ? $descripcion->uso_suelo_rastrojo . ' hectáreas' : '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Almacenamiento y Producción --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-box-seam-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Almacenamiento y Producción</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Almacenamiento Maquinaria
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->almacen_maquinaria ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Almacenamiento Insumos Químicos
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->almacen_insumos_quimicos ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Almacenamiento Abonos Orgánicos
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->almacen_abonos ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Condición del Terreno
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->condicion_terreno ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Sistema de Riego
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->sistema_riego ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Destino de Producción
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $descripcion->destino_produccion ? str_replace(',', ', ', $descripcion->destino_produccion) : '—' }}</p>
                                        @if($descripcion->otros_destinos_detalle)
                                            <small class="text-muted">Otros destinos: {{ $descripcion->otros_destinos_detalle }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

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
        <a class="btn card-btn edit" href="{{ route('descripciones.edit', $descripcion->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar Descripción</span>
        </a>
    </div>

    <div class="col-6">
        @if($descripcion->encuesta->produccion)
            <a class="btn card-btn next" href="{{ route('producciones.show', $descripcion->encuesta->produccion->id) }}">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>PRODUCCIÓN</span>
            </a>
        @else
            <a class="btn card-btn next" onclick="continuarADescripcion({{ $descripcion->encuesta_id }})">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>Siguiente: Producción</span>
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
        <button class="btn card-btn delete" onclick="eliminarDescripcion({{ $descripcion->id }})">
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
    function continuarADescripcion(encuestaId) {
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
                // Redirigir a la página de producción
                window.location.href = '{{ route("encuestas.produccion") }}';
            } else {
                alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
        });
    }

    function eliminarDescripcion(id) {
        if (confirm('¿Estás seguro de que quieres eliminar esta descripción?')) {
            // Crear formulario para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/encuestas/descripcion/${id}`;

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

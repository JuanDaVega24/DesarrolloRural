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
                            <i class="bi bi-house-fill me-2"></i>Detalles de Vivienda
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $vivienda->encuesta->nombre_identidad }} {{ $vivienda->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $vivienda->encuesta_id }}
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('encuestas.show', $vivienda->encuesta_id) }}"
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

                    {{-- CARD: Información de la Vivienda --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-house-door fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Datos de la Vivienda</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Tipo de Vivienda
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $vivienda->tipo_vivienda ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            Condición de Ocupación
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $vivienda->condicion_ocupacion ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Material de Pisos
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $vivienda->material_piso ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Material de Paredes Exteriores
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $vivienda->material_pared_exterior ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Destino Aguas Residuales
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $vivienda->destino_aguas_residuales ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Combustible Cocina
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $vivienda->combustible_cocina ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                        Medios de Comunicación
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $vivienda->medios_comunicacion ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            Medios Electrónicos
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $vivienda->medios_electronicos ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            Servicio Sanitario
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $vivienda->tipo_servicio_sanitario ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                          Acueducto Veredal
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">
                                            @if($vivienda->acueducto_veredal)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                          Filtro de Agua
                                        </label>
<p class="mb-0 fs-6 fw-medium">
                                            @if($vivienda->cuenta_con_filtro)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </p>                                    </div>
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
        <a class="btn card-btn edit" href="{{ route('viviendas.edit', $vivienda->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar Vivienda</span>
        </a>
    </div>

    <div class="col-6">
        @if($vivienda->encuesta->descripcion)
            <a class="btn card-btn next" href="{{ route('descripciones.show', $vivienda->encuesta->descripcion->id) }}">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>DESCRIPCIÓN</span>
            </a>
        @else
            <a class="btn card-btn next" onclick="continuarAVivienda({{ $vivienda->encuesta_id }})">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>Siguiente: Descripción</span>
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
        <button class="btn card-btn delete" onclick="eliminarVivienda({{ $vivienda->id }})">
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
    function continuarAVivienda(encuestaId) {
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
                // Redirigir a la página de descripción
                window.location.href = '{{ route("encuestas.descripcion") }}';
            } else {
                alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
        });
    }

    function eliminarVivienda(id) {
        if (confirm('¿Estás seguro de que quieres eliminar esta vivienda?')) {
            // Crear formulario para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/encuestas/vivienda/${id}`;

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

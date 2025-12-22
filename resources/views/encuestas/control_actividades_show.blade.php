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
                            <i class="bi bi-shield-check me-2"></i>Detalles del Control de Actividades
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $controlActividade->encuesta->nombre_identidad }} {{ $controlActividade->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $controlActividade->encuesta_id }}
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('predio.show', $controlActividade->encuesta->predio->id) }}"
                           class="btn px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Predio
                        </a>
                        <a href="{{ route('encuestas.show', $controlActividade->encuesta_id) }}"
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

                    {{-- CARD: Unidad Productiva --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-building fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Unidad Productiva</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Unidad Productiva
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->unidad_productiva ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Cuáles?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->cuales ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Fertilizantes --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-droplet fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Fertilizantes</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Fertilizantes
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->fertilizantes ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Tipo de Fertilizantes
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->tipo_fertilizantes ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Frecuencia de Aplicación
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->frecuencia_aplicacion ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Análisis de Suelo --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-search fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Análisis de Suelo</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Mecanismos
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->mecanismos ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Análisis
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->analisis ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿El análisis ayuda?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->analisis_ayuda ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Fecha del Análisis
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->fecha_analisis ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Control de Plagas --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-shield-check fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Control de Plagas y Enfermedades</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Control
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->control ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Frecuencia
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->frecuencia ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Control de Plagas
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->control_plagas ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Tipo de Control
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->tipo_control ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Conoce BPA?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->conoce_BPA ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Inocuidad --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-check-circle fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Inocuidad y Protección</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Conoce Inocuidad?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->conoce_inocuidad ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Desinfectar
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->desinfectar ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Toxicidad
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->toxicidad ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Protección
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->proteccion ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Cuáles Protecciones?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->cuales_proteccion ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Plaguicidas --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-bug fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Manejo de Plaguicidas</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Plaguicidas
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->plaguicidas ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Tiempo Plaguicida
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->tiempo_plaguicida ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Cultivo Plaguicida
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->cultivo_plaguicida ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Envases Plaguicida
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->envases_plaguicida ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Calidad --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-water fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Calidad del Predio y Análisis de Agua</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Calidad del Predio
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->calidad_predio ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Análisis de Agua
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->analisis_agua ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Cuál Análisis?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $controlActividade->cual_analisis ?? '—' }}</p>
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
        <a class="btn card-btn edit" href="{{ route('control_actividades.edit', $controlActividade->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar</span>
        </a>
    </div>

    <div class="col-6">
        <a class="btn card-btn next" href="{{ route('familiares.show', $controlActividade->encuesta) }}">
            <i class="bi bi-check-circle-fill icon"></i>
            <span>FINALIZAR</span>
        </a>
    </div>

    <div class="col-6">
        <button class="btn card-btn print">
            <i class="bi bi-printer icon"></i>
            <span>Exportar</span>
        </button>
    </div>

    <div class="col-6">
        <button class="btn card-btn delete" onclick="eliminarControlActividade({{ $controlActividade->id }})">
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
    function eliminarControlActividade(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este registro de control de actividades?')) {
            // Crear formulario para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/encuestas/control_actividades/${id}`;

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

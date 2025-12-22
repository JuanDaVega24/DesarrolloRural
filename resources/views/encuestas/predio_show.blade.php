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
                            <i class="bi bi-house-fill me-2"></i>Detalles del Predio
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $predio->encuesta->nombre_identidad }} {{ $predio->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $predio->encuesta_id }}
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('gestion_agropecuaria.show', $predio->encuesta->gestion_agropecuaria->id) }}"
                           class="btn px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Gestión Agropecuaria
                        </a>
                        <a href="{{ route('encuestas.show', $predio->encuesta_id) }}"
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

                    {{-- CARD: Uso del Suelo --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-tree-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Área en usos y cobertura de la tierra (Ha)</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Uso Agropecuario
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->uso_agropecuario ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Barbecho
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->barbecho ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Descanso
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->descanso ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Rastrojos
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->rastrojos ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Bosques Naturales
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->bosques_naturales ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Construcciones Infraestructura Agropecuaria
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->construcciones_infraestructura_agropecuaria ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Construcciones Infraestructura No Agropecuaria
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->construcciones_infraestructura_no_agropecuaria ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Otros Usos
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->otros_usos ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Identificación de Predios --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-house-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Identificación de otros Predios</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Predio No Continuo
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->predio_no_continuo ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($predio->predio_no_continuo === 'Si')
                            @php
                                $nombresPredio = is_array($predio->nombre_predio) ? $predio->nombre_predio : (is_string($predio->nombre_predio) ? json_decode($predio->nombre_predio, true) : []);
                                $areas = is_array($predio->area) ? $predio->area : (is_string($predio->area) ? json_decode($predio->area, true) : []);
                                $areas2 = is_array($predio->area2) ? $predio->area2 : (is_string($predio->area2) ? json_decode($predio->area2, true) : []);
                                $veredas = is_array($predio->vereda) ? $predio->vereda : (is_string($predio->vereda) ? json_decode($predio->vereda, true) : []);
                                $corregimientos = is_array($predio->corregimiento) ? $predio->corregimiento : (is_string($predio->corregimiento) ? json_decode($predio->corregimiento, true) : []);
                                $municipios = is_array($predio->municipio) ? $predio->municipio : (is_string($predio->municipio) ? json_decode($predio->municipio, true) : []);
                                $departamentos = is_array($predio->departamento) ? $predio->departamento : (is_string($predio->departamento) ? json_decode($predio->departamento, true) : []);
                                $tiposActividad = is_array($predio->tipo_actividad) ? $predio->tipo_actividad : (is_string($predio->tipo_actividad) ? json_decode($predio->tipo_actividad, true) : []);
                                $cantidades = is_array($predio->cantidad) ? $predio->cantidad : (is_string($predio->cantidad) ? json_decode($predio->cantidad, true) : []);
                            @endphp

                            @if(!empty($nombresPredio))
                                <div class="mt-4">
                                    <h6 class="fw-semibold mb-3">Predios Registrados</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nombre del Predio</th>
                                                    <th>Área Total (ha)</th>
                                                    <th>Vereda</th>
                                                    <th>Municipio</th>
                                                    <th>Tipo de Actividad</th>
                                                    <th>Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($nombresPredio as $index => $nombre)
                                                    <tr>
                                                        <td><strong>{{ $nombre ?? '—' }}</strong></td>
                                                        <td>{{ $areas[$index] ?? '—' }}</td>
                                                        <td>{{ $veredas[$index] ?? '—' }}</td>
                                                        <td>{{ $municipios[$index] ?? '—' }}</td>
                                                        <td>{{ $tiposActividad[$index] ?? '—' }}</td>
                                                        <td>{{ $cantidades[$index] ?? '—' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-house text-muted fs-1 mb-2"></i>
                                    <p class="text-muted">No hay información detallada de predios registrada</p>
                                </div>
                            @endif
                            @endif

                        </div>
                    </div>

                    {{-- CARD: Actividades No Agropecuarias --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-briefcase-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Actividades No Agropecuarias</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Actividades No Agropecuarias
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->actividades_no_agropecuarias ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Actividades
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $predio->actividades ?? '—' }}</p>
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
        <a class="btn card-btn edit" href="{{ route('predio.edit', $predio->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar Predio</span>
        </a>
    </div>

    <div class="col-6">
        <a class="btn card-btn next" href="{{ $predio->encuesta->controlActividade ? route('control_actividades.show', $predio->encuesta->controlActividade->id) : route('encuestas.control_actividades') }}">
            <i class="bi bi-check-circle-fill icon"></i>
            <span>SIGUIENTE</span>
        </a>
    </div>

    <div class="col-6">
        <button class="btn card-btn print">
            <i class="bi bi-printer icon"></i>
            <span>Exportar</span>
        </button>
    </div>

    <div class="col-6">
        <button class="btn card-btn delete" onclick="eliminarPredio({{ $predio->id }})">
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
    function eliminarPredio(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este registro de predio?')) {
            // Crear formulario para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/encuestas/predio/${id}`;

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

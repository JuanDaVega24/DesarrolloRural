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
                            <i class="bi bi-cash-coin-fill me-2"></i>Detalles de Gestión Agropecuaria
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $gestion->encuesta->nombre_identidad }} {{ $gestion->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $gestion->encuesta_id }}
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('maquinaria.show', $gestion->encuesta->maquinaria->id) }}"
                           class="btn px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Maquinaria
                        </a>
                        <a href="{{ route('encuestas.show', $gestion->encuesta_id) }}"
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

                    {{-- CARD: Participación --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-person-check-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Participación en Gestión Agropecuaria</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Participó en proyectos?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->participa ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Año de participación
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->año ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Entidad gestora
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->entidad_gestiono ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿En qué consistió?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->consistio ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Créditos y Financiación --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-cash-stack fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Créditos y Financiación</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Solicitó crédito?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->credito ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Fue aprobado?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->aprobado ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Fuentes de financiamiento
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->fuentes ? str_replace(',', ', ', $gestion->fuentes) : '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Destino de recursos
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->destino_recursos ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Tiene créditos actualmente?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->tiene_creditos ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Detalles de Créditos --}}
                    @if($gestion->tiene_creditos === 'si' && $gestion->entidad)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-credit-card-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Detalles de Créditos</h5>
                        </div>
                        <div class="card-body p-4">

                            @php
                                $entidades = json_decode($gestion->entidad, true) ?: [];
                                $valores = json_decode($gestion->valor_credito, true) ?: [];
                                $plazos = json_decode($gestion->plazo, true) ?: [];
                                $fechas = json_decode($gestion->fecha_aprobacion, true) ?: [];
                                $alDias = json_decode($gestion->al_dia, true) ?: [];
                                $seguros = json_decode($gestion->seguro, true) ?: [];
                            @endphp

                            @if(!empty($entidades))
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Entidad</th>
                                                <th>Valor del crédito</th>
                                                <th>Plazo</th>
                                                <th>Fecha aprobación</th>
                                                <th>¿Al día?</th>
                                                <th>Seguro</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($entidades as $index => $entidad)
                                                <tr>
                                                    <td><strong>{{ $entidad ?? '—' }}</strong></td>
                                                    <td>{{ $valores[$index] ?? '—' }}</td>
                                                    <td>{{ $plazos[$index] ?? '—' }}</td>
                                                    <td>{{ $fechas[$index] ?? '—' }}</td>
                                                    <td>{{ $alDias[$index] ?? '—' }}</td>
                                                    <td>{{ $seguros[$index] ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-credit-card text-muted fs-1 mb-2"></i>
                                    <p class="text-muted">No hay información detallada de créditos registrada</p>
                                </div>
                            @endif

                        </div>
                    </div>
                    @endif

                    {{-- CARD: Mano de Obra --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-people-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Mano de Obra</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                @php
                                    $personasData = json_decode($gestion->personas, true) ?: [];
                                    $cuantosData = json_decode($gestion->cuantos, true) ?: [];
                                @endphp

                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-semibold mb-3">Personas que trabajaron permanentemente</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Hombres
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $personasData['hombres']['cantidad'] ?? '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Mujeres
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $personasData['mujeres']['cantidad'] ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-semibold mb-3">Trabajadores permanentes del hogar</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Hombres
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $cuantosData['hombres']['cantidad'] ?? '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Mujeres
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $cuantosData['mujeres']['cantidad'] ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Jornales adicionales contratados
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->jornales ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           ¿Trabajo colectivo?
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->trabajo_colectivo ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Valor del jornal (MIL)
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $gestion->valor_jornal ? '$' . number_format($gestion->valor_jornal, 0, ',', '.') : '—' }}</p>
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
        <a class="btn card-btn edit" href="{{ route('gestion_agropecuaria.edit', $gestion->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar Gestión</span>
        </a>
    </div>

    <div class="col-6">
        <a class="btn card-btn next" href="{{ $gestion->encuesta->predio ? route('predio.show', $gestion->encuesta->predio->id) : route('encuestas.predio') }}">
            <i class="bi bi-arrow-right-circle icon"></i>
            <span>Predio</span>
        </a>
    </div>

    <div class="col-6">
        <button class="btn card-btn print">
            <i class="bi bi-printer icon"></i>
            <span>Exportar</span>
        </button>
    </div>

    <div class="col-6">
        <button class="btn card-btn delete" onclick="eliminarGestion({{ $gestion->id }})">
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
    function eliminarGestion(id) {
        if (confirm('¿Estás seguro de que quieres eliminar esta gestión agropecuaria?')) {
            // Crear formulario para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/encuestas/gestion_agropecuaria/${id}`;

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

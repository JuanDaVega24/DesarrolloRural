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
                            <i class="bi bi-people me-2"></i>Detalles de Familiares
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $encuesta->nombre_identidad }} {{ $encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $encuesta->id }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-people-fill me-1"></i>{{ $familiares->count() }} Familiar(es)
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('control_actividades.show', $encuesta->controlActividade) }}"
                           class="btn px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Control de Actividades
                        </a>
                        <a href="{{ route('encuestas.show', $encuesta->id) }}"
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

                    @if($familiares->isEmpty())
                        <div class="card shadow-sm border-0" style="border-radius:12px; overflow:hidden;">
                            <div class="card-body p-5 text-center">
                                <i class="bi bi-people display-1 text-muted mb-3"></i>
                                <h5 class="text-muted">No hay familiares registrados</h5>
                                <p class="text-muted">Esta encuesta no tiene familiares asociados.</p>
                            </div>
                        </div>
                    @else
                        @foreach($familiares as $familiar)
                            {{-- CARD: Familiar --}}
                            <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                                <div class="card-header text-white d-flex align-items-center"
                                     style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                    <h5 class="mb-0 fw-semibold">{{ $familiar->nombre_completo }}</h5>
                                    <span class="badge ms-auto" style="background: rgba(255,255,255,0.2);">{{ $familiar->parentesco ?? 'Sin parentesco' }}</span>
                                </div>
                                <div class="card-body p-4">

                                    <div class="row g-3">
                                        {{-- Información Personal --}}
                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Nombre Completo
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->nombre_completo ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Fecha de Nacimiento
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->fecha_nacimiento ? $familiar->fecha_nacimiento->format('d/m/Y') : '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Tipo de Documento
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->tipo_documento ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Documento
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->documento ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Fecha de Expedición
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->fecha_expedicion ? $familiar->fecha_expedicion->format('d/m/Y') : '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Lugar de Expedición
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->lugar_expedicion ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Parentesco
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->parentesco ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Género
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->genero ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Población
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->poblacion ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Condición
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->condicion ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Sabe Leer
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">
                                                    @if($familiar->sabe_leer)
                                                        <span class="badge" style="background: var(--verde);">Sí</span>
                                                    @else
                                                        <span class="badge bg-secondary">No</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Estudia
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">
                                                    @if($familiar->estudia)
                                                        <span class="badge" style="background: var(--verde);">Sí</span>
                                                    @else
                                                        <span class="badge bg-secondary">No</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Nivel Educativo
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->nivel_educativo ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                   Celular
                                                </label>
                                                <p class="mb-0 fs-6 fw-medium">{{ $familiar->celular ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
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
        <a class="btn card-btn edit" href="{{ route('familiares.edit', $encuesta->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar</span>
        </a>
    </div>

    <div class="col-6">
        <a class="btn card-btn next" href="{{ route('encuestas.index') }}">
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
        <a class="btn card-btn delete" href="{{ route('control_actividades.show', $encuesta->controlActividade) }}">
            <i class="bi bi-arrow-left icon"></i>
            <span>Volver</span>
        </a>
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
.card-btn.delete { background: #6c757d; color: #ffffff; }
    </style>

</x-app-layout>

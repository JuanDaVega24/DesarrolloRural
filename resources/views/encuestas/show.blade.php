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
                            <i class="bi bi-clipboard-check-fill me-2"></i>Detalles de la Encuesta
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-calendar3 me-1"></i>{{ $encuesta->fecha_encuesta ?? 'Sin fecha' }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-geo-alt-fill me-1"></i>{{ $encuesta->lugar_aplicacion ?? 'Sin lugar' }}
                        </p>
                    </div>

                    <a href="{{ route('encuestas.index') }}"
                       class="btn  px-4 py-2"
                       style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                        <i class="bi bi-arrow-left-circle me-2"></i>Volver al listado
                    </a>
                </div>
            </div>

            <div class="row g-4">
                
                {{-- Columna Izquierda --}}
                <div class="col-lg-8">
                    
                    {{-- CARD: Información del Encuestado --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center" 
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-person-circle fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Información del Encuestado</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            {{-- Nombre completo destacado --}}
                            <div class="mb-4 pb-3" style="border-bottom: 2px solid #e9ecef;">
                                <label class="text-muted small text-uppercase fw-semibold mb-1">Nombre Completo</label>
                                <h4 class="mb-0" style="color:#2d5f3f; font-weight:600;">
                                    {{ $encuesta->nombre_identidad }} 
                                    {{ $encuesta->primer_apellido }} 
                                    {{ $encuesta->segundo_apellido }}
                                </h4>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Tipo de Documento
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->tipo_documento }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Número de Documento
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->numero_documento }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           </i>Género
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->genero }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           </i>Nivel Educativo
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->nivel_educativo ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Datos del Predio --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center" 
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-map fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Datos del Predio</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-box p-3" style="background-color:#f8f9fa; border-radius:8px; border-left:4px solid #2d5f3f;">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            </i>Área del Predio
                                        </label>
                                        <p class="mb-0 fs-5 fw-bold" style="color:#2d5f3f;">
                                            {{ $encuesta->area_predio }} {{ $encuesta->unidad_medida }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box p-3" style="background-color:#f8f9fa; border-radius:8px; border-left:4px solid #2d5f3f;">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Área Disponible
                                        </label>
                                        <p class="mb-0 fs-5 fw-bold" style="color:#2d5f3f;">
                                            {{ $encuesta->area_total_disponible }} {{ $encuesta->unidad_medida2 }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                         Coordenadas
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium font-monospace">{{ $encuesta->coordenadas ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            Finca
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->finca ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                         Vereda
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->vereda?->nombre ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                          Corregimiento
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->corregimiento?->nombre ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Altitud
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->altitud ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Tenencia de la Tierra
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->tenencia_tierra ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                 <div>
                                <label class="text-muted small text-uppercase fw-semibold mb-2">
                                   Tipo de Tenencia
                                </label>
                                <p class="mb-0 fs-6 fw-medium">{{ $encuesta->tipo_tenencia ?? '—' }}</p>
                            </div>
                            </div>

                            </div>

                        </div>
                    </div>

                    {{-- CARD: Información Educativa --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center" 
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-book fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Interés Educativo</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-2">
                                           ¿Le gustaría estudiar?
                                        </label>
                                        <div>
                                            @if($encuesta->le_gustaria_estudiar)
                                                <span class="badge bg-success px-3 py-2 fs-6">
                                                    <i class="bi bi-check-circle me-1"></i>Sí
                                                </span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2 fs-6">
                                                    <i class="bi bi-x-circle me-1"></i>No
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Qué le gustaría estudiar
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $encuesta->que_le_gustaria_estudiar ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Columna Derecha --}}
                <div class="col-lg-4">
                    
                    {{-- CARD: Contacto --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center" 
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-telephone fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Contacto</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            <div class="mb-4">
                                <label class="text-muted small text-uppercase fw-semibold mb-2">
                                 Correo Electrónico
                                </label>
                                @if($encuesta->correo)
                                    <a href="mailto:{{ $encuesta->correo }}" 
                                       class="d-block text-decoration-none p-2 rounded" 
                                       style="background-color:#f8f9fa; color:#2d5f3f; font-weight:500;">
                                        {{ $encuesta->correo }}
                                    </a>
                                @else
                                    <p class="mb-0 text-muted">—</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="text-muted small text-uppercase fw-semibold mb-2">
                                   Celular Principal
                                </label>
                                @if($encuesta->celular_1)
                                    <a href="tel:{{ $encuesta->celular_1 }}" 
                                       class="d-block text-decoration-none p-2 rounded" 
                                       style="background-color:#f8f9fa; color:#2d5f3f; font-weight:500;">
                                        {{ $encuesta->celular_1 }}
                                    </a>
                                @else
                                    <p class="mb-0 text-muted">—</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="text-muted small text-uppercase fw-semibold mb-2">
                                   Celular Secundario
                                </label>
                                @if($encuesta->celular_2)
                                    <a href="tel:{{ $encuesta->celular_2 }}"
                                       class="d-block text-decoration-none p-2 rounded"
                                       style="background-color:#f8f9fa; color:#2d5f3f; font-weight:500;">
                                        {{ $encuesta->celular_2 }}
                                    </a>
                                @else
                                    <p class="mb-0 text-muted">—</p>
                                @endif
                            </div>

                           

                        </div>
                    </div>

                    {{-- CARD: Información Adicional --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center" 
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-info-circle fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Información Adicional</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            <div class="mb-4">
                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                  Población Especial
                                </label>
                                <p class="mb-0 fs-6 fw-medium">{{ $encuesta->pertenencia_poblacion_especial ?? '—' }}</p>
                            </div>

                            <div>
                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                  Tiempo en la Finca
                                </label>
                                <p class="mb-0 fs-6 fw-medium">{{ $encuesta->tiempo_viviendo_finca ?? '—' }}</p>
                            </div>

                        </div>
                    </div>

                    {{-- CARD: Acciones rápidas --}}
                    <div class="card shadow-sm border-0" style="border-radius:12px; overflow:hidden; border:2px solid #2d5f3f;">
                        <div class="card-body p-3">
                            <h6 class="mb-3 fw-semibold" style="color:#2d5f3f;">
                              Acciones 
                            </h6>
                            
                         <div class="row g-3">

    <div class="col-6">
        <a class="btn card-btn edit" href="{{ route('encuestas.edit', $encuesta->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar Encuesta</span>
        </a>
    </div>

    <div class="col-6">
        @if($encuesta->vivienda)
            <a class="btn card-btn next" href="{{ route('viviendas.show', $encuesta->vivienda->id) }}">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>VIVIENDA</span>
            </a>
        @else
            <a class="btn card-btn next" onclick="continuarEncuesta({{ $encuesta->id }})">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>Formulario de Vivienda aun no Realizado</span>
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
        <button class="btn card-btn delete">
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

        .info-box {
            transition: all 0.3s ease;
        }

        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 95, 63, 0.15);
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
    function continuarEncuesta(encuestaId) {
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
                // Redirigir a la página de vivienda
                window.location.href = '{{ route("encuestas.vivienda") }}';
            } else {
                alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
        });
    }
    </script>

</x-app-layout>

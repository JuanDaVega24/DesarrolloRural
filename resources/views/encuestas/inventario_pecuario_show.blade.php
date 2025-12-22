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
                            <i class="bi bi-bug-fill me-2"></i>Detalles del Inventario Pecuario
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $inventario_pecuario->encuesta->nombre_identidad }} {{ $inventario_pecuario->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $inventario_pecuario->encuesta_id }}
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                         <a href="{{ route('producciones.show', $inventario_pecuario->encuesta->produccion->id) }}"
                           class="btn  px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Produccion
                        </a>
                        <a href="{{ route('encuestas.show', $inventario_pecuario->encuesta_id) }}"
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

                    {{-- GANADO BOVINO --}}
                    @if($inventario_pecuario->tiene_ganado_bovino)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-cow fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Ganado Bovino</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Orientación Ganadera
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->orientacion_ganadera ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            Manejo de Alimentación
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->manejo_alimentacion ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Vacunas Recibidas
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->vacunas_recibidas ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Pago por Biológico
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">
                                            @if($inventario_pecuario->pago_biologico)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Cantidades de Bovinos --}}
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3" style="color:#2d5f3f;">Inventario de Bovinos</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded p-3" style="background:#f8f9fa;">
                                            <h6 class="mb-2 text-center fw-semibold">Machos</h6>
                                            <div class="row g-2 text-center">
                                                <div class="col-4"><small><1 año</small><br><strong>{{ $inventario_pecuario->bovino_machos_menor1 ?? 0 }}</strong></div>
                                                <div class="col-4"><small>1-3 años</small><br><strong>{{ $inventario_pecuario->bovino_machos_1a3 ?? 0 }}</strong></div>
                                                <div class="col-4"><small>>3 años</small><br><strong>{{ $inventario_pecuario->bovino_machos_mayor3 ?? 0 }}</strong></div>
                                            </div>
                                            <div class="text-center mt-2"><small>Reproductores: <strong>{{ $inventario_pecuario->bovino_machos_reproductores ?? 0 }}</strong></small></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3" style="background:#f8f9fa;">
                                            <h6 class="mb-2 text-center fw-semibold">Hembras</h6>
                                            <div class="row g-2 text-center">
                                                <div class="col-4"><small><1 año</small><br><strong>{{ $inventario_pecuario->bovino_hembras_menor1 ?? 0 }}</strong></div>
                                                <div class="col-4"><small>1-3 años</small><br><strong>{{ $inventario_pecuario->bovino_hembras_1a3 ?? 0 }}</strong></div>
                                                <div class="col-4"><small>>3 años</small><br><strong>{{ $inventario_pecuario->bovino_hembras_mayor3 ?? 0 }}</strong></div>
                                            </div>
                                            <div class="text-center mt-2"><small>En ordeño: <strong>{{ $inventario_pecuario->bovino_hembras_ordeño ?? 0 }}</strong></small></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @else
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-cow fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Ganado Bovino</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center py-4">
                                <i class="bi bi-cow text-muted fs-1 mb-2"></i>
                                <p class="text-muted">No hay información de ganado bovino registrada</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- LECHE --}}
                    @if($inventario_pecuario->produccion_leche_litros)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-egg-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Producción de Leche</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Producción Diaria (litros)
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->produccion_leche_litros }} L</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            Uso de la Leche
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ nl2br($inventario_pecuario->destino_leche ?? '—') }}</p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            Comercialización
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->comercializacion_leche ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @else
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-egg-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Producción de Leche</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center py-4">
                                <i class="bi bi-egg-fill text-muted fs-1 mb-2"></i>
                                <p class="text-muted">No hay información de producción de leche registrada</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- CERDOS --}}
                    @if($inventario_pecuario->tiene_cerdos)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-piggy-bank-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Porcinos</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Orientación Porícola
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->orientacion_porcicola ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Vacuna Peste Clásica
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">
                                            @if($inventario_pecuario->vacuna_peste_clasica)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Cantidades de Cerdos --}}
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3" style="color:#2d5f3f;">Inventario de Porcinos</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded p-3" style="background:#f8f9fa;">
                                            <h6 class="mb-2 text-center fw-semibold">Machos Reproductores</h6>
                                            <div class="text-center"><strong class="fs-4">{{ $inventario_pecuario->cerdos_machos_reproductores ?? 0 }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3" style="background:#f8f9fa;">
                                            <h6 class="mb-2 text-center fw-semibold">Hembras Gestantes/Lactantes/Vacías</h6>
                                            <div class="text-center"><strong class="fs-4">{{ $inventario_pecuario->cerdos_hembras_gestantes ?? 0 }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3" style="background:#f8f9fa;">
                                            <h6 class="mb-2 text-center fw-semibold">Hembras de Reemplazo</h6>
                                            <div class="text-center"><strong class="fs-4">{{ $inventario_pecuario->cerdos_hembras_reemplazo ?? 0 }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3" style="background:#f8f9fa;">
                                            <h6 class="mb-2 text-center fw-semibold">Hembras y Machos de Descarte</h6>
                                            <div class="text-center"><strong class="fs-4">{{ $inventario_pecuario->cerdos_descartes ?? 0 }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3" style="background:#f8f9fa;">
                                            <h6 class="mb-2 text-center fw-semibold">Destetos por Año</h6>
                                            <div class="text-center"><strong class="fs-4">{{ $inventario_pecuario->cerdos_destetos_anio ?? 0 }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3" style="background:#f8f9fa;">
                                            <h6 class="mb-2 text-center fw-semibold">Ceba por Año</h6>
                                            <div class="text-center"><strong class="fs-4">{{ $inventario_pecuario->cerdos_ceba_anio ?? 0 }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                    {{-- AVES --}}
                    @if($inventario_pecuario->cria_gallinas_pollos_galpon)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-egg-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Avicultura</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Orientación Avícola
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->orientacion_avicola ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Producción de Huevos (mes)
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->produccion_huevos_mes ?? 0 }} huevos</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Pollo Comercializado (kg/mes)
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->pollo_comercializado_kg_mes ?? 0 }} kg</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Método de Sacrificio
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->metodo_sacrificio ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Dónde comercializó el pollo
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->donde_comercializo_pollo ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            Comercialización de Huevos
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->comercializacion_huevos ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                    {{-- PECES --}}
                    @if($inventario_pecuario->cria_peces)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%); padding:1.25rem;">
                            <i class="bi bi-water fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Acuicultura</h5>
                        </div>
                        <div class="card-body p-4">

                            {{-- Orientación --}}
                            <div class="row g-4 mb-4">
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Orientación de la Producción
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->peces_orientacion ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Especies --}}
                            @php
                                $pecesEspecies = $inventario_pecuario->peces_especie ? json_decode($inventario_pecuario->peces_especie, true) : [];
                                $pecesCosechas = $inventario_pecuario->peces_cosechas_anio ? json_decode($inventario_pecuario->peces_cosechas_anio, true) : [];
                                $pecesAnimales = $inventario_pecuario->peces_animales_cosecha ? json_decode($inventario_pecuario->peces_animales_cosecha, true) : [];
                                $pecesPeso = $inventario_pecuario->peces_peso_promedio ? json_decode($inventario_pecuario->peces_peso_promedio, true) : [];
                                $pecesProduccion = $inventario_pecuario->peces_produccion_total_anterior ? json_decode($inventario_pecuario->peces_produccion_total_anterior, true) : [];
                                $pecesComercializacion = $inventario_pecuario->peces_comercializacion ? json_decode($inventario_pecuario->peces_comercializacion, true) : [];

                                $maxEspecies = max(count($pecesEspecies), count($pecesCosechas), count($pecesAnimales), count($pecesPeso), count($pecesProduccion), count($pecesComercializacion));
                            @endphp

                            @if($maxEspecies > 0)
                                <h6 class="fw-semibold mb-3" style="color:#00BCD4;">Especies Registradas</h6>

                                @for($i = 0; $i < $maxEspecies; $i++)
                                    <div class="border rounded p-3 mb-3" style="background:#f0f8ff;">
                                        <h6 class="mb-2 fw-semibold text-primary">
                                            <i class="bi bi-fish me-2"></i>Especie {{ $i + 1 }}: {{ $pecesEspecies[$i] ?? 'Sin nombre' }}
                                        </h6>

                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <small class="text-muted">Cosechas/año</small>
                                                <div class="fw-medium">{{ $pecesCosechas[$i] ?? 0 }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Animales/cosecha</small>
                                                <div class="fw-medium">{{ $pecesAnimales[$i] ?? 0 }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Peso promedio</small>
                                                <div class="fw-medium">{{ $pecesPeso[$i] ?? 0 }} kg</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Producción anterior</small>
                                                <div class="fw-medium">{{ $pecesProduccion[$i] ?? 0 }}</div>
                                            </div>
                                            <div class="col-md-12">
                                                <small class="text-muted">Comercialización</small>
                                                <div class="fw-medium">{{ $pecesComercializacion[$i] ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                                <div class="alert alert-info mt-3" style="border-radius:8px;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>{{ $maxEspecies }}</strong> especie{{ $maxEspecies > 1 ? 's' : '' }} registrada{{ $maxEspecies > 1 ? 's' : '' }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-fish text-muted fs-1 mb-2"></i>
                                    <p class="text-muted">No hay especies registradas</p>
                                </div>
                            @endif

                        </div>
                    </div>
                    @endif

                    {{-- OTROS ANIMALES --}}
                    @if($inventario_pecuario->tiene_otros_animales)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-tree-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Búfalos, Equinos, Ovinos y Caprinos</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Orientación Ovino-Caprina
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->orientacion_ovino_caprina ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Vacuna Encefalitis Equina
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">
                                            @if($inventario_pecuario->vacuna_encefalitis_equina)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Inventario de otros animales --}}
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3" style="color:#2d5f3f;">Inventario de Animales</h6>
                                <div class="row g-2">
                                    @php
                                        $animales = [
                                            'Caballos' => $inventario_pecuario->caballos,
                                            'Yeguas' => $inventario_pecuario->yeguas,
                                            'Mulos' => $inventario_pecuario->mulos,
                                            'Mulas' => $inventario_pecuario->mulas,
                                            'Burros' => $inventario_pecuario->burros,
                                            'Burras' => $inventario_pecuario->burras,
                                            'Cabros' => $inventario_pecuario->cabros,
                                            'Cabras' => $inventario_pecuario->cabras,
                                            'Ovejos' => $inventario_pecuario->ovejos,
                                            'Ovejas' => $inventario_pecuario->ovejas,
                                            'Búfalos Machos' => $inventario_pecuario->bufalos_machos,
                                            'Búfalos Hembras' => $inventario_pecuario->bufalos_hembras,
                                        ];
                                    @endphp

                                    @foreach(array_chunk($animales, 3, true) as $chunk)
                                    <div class="col-md-4">
                                        @foreach($chunk as $animal => $cantidad)
                                        <div class="d-flex justify-content-between py-1">
                                            <small>{{ $animal }}:</small>
                                            <strong>{{ $cantidad ?? 0 }}</strong>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                    {{-- ANIMALES DE TRASPATIO --}}
                    @if($inventario_pecuario->cerdos_traspatio || $inventario_pecuario->gallos_pollos_traspatio || $inventario_pecuario->gallos_pelea || $inventario_pecuario->pavos || $inventario_pecuario->patos_gansos || $inventario_pecuario->codornices || $inventario_pecuario->avestruces || $inventario_pecuario->cuyes || $inventario_pecuario->conejos)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-house-heart-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Animales de Traspatio</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-2">
                                @php
                                    $traspatio = [
                                        'Cerdos' => $inventario_pecuario->cerdos_traspatio,
                                        'Gallos/Pollos/Gallinas' => $inventario_pecuario->gallos_pollos_traspatio,
                                        'Gallos de Pelea' => $inventario_pecuario->gallos_pelea,
                                        'Pavos' => $inventario_pecuario->pavos,
                                        'Patos/Gansos' => $inventario_pecuario->patos_gansos,
                                        'Codornices' => $inventario_pecuario->codornices,
                                        'Avestruces' => $inventario_pecuario->avestruces,
                                        'Cuyes' => $inventario_pecuario->cuyes,
                                        'Conejos' => $inventario_pecuario->conejos,
                                        
                                    ];
                                @endphp

                                @foreach(array_chunk($traspatio, 3, true) as $chunk)
                                <div class="col-md-4">
                                    @foreach($chunk as $animal => $cantidad)
                                    <div class="d-flex justify-content-between py-1">
                                        <small>{{ $animal }}:</small>
                                        <strong>{{ $cantidad ?? 0 }}</strong>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                    @endif

                    {{-- ABEJAS --}}
                    @if($inventario_pecuario->colmenas_miel || $inventario_pecuario->colmenas_polen || $inventario_pecuario->colmenas_subproductos || $inventario_pecuario->colmenas_meliponas)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-hexagon-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Apicultura</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-3 text-center">
                                    <div class="border rounded p-3" style="background:#f8f9fa;">
                                        <h6 class="mb-2 fw-semibold">Miel</h6>
                                        <strong class="fs-4">{{ $inventario_pecuario->colmenas_miel ?? 0 }}</strong>
                                        <br><small>colmenas</small>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="border rounded p-3" style="background:#f8f9fa;">
                                        <h6 class="mb-2 fw-semibold">Polen</h6>
                                        <strong class="fs-4">{{ $inventario_pecuario->colmenas_polen ?? 0 }}</strong>
                                        <br><small>colmenas</small>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="border rounded p-3" style="background:#f8f9fa;">
                                        <h6 class="mb-2 fw-semibold">Subproductos</h6>
                                        <strong class="fs-4">{{ $inventario_pecuario->colmenas_subproductos ?? 0 }}</strong>
                                        <br><small>colmenas</small>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="border rounded p-3" style="background:#f8f9fa;">
                                        <h6 class="mb-2 fw-semibold">Meliponas</h6>
                                        <strong class="fs-4">{{ $inventario_pecuario->colmenas_meliponas ?? 0 }}</strong>
                                        <br><small>colmenas</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                    {{-- MASCOTAS --}}
                    @if($inventario_pecuario->caninos_hembras || $inventario_pecuario->caninos_machos || $inventario_pecuario->felinos_hembras || $inventario_pecuario->felinos_machos || $inventario_pecuario->tortugas)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-heart-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Mascotas</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-3 text-center">
                                    <div class="border rounded p-3" style="background:#f8f9fa;">
                                        <h6 class="mb-2 fw-semibold">Aves Ornamentales</h6>
                                        <strong class="fs-4">{{ $inventario_pecuario->aves_ornamentales ?? 0 }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="border rounded p-3" style="background:#f8f9fa;">
                                        <h6 class="mb-2 fw-semibold">Caninos</h6>
                                        <div class="row text-center">
                                            <div class="col-6"><small>Hembras</small><br><strong>{{ $inventario_pecuario->caninos_hembras ?? 0 }}</strong></div>
                                            <div class="col-6"><small>Machos</small><br><strong>{{ $inventario_pecuario->caninos_machos ?? 0 }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="border rounded p-3" style="background:#f8f9fa;">
                                        <h6 class="mb-2 fw-semibold">Felinos</h6>
                                        <div class="row text-center">
                                            <div class="col-6"><small>Hembras</small><br><strong>{{ $inventario_pecuario->felinos_hembras ?? 0 }}</strong></div>
                                            <div class="col-6"><small>Machos</small><br><strong>{{ $inventario_pecuario->felinos_machos ?? 0 }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="border rounded p-3" style="background:#f8f9fa;">
                                        <h6 class="mb-2 fw-semibold">Tortugas</h6>
                                        <strong class="fs-4">{{ $inventario_pecuario->tortugas ?? 0 }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-center">
                                <strong>Esterilizados:</strong>
                                @if($inventario_pecuario->esterilizados)
                                    <span class="badge bg-success ms-2">Sí</span>
                                @else
                                    <span class="badge bg-secondary ms-2">No</span>
                                @endif
                            </div>

                            @if($inventario_pecuario->otros2)
                            <div class="mt-3">
                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                    Otros Animales
                                </label>
                                <p class="mb-0 fs-6 fw-medium">{{ $inventario_pecuario->otros2 }}</p>
                            </div>
                            @endif

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
        <a class="btn card-btn edit" href="{{ route('inventario_pecuario.edit', $inventario_pecuario->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar Inventario</span>
        </a>
    </div>

    <div class="col-6">
        @if($inventario_pecuario->encuesta->maquinaria)
            <a class="btn card-btn next" href="{{ route('maquinaria.show', $inventario_pecuario->encuesta->maquinaria->id) }}">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>MAQUINARIA</span>
            </a>
        @else
            <a class="btn card-btn next" onclick="continuarAMaquinaria({{ $inventario_pecuario->encuesta_id }})">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>Siguiente: Maquinaria</span>
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
        <button class="btn card-btn delete" onclick="eliminarInventario({{ $inventario_pecuario->id }})">
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
    function continuarAMaquinaria(encuestaId) {
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
                // Redirigir a la página de maquinaria
                window.location.href = '{{ route("encuestas.maquinaria") }}';
            } else {
                alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
        });
    }

    function eliminarInventario(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este inventario pecuario?')) {
            // Crear formulario para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/encuestas/inventario_pecuario/${id}`;

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

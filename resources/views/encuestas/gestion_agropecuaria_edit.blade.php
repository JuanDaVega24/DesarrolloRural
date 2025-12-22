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
                            <i class="bi bi-pencil-square me-2"></i>Editar Gestión Agropecuaria
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $gestion->encuesta->nombre_identidad }} {{ $gestion->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $gestion->encuesta_id }}
                        </p>
                    </div>

                    <a href="{{ route('gestion_agropecuaria.show', $gestion->id) }}"
                       class="btn btn-outline-secondary px-4 py-2"
                       style="border-radius:8px; font-weight:500;">
                       <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <form action="{{ route('gestion_agropecuaria.update', $gestion->id) }}" method="POST">
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

                        {{-- === SECCIÓN: PARTICIPACIÓN ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                  style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-person-check-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Participación en Gestión Agropecuaria</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Participó en proyectos?
                                        </label>
                                        <select name="participa" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ old('participa', $gestion->participa) == 'si' ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ old('participa', $gestion->participa) == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Año de participación
                                        </label>
                                        <input type="number" name="año" class="form-control"
                                               value="{{ old('año', $gestion->año) }}"
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-6 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Entidad gestora
                                        </label>
                                        <input type="text" name="entidad_gestiono" class="form-control"
                                               value="{{ old('entidad_gestiono', $gestion->entidad_gestiono) }}"
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-6 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿En qué consistió?
                                        </label>
                                        <select name="consistio" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="Entrega de insumos" {{ old('consistio', $gestion->consistio) == 'Entrega de insumos' ? 'selected' : '' }}>Entrega de insumos</option>
                                            <option value="Entrega de recursos economicos" {{ old('consistio', $gestion->consistio) == 'Entrega de recursos economicos' ? 'selected' : '' }}>Entrega de recursos económicos</option>
                                            <option value="Transferencia de conocimiento" {{ old('consistio', $gestion->consistio) == 'Transferencia de conocimiento' ? 'selected' : '' }}>Transferencia de conocimiento</option>
                                            <option value="Entrega de herramientas, equipos y/o instalaciones" {{ old('consistio', $gestion->consistio) == 'Entrega de herramientas, equipos y/o instalaciones' ? 'selected' : '' }}>Entrega de herramientas, equipos y/o instalaciones</option>
                                            <option value="Entrega de plantas" {{ old('consistio', $gestion->consistio) == 'Entrega de plantas' ? 'selected' : '' }}>Entrega de plantas</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN: CRÉDITOS Y FINANCIACIÓN ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-cash-stack fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Créditos y Financiación</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-6 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Solicitó crédito?
                                        </label>
                                        <select name="credito" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ old('credito', $gestion->credito) == 'si' ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ old('credito', $gestion->credito) == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Fue aprobado?
                                        </label>
                                        <select name="aprobado" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ old('aprobado', $gestion->aprobado) == 'si' ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ old('aprobado', $gestion->aprobado) == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Fuentes de financiamiento
                                        </label>
                                        @php
                                            $oldFuentes = old('fuentes');
                                            if ($oldFuentes !== null) {
                                                // Old input exists (from validation errors)
                                                $fuentesSeleccionadas = is_array($oldFuentes) ? $oldFuentes : (is_string($oldFuentes) ? explode(',', $oldFuentes) : []);
                                            } else {
                                                // No old input, use model data
                                                $modelFuentes = $gestion->fuentes;
                                                if (is_array($modelFuentes)) {
                                                    $fuentesSeleccionadas = $modelFuentes;
                                                } elseif (is_string($modelFuentes) && !empty($modelFuentes)) {
                                                    $fuentesSeleccionadas = explode(',', $modelFuentes);
                                                } else {
                                                    $fuentesSeleccionadas = [];
                                                }
                                            }
                                            $fuentesDisponibles = [
                                                'Banco Agrario',
                                                'Otros bancos',
                                                'Cooperativa',
                                                'Particulares o prestamistas',
                                                'Organizaciones gubernamentales (ONG\'s)',
                                                'Programas del gobierno',
                                                'Cooperación internacional',
                                                'Almacenes de insumos agrícolas y agroindustria'
                                            ];
                                        @endphp

                                        <div class="border rounded p-3" style="background:#f8f9fa; border-radius:8px;">
                                            @foreach($fuentesDisponibles as $fuente)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="fuentes[]" value="{{ $fuente }}"
                                                           {{ in_array($fuente, $fuentesSeleccionadas) ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $fuente }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-md-6 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Destino de recursos
                                        </label>
                                        <select name="destino_recursos" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="Pago de mano de obra" {{ old('destino_recursos', $gestion->destino_recursos) == 'Pago de mano de obra' ? 'selected' : '' }}>Pago de mano de obra</option>
                                            <option value="Compra de insumos" {{ old('destino_recursos', $gestion->destino_recursos) == 'Compra de insumos' ? 'selected' : '' }}>Compra de insumos</option>
                                            <option value="Compra de maquinaria de uso agricola" {{ old('destino_recursos', $gestion->destino_recursos) == 'Compra de maquinaria de uso agricola' ? 'selected' : '' }}>Compra de maquinaria de uso agrícola</option>
                                            <option value="Compra de maquinaria de uso pecuario" {{ old('destino_recursos', $gestion->destino_recursos) == 'Compra de maquinaria de uso pecuario' ? 'selected' : '' }}>Compra de maquinaria de uso pecuario</option>
                                            <option value="Compra de animales" {{ old('destino_recursos', $gestion->destino_recursos) == 'Compra de animales' ? 'selected' : '' }}>Compra de animales</option>
                                            <option value="Instalacion de cultivo" {{ old('destino_recursos', $gestion->destino_recursos) == 'Instalacion de cultivo' ? 'selected' : '' }}>Instalación de cultivo</option>
                                            <option value="Compra de tierras" {{ old('destino_recursos', $gestion->destino_recursos) == 'Compra de tierras' ? 'selected' : '' }}>Compra de tierras</option>
                                            <option value="Pago de alquiler y otros servicios agropecuarios" {{ old('destino_recursos', $gestion->destino_recursos) == 'Pago de alquiler y otros servicios agropecuarios' ? 'selected' : '' }}>Pago de alquiler y otros servicios agropecuarios</option>
                                            <option value="Obras y mantenimiento de infraestructura" {{ old('destino_recursos', $gestion->destino_recursos) == 'Obras y mantenimiento de infraestructura' ? 'selected' : '' }}>Obras y mantenimiento de infraestructura</option>
                                            <option value="Postcosecha" {{ old('destino_recursos', $gestion->destino_recursos) == 'Postcosecha' ? 'selected' : '' }}>Postcosecha</option>
                                            <option value="Otro destino" {{ old('destino_recursos', $gestion->destino_recursos) == 'Otro destino' ? 'selected' : '' }}>Otro destino</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Tiene créditos actualmente?
                                        </label>
                                        <select name="tiene_creditos" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ old('tiene_creditos', $gestion->tiene_creditos) == 'si' ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ old('tiene_creditos', $gestion->tiene_creditos) == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN: DETALLES DE CRÉDITOS ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                  style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-credit-card-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Detalles de Créditos</h5>
                            </div>
                            <div class="card-body p-4 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">

                                {{-- Campos dinámicos de créditos --}}
                                <div id="contenedor-creditos">
                            @php
                                $entidades = is_string($gestion->entidad) ? json_decode($gestion->entidad, true) : $gestion->entidad;
                                $valores = is_string($gestion->valor_credito) ? json_decode($gestion->valor_credito, true) : $gestion->valor_credito;
                                $plazos = is_string($gestion->plazo) ? json_decode($gestion->plazo, true) : $gestion->plazo;
                                $fechas = is_string($gestion->fecha_aprobacion) ? json_decode($gestion->fecha_aprobacion, true) : $gestion->fecha_aprobacion;
                                $alDias = is_string($gestion->al_dia) ? json_decode($gestion->al_dia, true) : $gestion->al_dia;
                                $seguros = is_string($gestion->seguro) ? json_decode($gestion->seguro, true) : $gestion->seguro;

                                // Handle old input
                                $entidades = old('entidad', $entidades ?: []);
                                $valores = old('valor_credito', $valores ?: []);
                                $plazos = old('plazo', $plazos ?: []);
                                $fechas = old('fecha_aprobacion', $fechas ?: []);
                                $alDias = old('al_dia', $alDias ?: []);
                                $seguros = old('seguro', $seguros ?: []);

                                $maxCreditos = max(count($entidades), count($valores), count($plazos), count($fechas), count($alDias), count($seguros), 1);
                            @endphp

                                    @for($i = 0; $i < $maxCreditos; $i++)
                                    <div class="credito-item border rounded p-3 mb-3" style="background:#f8f9fa;">
                                        <h6 class="fw-semibold mb-3 text-primary">Crédito {{ $i + 1 }}</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Entidad</label>
                                                <input type="text" name="entidad[]" class="form-control"
                                                       value="{{ $entidades[$i] ?? '' }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Valor del crédito</label>
                                                <input type="text" name="valor_credito[]" class="form-control"
                                                       value="{{ $valores[$i] ?? '' }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Plazo</label>
                                                <input type="text" name="plazo[]" class="form-control"
                                                       value="{{ $plazos[$i] ?? '' }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Fecha de aprobación</label>
                                                <input type="text" name="fecha_aprobacion[]" class="form-control"
                                                       value="{{ $fechas[$i] ?? '' }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">¿Al día?</label>
                                                <select name="al_dia[]" class="form-select"
                                                        style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="Si" {{ ($alDias[$i] ?? '') == 'Si' ? 'selected' : '' }}>Sí</option>
                                                    <option value="No" {{ ($alDias[$i] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Seguro</label>
                                                <input type="text" name="seguro[]" class="form-control"
                                                       value="{{ $seguros[$i] ?? '' }}"
                                                       style="border-radius:8px;">
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>

                                <div class="text-end mb-3">
                                    <button type="button" class="btn btn-primary" id="btn-add-credito">
                                        <i class="fas fa-plus me-1"></i>Añadir crédito
                                    </button>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN: MANO DE OBRA ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-people-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Mano de Obra</h5>
                            </div>
                            <div class="card-body p-4 participacion-campos" style="{{ old('participa', $gestion->participa) == 'si' ? '' : 'display: none;' }}">

                                @php
                                    $personasData = is_string($gestion->personas) ? json_decode($gestion->personas, true) : $gestion->personas;
                                    $personasData = old('personas', $personasData ?: []);

                                    $cuantosData = is_string($gestion->cuantos) ? json_decode($gestion->cuantos, true) : $gestion->cuantos;
                                    $cuantosData = old('cuantos', $cuantosData ?: []);
                                @endphp

                                {{-- Personas que trabajaron --}}
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-3">Personas que trabajaron permanentemente</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="hombres_personas" name="personas[hombres][activo]"
                                                           value="1" {{ isset($personasData['hombres']) ? 'checked' : '' }}
                                                           onchange="toggleCantidad('hombres_personas')">
                                                    <label class="form-check-label fw-semibold" for="hombres_personas">
                                                        Hombres
                                                    </label>
                                                </div>
                                                <div class="flex-grow-1" id="cantidad_hombres_personas" style="{{ isset($personasData['hombres']) ? '' : 'display: none;' }}">
                                                    <input type="number" min="0" name="personas[hombres][cantidad]"
                                                           class="form-control form-control-sm"
                                                           placeholder="Cantidad"
                                                           value="{{ $personasData['hombres']['cantidad'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="mujeres_personas" name="personas[mujeres][activo]"
                                                           value="1" {{ isset($personasData['mujeres']) ? 'checked' : '' }}
                                                           onchange="toggleCantidad('mujeres_personas')">
                                                    <label class="form-check-label fw-semibold" for="mujeres_personas">
                                                        Mujeres
                                                    </label>
                                                </div>
                                                <div class="flex-grow-1" id="cantidad_mujeres_personas" style="{{ isset($personasData['mujeres']) ? '' : 'display: none;' }}">
                                                    <input type="number" min="0" name="personas[mujeres][cantidad]"
                                                           class="form-control form-control-sm"
                                                           placeholder="Cantidad"
                                                           value="{{ $personasData['mujeres']['cantidad'] ?? '' }}">
                                                </input>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Trabajadores permanentes --}}
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-3">Trabajadores permanentes del hogar</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="hombres_trab" name="cuantos[hombres][activo]"
                                                           value="1" {{ isset($cuantosData['hombres']) ? 'checked' : '' }}
                                                           onchange="toggleCantidad('hombres_trab')">
                                                    <label class="form-check-label fw-semibold" for="hombres_trab">
                                                        Hombres
                                                    </label>
                                                </div>
                                                <div class="flex-grow-1" id="cantidad_hombres_trab" style="{{ isset($cuantosData['hombres']) ? '' : 'display: none;' }}">
                                                    <input type="number" min="0" name="cuantos[hombres][cantidad]"
                                                           class="form-control form-control-sm"
                                                           placeholder="Cantidad"
                                                           value="{{ $cuantosData['hombres']['cantidad'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="mujeres_trab" name="cuantos[mujeres][activo]"
                                                           value="1" {{ isset($cuantosData['mujeres']) ? 'checked' : '' }}
                                                           onchange="toggleCantidad('mujeres_trab')">
                                                    <label class="form-check-label fw-semibold" for="mujeres_trab">
                                                        Mujeres
                                                    </label>
                                                </div>
                                                <div class="flex-grow-1" id="cantidad_mujeres_trab" style="{{ isset($cuantosData['mujeres']) ? '' : 'display: none;' }}">
                                                    <input type="number" min="0" name="cuantos[mujeres][cantidad]"
                                                           class="form-control form-control-sm"
                                                           placeholder="Cantidad"
                                                           value="{{ $cuantosData['mujeres']['cantidad'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Otros campos --}}
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Jornales adicionales contratados
                                        </label>
                                        <input type="number" name="jornales" class="form-control"
                                               value="{{ old('jornales', $gestion->jornales) }}"
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Trabajo colectivo?
                                        </label>
                                        <select name="trabajo_colectivo" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ old('trabajo_colectivo', $gestion->trabajo_colectivo) == 'si' ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ old('trabajo_colectivo', $gestion->trabajo_colectivo) == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Valor del jornal (MIL)
                                        </label>
                                        <input type="number" step="0.01" name="valor_jornal" class="form-control"
                                               value="{{ old('valor_jornal', $gestion->valor_jornal) }}"
                                               style="border-radius:8px;">
                                    </div>
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
                                        <i class="bi bi-check-circle me-2"></i>Actualizar Gestión Agropecuaria
                                    </button>

                                    <a href="{{ route('gestion_agropecuaria.show', $gestion->id) }}"
                                       class="btn btn-outline-secondary py-2"
                                       style="border-radius:8px;">
                                        <i class="bi bi-x-circle me-2"></i>Cancelar
                                    </a>
                                </div>

                                <div class="alert alert-info mt-3 mb-0 small" style="border-radius:8px;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Los cambios se guardarán automáticamente
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
    </style>

    <script>
        // Función para mostrar/ocultar campos de participación
        function handleParticipacionChange() {
            const select = document.querySelector('select[name="participa"]');
            if (!select) return;

            const mostrar = select.value === 'si';
            const camposParticipacion = document.querySelectorAll('.participacion-campos');

            camposParticipacion.forEach(campo => {
                campo.style.display = mostrar ? 'block' : 'none';
            });
        }

        // Función para mostrar/ocultar campos de cantidad
        window.toggleCantidad = function (tipo) {
            const checkbox = document.getElementById(tipo);
            const divCantidad = document.getElementById('cantidad_' + tipo);

            if (!checkbox || !divCantidad) return;

            divCantidad.style.display = checkbox.checked ? 'block' : 'none';
        };

        // Event listeners
        document.addEventListener('DOMContentLoaded', function () {
            let creditoIndex = {{ count(is_string($gestion->entidad) ? json_decode($gestion->entidad, true) : ($gestion->entidad ?: [])) }};

            // Participación change
            const participaSelect = document.querySelector('select[name="participa"]');
            if (participaSelect) {
                participaSelect.addEventListener('change', handleParticipacionChange);
            }

            // Inicializar estados
            handleParticipacionChange();

            // Botón añadir crédito
            const btnAddCredito = document.getElementById('btn-add-credito');
            if (btnAddCredito) {
                btnAddCredito.addEventListener('click', function () {
                    const contenedor = document.getElementById('contenedor-creditos');
                    const nuevo = document.querySelector('.credito-item').cloneNode(true);

                    // Limpiar valores
                    nuevo.querySelectorAll('input').forEach(input => input.value = '');
                    nuevo.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                    contenedor.appendChild(nuevo);
                    creditoIndex++;
                });
            }
        });
    </script>

</x-app-layout>

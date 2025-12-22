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
                            <i class="bi bi-bug-fill me-2"></i>Editar Inventario Pecuario
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $inventario_pecuario->encuesta->nombre_identidad }} {{ $inventario_pecuario->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $inventario_pecuario->encuesta->id }}
                        </p>
                    </div>

                    <a href="{{ route('inventario_pecuario.show', $inventario_pecuario->id) }}"
                       class="btn btn-outline-secondary px-4 py-2"
                       style="border-radius:8px; font-weight:500;">
                       <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('inventario_pecuario.update', $inventario_pecuario->id) }}" class="bg-white shadow-lg rounded p-4 p-md-5">
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

                        {{-- === SECCIÓN GANADO BOVINO ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-cow fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Ganado Bovino</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Tiene ganado bovino?
                                        </label>
                                        <select name="tiene_ganado_bovino" class="form-select ganado-bovino-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ $inventario_pecuario->tiene_ganado_bovino ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ !$inventario_pecuario->tiene_ganado_bovino ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Campos de bovino --}}
                                <div id="contenedor-bovino" class="mt-3">
                                    <div class="bovino-item border rounded p-3 mb-3 shadow-sm bg-white bovino-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Información General</h6>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Orientación ganadera
                                                </label>
                                                <select name="orientacion_ganadera" class="form-select"
                                                        style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="doble" {{ $inventario_pecuario->orientacion_ganadera == 'doble' ? 'selected' : '' }}>Doble propósito</option>
                                                    <option value="leche" {{ $inventario_pecuario->orientacion_ganadera == 'leche' ? 'selected' : '' }}>Leche</option>
                                                    <option value="carne_completo" {{ $inventario_pecuario->orientacion_ganadera == 'carne_completo' ? 'selected' : '' }}>Carne (ciclo completo)</option>
                                                    <option value="carne_cria" {{ $inventario_pecuario->orientacion_ganadera == 'carne_cria' ? 'selected' : '' }}>Carne (cria, levante)</option>
                                                    <option value="carne_ceba" {{ $inventario_pecuario->orientacion_ganadera == 'carne_ceba' ? 'selected' : '' }}>Carne (ceba)</option>
                                                    <option value="genetica" {{ $inventario_pecuario->orientacion_ganadera == 'genetica' ? 'selected' : '' }}>Genetica</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Manejo de alimentación --}}
                                    <div class="bovino-item border rounded p-3 mb-3 shadow-sm bg-white bovino-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Manejo de Alimentación</h6>

                                        @php
                                            $opcionesManejo = [
                                                "Continuo (periodo prolongado en el mismo potrero)",
                                                "Rotacional (A la cuerda, franjas)",
                                                "Pastoreo y encierro",
                                                "Confinamiento o estabulado (encierro permanente)"
                                            ];
                                            $valoresSeleccionados = $inventario_pecuario->manejo_alimentacion ? explode(',', $inventario_pecuario->manejo_alimentacion) : [];
                                        @endphp

                                        <div class="row">
                                            @foreach ($opcionesManejo as $op)
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="manejo_alimentacion[]" value="{{ $op }}" id="manejo_{{ $loop->index }}" {{ in_array($op, $valoresSeleccionados) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="manejo_{{ $loop->index }}">{{ $op }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Vacunas --}}
                                    <div class="bovino-item border rounded p-3 mb-3 shadow-sm bg-white bovino-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Vacunas Recibidas</h6>

                                        @php
                                            $opcionesVacunas = [
                                                "Fiebre aftosa",
                                                "Brucelosis",
                                                "No aplico a ninguna de estas vacunas"
                                            ];
                                            $vacunasSeleccionadas = $inventario_pecuario->vacunas_recibidas ? explode(',', $inventario_pecuario->vacunas_recibidas) : [];
                                        @endphp

                                        <div class="row">
                                            @foreach ($opcionesVacunas as $vac)
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="vacunas_recibidas[]" value="{{ $vac }}" id="vacuna_{{ $loop->index }}" {{ in_array($vac, $vacunasSeleccionadas) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="vacuna_{{ $loop->index }}">{{ $vac }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Pago biológico --}}
                                    <div class="bovino-item border rounded p-3 mb-3 shadow-sm bg-white bovino-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Pago por Biológico</h6>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   En caso de haber vacunado ¿pagó por el biológico?
                                                </label>
                                                <select name="pago_biologico" class="form-select" style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="si" {{ $inventario_pecuario->pago_biologico ? 'selected' : '' }}>Sí</option>
                                                    <option value="no" {{ !$inventario_pecuario->pago_biologico ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Inventario de bovinos --}}
                                    <div class="bovino-item border rounded p-3 mb-3 shadow-sm bg-white bovino-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Inventario de Bovinos</h6>

                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Machos <1 año</label>
                                                <input type="number" name="bovino_machos_menor1" class="form-control" style="border-radius:8px;" value="{{ old('bovino_machos_menor1', $inventario_pecuario->bovino_machos_menor1 ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Machos 1-3 años</label>
                                                <input type="number" name="bovino_machos_1a3" class="form-control" style="border-radius:8px;" value="{{ old('bovino_machos_1a3', $inventario_pecuario->bovino_machos_1a3 ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Machos >3 años</label>
                                                <input type="number" name="bovino_machos_mayor3" class="form-control" style="border-radius:8px;" value="{{ old('bovino_machos_mayor3', $inventario_pecuario->bovino_machos_mayor3 ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Reproductores</label>
                                                <input type="number" name="bovino_machos_reproductores" class="form-control" style="border-radius:8px;" value="{{ old('bovino_machos_reproductores', $inventario_pecuario->bovino_machos_reproductores ?? '') }}">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Hembras <1 año</label>
                                                <input type="number" name="bovino_hembras_menor1" class="form-control" style="border-radius:8px;" value="{{ old('bovino_hembras_menor1', $inventario_pecuario->bovino_hembras_menor1 ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Hembras 1-3 años</label>
                                                <input type="number" name="bovino_hembras_1a3" class="form-control" style="border-radius:8px;" value="{{ old('bovino_hembras_1a3', $inventario_pecuario->bovino_hembras_1a3 ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Hembras >3 años</label>
                                                <input type="number" name="bovino_hembras_mayor3" class="form-control" style="border-radius:8px;" value="{{ old('bovino_hembras_mayor3', $inventario_pecuario->bovino_hembras_mayor3 ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Vacas en ordeño</label>
                                                <input type="number" name="bovino_hembras_ordeño" class="form-control" style="border-radius:8px;" value="{{ old('bovino_hembras_ordeño', $inventario_pecuario->bovino_hembras_ordeño ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN LECHE ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-egg-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Producción de Leche</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Cuánta fue la cantidad de leche producida en la unidad productiva el día de ayer? (en litros)
                                        </label>
                                        <input type="number"
                                               name="produccion_leche_litros"
                                               class="form-control border-success"
                                               value="{{ $inventario_pecuario->produccion_leche_litros ?? '' }}"
                                               style="border-radius:8px;">
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Qué uso le dio? (en porcentaje)
                                        </label>

                                        @php
                                            $usos = ["Consumo", "Comercialización"];
                                            $usosSeleccionados = [];

                                            // Parsear datos guardados del destino_leche
                                            if ($inventario_pecuario->destino_leche) {
                                                $lineas = explode("\n", trim($inventario_pecuario->destino_leche));
                                                foreach ($lineas as $linea) {
                                                    // Parsear formato: "Consumo: 10% de 50 litros"
                                                    if (preg_match('/^(.+?):\s*\d+%\s*de\s*.+$/', $linea, $matches)) {
                                                        $usosSeleccionados[] = trim($matches[1]);
                                                    }
                                                }
                                            }

                                            $porcentajesLeche = [];
                                            foreach ($lineas ?? [] as $linea) {
                                                if (preg_match('/^(.+?):\s*(\d+)%\s*de\s*.+$/', $linea, $matches)) {
                                                    $porcentajesLeche[$matches[1]] = $matches[2];
                                                }
                                            }
                                        @endphp

                                        @foreach ($usos as $index => $uso)
                                            <div class="d-flex align-items-center mb-2">
                                                <label class="form-check me-3">
                                                    <input type="checkbox"
                                                           class="form-check-input uso-checkbox"
                                                           name="uso_leche[]"
                                                           value="{{ $uso }}"
                                                           @if(in_array($uso, $usosSeleccionados)) checked @endif>
                                                    {{ $uso }}
                                                </label>

                                                <!-- Input porcentaje -->
                                                <input type="number"
                                                       name="porcentaje_uso_leche[{{ $uso }}]"
                                                       class="form-control border-primary porcentaje-input"
                                                       style="max-width:100px; border-radius:8px;"
                                                       placeholder="%"
                                                       min="0"
                                                       max="100"
                                                       value="{{ $porcentajesLeche[$uso] ?? '' }}">

                                                <span class="mx-2">de</span>

                                                <!-- Producción total como referencia -->
                                                <input type="text"
                                                       class="form-control border-secondary"
                                                       style="max-width:120px;"
                                                       value="{{ $inventario_pecuario->produccion_leche_litros ? $inventario_pecuario->produccion_leche_litros . ' litros' : 'producción total' }}"
                                                       readonly>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Dónde la comercializó? (en porcentaje)
                                        </label>

                                        @php
                                            $opcionesComercializacion = ["Vecinos", "Industria", "Transformacion/Derivados"];
                                            $comercializacionSeleccionada = [];

                                            // Parsear datos guardados de comercializacion_leche
                                            if ($inventario_pecuario->comercializacion_leche) {
                                                $comercializacionSeleccionada = array_map('trim', explode(', ', $inventario_pecuario->comercializacion_leche));
                                            }

                                            $porcentajesComercializacion = [];
                                            foreach ($comercializacionSeleccionada as $opcion) {
                                                if (preg_match('/^(.+?):\s*(\d+)%$/', $opcion, $matches)) {
                                                    $porcentajesComercializacion[$matches[1]] = $matches[2];
                                                } else {
                                                    // Si no tiene porcentaje, marcar como seleccionado
                                                    $porcentajesComercializacion[$opcion] = '';
                                                }
                                            }
                                        @endphp

                                        @foreach ($opcionesComercializacion as $opcion)
                                            <div class="d-flex align-items-center mb-2">
                                                <label class="form-check me-3">
                                                    <input type="checkbox"
                                                           class="form-check-input comercializacion-checkbox"
                                                           name="comercializacion_leche[]"
                                                           value="{{ $opcion }}"
                                                           @if(in_array($opcion, array_keys($porcentajesComercializacion))) checked @endif>
                                                    {{ $opcion }}
                                                </label>

                                                <!-- Input porcentaje -->
                                                <input type="number"
                                                       name="porcentaje_comercializacion_leche[{{ $opcion }}]"
                                                       class="form-control border-primary porcentaje-comercializacion-input"
                                                       style="max-width:100px; border-radius:8px;"
                                                       placeholder="%"
                                                       min="0"
                                                       max="100"
                                                       value="{{ $porcentajesComercializacion[$opcion] ?? '' }}">

                                                <span class="mx-2">de</span>
                                                <input type="text"
                                                       class="form-control border-secondary"
                                                       style="max-width:120px;"
                                                       value="{{ $inventario_pecuario->produccion_leche_litros ? $inventario_pecuario->produccion_leche_litros . ' litros' : 'producción total' }}"
                                                       readonly>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN CERDOS ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-piggy-bank-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Porcinos</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Tiene cerdos en confinamiento?
                                        </label>
                                        <select name="tiene_cerdos" class="form-select cerdos-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ $inventario_pecuario->tiene_cerdos ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ !$inventario_pecuario->tiene_cerdos ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Campos de cerdos --}}
                                <div id="contenedor-cerdos" class="mt-3">
                                    <div class="cerdos-item border rounded p-3 mb-3 shadow-sm bg-white cerdos-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Información General</h6>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Orientación Porícola
                                                </label>
                                                <select name="orientacion_porcicola" class="form-select"
                                                        style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="cria" {{ $inventario_pecuario->orientacion_porcicola == 'cria' ? 'selected' : '' }}>Cría</option>
                                                    <option value="levante_y_ceba" {{ $inventario_pecuario->orientacion_porcicola == 'levante_y_ceba' ? 'selected' : '' }}>Levante y ceba</option>
                                                    <option value="ciclo_completo" {{ $inventario_pecuario->orientacion_porcicola == 'ciclo_completo' ? 'selected' : '' }}>Ciclo completo</option>
                                                    <option value="genetica" {{ $inventario_pecuario->orientacion_porcicola == 'genetica' ? 'selected' : '' }}>Genetica</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Vacuna Peste Clásica
                                                </label>
                                                <select name="vacuna_peste_clasica" class="form-select"
                                                        style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="si" {{ $inventario_pecuario->vacuna_peste_clasica ? 'selected' : '' }}>Sí</option>
                                                    <option value="no" {{ !$inventario_pecuario->vacuna_peste_clasica ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Inventario de cerdos --}}
                                    <div class="cerdos-item border rounded p-3 mb-3 shadow-sm bg-white cerdos-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Inventario de Porcinos</h6>

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Machos Reproductores</label>
                                                <input type="number" name="cerdos_machos_reproductores" class="form-control" style="border-radius:8px;" value="{{ old('cerdos_machos_reproductores', $inventario_pecuario->cerdos_machos_reproductores ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Hembras Gestantes/Lactantes/Vacías</label>
                                                <input type="number" name="cerdos_hembras_gestantes" class="form-control" style="border-radius:8px;" value="{{ old('cerdos_hembras_gestantes', $inventario_pecuario->cerdos_hembras_gestantes ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Hembras de Reemplazo</label>
                                                <input type="number" name="cerdos_hembras_reemplazo" class="form-control" style="border-radius:8px;" value="{{ old('cerdos_hembras_reemplazo', $inventario_pecuario->cerdos_hembras_reemplazo ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Hembras y Machos de Descarte</label>
                                                <input type="number" name="cerdos_descartes" class="form-control" style="border-radius:8px;" value="{{ old('cerdos_descartes', $inventario_pecuario->cerdos_descartes ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">¿Cuantos cerdos destetos tuvo durante el año anterior?</label>
                                                <input type="number" name="cerdos_destetos_anio" class="form-control" style="border-radius:8px;" value="{{ old('cerdos_destetos_anio', $inventario_pecuario->cerdos_destetos_anio ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">¿Cuantos cerdos cebados tuvo durante el ultimo anterior?</label>
                                                <input type="number" name="cerdos_ceba_anio" class="form-control" style="border-radius:8px;" value="{{ old('cerdos_ceba_anio', $inventario_pecuario->cerdos_ceba_anio ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN AVES ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-egg-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Avicultura</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Cria gallinas, pollos en galpón?
                                        </label>
                                        <select name="cria_gallinas_pollos_galpon" class="form-select aves-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ $inventario_pecuario->cria_gallinas_pollos_galpon ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ !$inventario_pecuario->cria_gallinas_pollos_galpon ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Campos de aves --}}
                                <div id="contenedor-aves" class="mt-3">
                                   
                                   
                                   
                                    <div class="aves-item border rounded p-3 mb-3 shadow-sm bg-white aves-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Inventario de Aves</h6>

                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold d-block">Orientación Avícola</label>

                                                @php
                                                    $opcionesOrientacion = [
                                                        "Postura",
                                                        "Engorda",
                                                        "Genetica para produccion de huevo",
                                                        "Genetica para produccion de pollo de engorde"
                                                    ];

                                                    // Obtener valores guardados
                                                    $valoresSeleccionados = old('orientacion_avicola', $inventario_pecuario->orientacion_avicola ?? []);

                                                    // Convertir string a array si viene almacenado como texto separado por comas
                                                    if (is_string($valoresSeleccionados)) {
                                                        $valoresSeleccionados = explode(',', $valoresSeleccionados);
                                                    }
                                                @endphp

                                                <div class="row">
                                                    @foreach ($opcionesOrientacion as $op)
                                                        <div class="col-md-12 my-1">
                                                            <label class="form-check">
                                                                <input type="checkbox"
                                                                       class="form-check-input"
                                                                       name="orientacion_avicola[]"
                                                                       value="{{ $op }}"
                                                                       @if(in_array($op, $valoresSeleccionados)) checked @endif>
                                                                {{ $op }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Gallinas ponedoras</label>
                                                <input type="number" name="aves_ponedoras" class="form-control" style="border-radius:8px;" value="{{ old('aves_ponedoras', $inventario_pecuario->aves_ponedoras ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Pollos de engorde</label>
                                                <input type="number" name="aves_pollos_engorde" class="form-control" style="border-radius:8px;" value="{{ old('aves_pollos_engorde', $inventario_pecuario->aves_pollos_engorde ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Aves para genética huevo</label>
                                                <input type="number" name="aves_genetica_huevo" class="form-control" style="border-radius:8px;" value="{{ old('aves_genetica_huevo', $inventario_pecuario->aves_genetica_huevo ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Aves para genética pollo engorde</label>
                                                <input type="number" name="aves_genetica_engorde" class="form-control" style="border-radius:8px;" value="{{ old('aves_genetica_engorde', $inventario_pecuario->aves_genetica_engorde ?? '') }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Producción y comercialización --}}
                                    <div class="aves-item border rounded p-3 mb-3 shadow-sm bg-white aves-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Producción y Comercialización</h6>

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">¿Cuantos huevos produjo el último mes?</label>
                                                <input type="number" name="produccion_huevos_mes" class="form-control" style="border-radius:8px;" value="{{ old('produccion_huevos_mes', $inventario_pecuario->produccion_huevos_mes ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">¿Donde los comercializo?</label>
                                                <input type="text" name="comercializacion_huevos" class="form-control" style="border-radius:8px;" value="{{ old('comercializacion_huevos', $inventario_pecuario->comercializacion_huevos ?? '') }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">¿Que cantidad de pollo comercializó durante el ultimo mes (kilogramos)?</label>
                                                <input type="number" name="pollo_comercializado_kg_mes" class="form-control" style="border-radius:8px;" value="{{ old('pollo_comercializado_kg_mes', $inventario_pecuario->pollo_comercializado_kg_mes ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">¿Donde los comercializo?</label>
                                                <input type="text" name="donde_comercializo_pollo" class="form-control" style="border-radius:8px;" value="{{ old('donde_comercializo_pollo', $inventario_pecuario->donde_comercializo_pollo ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Método de Sacrificio</label>
                                                <select name="metodo_sacrificio" class="form-select" style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="aturdimiento_electrico" {{ $inventario_pecuario->metodo_sacrificio == 'aturdimiento_electrico' ? 'selected' : '' }}>Aturdimiento eléctrico</option>
                                                    <option value="aturdimiento_por_conmocion" {{ $inventario_pecuario->metodo_sacrificio == 'aturdimiento_por_conmocion' ? 'selected' : '' }}>Aturdimiento por conmoción (golpe)</option>
                                                    <option value="dislocacion" {{ $inventario_pecuario->metodo_sacrificio == 'dislocacion' ? 'selected' : '' }}>Dislocación del cuello</option>
                                                    <option value="degüello" {{ $inventario_pecuario->metodo_sacrificio == 'degüello' ? 'selected' : '' }}>Degüello</option>
                                                    <option value="otros" {{ $inventario_pecuario->metodo_sacrificio == 'otros' ? 'selected' : '' }}>Otros</option>
                                                </select>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN PECES ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%); padding:1.25rem;">
                                <i class="bi bi-water fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Acuicultura</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Cria peces?
                                        </label>
                                        <select name="cria_peces" class="form-select peces-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ $inventario_pecuario->cria_peces ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ !$inventario_pecuario->cria_peces ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                            {{-- Campos de peces --}}
                            <div id="contenedor-peces" class="acuicultura-campos" style="display: none;">
                                {{-- Primera especie --}}
                                
                            {{-- Especies existentes --}}
                            @php
                                // Los campos ya vienen como arrays del controlador (edit method)
                                $pecesEspecies = is_array($inventario_pecuario->peces_especie) ? $inventario_pecuario->peces_especie : [];
                                $pecesCosechas = is_array($inventario_pecuario->peces_cosechas_anio) ? $inventario_pecuario->peces_cosechas_anio : [];
                                $pecesAnimales = is_array($inventario_pecuario->peces_animales_cosecha) ? $inventario_pecuario->peces_animales_cosecha : [];
                                $pecesPeso = is_array($inventario_pecuario->peces_peso_promedio) ? $inventario_pecuario->peces_peso_promedio : [];
                                $pecesProduccion = is_array($inventario_pecuario->peces_produccion_total_anterior) ? $inventario_pecuario->peces_produccion_total_anterior : [];
                                $pecesComercializacion = is_array($inventario_pecuario->peces_comercializacion) ? $inventario_pecuario->peces_comercializacion : [];

                                // Calcular máximo de especies de forma segura
                                $counts = [
                                    count($pecesEspecies),
                                    count($pecesCosechas),
                                    count($pecesAnimales),
                                    count($pecesPeso),
                                    count($pecesProduccion),
                                    count($pecesComercializacion)
                                ];
                                $maxEspecies = max($counts);
                            @endphp

                            @if($maxEspecies > 0)
                                <h6 class="fw-semibold mb-3" style="color:#00BCD4;">Especies Registradas</h6>

                                @for($i = 0; $i < $maxEspecies; $i++)
                                    <div class="peces-item border rounded p-3 mb-3 shadow-sm bg-white">
                                        <h6 class="fw-semibold mb-3 text-primary">
                                            <i class="bi bi-fish me-2"></i>Especie {{ $i + 1 }}: {{ $pecesEspecies[$i] ?? 'Sin nombre' }}
                                            <button type="button" class="btn btn-sm btn-outline-danger float-end btn-remove-especie">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </h6>

                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label class="fw-semibold">Nombre de la Especie</label>
                                                <input type="text" name="peces_especie[]" class="form-control" value="{{ $pecesEspecies[$i] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="fw-semibold"># de cosechas / año</label>
                                                <input type="number" name="peces_cosechas_anio[]" class="form-control" value="{{ $pecesCosechas[$i] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="fw-semibold"># de animales / cosecha</label>
                                                <input type="number" name="peces_animales_cosecha[]" class="form-control" value="{{ $pecesAnimales[$i] ?? '' }}">
                                            </div>
                                             <div class="col-md-2">
                                                <label class="fw-semibold">Peso promedio / animal</label>
                                                <input type="number" step="0.01" name="peces_peso_promedio[]" class="form-control" value="{{ $pecesPeso[$i] ?? '' }}">
                                            </div>
                                             <div class="col-md-2">
                                                <label class="fw-semibold">Producción total año anterior</label>
                                                <input type="number" name="peces_produccion_total_anterior[]" class="form-control" value="{{ $pecesProduccion[$i] ?? '' }}">
                                            </div>
                                              <div class="col-md-2">
                                                <label class="fw-semibold">Lugar de comercialización</label>
                                                <input type="text" name="peces_comercializacion[]" class="form-control" value="{{ $pecesComercializacion[$i] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                            </div>

                            {{-- Botón para añadir especie --}}
                            <div class="text-end mb-4 acuicultura-campos" style="display: none;">
                                <button type="button" class="btn btn-primary" id="btn-add-especie">
                                    <i class="fas fa-plus me-1"></i> Añadir especie
                                </button>
                            </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN OTROS ANIMALES ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-tree-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Búfalos, Equinos, Ovinos y Caprinos</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Tiene otros animales?
                                        </label>
                                        <select name="tiene_otros_animales" class="form-select otros-animales-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ $inventario_pecuario->tiene_otros_animales ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ !$inventario_pecuario->tiene_otros_animales ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Campos de otros animales --}}
                                <div id="contenedor-otros-animales" class="mt-3">
                                    <div class="otros-animales-item border rounded p-3 mb-3 shadow-sm bg-white otros-animales-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Información General</h6>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold d-block">Orientación Ovino-Caprina</label>

                                                @php
                                                    $opcionesOrientacion = [
                                                        "Carne",
                                                        "Leche",
                                                        "Lana",
                                                        "Pie de cría",
                                                        "Otro"
                                                    ];

                                                    // Obtener valores guardados
                                                    $valoresSeleccionados = old('orientacion_ovino_caprina', $inventario_pecuario->orientacion_ovino_caprina ?? []);

                                                    // Convertir string a array si viene almacenado como texto separado por comas
                                                    if (is_string($valoresSeleccionados)) {
                                                        $valoresSeleccionados = explode(',', $valoresSeleccionados);
                                                    }
                                                @endphp

                                                <div class="row">
                                                    @foreach ($opcionesOrientacion as $op)
                                                        <div class="col-md-12 my-1">
                                                            <label class="form-check">
                                                                <input type="checkbox"
                                                                       class="form-check-input"
                                                                       name="orientacion_ovino_caprina[]"
                                                                       value="{{ $op }}"
                                                                       @if(in_array($op, $valoresSeleccionados)) checked @endif>
                                                                {{ $op }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Vacuna Encefalitis Equina</label>
                                                <select name="vacuna_encefalitis_equina" class="form-select" style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="si" {{ $inventario_pecuario->vacuna_encefalitis_equina ? 'selected' : '' }}>Sí</option>
                                                    <option value="no" {{ !$inventario_pecuario->vacuna_encefalitis_equina ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Inventario de otros animales --}}
                                    <div class="otros-animales-item border rounded p-3 mb-3 shadow-sm bg-white otros-animales-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Inventario de Animales</h6>

                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Caballos</label>
                                                <input type="number" name="caballos" class="form-control" style="border-radius:8px;" value="{{ old('caballos', $inventario_pecuario->caballos ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Yeguas</label>
                                                <input type="number" name="yeguas" class="form-control" style="border-radius:8px;" value="{{ old('yeguas', $inventario_pecuario->yeguas ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Mulos</label>
                                                <input type="number" name="mulos" class="form-control" style="border-radius:8px;" value="{{ old('mulos', $inventario_pecuario->mulos ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Mulas</label>
                                                <input type="number" name="mulas" class="form-control" style="border-radius:8px;" value="{{ old('mulas', $inventario_pecuario->mulas ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Burros</label>
                                                <input type="number" name="burros" class="form-control" style="border-radius:8px;" value="{{ old('burros', $inventario_pecuario->burros ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Burras</label>
                                                <input type="number" name="burras" class="form-control" style="border-radius:8px;" value="{{ old('burras', $inventario_pecuario->burras ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Cabros</label>
                                                <input type="number" name="cabros" class="form-control" style="border-radius:8px;" value="{{ old('cabros', $inventario_pecuario->cabros ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Cabras</label>
                                                <input type="number" name="cabras" class="form-control" style="border-radius:8px;" value="{{ old('cabras', $inventario_pecuario->cabras ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Ovejos</label>
                                                <input type="number" name="ovejos" class="form-control" style="border-radius:8px;" value="{{ old('ovejos', $inventario_pecuario->ovejos ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Ovejas</label>
                                                <input type="number" name="ovejas" class="form-control" style="border-radius:8px;" value="{{ old('ovejas', $inventario_pecuario->ovejas ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Búfalos Machos</label>
                                                <input type="number" name="bufalos_machos" class="form-control" style="border-radius:8px;" value="{{ old('bufalos_machos', $inventario_pecuario->bufalos_machos ?? '') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">Búfalos Hembras</label>
                                                <input type="number" name="bufalos_hembras" class="form-control" style="border-radius:8px;" value="{{ old('bufalos_hembras', $inventario_pecuario->bufalos_hembras ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN ANIMALES DE TRASPATIO ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-house-heart-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Animales de Traspatio</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Cerdos</label>
                                        <input type="number" name="cerdos_traspatio" class="form-control" style="border-radius:8px;" value="{{ old('cerdos_traspatio', $inventario_pecuario->cerdos_traspatio ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Gallos/Pollos/Gallinas</label>
                                        <input type="number" name="gallos_pollos_traspatio" class="form-control" style="border-radius:8px;" value="{{ old('gallos_pollos_traspatio', $inventario_pecuario->gallos_pollos_traspatio ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Gallos de Pelea</label>
                                        <input type="number" name="gallos_pelea" class="form-control" style="border-radius:8px;" value="{{ old('gallos_pelea', $inventario_pecuario->gallos_pelea ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Piscos o Pavos</label>
                                        <input type="number" name="pavos" class="form-control" style="border-radius:8px;" value="{{ old('pavos', $inventario_pecuario->pavos ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Patos/Gansos</label>
                                        <input type="number" name="patos_gansos" class="form-control" style="border-radius:8px;" value="{{ old('patos_gansos', $inventario_pecuario->patos_gansos ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Codornices</label>
                                        <input type="number" name="codornices" class="form-control" style="border-radius:8px;" value="{{ old('codornices', $inventario_pecuario->codornices ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Avestruces</label>
                                        <input type="number" name="avestruces" class="form-control" style="border-radius:8px;" value="{{ old('avestruces', $inventario_pecuario->avestruces ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Cuyes</label>
                                        <input type="number" name="cuyes" class="form-control" style="border-radius:8px;" value="{{ old('cuyes', $inventario_pecuario->cuyes ?? '') }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Conejos</label>
                                        <input type="number" name="conejos" class="form-control" style="border-radius:8px;" value="{{ old('conejos', $inventario_pecuario->conejos ?? '') }}">
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN ABEJAS ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-hexagon-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Apicultura</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Colmenas Miel</label>
                                        <input type="number" name="colmenas_miel" class="form-control" style="border-radius:8px;" value="{{ old('colmenas_miel', $inventario_pecuario->colmenas_miel ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Colmenas Polen</label>
                                        <input type="number" name="colmenas_polen" class="form-control" style="border-radius:8px;" value="{{ old('colmenas_polen', $inventario_pecuario->colmenas_polen ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Colmenas Subproductos</label>
                                        <input type="number" name="colmenas_subproductos" class="form-control" style="border-radius:8px;" value="{{ old('colmenas_subproductos', $inventario_pecuario->colmenas_subproductos ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Colmenas Meliponas</label>
                                        <input type="number" name="colmenas_meliponas" class="form-control" style="border-radius:8px;" value="{{ old('colmenas_meliponas', $inventario_pecuario->colmenas_meliponas ?? '') }}">
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN MASCOTAS ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-heart-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Mascotas</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">

                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Aves Ornamentales</label>
                                        <input type="number" name="aves_ornamentales" class="form-control" style="border-radius:8px;" value="{{ old('aves_ornamentales', $inventario_pecuario->aves_ornamentales ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Caninos Hembras</label>
                                        <input type="number" name="caninos_hembras" class="form-control" style="border-radius:8px;" value="{{ old('caninos_hembras', $inventario_pecuario->caninos_hembras ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Caninos Machos</label>
                                        <input type="number" name="caninos_machos" class="form-control" style="border-radius:8px;" value="{{ old('caninos_machos', $inventario_pecuario->caninos_machos ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Felinos Hembras</label>
                                        <input type="number" name="felinos_hembras" class="form-control" style="border-radius:8px;" value="{{ old('felinos_hembras', $inventario_pecuario->felinos_hembras ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Felinos Machos</label>
                                        <input type="number" name="felinos_machos" class="form-control" style="border-radius:8px;" value="{{ old('felinos_machos', $inventario_pecuario->felinos_machos ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Tortugas</label>
                                        <input type="number" name="tortugas" class="form-control" style="border-radius:8px;" value="{{ old('tortugas', $inventario_pecuario->tortugas ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Esterilizados</label>
                                        <select name="esterilizados" class="form-select" style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="si" {{ $inventario_pecuario->esterilizados ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ !$inventario_pecuario->esterilizados ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Otros Animales</label>
                                        <textarea name="otros2" class="form-control" rows="2" style="border-radius:8px;">{{ old('otros2', $inventario_pecuario->otros2 ?? '') }}</textarea>
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
                                        <i class="bi bi-check-circle me-2"></i>Actualizar Inventario Pecuario
                                    </button>

                                    <a href="{{ route('inventario_pecuario.show', $inventario_pecuario->id) }}"
                                       class="btn btn-outline-secondary py-2"
                                       style="border-radius:8px;">
                                        <i class="bi bi-x-circle me-2"></i>Cancelar
                                    </a>
                                </div>

                                <div class="alert alert-info mt-3 mb-0 small" style="border-radius:8px;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Los campos marcados son obligatorios
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <style>
        .form-control:focus, .form-select:focus {
            border-color: #2d5f3f;
            box-shadow: 0 0 0 0.2rem rgba(45, 95, 63, 0.25);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
        document.addEventListener('DOMContentLoaded', function() {
            // Función helper para manejar mostrar/ocultar campos
            function setupConditionalFields(selectSelector, fieldsSelector) {
                const select = document.querySelector(selectSelector);
                const campos = document.querySelectorAll(fieldsSelector);

                function toggleCampos() {
                    const mostrar = select.value === 'si';
                    campos.forEach(campo => {
                        campo.style.display = mostrar ? 'block' : 'none';
                    });
                }

                if (select) {
                    // Estado inicial
                    toggleCampos();

                    // Event listener para cambios
                    select.addEventListener('change', toggleCampos);
                }
            }

            // Configurar todas las secciones condicionales
            setupConditionalFields('.ganado-bovino-select', '.bovino-campos');
            setupConditionalFields('.cerdos-select', '.cerdos-campos');
            setupConditionalFields('.aves-select', '.aves-campos');

            // Configurar peces con valores 'si'/'no' en lugar de '1'/'0'
            function setupPecesFields() {
                const select = document.querySelector('.peces-select');
                const campos = document.querySelectorAll('.acuicultura-campos');

                function toggleCampos() {
                    const mostrar = select.value === 'si';
                    campos.forEach(campo => {
                        campo.style.display = mostrar ? 'block' : 'none';
                    });
                }

                if (select) {
                    toggleCampos();
                    select.addEventListener('change', toggleCampos);
                }
            }
            setupPecesFields();

            setupConditionalFields('.otros-animales-select', '.otros-animales-campos');

            // Actualizar referencias cuando cambia la producción de leche
            const produccionInput = document.querySelector('input[name="produccion_leche_litros"]');
            const referenciaInputs = document.querySelectorAll('.form-control[readonly]');

            if (produccionInput) {
                produccionInput.addEventListener('input', function() {
                    const valor = this.value;
                    const texto = valor ? valor + ' litros' : 'producción total';

                    referenciaInputs.forEach(input => {
                        input.value = texto;
                    });
                });
            }

            // Validación básica de porcentajes para uso de leche
            const porcentajeInputs = document.querySelectorAll('input[name^="porcentaje_uso_leche"]');
            porcentajeInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const valor = parseFloat(this.value);
                    if (valor < 0 || valor > 100) {
                        this.setCustomValidity('El porcentaje debe estar entre 0 y 100');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            });

            // Validación básica de porcentajes para comercialización de leche
            const porcentajeComercializacionInputs = document.querySelectorAll('input[name^="porcentaje_comercializacion_leche"]');
            porcentajeComercializacionInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const valor = parseFloat(this.value);
                    if (valor < 0 || valor > 100) {
                        this.setCustomValidity('El porcentaje debe estar entre 0 y 100');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            });

            // Funcionalidad para añadir especies de peces (igual que vista de creación)
            document.getElementById('btn-add-especie').addEventListener('click', function() {
                const contenedor = document.getElementById('contenedor-peces');

                // Crear elemento de especie basado en el primero
                const primeraEspecie = document.querySelector('.peces-item');
                const nuevaEspecie = primeraEspecie.cloneNode(true);

                // Limpiar valores
                nuevaEspecie.querySelectorAll('input').forEach(input => input.value = '');
                nuevaEspecie.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                contenedor.appendChild(nuevaEspecie);
            });

            // Funcionalidad para eliminar especies
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-especie') || e.target.closest('.btn-remove-especie')) {
                    e.preventDefault();
                    const especieItem = e.target.closest('.peces-item');
                    if (especieItem) {
                        especieItem.remove();
                    }
                }
            });
        });
    </script>

</x-app-layout>

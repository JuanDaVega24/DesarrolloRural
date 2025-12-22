<x-app-layout>

    <x-steps 
        :progress="70"
        :current="5"
        :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Final']"
    />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <form method="POST" action="{{ route('inventario_pecuario.guardarPecuario') }}" 
                      class="bg-white shadow-lg rounded p-4 p-md-5">
                    @csrf

                    <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">

                    {{-- ERRORES --}}
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



                    {{-- =======================
                        GANADO BOVINO
                    ======================== --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color:#2d5f3f;">
                                <i class="bi bi-cow me-2"></i> Ganado Bovino
                            </h5>

                        <div class="row g-4 mb-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">¿Tiene ganado bovino?</label>
                                    <select name="tiene_ganado_bovino" class="form-select border-primary ganado-bovino-select">
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                <div class="col-md-6 ganado-bovino-campos" style="display: none;">
                                    <label class="form-label fw-semibold">Orientación ganadera</label>
                                    <select name="orientacion_ganadera" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="doble">Doble propósito</option>
                                        <option value="leche">Leche</option>
                                        <option value="carne_completo">Carne (ciclo completo)</option>
                                        <option value="carne_cria">Carne (cria, levante)</option>
                                        <option value="carne_ceba">Carne (ceba)</option>
                                        <option value="genetica">Genetica</option>

                                    </select>
                                </div>
                            </div>

                            <div class="row g-4 mb-3 ganado-bovino-campos" style="display: none;">
                               <div class="row g-4">
                                <div class="col-md-6">
    <label class="fw-semibold d-block">Manejo de alimentación</label>

    @php
        $opcionesManejo = [
            "Continuo (periodo prolongado en el mismo potrero)",
            "Rotacional (A la cuerda, franjas)",
            "Pastoreo y encierro",
            "Confinamiento o estabulado (encierro permanente)"
        ];

        // Obtener valores guardados
        $valoresSeleccionados = old('manejo_alimentacion', $pecuario->manejo_alimentacion ?? []);

        // Convertir string a array si viene almacenado como texto separado por comas
        if (is_string($valoresSeleccionados)) {
            $valoresSeleccionados = explode(',', $valoresSeleccionados);
        }
    @endphp

    <div class="row">
        @foreach ($opcionesManejo as $op)
            <div class="col-md-12 my-1">
                <label class="form-check">
                    <input type="checkbox"
                           class="form-check-input"
                           name="manejo_alimentacion[]"
                           value="{{ $op }}"
                           @if(in_array($op, $valoresSeleccionados)) checked @endif>
                    {{ $op }}
                </label>
            </div>
        @endforeach
    </div>
</div>


                                <div class="col-md-6 ganado-bovino-campos" style="display: none;">
    <label class="fw-semibold d-block">Vacunas recibidas</label>

    @php
        $opcionesVacunas = [
            "Fiebre aftosa",
            "Brucelosis",
            "No aplico a ninguna de estas vacunas"
        ];

        // Obtener valores guardados previamente
        $vacunasSeleccionadas = old('vacunas_recibidas', $pecuario->vacunas_recibidas ?? []);

        // Convertir string a array si está guardado como texto separado por comas
        if (is_string($vacunasSeleccionadas)) {
            $vacunasSeleccionadas = explode(',', $vacunasSeleccionadas);
        }
    @endphp

    <div class="row">
        @foreach ($opcionesVacunas as $vac)
            <div class="col-md-12 my-1">
                <label class="form-check">
                    <input type="checkbox"
                           class="form-check-input"
                           name="vacunas_recibidas[]"
                           value="{{ $vac }}"
                           @if(in_array($vac, $vacunasSeleccionadas)) checked @endif>
                    {{ $vac }}
                </label>
            </div>
        @endforeach
    </div>
</div>
</div>
 <div class="col-md-6 ganado-bovino-campos" style="display: none;">
                                    <label class="form-label fw-semibold">En caso de haber vacunado ¿pagó por el biológico?</label>
                                    <select name="pago_biologico" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Cantidad de bovinos --}}
                            <div class="ganado-bovino-campos" style="display: none;">

                            {{-- Cantidad de bovinos --}}
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <label class="fw-semibold">Machos &lt;1 año</label>
                                    <input type="number" name="bovino_machos_menor1" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-semibold">Machos 1-3</label>
                                    <input type="number" name="bovino_machos_1a3" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-semibold">Machos &gt;3</label>
                                    <input type="number" name="bovino_machos_mayor3" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-semibold">Reproductores</label>
                                    <input type="number" name="bovino_machos_reproductores" class="form-control">
                                </div>
                            </div>

                            <div class="row g-4 mt-3 mb-3">
                                <div class="col-md-3">
                                    <label class="fw-semibold">Hembras &lt;1 año</label>
                                    <input type="number" name="bovino_hembras_menor1" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-semibold">Hembras 1-3</label>
                                    <input type="number" name="bovino_hembras_1a3" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-semibold">Hembras &gt;3</label>
                                    <input type="number" name="bovino_hembras_mayor3" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-semibold">Vacas en ordeño</label>
                                    <input type="number" name="bovino_hembras_ordeño" class="form-control">
                                </div>
                            </div>

                        </div>
                    </div>

{{-- =======================
                        LECHE
                    ======================== --}}
                   <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
    <div class="card-body">

        <h5 class="card-title mb-4" style="color:#2d5f3f;">
            <i class="bi bi-egg-fill me-2"></i> Leche
        </h5>

        <div class="row g-4">

            <!-- Cantidad producida -->
            <div class="col-md-6">
                <label class="fw-semibold">
                    ¿Cuánta fue la cantidad de leche producida en la unidad productiva el día de ayer? (en litros)
                </label>
                <input type="number"
                       name="produccion_leche_litros"
                       class="form-control border-success"
                       value="{{ old('produccion_leche_litros', $pecuario->produccion_leche_litros ?? '') }}">
            </div>

            <!-- Uso de la leche -->
            <div class="col-md-6">
                <label class="fw-semibold d-block">¿Qué uso le dio? (en porcentaje)</label>

                @php
                    $usos = ["Consumo", "Comercialización"];
                    $usosSeleccionados = old('uso_leche', $pecuario->uso_leche ?? []);
                    $porcentajesLeche = old('porcentaje_uso_leche', $pecuario->porcentaje_uso_leche ?? []);

                    if (is_string($usosSeleccionados)) $usosSeleccionados = explode(',', $usosSeleccionados);

                    // Obtener producción total para referencia
                    $produccionTotal = old('produccion_leche_litros', $pecuario->produccion_leche_litros ?? '');

                    // Parsear datos guardados del destino_leche
                    if (!$usosSeleccionados && $pecuario && $pecuario->destino_leche) {
                        $usosSeleccionados = [];
                        $porcentajesLeche = [];

                        $lineas = explode("\n", trim($pecuario->destino_leche));
                        foreach ($lineas as $linea) {
                            // Parsear formato: "Consumo: 10% de 50 litros"
                            if (preg_match('/^(.+?):\s*(\d+)%\s*de\s*.+$/', $linea, $matches)) {
                                $uso = trim($matches[1]);
                                $porcentaje = trim($matches[2]);
                                $usosSeleccionados[] = $uso;
                                $porcentajesLeche[$uso] = $porcentaje;
                            }
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
                                   @if(in_array($uso, (array)$usosSeleccionados)) checked @endif>
                            {{ $uso }}
                        </label>

                        <!-- Input porcentaje -->
                        <input type="number"
                               name="porcentaje_uso_leche[{{ $uso }}]"
                               class="form-control border-primary porcentaje-input"
                               style="max-width:100px;"
                               placeholder="%"
                               min="0"
                               max="100"
                               value="{{ $porcentajesLeche[$uso] ?? '' }}">

                        <span class="mx-2">de</span>

                        <!-- Producción total como referencia -->
                        <input type="text"
                               class="form-control border-secondary"
                               style="max-width:120px;"
                               value="{{ $produccionTotal ? $produccionTotal . ' litros' : 'producción total' }}"
                               readonly>
                    </div>
                @endforeach


            </div>

            <!-- Donde comercializó -->
            <div class="col-md-6">
                <label class="fw-semibold d-block">¿Dónde la comercializó? (en porcentaje)</label>

                @php
                    $opcionesComercializacion = ["Vecinos", "Industria", "Transformacion/Derivados"];
                    $comercializacionSeleccionada = old('comercializacion_leche', $pecuario->comercializacion_leche ?? []);
                    $porcentajesComercializacion = old('porcentaje_comercializacion_leche', $pecuario->porcentaje_comercializacion_leche ?? []);

                    if (is_string($comercializacionSeleccionada)) {
                        $comercializacionSeleccionada = array_map('trim', explode(',', $comercializacionSeleccionada));
                    }

                    // Parsear datos guardados de comercializacion_leche si no están en arrays separados
                    if (!$comercializacionSeleccionada && $pecuario && $pecuario->comercializacion_leche) {
                        $comercializacionSeleccionada = [];
                        $porcentajesComercializacion = [];

                        // Si está en formato "Vecinos: 30%, Industria: 70%"
                        $lineas = explode(',', trim($pecuario->comercializacion_leche));
                        foreach ($lineas as $linea) {
                            if (preg_match('/^(.+?):\s*(\d+)%$/', trim($linea), $matches)) {
                                $opcion = trim($matches[1]);
                                $porcentaje = trim($matches[2]);
                                $comercializacionSeleccionada[] = $opcion;
                                $porcentajesComercializacion[$opcion] = $porcentaje;
                            } else {
                                // Si está en formato simple "Vecinos, Industria"
                                $comercializacionSeleccionada = array_map('trim', explode(',', $pecuario->comercializacion_leche));
                                break;
                            }
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
                                   @if(in_array($opcion, (array)$comercializacionSeleccionada)) checked @endif>
                            {{ $opcion }}
                        </label>

                        <!-- Input porcentaje -->
                        <input type="number"
                               name="porcentaje_comercializacion_leche[{{ $opcion }}]"
                               class="form-control border-primary porcentaje-comercializacion-input"
                               style="max-width:100px;"
                               placeholder="%"
                               min="0"
                               max="100"
                               value="{{ $porcentajesComercializacion[$opcion] ?? '' }}">

                        <span class="mx-2">de</span>
                          <input type="text"
                               class="form-control border-secondary"
                               style="max-width:120px;"
                               value="{{ $produccionTotal ? $produccionTotal . ' litros' : 'producción total' }}"
                               readonly>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
</div>

                    {{-- =======================
                        PORCINOS
                    ======================== --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color:#2d5f3f;">
                                <i class="bi bi-piggy-bank-fill me-2"></i> Porcinos
                            </h5>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="fw-semibold">¿Tiene cerdos en confinamiento?</label>
                                    <select name="tiene_cerdos" class="form-select border-primary porcinos-select">
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                <div class="col-md-6 porcinos-campos" style="display: none;">
                                    <label class="fw-semibold">La Orientación de la actividad porícola ha sido para:</label>
                                    <select name="orientacion_porcicola" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="cria">Cría</option>
                                        <option value="levante_y_ceba">Levante y ceba</option>
                                        <option value="ciclo_completo">Ciclo completo</option>
                                        <option value="genetica">Genetica</option>
                                    </select>
                                </div>

                                <div class="col-md-6 porcinos-campos ">
                                                <label class="form-label text-muted small text-uppercase fw-semibold ">
                                                   Vacuna Peste Clásica
                                                </label>
                                                <select name="vacuna_peste_clasica" class="form-select"
                                                        style="border-radius:8px;">
                                                    <option value="">Seleccionar</option>
                                                    <option value="si">Sí</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </div>
                            </div>

                            <div class="row g-4 mt-3 porcinos-campos" style="display: none;">
                                <div class="row g-4">
                                <div class="col-md-2">
                                    <label class="fw-semibold">Machos reproductores</label>
                                    <input type="number" name="cerdos_machos_reproductores" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="fw-semibold">Hembras gestantes, lactantes y vacías</label>
                                    <input type="number" name="cerdos_hembras_gestantes" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="fw-semibold">Hembras de Reemplazo</label>
                                    <input type="number" name="cerdos_hembras_reemplazo" class="form-control">
                                </div>
                                 <div class="col-md-3">
                                    <label class="fw-semibold">Hembras y machos de descarte</label>
                                    <input type="number" name="cerdos_descartes" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">¿Cuantos cerdos destetos tuvo durante el año anterior?</label>
                                    <input type="number" name="cerdos_destetos_anio" class="form-control">
                                </div>
                                 <div class="col-md-6">
                                    <label class="fw-semibold">¿Cuantos cerdos cebados tuvo durante el ultimo anterior?</label>
                                    <input type="number" name="cerdos_ceba_anio" class="form-control">
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    {{-- =======================
                        AVES
                    ======================== --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">

                            <h5 class="card-title mb-4" style="color:#2d5f3f;">
                                <i class="bi bi-egg-fill me-2"></i> Avicultura
                            </h5>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="fw-semibold">¿Cria gallinas o engorda pollos en galpones?</label>
                                    <select name="cria_gallinas_pollos_galpon" class="form-select border-primary avicultura-select">
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                 <div class="col-md-6 avicultura-campos" style="display: none;">
    <label class="fw-semibold d-block">La orientacion de la actividad es:</label>

    @php
        $opcionesOrientacion = [
            "Postura",
            "Engorda",
            "Genetica para produccion de huevo",
            "Genetica para produccion de pollo de engorde"
        ];

        // Obtener valores guardados
        $valoresSeleccionados = old('orientacion_avicola', $pecuario->orientacion_avicola ?? []);

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

                                <div class="col-md-3 avicultura-campos" style="display: none;">
                                    <label class="fw-semibold">Gallinas ponedoras</label>
                                    <input type="number" name="aves_ponedoras" class="form-control">
                                </div>
                                 <div class="col-md-2 avicultura-campos" style="display: none;">
                                    <label class="fw-semibold">Pollos de engorde</label>
                                    <input type="number" name="aves_pollos_engorde" class="form-control">
                                </div>
                                 <div class="col-md-3 avicultura-campos" style="display: none;">
                                    <label class="fw-semibold">Aves para genética huevo</label>
                                    <input type="number" name="aves_genetica_huevo" class="form-control">
                                </div>
                                 <div class="col-md-4 avicultura-campos" style="display: none;">
                                    <label class="fw-semibold">Aves para genética pollo engorde</label>
                                    <input type="number" name="aves_genetica_engorde" class="form-control">
                                </div>


                                   <div class="col-md-5 avicultura-campos" style="display: none;">
                                    <label class="fw-semibold">¿Cuantos huevos produjo el último mes?</label>
                                    <input type="number" name="produccion_huevos_mes" class="form-control">
                                </div>
                                <div class="col-md-3 avicultura-campos" style="display: none;">
                                    <label class="fw-semibold">¿Donde los comercializo?</label>
                                    <input type="text" name="comercializacion_huevos" class="form-control">
                                </div>

                                   <div class="col-md-5 avicultura-campos" style="display: none;">
                                    <label class="fw-semibold">¿Que cantidad de pollo comercializó durante el ultimo mes (kilogramos)?</label>
                                    <input type="number" name="pollo_comercializado_kg_mes" class="form-control">
                                </div>
                                   <div class="col-md-3 avicultura-campos" style="display: none;">
                                    <label class="fw-semibold">¿Donde lo comercializo?</label>
                                    <input type="text" name="donde_comercializo_pollo" class="form-control">
                                </div>


                                   <div class="col-md-6 avicultura-campos" style="display: none;">
                                    <label class="fw-semibold">¿Que metodo empleó para el sacrificio de los pollos?</label>
                                    <select name="metodo_sacrificio" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="aturdimiento_electrico">Aturdimiento eléctrico</option>
                                        <option value="aturdimiento_por_conmocion">Aturdimiento por conmoción (golpe)</option>
                                         <option value="dislocacion">Dislocacion del cuello</option>
                                         <option value="degüello">Degüello</option>
                                         <option value="otros">Otros</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>



                    {{-- =======================
                        PECES
                    ======================== --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">

                            <h5 class="card-title mb-4" style="color:#2d5f3f;">
                                <i class="bi bi-water me-2"></i> Acuicultura
                            </h5>

                            <div class="row g-4">

                                <div class="col-md-6">
                                    <label class="fw-semibold">¿Cría peces?</label>
                                    <select name="cria_peces" class="form-select border-primary acuicultura-select">
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                <div class="col-md-6 acuicultura-campos" style="display: none;">
                                    <label class="fw-semibold d-block">¿Cuál es la orientación de la producción?</label>

                                    @php
                                        $opcionesOrientacion = [
                                            "Alevinaje",
                                            "Carne",
                                            "Ciclo completo",
                                            "Ornamentales"
                                        ];

                                        $valoresSeleccionados = old('peces_orientacion', $pecuario->peces_orientacion ?? []);
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
                                                           name="peces_orientacion[]"
                                                           value="{{ $op }}"
                                                           @if(in_array($op, $valoresSeleccionados)) checked @endif>
                                                    {{ $op }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                            {{-- Contenedor para especies de peces --}}
                            <div id="contenedor-peces" class="acuicultura-campos" style="display: none;">
                                {{-- Primera especie --}}
                                <div class="peces-item border rounded p-3 mb-3 shadow-sm bg-white">
                                    <div class="row g-4">
                                        <div class="col-md-2">
                                            <label class="fw-semibold">Nombre de la Especie</label>
                                            <input type="text" name="peces_especie[]" class="form-control" value="{{ old('peces_especie.0') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="fw-semibold"># de cosechas / año</label>
                                            <input type="number" name="peces_cosechas_anio[]" class="form-control" value="{{ old('peces_cosechas_anio.0') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="fw-semibold"># de animales / cosecha</label>
                                            <input type="number" name="peces_animales_cosecha[]" class="form-control" value="{{ old('peces_animales_cosecha.0') }}">
                                        </div>
                                         <div class="col-md-2">
                                            <label class="fw-semibold">Peso promedio / animal</label>
                                            <input type="number" step="0.01" name="peces_peso_promedio[]" class="form-control" value="{{ old('peces_peso_promedio.0') }}">
                                        </div>
                                         <div class="col-md-2">
                                            <label class="fw-semibold">Producción total año anterior</label>
                                            <input type="number" name="peces_produccion_total_anterior[]" class="form-control" value="{{ old('peces_produccion_total_anterior.0') }}">
                                        </div>
                                          <div class="col-md-2">
                                            <label class="fw-semibold">Lugar de comercialización</label>
                                            <input type="text" name="peces_comercializacion[]" class="form-control" value="{{ old('peces_comercializacion.0') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Contenedor para especies adicionales --}}
                            <div id="contenedor-especies-adicionales" class="acuicultura-campos" style="display: none;">
                            </div>

                            {{-- Botón para añadir especie --}}
                            <div class="text-end mb-4 acuicultura-campos" style="display: none;">
                                <button type="button" class="btn btn-primary" id="btn-add-especie">
                                    <i class="fas fa-plus me-1"></i> Añadir especie
                                </button>
                            </div>

                        </div>
                    </div>

                   



                    {{-- =======================
                        Equinos
                    ======================== --}}
                   <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">

                            <h5 class="card-title mb-4" style="color:#2d5f3f;">
                                <i class="bi bi-tree-fill me-2"></i>Búfalos, equinos, ovinos y caprinos
                            </h5>
                            <div class="row g-4">

                          <div class="col-md-6">
                                    <label class="fw-semibold">¿Tiene Búfalos, ovinos o caprinos?</label>
                                    <select name="tiene_otros_animales" class="form-select border-primary equinos-select">
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                    <div class="col-md-6 equinos-campos" style="display: none;">
    <label class="fw-semibold d-block">La orientacion de la actividad ovino-caprina ha sido para:</label>

    @php
        $opcionesOrientacion = [
            "Carne",
            "Leche",
            "Lana",
            "Pie de cría",
            "Otro"

        ];

        // Obtener valores guardados
        $valoresSeleccionados = old('orientacion_ovino_caprina', $pecuario->orientacion_ovino_caprina ?? []);

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
                    <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Caballos</label>
                                    <input type="number" name="caballos" class="form-control">
                                </div>
                                <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Yeguas</label>
                                    <input type="number" name="yeguas" class="form-control">
                                </div>
                                <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Mulos</label>
                                    <input type="number" name="mulos" class="form-control">
                                </div>
                                 <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Mulas</label>
                                    <input type="number" name="mulas" class="form-control">
                                </div>
                                 <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Burros</label>
                                    <input type="number" name="burros" class="form-control">
                                </div>
                                  <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Burras</label>
                                    <input type="number" name="burras" class="form-control">
                                </div>
 <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Cabros</label>
                                    <input type="number" name="cabros" class="form-control">
                                </div>
                                <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Cabras</label>
                                    <input type="number" name="cabras" class="form-control">
                                </div>
                                <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Ovejos</label>
                                    <input type="number" name="ovejos" class="form-control">
                                </div>
                                 <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Ovejas</label>
                                    <input type="number" name="Ovejas" class="form-control">
                                </div>
                                 <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Búfalos machos</label>
                                    <input type="number" name="bufalos_machos" class="form-control">
                                </div>
                                  <div class="col-md-2 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">Búfalos hembras</label>
                                    <input type="number" name="bufalos_hembras" class="form-control">
                                </div>

<div class="col-md-6 equinos-campos" style="display: none;">
                                    <label class="fw-semibold">¿Durante los ultimos meses vacuno contra encefalitis equina venezolana?</label>
                                    <select name="vacuna_encefalitis_equina" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>


</div>

                        </div>

                    </div>

                    {{-- =======================
                        Otras especies
                    ======================== --}}

                     <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">

                            <h5 class="card-title mb-4" style="color:#2d5f3f;">
                                <i class="bi bi-tree-fill me-2"></i>Inventario otras especies pecuarias
                            </h5>
                            <div class="row g-4">

                          <div class="col-md-12">
                                    <label class="fw-semibold">¿Tiene otras especies de animales (pollos, patos, piscos, avestruces, codornices, cuyes, conejos, colmenas, etc)?</label>
                                    <select name="tiene_otros_animales" class="form-select border-primary otras-especies-select">
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                   
                    <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Cerdos</label>
                                    <input type="number" name="cerdos_traspatio" class="form-control">
                                </div>
                                <div class="col-md-4 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Gallos, pollos y gallinas traspatio</label>
                                    <input type="number" name="gallos_pollos_traspatio" class="form-control">
                                </div>
                                <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Gallos de pelea</label>
                                    <input type="number" name="gallos_pelea" class="form-control">
                                </div>
                                 <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Piscos o pavos</label>
                                    <input type="number" name="pavos" class="form-control">
                                </div>
                                 <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Patos y gansos</label>
                                    <input type="number" name="patos_gansos" class="form-control">
                                </div>
                                  <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Codornices</label>
                                    <input type="number" name="codornices" class="form-control">
                                </div>
 <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Avestruces</label>
                                    <input type="number" name="avestruces" class="form-control">
                                </div>
                                <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Cuyes</label>
                                    <input type="number" name="cuyes" class="form-control">
                                </div>
                                <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Conejos</label>
                                    <input type="number" name="conejos" class="form-control">
                                </div>
                                 <div class="col-md-4 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Colmenas de abejas para produccion de miel</label>
                                    <input type="number" name="colmenas_miel" class="form-control">
                                </div>
                                 <div class="col-md-4 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Colmenas de abejas para produccion de polen</label>
                                    <input type="number" name="colmenas_polen" class="form-control">
                                </div>
                                  <div class="col-md-3 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Colmenas de abejas para subproductos</label
                                    <input type="number" name="colmenas_subproductos" class="form-control">
                                </div>
                                 <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Colmenas de abejas meliponas</label>
                                    <input type="number" name="colmenas_meliponas" class="form-control">
                                </div>
                                 <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Aves ornamentales</label>
                                    <input type="number" name="aves_ornamentales" class="form-control">       {{-- == aves_ornamentales == --}}
                                </div>
                                 <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Caninos hembra</label>
                                    <input type="number" name="caninos_hembras" class="form-control">
                                </div>
                                 <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Caninos macho</label>
                                    <input type="number" name="caninos_machos" class="form-control">
                                </div>
                                <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Felinos hembra</label>
                                    <input type="number" name="felinos_hembras" class="form-control">
                                </div>
                                <div class="col-md-2 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Felinos macho</label>
                                    <input type="number" name="felinos_machos" class="form-control">
                                </div>
                                <div class="col-md-3 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Tortuga/ Morrocoy</label>
                                    <input type="number" name="tortugas" class="form-control">
                                </div>
                                <div class="col-md-12 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">Otros (describe)</label>
                                    <textarea name="otros2" class="form-control" rows="2" placeholder="Describe otros animales..."></textarea>
                              </div>


<div class="col-md-6 otras-especies-campos" style="display: none;">
                                    <label class="fw-semibold">¿Estirilizados?</label>
                                    <select name="esterilizados" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>


</div>

                        </div>

                    </div>





                </form>

                  {{-- =======================
                        BOTONES
                    ======================== --}}
                    <div class="d-flex justify-content-between pt-3">

                        <a href="{{ route('encuestas.produccion', $encuesta->id) }}"
                           class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-arrow-left-circle me-2"></i> Volver
                        </a>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right-circle me-2"></i>
                            Siguiente
                        </button>

                    </div>
            </div>
            
                  
        </div>
    </div>


    <style>
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(45,95,63,0.25);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .btn:hover {
            background-color:#1e4430 !important;
            transform:translateX(5px);
            transition:all 0.3s ease;
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
            setupConditionalFields('.ganado-bovino-select', '.ganado-bovino-campos');
            setupConditionalFields('.porcinos-select', '.porcinos-campos');
            setupConditionalFields('.avicultura-select', '.avicultura-campos');
            setupConditionalFields('.acuicultura-select', '.acuicultura-campos');
            setupConditionalFields('.equinos-select', '.equinos-campos');
            setupConditionalFields('.otras-especies-select', '.otras-especies-campos');

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

            // Funcionalidad para añadir especies de peces (igual que producción)
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
        });
    </script>

</x-app-layout>

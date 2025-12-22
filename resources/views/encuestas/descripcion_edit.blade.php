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
                            <i class="bi bi-house-fill me-2"></i>Editar Descripción
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $descripcion->encuesta->nombre_identidad }} {{ $descripcion->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $descripcion->encuesta_id }}
                        </p>
                    </div>

                    <a href="{{ route('descripciones.show', $descripcion->id) }}"
                       class="btn btn-outline-secondary px-4 py-2"
                       style="border-radius:8px; font-weight:500;">
                       <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <form action="{{ route('descripciones.update', $descripcion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Columna Principal --}}
                    <div class="col-lg-12">

                        {{-- === SECCIÓN: FUENTES DE AGUA ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-droplet-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Fuentes de Agua</h5>
                            </div>
                            <div class="card-body p-4">

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

                                <div class="row g-3">

                                    @php
                                        $fuentes = [
                                            'acueducto_publico' => false,
                                            'acuifero' => true,
                                            'almacenamiento_aguas_lluvias' => true,
                                            'aljibes' => true,
                                            'carrotanque' => false,
                                            'nacimientos' => true,
                                            'pila_publica' => false,
                                            'pozos' => true,
                                            'red_distribucion_comunitaria' => true,
                                            'acueducto_veredal' => false,
                                            'rios' => true,
                                            'quebradas' => true,
                                            'otro' => true,
                                        ];

                                        $labels = [
                                            'acueducto_publico' => 'Acueducto Público',
                                            'acuifero' => 'Acuífero',
                                            'almacenamiento_aguas_lluvias' => 'Almacenamiento de Aguas Lluvias',
                                            'aljibes' => 'Aljibes',
                                            'carrotanque' => 'Carrotanque',
                                            'nacimientos' => 'Nacimientos',
                                            'pila_publica' => 'Pila Pública',
                                            'pozos' => 'Pozos',
                                            'red_distribucion_comunitaria' => 'Red Comunitaria',
                                            'acueducto_veredal' => 'Acueducto Veredal',
                                            'rios' => 'Río',
                                            'quebradas' => 'Quebradas',
                                            'otro' => 'Otro'
                                        ];

                                        $opciones = ['Uso Agropecuario', 'Uso Doméstico'];
                                    @endphp

                                    @foreach($fuentes as $campo => $mostrarUbicado)
                                        <div class="col-md-4 border rounded p-3 shadow-sm bg-white">

                                            <label class="form-label fw-bold text-primary mb-2">
                                                {{ $labels[$campo] }}
                                            </label>

                                            <!-- CHECKBOXES -->
                                            @php
                                                $seleccionadosFuente = explode(',', $descripcion->$campo ?? '');
                                            @endphp

                                            @foreach($opciones as $op)
                                                <div class="form-check">
                                                    <input class="form-check-input fuente-check"
                                                           type="checkbox"
                                                           data-group="{{ $campo }}"
                                                           name="{{ $campo }}[]"
                                                           value="{{ $op }}"
                                                           {{ in_array($op, $seleccionadosFuente) ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $op }}</label>
                                                </div>
                                            @endforeach

                                            <!-- CANTIDAD -->
                                            <div class="mt-2 cantidad-box d-none" id="cantidad_{{ $campo }}">
                                                <label class="form-label fw-semibold">Cantidad</label>
                                                <input type="number"
                                                       min="0"
                                                       class="form-control border-success"
                                                       name="cantidad_{{ $campo }}"
                                                       value="{{ old('cantidad_'.$campo, $descripcion->{'cantidad_'.$campo} ?? '') }}">
                                            </div>

                                            <!-- UBICADO EN EL PREDIO -->
                                            @if($mostrarUbicado)
                                            <div class="mt-2 ubicado-box d-none" id="ubicado_{{ $campo }}">
                                                <label class="form-label fw-semibold">¿Ubicado en el predio?</label>
                                                <select name="ubicado_{{ $campo }}" class="form-select border-success">
                                                    <option value="">Seleccione...</option>
                                                    <option value="sí" {{ old('ubicado_'.$campo, $descripcion->{'ubicado_'.$campo} ?? '') == 'sí' ? 'selected' : '' }}>Sí</option>
                                                    <option value="no" {{ old('ubicado_'.$campo, $descripcion->{'ubicado_'.$campo} ?? '') == 'no' ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                            @endif

                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- === SECCIÓN: ACCESO AL PREDIO ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-signpost-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Acceso al Predio</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                          Herramienta Agrícola
                                        </label>
                                        <select name="herramienta_agricola" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="pala" {{ old('herramienta_agricola', $descripcion->herramienta_agricola) == 'pala' ? 'selected' : '' }}>Pala</option>
                                            <option value="hacha" {{ old('herramienta_agricola', $descripcion->herramienta_agricola) == 'hacha' ? 'selected' : '' }}>Hacha</option>
                                            <option value="rastrillo" {{ old('herramienta_agricola', $descripcion->herramienta_agricola) == 'rastrillo' ? 'selected' : '' }}>Rastrillo</option>
                                            <option value="pico" {{ old('herramienta_agricola', $descripcion->herramienta_agricola) == 'pico' ? 'selected' : '' }}>Pico</option>
                                            <option value="azadon" {{ old('herramienta_agricola', $descripcion->herramienta_agricola) == 'azadon' ? 'selected' : '' }}>Azadón</option>
                                            <option value="machete" {{ old('herramienta_agricola', $descripcion->herramienta_agricola) == 'machete' ? 'selected' : '' }}>Machete</option>
                                            <option value="barra" {{ old('herramienta_agricola', $descripcion->herramienta_agricola) == 'barra' ? 'selected' : '' }}>Barra</option>
                                            <option value="carretilla" {{ old('herramienta_agricola', $descripcion->herramienta_agricola) == 'carretilla' ? 'selected' : '' }}>Carretilla</option>
                                            <option value="paladraga" {{ old('herramienta_agricola', $descripcion->herramienta_agricola) == 'paladraga' ? 'selected' : '' }}>Paladraga</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Distancia a Cabecera
                                        </label>
                                        <select name="distancia_finca_cabecera" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="30 minutos" {{ old('distancia_finca_cabecera', $descripcion->distancia_finca_cabecera) == '30 minutos' ? 'selected' : '' }}>30 minutos</option>
                                            <option value="1 hora" {{ old('distancia_finca_cabecera', $descripcion->distancia_finca_cabecera) == '1 hora' ? 'selected' : '' }}>1 hora</option>
                                            <option value="1 hora y 30 minutos" {{ old('distancia_finca_cabecera', $descripcion->distancia_finca_cabecera) == '1 hora y 30 minutos' ? 'selected' : '' }}>1 hora y 30 minutos</option>
                                            <option value="Más de 1 hora y 30 minutos" {{ old('distancia_finca_cabecera', $descripcion->distancia_finca_cabecera) == 'Más de 1 hora y 30 minutos' ? 'selected' : '' }}>Más de 1 hora y 30 minutos</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Tipo de Transporte
                                        </label>
                                        <select name="transporte_cabecera" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="Público" {{ old('transporte_cabecera', $descripcion->transporte_cabecera) == 'Público' ? 'selected' : '' }}>Público</option>
                                            <option value="Caminando" {{ old('transporte_cabecera', $descripcion->transporte_cabecera) == 'Caminando' ? 'selected' : '' }}>Caminando</option>
                                            <option value="Equinos" {{ old('transporte_cabecera', $descripcion->transporte_cabecera) == 'Equinos' ? 'selected' : '' }}>Equinos</option>
                                            <option value="Bus" {{ old('transporte_cabecera', $descripcion->transporte_cabecera) == 'Bus' ? 'selected' : '' }}>Bus</option>
                                            <option value="Moto" {{ old('transporte_cabecera', $descripcion->transporte_cabecera) == 'Moto' ? 'selected' : '' }}>Moto</option>
                                            <option value="Automóvil" {{ old('transporte_cabecera', $descripcion->transporte_cabecera) == 'Automóvil' ? 'selected' : '' }}>Automóvil</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Vías de Acceso
                                        </label>

                                        @php
                                            $viaSeleccionada = old('vias_acceso', $descripcion->vias_acceso ?? '');
                                            $esOtro = !in_array($viaSeleccionada, [
                                                'Carretera pavimentada',
                                                'Carretera destapada',
                                                'Camino de herradura'
                                            ]);
                                        @endphp

                                        <div class="form-control p-3" style="border-radius:8px;">

                                            <!-- Carretera pavimentada -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="vias_acceso"
                                                       value="Carretera pavimentada"
                                                       {{ $viaSeleccionada == 'Carretera pavimentada' ? 'checked' : '' }}>
                                                <label class="form-check-label">Carretera pavimentada</label>
                                            </div>

                                            <!-- Carretera destapada -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="vias_acceso"
                                                       value="Carretera destapada"
                                                       {{ $viaSeleccionada == 'Carretera destapada' ? 'checked' : '' }}>
                                                <label class="form-check-label">Carretera destapada</label>
                                            </div>

                                            <!-- Camino de herradura -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="vias_acceso"
                                                       value="Camino de herradura"
                                                       {{ $viaSeleccionada == 'Camino de herradura' ? 'checked' : '' }}>
                                                <label class="form-check-label">Camino de herradura</label>
                                            </div>

                                            <!-- Opción Otros -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="vias_acceso"
                                                       id="viasOtrosRadio" value="Otros"
                                                       {{ $esOtro ? 'checked' : '' }}>
                                                <label class="form-check-label">Otros</label>
                                            </div>

                                            <!-- Input para escribir el valor de Otros -->
                                            <input type="text"
                                                   name="vias_acceso_otro"
                                                   id="viasOtrosInput"
                                                   class="form-control mt-2 {{ $esOtro ? '' : 'd-none' }}"
                                                   placeholder="¿Cuáles?"
                                                   value="{{ $esOtro ? $viaSeleccionada : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Condición de la Vía
                                        </label>
                                        <select name="condicion_vias" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="buena" {{ old('condicion_vias', $descripcion->condicion_vias) == 'buena' ? 'selected' : '' }}>Buena</option>
                                            <option value="regular" {{ old('condicion_vias', $descripcion->condicion_vias) == 'regular' ? 'selected' : '' }}>Regular</option>
                                            <option value="mala" {{ old('condicion_vias', $descripcion->condicion_vias) == 'mala' ? 'selected' : '' }}>Mala</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- === USO DEL SUELO ================================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-tree-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Uso del Suelo en el Predio</h5>
                            </div>
                            <div class="card-body p-4">

                               <div class="row g-3">

                                    <!-- Agricultura -->
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Agricultura</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                   name="uso_suelo_agricultura"
                                                   class="form-control"
                                                   value="{{ old('uso_suelo_agricultura', $descripcion->uso_suelo_agricultura) }}"
                                                   style="border-radius:8px;">
                                            <span class="input-group-text">Hectáreas</span>
                                        </div>
                                    </div>

                                    <!-- Ganadería -->
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Ganadería</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                   name="uso_suelo_ganaderia"
                                                   class="form-control"
                                                   value="{{ old('uso_suelo_ganaderia', $descripcion->uso_suelo_ganaderia) }}"
                                                   style="border-radius:8px;">
                                            <span class="input-group-text">Hectáreas</span>
                                        </div>
                                    </div>

                                    <!-- Conservación -->
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Conservación</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                   name="uso_suelo_conservacion"
                                                   class="form-control"
                                                   value="{{ old('uso_suelo_conservacion', $descripcion->uso_suelo_conservacion) }}"
                                                   style="border-radius:8px;">
                                            <span class="input-group-text">Hectáreas</span>
                                        </div>
                                    </div>

                                    <!-- Casa -->
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Casa</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                   name="uso_suelo_casa"
                                                   class="form-control"
                                                   value="{{ old('uso_suelo_casa', $descripcion->uso_suelo_casa) }}"
                                                   style="border-radius:8px;">
                                            <span class="input-group-text">Hectáreas</span>
                                        </div>
                                    </div>

                                    <!-- Rastrojo -->
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">Rastrojo</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                   name="uso_suelo_rastrojo"
                                                   class="form-control"
                                                   value="{{ old('uso_suelo_rastrojo', $descripcion->uso_suelo_rastrojo) }}"
                                                   style="border-radius:8px;">
                                            <span class="input-group-text">Hectáreas</span>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        {{-- === ALMACENAMIENTO Y PRODUCCIÓN ================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-box-seam-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Almacenamientos y Producción</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Donde almacena la Maquinaria y/o herramientas?
                                        </label>
                                        <select name="almacen_maquinaria" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="En la vivienda" {{ old('almacen_maquinaria', $descripcion->almacen_maquinaria) == 'En la vivienda' ? 'selected' : '' }}>En la vivienda</option>
                                            <option value="En bodega continua a la vivienda" {{ old('almacen_maquinaria', $descripcion->almacen_maquinaria) == 'En bodega continua a la vivienda' ? 'selected' : '' }}>En bodega continua a la vivienda</option>
                                            <option value="Al aire libre" {{ old('almacen_maquinaria', $descripcion->almacen_maquinaria) == 'Al aire libre' ? 'selected' : '' }}>Al aire libre</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Donde almacena los Insumos agropecuarios Químicos?
                                        </label>
                                        <select name="almacen_insumos_quimicos" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="En la vivienda" {{ old('almacen_insumos_quimicos', $descripcion->almacen_insumos_quimicos) == 'En la vivienda' ? 'selected' : '' }}>En la vivienda</option>
                                            <option value="En bodega continua a la vivienda" {{ old('almacen_insumos_quimicos', $descripcion->almacen_insumos_quimicos) == 'En bodega continua a la vivienda' ? 'selected' : '' }}>En bodega continua a la vivienda</option>
                                            <option value="Al aire libre" {{ old('almacen_insumos_quimicos', $descripcion->almacen_insumos_quimicos) == 'Al aire libre' ? 'selected' : '' }}>Al aire libre</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Donde almacena Abonos Orgánicos?
                                        </label>
                                        <select name="almacen_abonos" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="En la vivienda" {{ old('almacen_abonos', $descripcion->almacen_abonos) == 'En la vivienda' ? 'selected' : '' }}>En la vivienda</option>
                                            <option value="En bodega continua a la vivienda" {{ old('almacen_abonos', $descripcion->almacen_abonos) == 'En bodega continua a la vivienda' ? 'selected' : '' }}>En bodega continua a la vivienda</option>
                                            <option value="Al aire libre" {{ old('almacen_abonos', $descripcion->almacen_abonos) == 'Al aire libre' ? 'selected' : '' }}>Al aire libre</option>
                                        </select>
                                    </div>

                                   <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           La mayor parte del terreno que conforma esta Unidad Productiva Agropecuaria es:
                                        </label>
                                        <select name="condicion_terreno" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="Plano" {{ old('condicion_terreno', $descripcion->condicion_terreno) == 'Plano' ? 'selected' : '' }}>Plano</option>
                                            <option value="Quebrado (con pendiente)" {{ old('condicion_terreno', $descripcion->condicion_terreno) == 'Quebrado (con pendiente)' ? 'selected' : '' }}>Quebrado (con pendiente)</option>
                                        </select>
                                    </div>

                                   <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Sistema de Riego
                                        </label>
                                        <select name="sistema_riego" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="Aspersion" {{ old('sistema_riego', $descripcion->sistema_riego) == 'Aspersion' ? 'selected' : '' }}>Aspersión</option>
                                            <option value="Goteo" {{ old('sistema_riego', $descripcion->sistema_riego) == 'Goteo' ? 'selected' : '' }}>Goteo</option>
                                            <option value="Gravedad" {{ old('sistema_riego', $descripcion->sistema_riego) == 'Gravedad' ? 'selected' : '' }}>Gravedad</option>
                                            <option value="Bombeo" {{ old('sistema_riego', $descripcion->sistema_riego) == 'Bombeo' ? 'selected' : '' }}>Bombeo</option>
                                            <option value="Manual o por manguera" {{ old('sistema_riego', $descripcion->sistema_riego) == 'Manual o por manguera' ? 'selected' : '' }}>Manual o por manguera</option>
                                            <option value="No utiliza" {{ old('sistema_riego', $descripcion->sistema_riego) == 'No utiliza' ? 'selected' : '' }}>No utiliza</option>
                                        </select>
                                    </div>

                                   <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Destino de Producción
                                        </label>

                                        @php
                                            $destinos = [
                                                'Autoconsumo',
                                                'Intercambio',
                                                'Venta de producción en el lote',
                                                'Venta a cooperativa',
                                                'Venta a central de abastos',
                                                'Venta directa en plaza de mercado',
                                                'Venta a comercializador',
                                                'Venta a tienda, supermercado o grandes superficies',
                                                'Para la industria',
                                                'Mercadillos campesinos',
                                                'Otros destinos'
                                            ];

                                            $oldDestino = old('destino_produccion');
                                            if (is_array($oldDestino)) {
                                                $seleccionados = $oldDestino;
                                            } else {
                                                $seleccionados = explode(',', $descripcion->destino_produccion ?? '');
                                            }
                                        @endphp

                                        <div class="row">
                                            @foreach($destinos as $destino)
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="destino_produccion[]"
                                                            value="{{ $destino }}"
                                                            id="destino_{{ $loop->index }}"
                                                            {{ in_array($destino, $seleccionados) ? 'checked' : '' }}
                                                            @if($destino == 'Otros destinos') onchange="toggleOtrosDestinoInput()" @endif
                                                        >
                                                        <label class="form-check-label" for="destino_{{ $loop->index }}">
                                                            {{ $destino }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Campo extra visible solo si seleccionan Otros Destinos -->
                                        <div id="otros_destino_input" class="mt-3" style="display: none;">
                                            <label class="form-label">Especifique otros destinos</label>
                                            <input
                                                type="text"
                                                name="otros_destinos_detalle"
                                                class="form-control"
                                                value="{{ old('otros_destinos_detalle', $descripcion->otros_destinos_detalle ?? '') }}"
                                                style="border-radius:8px;"
                                            >
                                        </div>
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
                                        <i class="bi bi-check-circle me-2"></i>Actualizar Descripción
                                    </button>

                                    <a href="{{ route('descripciones.show', $descripcion->id) }}"
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
        function toggleOtrosDestinoInput() {
            const checkbox = document.querySelector('input[value="Otros destinos"]');
            const inputDiv = document.getElementById('otros_destino_input');
            inputDiv.style.display = checkbox.checked ? 'block' : 'none';
        }

        // Mostrar al cargar si viene seleccionado
        document.addEventListener('DOMContentLoaded', toggleOtrosDestinoInput);
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const otrosRadio = document.getElementById('viasOtrosRadio');
            const otrosInput = document.getElementById('viasOtrosInput');
            const radios = document.querySelectorAll('input[name="vias_acceso"]');

            radios.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (otrosRadio.checked) {
                        otrosInput.classList.remove('d-none');
                        otrosInput.focus();
                    } else {
                        otrosInput.classList.add('d-none');
                        otrosInput.value = '';
                    }
                });
            });
        });
     </script>

     <script>
        document.addEventListener("DOMContentLoaded", () => {

            function actualizarGrupo(campo) {
                let checks = document.querySelectorAll(`input[data-group="${campo}"]`);
                let seleccionado = Array.from(checks).some(ch => ch.checked);

                // Cantidad
                let cantidad = document.getElementById(`cantidad_${campo}`);
                if (cantidad) cantidad.classList.toggle("d-none", !seleccionado);

                // Ubicado (si aplica)
                let ubicado = document.getElementById(`ubicado_${campo}`);
                if (ubicado) ubicado.classList.toggle("d-none", !seleccionado);
            }

            document.querySelectorAll(".fuente-check").forEach(ch => {
                ch.addEventListener("change", () => {
                    actualizarGrupo(ch.dataset.group);
                });

                // Ejecutar al cargar para reactivar valores viejos
                actualizarGrupo(ch.dataset.group);
            });

        });
     </script>

</x-app-layout>

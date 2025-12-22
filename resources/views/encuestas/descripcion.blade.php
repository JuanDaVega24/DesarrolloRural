<x-app-layout>
  <x-steps 
        :progress="50"
        :current="3"
        :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Final']"
    />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="POST" action="{{ route('descripcion.guardarDescripcion', $encuesta->id) }}" class="bg-white shadow-lg rounded p-4 p-md-5">
                    @csrf

                    <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">

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

                    <h2 class="text-center mb-4 pb-3 border-bottom border-success" style="color: #2d5f3f; font-weight: 700;">
                        <i class="bi bi-house-door-fill me-2"></i>Descripción de Vivienda
                    </h2>

                    {{-- === SECCIÓN: FUENTES DE AGUA ===================================== --}}
                <div class="card mb-4 border-0" style="background-color: #f8f9fa;">
    <div class="card-body">
        <h5 class="card-title mb-4" style="color: #2d5f3f;">
            <i class="bi bi-droplet-fill me-2"></i>Fuentes de Agua
        </h5>

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
                        $oldFuente = old($campo);
                        if (is_array($oldFuente)) {
                            $seleccionadosFuente = $oldFuente;
                        } else {
                            $seleccionadosFuente = explode(',', $descripcion->$campo ?? '');
                        }
                    @endphp

                    @foreach($opciones as $op)
                        <div class="form-check">
                            <input class="form-check-input fuente-check"
                                   type="checkbox"
                                   data-group="{{ $campo }}"
                                   name="{{ $campo }}[]"
                                   value="{{ $op }}"
                                   @if( in_array($op, $seleccionadosFuente) ) checked @endif >
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
                            <option value="sí">Sí</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    @endif

                </div>
            @endforeach

        </div>
    </div>
</div>



                    {{-- === SECCIÓN: ACCESO AL PREDIO ===================================== --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                <i class="bi bi-signpost-fill me-2"></i>Acceso al Predio
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
    <label class="form-label fw-semibold">¿Cuenta con Herramienta Agrícola?</label>
    <select name="herramienta_agricola" class="form-select border-primary" style="border-radius: 8px;">
        <option value="">Seleccione...</option>
        <option value="pala"        {{ old('herramienta_agricola', $descripcion->herramienta_agricola ?? '') == 'pala' ? 'selected' : '' }}>Pala</option>
        <option value="hacha"       {{ old('herramienta_agricola', $descripcion->herramienta_agricola ?? '') == 'hacha' ? 'selected' : '' }}>Hacha</option>
        <option value="rastrillo"   {{ old('herramienta_agricola', $descripcion->herramienta_agricola ?? '') == 'rastrillo' ? 'selected' : '' }}>Rastrillo</option>
        <option value="pico"        {{ old('herramienta_agricola', $descripcion->herramienta_agricola ?? '') == 'pico' ? 'selected' : '' }}>Pico</option>
        <option value="azadon"      {{ old('herramienta_agricola', $descripcion->herramienta_agricola ?? '') == 'azadon' ? 'selected' : '' }}>Azadón</option>
        <option value="machete"     {{ old('herramienta_agricola', $descripcion->herramienta_agricola ?? '') == 'machete' ? 'selected' : '' }}>Machete</option>
        <option value="barra"       {{ old('herramienta_agricola', $descripcion->herramienta_agricola ?? '') == 'barra' ? 'selected' : '' }}>Barra</option>
        <option value="carretilla"  {{ old('herramienta_agricola', $descripcion->herramienta_agricola ?? '') == 'carretilla' ? 'selected' : '' }}>Carretilla</option>
        <option value="paladraga"   {{ old('herramienta_agricola', $descripcion->herramienta_agricola ?? '') == 'paladraga' ? 'selected' : '' }}>Paladraga</option>
    </select>
</div>


                                <div class="col-md-4">
    <label class="form-label fw-semibold">Distancia a Cabecera</label>
    <select name="distancia_finca_cabecera" class="form-control border-primary">
        <option value="">Seleccione...</option>
        <option value="30 minutos" 
            {{ old('distancia_finca_cabecera', $descripcion->distancia_finca_cabecera ?? '') == '30 minutos' ? 'selected' : '' }}>
            30 minutos
        </option>
        <option value="1 hora" 
            {{ old('distancia_finca_cabecera', $descripcion->distancia_finca_cabecera ?? '') == '1 hora' ? 'selected' : '' }}>
            1 hora
        </option>
        <option value="1 hora y 30 minutos" 
            {{ old('distancia_finca_cabecera', $descripcion->distancia_finca_cabecera ?? '') == '1 hora y 30 minutos' ? 'selected' : '' }}>
            1 hora y 30 minutos
        </option>
        <option value="Más de 1 hora y 30 minutos" 
            {{ old('distancia_finca_cabecera', $descripcion->distancia_finca_cabecera ?? '') == 'Más de 1 hora y 30 minutos' ? 'selected' : '' }}>
            Más de 1 hora y 30 minutos
        </option>
    </select>
</div>

<div class="col-md-4">
    <label class="form-label fw-semibold">Tipo de Transporte</label>
    <select name="transporte_cabecera" class="form-control border-primary">
        <option value="">Seleccione...</option>

        <option value="Público"
            {{ old('transporte_cabecera', $descripcion->transporte_cabecera ?? '') == 'Público' ? 'selected' : '' }}>
            Público
        </option>

        <option value="Caminando"
            {{ old('transporte_cabecera', $descripcion->transporte_cabecera ?? '') == 'Caminando' ? 'selected' : '' }}>
            Caminando
        </option>

        <option value="Equinos"
            {{ old('transporte_cabecera', $descripcion->transporte_cabecera ?? '') == 'Equinos' ? 'selected' : '' }}>
            Equinos
        </option>

        <option value="Bus"
            {{ old('transporte_cabecera', $descripcion->transporte_cabecera ?? '') == 'Bus' ? 'selected' : '' }}>
            Bus
        </option>

        <option value="Moto"
            {{ old('transporte_cabecera', $descripcion->transporte_cabecera ?? '') == 'Moto' ? 'selected' : '' }}>
            Moto
        </option>

        <option value="Automóvil"
            {{ old('transporte_cabecera', $descripcion->transporte_cabecera ?? '') == 'Automóvil' ? 'selected' : '' }}>
            Automóvil
        </option>

    </select>
</div>


                               <div class="col-md-6">
    <label class="form-label fw-semibold">Vías de Acceso</label>

    @php
        $viaSeleccionada = old('vias_acceso', $descripcion->vias_acceso ?? '');
        $esOtro = !in_array($viaSeleccionada, [
            'Carretera pavimentada',
            'Carretera destapada',
            'Camino de herradura'
        ]);
    @endphp

    <div class="form-control border-primary p-3">

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
    <label class="form-label fw-semibold">Condición de la Vía</label>
    <select name="condicion_vias" class="form-select border-primary">
        <option value="">Seleccione...</option>
        <option value="buena" {{ old('condicion_vias', $descripcion->condicion_vias ?? '') == 'buena' ? 'selected' : '' }}>
            Buena
        </option>
        <option value="regular" {{ old('condicion_vias', $descripcion->condicion_vias ?? '') == 'regular' ? 'selected' : '' }}>
            Regular
        </option>
        <option value="mala" {{ old('condicion_vias', $descripcion->condicion_vias ?? '') == 'mala' ? 'selected' : '' }}>
            Mala
        </option>
    </select>
</div>

                            </div>
                        </div>
                    </div>

                    {{-- === USO DEL SUELO ================================================== --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa">
                        <div class="card-body">
                            <h5 class="fw-semibold card-title mb-4" style="color: #2d5f3f;">
                               Uso del Suelo en el Predio
                            </h5>
                           <div class="row g-3">

    <!-- Agricultura -->
    <div class="col-md-4">
        <label class="form-label fw-semibold">Agricultura</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0"
                   name="uso_suelo_agricultura"
                   class="form-control border-success"
                   value="{{ old('uso_suelo_agricultura', $descripcion->uso_suelo_agricultura ?? '') }}">
            <span class="input-group-text">Hectáreas</span>
        </div>
    </div>

    <!-- Ganadería -->
    <div class="col-md-4">
        <label class="form-label fw-semibold">Ganadería</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0"
                   name="uso_suelo_ganaderia"
                   class="form-control border-success"
                   value="{{ old('uso_suelo_ganaderia', $descripcion->uso_suelo_ganaderia ?? '') }}">
            <span class="input-group-text">Hectáreas</span>
        </div>
    </div>

    <!-- Conservación -->
    <div class="col-md-4">
        <label class="form-label fw-semibold">Conservación</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0"
                   name="uso_suelo_conservacion"
                   class="form-control border-success"
                   value="{{ old('uso_suelo_conservacion', $descripcion->uso_suelo_conservacion ?? '') }}">
            <span class="input-group-text">Hectáreas</span>
        </div>
    </div>

    <!-- Casa -->
    <div class="col-md-4">
        <label class="form-label fw-semibold">Casa</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0"
                   name="uso_suelo_casa"
                   class="form-control border-success"
                   value="{{ old('uso_suelo_casa', $descripcion->uso_suelo_casa ?? '') }}">
            <span class="input-group-text">Hectáreas</span>
        </div>
    </div>

    <!-- Rastrojo -->
    <div class="col-md-4">
        <label class="form-label fw-semibold">Rastrojo</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0"
                   name="uso_suelo_rastrojo"
                   class="form-control border-success"
                   value="{{ old('uso_suelo_rastrojo', $descripcion->uso_suelo_rastrojo ?? '') }}">
            <span class="input-group-text">Hectáreas</span>
        </div>
    </div>

</div>

                        </div>
                    </div>

                    {{-- === ALMACENAMIENTO Y PRODUCCIÓN ================================== --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                <i class="bi bi-box-seam me-2"></i>Almacenamientos y Producción
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
    <label class="form-label fw-semibold">¿Donde almacena la Maquinaria y/o herramientas?</label>
    <select name="almacen_maquinaria" class="form-select border-primary">
        <option value="">Seleccione una opción</option>

        <option value="En la vivienda"
            {{ old('almacen_maquinaria', $descripcion->almacen_maquinaria ?? '') == 'En la vivienda' ? 'selected' : '' }}>
            En la vivienda
        </option>

        <option value="En bodega continua a la vivienda"
            {{ old('almacen_maquinaria', $descripcion->almacen_maquinaria ?? '') == 'En bodega continua a la vivienda' ? 'selected' : '' }}>
            En bodega continua a la vivienda
        </option>

        <option value="Al aire libre"
            {{ old('almacen_maquinaria', $descripcion->almacen_maquinaria ?? '') == 'Al aire libre' ? 'selected' : '' }}>
            Al aire libre
        </option>
    </select>
</div>

<div class="col-md-4">
    <label class="form-label fw-semibold">¿Donde almacena los Insumos agropecuarios Químicos?</label>
    <select name="almacen_insumos_quimicos" class="form-select border-primary">
        <option value="">Seleccione una opción</option>

        <option value="En la vivienda"
            {{ old('almacen_insumos_quimicos', $descripcion->almacen_insumos_quimicos ?? '') == 'En la vivienda' ? 'selected' : '' }}>
            En la vivienda
        </option>

        <option value="En bodega continua a la vivienda"
            {{ old('almacen_insumos_quimicos', $descripcion->almacen_insumos_quimicos ?? '') == 'En bodega continua a la vivienda' ? 'selected' : '' }}>
            En bodega continua a la vivienda
        </option>

        <option value="Al aire libre"
            {{ old('almacen_insumos_quimicos', $descripcion->almacen_insumos_quimicos ?? '') == 'Al aire libre' ? 'selected' : '' }}>
            Al aire libre
        </option>
    </select>
</div>

<div class="col-md-4">
    <label class="form-label fw-semibold">¿Donde almacena Abonos Orgánicos?</label>
    <select name="almacen_abonos" class="form-select border-primary">
        <option value="">Seleccione una opción</option>

        <option value="En la vivienda"
            {{ old('almacen_abonos', $descripcion->almacen_abonos ?? '') == 'En la vivienda' ? 'selected' : '' }}>
            En la vivienda
        </option>

        <option value="En bodega continua a la vivienda"
            {{ old('almacen_abonos', $descripcion->almacen_abonos ?? '') == 'En bodega continua a la vivienda' ? 'selected' : '' }}>
            En bodega continua a la vivienda
        </option>

        <option value="Al aire libre"
            {{ old('almacen_abonos', $descripcion->almacen_abonos ?? '') == 'Al aire libre' ? 'selected' : '' }}>
            Al aire libre
        </option>
    </select>
</div>


                               <div class="col-md-4">
    <label class="form-label fw-semibold">La mayor parte del terreno que conforma esta Unidad Productiva Agropecuaria es:</label>
    <select name="condicion_terreno" class="form-select border-primary">
        <option value="">Seleccione una opción</option>

        <option value="Plano"
            {{ old('condicion_terreno', $descripcion->condicion_terreno ?? '') == 'Plano' ? 'selected' : '' }}>
            Plano
        </option>

        <option value="Quebrado (con pendiente)"
            {{ old('condicion_terreno', $descripcion->condicion_terreno ?? '') == 'Quebrado (con pendiente)' ? 'selected' : '' }}>
            Quebrado (con pendiente)
        </option>
    </select>
</div>


                               <div class="col-md-4">
    <label class="form-label fw-semibold">Sistema de Riego</label>
    <select name="sistema_riego" class="form-select border-primary">
        <option value="">Seleccione...</option>
        <option value="Aspersion" {{ old('sistema_riego', $descripcion->sistema_riego ?? '') == 'Aspersion' ? 'selected' : '' }}>Aspersión</option>
        <option value="Goteo" {{ old('sistema_riego', $descripcion->sistema_riego ?? '') == 'Goteo' ? 'selected' : '' }}>Goteo</option>
        <option value="Gravedad" {{ old('sistema_riego', $descripcion->sistema_riego ?? '') == 'Gravedad' ? 'selected' : '' }}>Gravedad</option>
        <option value="Bombeo" {{ old('sistema_riego', $descripcion->sistema_riego ?? '') == 'Bombeo' ? 'selected' : '' }}>Bombeo</option>
        <option value="Manual o por manguera" {{ old('sistema_riego', $descripcion->sistema_riego ?? '') == 'Manual o por manguera' ? 'selected' : '' }}>Manual o por manguera</option>
        <option value="No utiliza" {{ old('sistema_riego', $descripcion->sistema_riego ?? '') == 'No utiliza' ? 'selected' : '' }}>No utiliza</option>
    </select>
</div>


                               <div class="col-md-12">
    <label class="form-label fw-semibold">Destino de Producción</label>

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
            class="form-control border-primary"
            value="{{ old('otros_destinos_detalle', $descripcion->otros_destinos_detalle ?? '') }}"
        >
    </div>
</div>

<script>
    function toggleOtrosDestinoInput() {
        const checkbox = document.querySelector('input[value="Otros destinos"]');
        const inputDiv = document.getElementById('otros_destino_input');
        inputDiv.style.display = checkbox.checked ? 'block' : 'none';
    }

    // Mostrar al cargar si viene seleccionado
    document.addEventListener('DOMContentLoaded', toggleOtrosDestinoInput);
</script>

                            </div>
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('encuestas.vivienda') }}" class="btn btn-secondary btn-lg px-5 py-2">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver
                        </a>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right-circle me-2"></i>Siguiente
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(45, 95, 63, 0.25);
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn:hover {
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        
          
    </style>
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

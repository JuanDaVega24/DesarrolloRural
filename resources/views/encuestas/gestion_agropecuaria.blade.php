<x-app-layout>
    <x-steps :progress="86" :current="7" :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Gestión Agropecuaria','Final']" />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="POST" action="{{ route('gestion_agropecuaria.guardarGestionAgropecuaria') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
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

                    {{-- SECCIÓN PARTICIPACIÓN --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-check me-2"></i>Participación en Gestión Agropecuaria
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">¿Participa o ha sigo beneficiado en los últimos 3 años de algún proyecto de desarrollo?</label>
                                    <select name="participa" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1" {{ old('participa', $gestion->participa ?? '') == '1' ? 'selected' : '' }}>Sí</option>
                                        <option value="0" {{ old('participa', $gestion->participa ?? '') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-3 participacion-campos" style="display: none;">
                                    <label class="form-label fw-semibold">Año</label>
                                    <input type="number" name="año" class="form-control-sm border-primary"
                                           style="max-width: 250px;"
                                           value="{{ old('año', $gestion->año ?? '') }}">
                                </div>
                            </div>
                            <div class="row g-4 mt-2 participacion-campos" style="display: none;">
                             <div class="col-md-4">
    <label class="form-label fw-semibold">Entidad</label>
    <select 
        name="entidad" 
        id="entidad"
        class="form-select border-primary"
        onchange="toggleOtroEntidad()"
    >
        <option value="" disabled {{ old('entidad') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Alcaldia" {{ old('entidad') == 'Alcaldia' ? 'selected' : '' }}>
            Alcaldía
        </option>

        <option value="Gobernacion" {{ old('entidad') == 'Gobernacion' ? 'selected' : '' }}>
            Gobernación
        </option>

        <option value="MADR/ADR" {{ old('entidad') == 'MADR/ADR' ? 'selected' : '' }}>
            MADR / ADR
        </option>

        <option value="Asociacion" {{ old('entidad') == 'Asociacion' ? 'selected' : '' }}>
            Asociación
        </option>

        <option value="Cooperacion Internacional" {{ old('entidad') == 'Cooperacion Internacional' ? 'selected' : '' }}>
            Cooperación internacional
        </option>

        <option value="Otros" {{ old('entidad') == 'Otros' ? 'selected' : '' }}>
            Otros
        </option>
    </select>
</div>

<!-- Campo adicional -->
<div class="col-md-4 mt-2" id="otroEntidadDiv" style="display: none;">
    <label class="form-label fw-semibold">Especifique la entidad</label>
    <input 
        type="text" 
        name="entidad_otro" 
        class="form-control border-primary"
        value="{{ old('entidad_otro') }}"
        placeholder="Ingrese el nombre de la entidad"
    >
</div>


                                <div class="col-md-6">
    <label class="form-label fw-semibold">¿En qué consistió?</label>
    <select name="consistio" id="consistio" class="form-select border-primary">
        <option value="" disabled {{ old('consistio', $gestion->consistio ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Entrega de insumos"
            {{ old('consistio', $gestion->consistio ?? '') == 'Entrega de insumos' ? 'selected' : '' }}>
            Entrega de insumos
        </option>

        <option value="Entrega de recursos economicos"
            {{ old('consistio', $gestion->consistio ?? '') == 'Entrega de recursos economicos' ? 'selected' : '' }}>
            Entrega de recursos económicos
        </option>

        <option value="Transferencia de conocimiento"
            {{ old('consistio', $gestion->consistio ?? '') == 'Transferencia de conocimiento' ? 'selected' : '' }}>
            Transferencia de conocimiento
        </option>

        <option value="Entrega de herramientas, equipos y/o instalaciones"
            {{ old('consistio', $gestion->consistio ?? '') == 'Entrega de herramientas, equipos y/o instalaciones' ? 'selected' : '' }}>
            Entrega de herramientas, equipos y/o instalaciones
        </option>

        <option value="Entrega de plantas"
            {{ old('consistio', $gestion->consistio ?? '') == 'Entrega de plantas' ? 'selected' : '' }}>
            Entrega de plantas
        </option>

      
    </select>
</div>

                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN CRÉDITOS --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa;">
                        <div class="card-body participacion-campos">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-cash me-2"></i>Créditos o Financiación
                            </h5>
                        </div>
                        <div class="card-body participacion-campos" style="display: none ;">
                            <div class="row g-4 mb-3">
                                <div class="col-md-7">
                                    <label class="form-label fw-semibold">¿Ha solicitado crédito o financiación para el desarrollo de las actividades agropecuarias?</label>
                                    <select name="credito" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1" {{ old('credito', $gestion->credito ?? '') == '1' ? 'selected' : '' }}>Sí</option>
                                        <option value="0" {{ old('credito', $gestion->credito ?? '') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">¿El crédito o la financiacion que solicito fue aprobado?</label>
                                    <select name="aprobado" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1" {{ old('aprobado', $gestion->aprobado ?? '') == '1' ? 'selected' : '' }}>Sí</option>
                                        <option value="0" {{ old('aprobado', $gestion->aprobado ?? '') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-4">
                                @php
    $fuentesSeleccionadas = old(
        'fuentes',
        isset($gestion->fuentes)
            ? (is_array($gestion->fuentes) ? $gestion->fuentes : explode(',', $gestion->fuentes))
            : []
    );
@endphp

<div class="col-md-8">
    <label class="form-label fw-semibold d-block mb-3">Fuentes</label>

    <div class="row g-2">
        @php
            $fuentes = [
                'Banco Agrario',
                'Otros bancos',
                'Cooperativa',
                'Particulares o prestamistas',
                'Organizaciones gubernamentales (ONG\'s)',
                'Programas del gobierno',
                'Cooperación internacional',
                'Almacenes de insumos agrícolas y agroindustria'
            ];
            $chunks = array_chunk($fuentes, 2);
        @endphp

        @foreach($chunks as $chunk)
        <div class="col-md-6">
            @foreach($chunk as $fuente)
            <div class="form-check mb-2">
                <input
                    class="form-check-input border-primary"
                    type="checkbox"
                    name="fuentes[]"
                    value="{{ $fuente }}"
                    id="fuente_{{ Str::slug($fuente) }}"
                    {{ in_array($fuente, $fuentesSeleccionadas) ? 'checked' : '' }}
                >
                <label class="form-check-label small" for="fuente_{{ Str::slug($fuente) }}">
                    {{ $fuente }}
                </label>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

                               <div class="col-md-4">
    <label class="form-label fw-semibold">Destino de recursos</label>
    <select name="destino_recursos" class="form-select border-primary">
        <option value="" disabled
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Pago de mano de obra"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Pago de mano de obra' ? 'selected' : '' }}>
            Pago de mano de obra
        </option>

        <option value="Compra de insumos"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Compra de insumos' ? 'selected' : '' }}>
            Compra de insumos
        </option>

        <option value="Compra de maquinaria de uso agricola"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Compra de maquinaria de uso agricola' ? 'selected' : '' }}>
            Compra de maquinaria de uso agrícola
        </option>

        <option value="Compra de maquinaria de uso pecuario"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Compra de maquinaria de uso pecuario' ? 'selected' : '' }}>
            Compra de maquinaria de uso pecuario
        </option>

        <option value="Compra de animales"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Compra de animales' ? 'selected' : '' }}>
            Compra de animales
        </option>

        <option value="Instalacion de cultivo"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Instalacion de cultivo' ? 'selected' : '' }}>
            Instalación de cultivo
        </option>

        <option value="Compra de tierras"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Compra de tierras' ? 'selected' : '' }}>
            Compra de tierras
        </option>

        <option value="Pago de alquiler y otros servicios agropecuarios"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Pago de alquiler y otros servicios agropecuarios' ? 'selected' : '' }}>
            Pago de alquiler y otros servicios agropecuarios
        </option>

        <option value="Obras y mantenimiento de infraestructura"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Obras y mantenimiento de infraestructura' ? 'selected' : '' }}>
            Obras y mantenimiento de infraestructura
        </option>

        <option value="Postcosecha"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Postcosecha' ? 'selected' : '' }}>
            Postcosecha
        </option>

        <option value="Otro destino"
            {{ old('destino_recursos', $gestion->destino_recursos ?? '') == 'Otro destino' ? 'selected' : '' }}>
            Otro destino
        </option>
    </select>
</div>

                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN DATOS DE CRÉDITOS --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa;">
                        <div class="card-body participacion-campos" style="display: none;">
                            <h5 class="card-title mb-0">
                            </h5>
                        </div>
                        <div class="card-body participacion-campos" style="display: none;">
                            <div class="row g-4 mb-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">¿Actualmente tiene créditos o financiacion?</label>
                                    <select name="tiene_creditos" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1" {{ old('tiene_creditos', $gestion->tiene_creditos ?? '') == '1' ? 'selected' : '' }}>Sí</option>
                                        <option value="0" {{ old('tiene_creditos', $gestion->tiene_creditos ?? '') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Campos de créditos --}}
                            <div id="contenedor-creditos">
                                <div class="credito-item">
                                    <div class="row g-4 mb-3 credito-campos" style="display: none;">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Entidad</label>
                                            <input type="text" name="entidad[]" class="form-control border-primary"
                                                   value="{{ old('entidad.0', isset($gestion->entidad[0]) ? $gestion->entidad[0] : '') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Valor del crédito</label>
                                            <input type="text" name="valor_credito[]" class="form-control border-primary"
                                                   value="{{ old('valor_credito.0', isset($gestion->valor_credito[0]) ? $gestion->valor_credito[0] : '') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Plazo</label>
                                            <input type="text" name="plazo[]" class="form-control border-primary"
                                                   value="{{ old('plazo.0', isset($gestion->plazo[0]) ? $gestion->plazo[0] : '') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Fecha de aprobación</label>
                                            <input type="text" name="fecha_aprobacion[]" class="form-control border-primary"
                                                   value="{{ old('fecha_aprobacion.0', isset($gestion->fecha_aprobacion[0]) ? $gestion->fecha_aprobacion[0] : '') }}">
                                        </div>
                                       <div class="col-md-2">
    <label class="form-label fw-semibold">¿Al día?</label>
    <select name="al_dia[]" class="form-select border-primary">
        <option value="" disabled
            {{ old('al_dia.0', isset($gestion->al_dia[0]) ? $gestion->al_dia[0] : '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('al_dia.0', isset($gestion->al_dia[0]) ? $gestion->al_dia[0] : '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('al_dia.0', isset($gestion->al_dia[0]) ? $gestion->al_dia[0] : '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Seguro</label>
                                            <input type="text" name="seguro[]" class="form-control border-primary"
                                                   value="{{ old('seguro.0', isset($gestion->seguro[0]) ? $gestion->seguro[0] : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mb-2 credito-campos" style="display: none;">
                                <button type="button" class="btn btn-primary" id="btn-add-credito">
                                    <i class="fas fa-plus me-1"></i>Añadir crédito
                                </button>
                            </div>
                        </div>
                       
                            
                    </div>

                    {{-- SECCIÓN TRABAJO --}}
                    <div class="card mb-4 border-0 shadow-sm" style="background-color: #f8f9fa;">
                        <div class="card-body participacion-campos" style="display: none;">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people me-2"></i>Mano de obra
                            </h5>
                        </div>
                        <div class="card-body participacion-campos" style="display: none;">
                            <div class="row g-4 mb-3">
                                @php
    $personas = old(
        'personas',
        isset($gestion->personas)
            ? $gestion->personas
            : []
    );
@endphp

<div class="col-md-12">
    <label class="form-label fw-semibold d-block mb-3">¿En total cuantas personas trabajaron de manera permanente para realizar las actividades agropecuarias en los ultimos 30 dias(incluido el productor y los miembros del hogar)?</label>

    <div class="border rounded p-3 mb-3" style="background-color: #f8f9fa;">
        <div class="row g-3 align-items-center">
            <!-- HOMBRES -->
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="form-check me-3">
                        <input
                            class="form-check-input border-primary"
                            type="checkbox"
                            id="hombres"
                            name="personas[hombres][activo]"
                            value="1"
                            onchange="toggleCantidad('hombres')"
                            {{ isset($personas['hombres']) ? 'checked' : '' }}
                        >
                        <label class="form-check-label fw-semibold" for="hombres">
                            Hombres
                        </label>
                    </div>
                    <div class="flex-grow-1" id="cantidad_hombres" style="display: none;">
                        <input
                            type="number"
                            min="0"
                            name="personas[hombres][cantidad]"
                            class="form-control form-control-sm border-primary"
                            placeholder="Cantidad"
                            value="{{ $personas['hombres']['cantidad'] ?? '' }}"
                        >
                    </div>
                </div>
            </div>

            <!-- MUJERES -->
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="form-check me-3">
                        <input
                            class="form-check-input border-primary"
                            type="checkbox"
                            id="mujeres"
                            name="personas[mujeres][activo]"
                            value="1"
                            onchange="toggleCantidad('mujeres')"
                            {{ isset($personas['mujeres']) ? 'checked' : '' }}
                        >
                        <label class="form-check-label fw-semibold" for="mujeres">
                            Mujeres
                        </label>
                    </div>
                    <div class="flex-grow-1" id="cantidad_mujeres" style="display: none;">
                        <input
                            type="number"
                            min="0"
                            name="personas[mujeres][cantidad]"
                            class="form-control form-control-sm border-primary"
                            placeholder="Cantidad"
                            value="{{ $personas['mujeres']['cantidad'] ?? '' }}"
                        >
                    </div>
                </div>
            </div>

            <!-- TOTAL PERSONAS -->
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Total de personas</label>
                <input
                    type="number"
                    id="total_personas"
                    class="form-control form-control-sm border-primary bg-light"
                    readonly
                    value="0"
                >
            </div>
        </div>
    </div>
</div>

@php
    $cuantos = old(
        'cuantos',
        isset($gestion->cuantos)
            ? $gestion->cuantos
            : []
    );
@endphp

<div class="col-md-12">
    <label class="form-label fw-semibold d-block mb-3">
        ¿Cuántos de los trabajadores permanentes pertenecen al hogar del productor?
    </label>

    <div class="border rounded p-3 mb-3" style="background-color: #f8f9fa;">
        <div class="row g-3 align-items-center">
            <!-- HOMBRES -->
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="form-check me-3">
                        <input
                            class="form-check-input border-primary"
                            type="checkbox"
                            id="trab_hombres"
                            name="cuantos[hombres][activo]"
                            value="1"
                            onchange="toggleCantidad('trab_hombres')"
                            {{ isset($cuantos['hombres']) ? 'checked' : '' }}
                        >
                        <label class="form-check-label fw-semibold" for="trab_hombres">
                            Hombres
                        </label>
                    </div>
                    <div class="flex-grow-1" id="cantidad_trab_hombres" style="display: none;">
                        <input
                            type="number"
                            min="0"
                            name="cuantos[hombres][cantidad]"
                            class="form-control form-control-sm border-primary"
                            placeholder="Cantidad"
                            value="{{ $cuantos['hombres']['cantidad'] ?? '' }}"
                        >
                    </div>
                </div>
            </div>

            <!-- MUJERES -->
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="form-check me-3">
                        <input
                            class="form-check-input border-primary"
                            type="checkbox"
                            id="trab_mujeres"
                            name="cuantos[mujeres][activo]"
                            value="1"
                            onchange="toggleCantidad('trab_mujeres')"
                            {{ isset($cuantos['mujeres']) ? 'checked' : '' }}
                        >
                        <label class="form-check-label fw-semibold" for="trab_mujeres">
                            Mujeres
                        </label>
                    </div>
                    <div class="flex-grow-1" id="cantidad_trab_mujeres" style="display: none;">
                        <input
                            type="number"
                            min="0"
                            name="cuantos[mujeres][cantidad]"
                            class="form-control form-control-sm border-primary"
                            placeholder="Cantidad"
                            value="{{ $cuantos['mujeres']['cantidad'] ?? '' }}"
                        >
                    </div>
                </div>
            </div>

            <!-- TOTAL AUTOMÁTICO -->
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Total de trajadores</label>
                <input
                    type="number"
                    id="total_trabajadores"
                    class="form-control form-control-sm border-primary bg-light"
                    readonly
                    value="0"
                >
            </div>
        </div>
    </div>
</div>

                            </div>
                            <div class="row g-4 mb-3">
                                <div class="col-md-6 participacion-campos" style="display: none;">
                               
                                    <label class="form-label fw-semibold">¿Cuantos jornales adicionales contrató directamente para realizar las actividades agropecuarias en los ultimos 30 dias?</label>
                                    <input type="number" name="jornales" class="form-control border-primary"
                                           value="{{ old('jornales', $gestion->jornales ?? '') }}">
                                </div>
                                <div class="col-md-6 participacion-campos" style="display: none;">
                                    <label class="form-label fw-semibold">¿Se empleó trabajo colectivo para realizar las actividades agropecuarias en los ultimos 30 dias (minga, convite, mano prestada)?</label>
                                    <select name="trabajo_colectivo" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1" {{ old('trabajo_colectivo', $gestion->trabajo_colectivo ?? '') == '1' ? 'selected' : '' }}>Sí</option>
                                        <option value="0" {{ old('trabajo_colectivo', $gestion->trabajo_colectivo ?? '') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-4 participacion-campos" style="display: none">
                                    <label class="form-label fw-semibold">'¿Cuanto valio cada jornal? (MIL)</label>
                                    <input type="number" step="0.01" name="valor_jornal" class="form-control border-primary"
                                           value="{{ old('valor_jornal', $gestion->valor_jornal ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('encuestas.maquinaria') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-arrow-left-circle me-2"></i> Volver
                        </a>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right-circle me-2"></i> Siguiente
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
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn:hover {
            background-color: #1e4430 !important;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
    </style>

   <script>
document.addEventListener('DOMContentLoaded', function () {

    let creditoIndex = 1;

    /* ===============================
       FUNCIÓN HELPER GENERAL
    =============================== */
    function toggleCampos(selector, mostrar) {
        const elementos = document.querySelectorAll(selector);
        elementos.forEach(elemento => {
            if (mostrar) {
                elemento.style.display = 'flex';
                elemento.style.flexWrap = 'wrap';
                elemento.querySelectorAll('select, input, textarea').forEach(field => {
                    field.disabled = false;
                });
            } else {
                elemento.style.display = 'none';
                elemento.querySelectorAll('select, input, textarea').forEach(field => {
                    field.disabled = true;
                });
            }
        });
    }

    /* ===============================
       PARTICIPACIÓN
    =============================== */
    function handleParticipacionChange() {
        const select = document.querySelector('select[name="participa"]');
        if (!select) return;

        const mostrar = select.value === '1';
        toggleCampos('.participacion-campos', mostrar);
    }

    /* ===============================
       CRÉDITOS
    =============================== */
    function handleCreditosChange() {
        const select = document.querySelector('select[name="tiene_creditos"]');
        if (!select) return;

        const mostrar = select.value === '1';
        toggleCampos('.credito-campos', mostrar);
    }

    /* ===============================
       ENTIDAD - OTROS
    =============================== */
    function toggleOtroEntidad() {
        const select = document.getElementById('entidad');
        const otroDiv = document.getElementById('otroEntidadDiv');

        if (!select || !otroDiv) return;

        otroDiv.style.display = select.value === 'Otros' ? 'block' : 'none';
    }

    /* ===============================
       PERSONAS (HOMBRES / MUJERES)
    =============================== */
    window.toggleCantidad = function (tipo) {
        const checkbox = document.getElementById(tipo);
        const divCantidad = document.getElementById('cantidad_' + tipo);

        if (!checkbox || !divCantidad) return;

        divCantidad.style.display = checkbox.checked ? 'block' : 'none';
    };

    /* ===============================
       CALCULAR TOTAL PERSONAS
    =============================== */
    function calcularTotalPersonas() {
        const inputHombres = document.querySelector('input[name="personas[hombres][cantidad]"]');
        const inputMujeres = document.querySelector('input[name="personas[mujeres][cantidad]"]');
        const totalInput = document.getElementById('total_personas');

        if (!inputHombres || !inputMujeres || !totalInput) return;

        const hombres = parseInt(inputHombres.value) || 0;
        const mujeres = parseInt(inputMujeres.value) || 0;
        const total = hombres + mujeres;

        totalInput.value = total;
    }

    /* ===============================
       CALCULAR TOTAL TRABAJADORES
    =============================== */
    function calcularTotalTrabajadores() {
        const inputHombres = document.querySelector('input[name="cuantos[hombres][cantidad]"]');
        const inputMujeres = document.querySelector('input[name="cuantos[mujeres][cantidad]"]');
        const totalInput = document.getElementById('total_trabajadores');

        if (!inputHombres || !inputMujeres || !totalInput) return;

        const hombres = parseInt(inputHombres.value) || 0;
        const mujeres = parseInt(inputMujeres.value) || 0;
        const total = hombres + mujeres;

        totalInput.value = total;
    }

    /* ===============================
       EVENT LISTENERS
    =============================== */
    const participaSelect = document.querySelector('select[name="participa"]');
    if (participaSelect) {
        participaSelect.addEventListener('change', handleParticipacionChange);
    }

    const creditosSelect = document.querySelector('select[name="tiene_creditos"]');
    if (creditosSelect) {
        creditosSelect.addEventListener('change', handleCreditosChange);
    }

    const entidadSelect = document.getElementById('entidad');
    if (entidadSelect) {
        entidadSelect.addEventListener('change', toggleOtroEntidad);
    }

    const btnAddCredito = document.getElementById('btn-add-credito');
    if (btnAddCredito) {
        btnAddCredito.addEventListener('click', function () {
            let contenedor = document.getElementById('contenedor-creditos');
            let original = document.querySelector('.credito-item');
            if (!contenedor || !original) return;

            let nuevo = original.cloneNode(true);

            nuevo.querySelectorAll('input').forEach(i => i.value = "");

            nuevo.querySelectorAll('input').forEach(element => {
                if (element.name) {
                    element.name = element.name.replace(/\[\d*\]$/, '[' + creditoIndex + ']');
                }
            });

            contenedor.appendChild(nuevo);
            creditoIndex++;
        });
    }

    /* ===============================
       EVENT LISTENERS PARA TOTAL PERSONAS
    =============================== */
    const inputHombresPersonas = document.querySelector('input[name="personas[hombres][cantidad]"]');
    const inputMujeresPersonas = document.querySelector('input[name="personas[mujeres][cantidad]"]');

    if (inputHombresPersonas) {
        inputHombresPersonas.addEventListener('input', calcularTotalPersonas);
    }
    if (inputMujeresPersonas) {
        inputMujeresPersonas.addEventListener('input', calcularTotalPersonas);
    }

    /* ===============================
       EVENT LISTENERS PARA TOTAL TRABAJADORES
    =============================== */
    const inputHombresTrab = document.querySelector('input[name="cuantos[hombres][cantidad]"]');
    const inputMujeresTrab = document.querySelector('input[name="cuantos[mujeres][cantidad]"]');

    if (inputHombresTrab) {
        inputHombresTrab.addEventListener('input', calcularTotalTrabajadores);
    }
    if (inputMujeresTrab) {
        inputMujeresTrab.addEventListener('input', calcularTotalTrabajadores);
    }

    /* ===============================
       INICIALIZACIÓN (OLD / EDICIÓN)
    =============================== */
    handleParticipacionChange();
    handleCreditosChange();
    toggleOtroEntidad();
    toggleCantidad('hombres');
    toggleCantidad('mujeres');
    toggleCantidad('trab_hombres');
    toggleCantidad('trab_mujeres');
    calcularTotalPersonas();
    calcularTotalTrabajadores();

});
</script>


</x-app-layout>

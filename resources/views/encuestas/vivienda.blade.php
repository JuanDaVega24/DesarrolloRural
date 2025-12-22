<x-app-layout>

    <x-steps 
        :progress="32"
        :current="2"
        :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Final']"
    />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="POST" action="{{ route('vivienda.guardarVivienda') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
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

                    {{-- Sección 1: Tipo y condición de la vivienda --}}
              <div class="card mb-4 border-0" style="background-color: #f8f9fa;">
    <div class="card-body">
        <h5 class="card-title mb-4" style="color: #2d5f3f;">
            <i class="bi bi-house-door-fill me-2"></i>Datos de la Vivienda
        </h5>

        {{-- FILA 1 --}}
        <div class="row g-4 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tipo de vivienda*</label>
                <select name="tipo_vivienda" class="form-select border-primary">
                    <option value="">Seleccionar</option>
                    <option value="casa">Casa</option>
                    <option value="apartamento">Apartamento</option>
                    <option value="tipo_cuarto">Tipo de Cuarto</option>
                    <option value="indigena">Vivienda tradicional indígena</option>
                    <option value="etnica">Vivienda étnica</option>
                    <option value="otro">Otro</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Condición de ocupación*</label>
                <select name="condicion_ocupacion" class="form-select border-primary">
                    <option value="">Seleccionar</option>
                    <option value="ocupada">Ocupada</option>
                    <option value="vivienda_temporal">Temporal</option>
                    <option value="desocupada">Desocupada</option>
                    <option value="ocupada_por_viviente">Ocupada por viviente</option>
                    <option value="ocupada_por_familia">Ocupada por familia</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Material de pisos*</label>
                <select name="material_piso" class="form-select border-primary">
                    <option value="">Seleccionar</option>
                    <option value="marmol">Mármol</option>
                    <option value="baldosa">Baldosa</option>
                    <option value="alfombra">Alfombra</option>
                    <option value="cemento">Cemento</option>
                    <option value="madera_burda">Madera burda</option>
                    <option value="tierra">Tierra</option>
                </select>
            </div>
        </div>

        {{-- FILA 2 --}}
        <div class="row g-4 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Material de pared exterior</label>
                <input type="text" name="material_pared_exterior" class="form-control border-success" value="{{ old('material_pared_exterior') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Destino aguas residuales</label>
                <select name="destino_aguas_residuales" class="form-select border-primary">
                    <option value="">Seleccionar</option>
                    <option value="alcantarillado">Alcantarillado</option>
                    <option value="pozo">Pozo séptico</option>
                    <option value="pozo_no_funcional">Pozo no funcional</option>
                    <option value="ninguno">Ninguno</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Combustible cocina</label>
                <select name="combustible_cocina" class="form-select border-primary">
                    <option value="">Seleccionar</option>
                    <option value="madera">Madera</option>
                    <option value="propano">Propano</option>
                    <option value="electrico">Eléctrico</option>
                    <option value="carbon">Carbón</option>
                    <option value="biogas">Biogás</option>
                    <option value="solar">Solar</option>
                    <option value="ninguno">Ninguno</option>
                </select>
            </div>
        </div>

        {{-- FILA 3 --}}
        <div class="row g-4 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Medios de comunicación</label>
                <select name="medios_comunicacion" class="form-select border-primary">
                    <option value="">Seleccionar</option>
                    <option value="fibra_optica">Fibra óptica</option>
                    <option value="internet_satelital">Internet satelital</option>
                    <option value="tv">TV</option>
                    <option value="radio">Radio</option>
                    <option value="internet">Internet</option>
                    <option value="telefono_fijo">Teléfono fijo</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Medios electrónicos</label>
                <select name="medios_electronicos" class="form-select border-primary">
                    <option value="">Seleccionar</option>
                    <option value="televisor">Televisor</option>
                    <option value="computador">Computador</option>
                    <option value="radio">Radio</option>
                    <option value="tablet">Tablet</option>
                    <option value="celular">Celular</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tipo de servicio sanitario</label>
            <select name="tipo_servicio_sanitario" class="form-select border-primary" value="{{ old('tipo_servicio_sanitario') }}">
                    <option value="">Seleccionar</option>
                    <option value="inodoro_alcantarillado">Inodoro conectado al alcantarillado</option>
                    <option value="inodoro_pozo">Inodoro conectado al pozo séptico</option>
                    <option value="inodo_sin_conexion">indoro sin conexión</option>
                    <option value="letrina">Letrina</option>
                    <option value="inodoro_directo">Inodoro directo</option>
                    <option value="sin_sanitario">Esta vivienda no tiene servicio sanitario</option>
                </select>
           
            </div>
        </div>

        {{-- FILA 4 – Radios horizontal --}}
        <div class="row g-4 mb-3">
            
<div class="col-md-6">
                                    <label class="form-label fw-semibold">¿Cuenta con acueducto veredal?</label>
                                    <select name="acueducto_veredal" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
            
            <div class="col-md-6">
                                    <label class="form-label fw-semibold">¿Tiene filtro de agua?</label>
                                    <select name="cuenta_con_filtro" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
        </div>
    </div>
      

</div>
    <div class="d-flex justify-content-between pt-3">

    <a href="{{ route('encuestas.datos_personales') }}" class="btn btn-secondary px-4">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <button type="submit" class="btn btn-primary btn-lg">
        <i class="bi bi-arrow-right-circle me-2"></i>Siguiente
    </button>

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
            background-color: #1e4430 !important;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
    </style>

</x-app-layout>

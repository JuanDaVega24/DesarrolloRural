<x-app-layout>

    <x-steps 
        :progress="16"
        :current="1"
        :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Final']"
    />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="POST" action="{{ route('encuestas.guardarDatosPersonales') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
                    @csrf

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
                        <i class="bi bi-person-fill me-2"></i>Datos Personales
                    </h2>

                    {{-- Sección 1: Información general --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                <i class="bi bi-info-circle me-2"></i>Información General
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Fecha de la encuesta*</label>
                                    <input type="date" name="fecha_encuesta" class="form-control border-success" value="{{ old('fecha_encuesta') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Lugar de aplicación*</label>
                                    <input type="text" name="lugar_aplicacion" class="form-control border-success" value="{{ old('lugar_aplicacion') }}">
                                </div>
                               <div class="col-md-4">
    <label class="form-label fw-semibold">Corregimiento*</label>
    <select 
        name="corregimiento" 
        id="corregimiento" 
        class="form-select border-primary"
    >
        <option value="">Seleccionar</option>

       @foreach ($corregimientos as $c)
    <option value="{{ $c->id }}" {{ old('corregimiento') == $c->id ? 'selected' : '' }}>
        {{ $c->nombre }}
    </option>
@endforeach

    </select>
</div>

<div class="col-md-4">
    <label class="form-label fw-semibold">Vereda*</label>
    <select 
        name="vereda" 
        id="vereda" 
        class="form-select border-success"
    >
        <option value="">Seleccione un corregimiento</option>

       @if(old('corregimiento'))
    @foreach ($veredas as $v)
        <option value="{{ $v->id }}" {{ old('vereda') == $v->id ? 'selected' : '' }}>
            {{ $v->nombre }}
        </option>
    @endforeach
@endif

    </select>
</div>

{{-- Script para recargar veredas al seleccionar corregimiento --}}
<script>
document.getElementById('corregimiento').addEventListener('change', function () {
    const id = this.value;

    fetch(`/encuestas/veredas/${id}`)
        .then(response => response.json())
        .then(data => {
            const veredaSelect = document.getElementById('vereda');
            veredaSelect.innerHTML = '<option value="">Seleccionar vereda</option>';

            data.forEach(v => {
                veredaSelect.innerHTML += `<option value="${v.id}">${v.nombre}</option>`;
            });
        });
});
</script>


                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Finca*</label>
                                    <input type="text" name="finca" class="form-control border-success" value="{{ old('finca') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sección 2: Área del predio --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                <i class="bi bi-map me-2"></i>Área del Predio*
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Área del predio*</label>
                                    <input type="number" step="0.01" name="area_predio" class="form-control border-primary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Unidad de medida*</label>
                                    <select name="unidad_medida" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="HA">HA</option>
                                        <option value="MTS">MTS</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Coordenadas*</label>
                                    <input type="text" name="coordenadas" class="form-control border-primary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Área total disponible*</label>
                                    <input type="number" step="0.01" name="area_total_disponible" class="form-control border-primary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Unidad de medida 2*</label>
                                    <select name="unidad_medida2" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="HA">HA</option>
                                        <option value="MTS">MTS</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Altitud*</label>
                                    <input type="number" step="0.01" name="altitud" class="form-control border-primary">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sección 3: Información del encuestado --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                <i class="bi bi-person-badge me-2"></i>Información del Encuestado
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nombre*</label>
                                    <input type="text" name="nombre_identidad" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Primer apellido*</label>
                                    <input type="text" name="primer_apellido" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Segundo apellido*</label>
                                    <input type="text" name="segundo_apellido" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Número de documento*</label>
                                    <input type="text" name="numero_documento" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tipo de documento*</label>
                                    <select name="tipo_documento" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="TI">Tarjeta de Identidad</option>
                                        <option value="CC">Cédula de Ciudadanía</option>
                                        <option value="CE">Cédula de Extranjería</option>
                                        <option value="CD">Carné Diplomatico</option>
                                        <option value="SC">Salvoconducto</option>
                                        <option value="PE">Permiso de Permanencia</option>
                                        <option value="DE">Documento de Extranjería</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Fecha de expedición*</label>
                                    <input type="date" name="fecha_expedicion" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Municipio de expedición*</label>
                                    <input type="text" name="municipio_expedicion" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Departamento de expedición*</label>
                                    <input type="text" name="departamento_expedicion" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Fecha de nacimiento*</label>
                                    <input type="date" name="fecha_nacimiento" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Municipio de nacimiento*</label>
                                    <input type="text" name="municipio_nacimiento" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Departamento de nacimiento*</label>
                                    <input type="text" name="departamento_nacimiento" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Género*</label>
                                    <select name="genero" class="form-select border-success">
                                        <option value="">Seleccionar</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                        <option value="No Binario">No Binario</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sección 4: Contacto --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                <i class="bi bi-telephone me-2"></i>Información de Contacto
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Correo electrónico</label>
                                    <input type="email" name="correo" class="form-control border-primary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Celular 1*</label>
                                    <input type="text" name="celular_1" class="form-control border-primary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Celular 2</label>
                                    <input type="text" name="celular_2" class="form-control border-primary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tipo de tenencia</label>
                                    <select name="tipo_tenencia" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="Propietario">Propietario</option>
                                        <option value="Viviente">Viviente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sección 5: Educación y actividades --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                <i class="bi bi-book me-2"></i>Educación y Actividades
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nivel educativo*</label>
                                    <select name="nivel_educativo" class="form-select border-success">
                                        <option value="" selected>Selecciona nivel educativo</option>
                                        <option value="primaria_incompleta">Primaria Incompleta</option>
                                        <option value="primaria_completa">Primaria Completa</option>
                                        <option value="secundaria_incompleta">Secundaria Incompleta</option>
                                        <option value="secundaria_completa">Secundaria Completa</option>
                                        <option value="tecnica">Técnica</option>
                                        <option value="tecnologica">Tecnológica</option>
                                        <option value="profesional">Profesional</option>
                                        <option value="especializacion">Especialización</option>
                                        <option value="maestria">Maestría</option>
                                        <option value="postDoctorado">PostDoctorado</option>
                                        <option value="sabe_leer">Sabe Leer</option>
                                        <option value="sabe_escribir">Sabe Escribir</option>
                                        <option value="ninguna">Ninguna</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Qué estudió</label>
                                    <input type="text" name="que_estudio" class="form-control border-success">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sección 6: Programas sociales --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                <i class="bi bi-cash-stack me-2"></i>Ingresos del hogar por % proviene de:
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Actividades agrícolas</label>
                                    <div class="input-group">
                                        <input type="number" name="actividades_agricolas" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Actividades pecuarias</label>
                                    <div class="input-group">
                                        <input type="number" name="actividades_pecuarias" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Renta ciudadana</label>
                                    <div class="input-group">
                                        <input type="number" name="renta_ciudadana" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Renta joven</label>
                                    <div class="input-group">
                                        <input type="number" name="renta_joven" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Colombia mayor</label>
                                    <div class="input-group">
                                        <input type="number" name="colombia_mayor" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Devolución IVA</label>
                                    <div class="input-group">
                                        <input type="number" name="devolucion_iva" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Pensión</label>
                                    <div class="input-group">
                                        <input type="number" name="pension" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Arriendos</label>
                                    <div class="input-group">
                                        <input type="number" name="arriendos" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Empleo formal</label>
                                    <div class="input-group">
                                        <input type="number" name="empleo_formal" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Actividad comercial</label>
                                    <div class="input-group">
                                        <input type="number" name="actividad_comercial" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Independiente</label>
                                    <div class="input-group">
                                        <input type="number" name="independiente" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Otros</label>
                                    <div class="input-group">
                                        <input type="number" name="otros" class="form-control border-success input-porcentaje" min="0" max="100">
                                        <span class="input-group-text bg-success text-white">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sección 7: Otros datos --}}
                    <div class="card mb-4 border-0" style="background-color: #f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-4" style="color: #2d5f3f;">
                                <i class="bi bi-house-door me-2"></i>Información Adicional
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tiempo viviendo en la finca</label>
                                    <select name="tiempo_viviendo_finca" class="form-select border-primary">
                                        <option value="" selected>Selecciona el tiempo</option>
                                        <option value="menos_2">Menos de 2 años</option>
                                        <option value="entre_2_10">Entre 2 y 10 años</option>
                                        <option value="entre_10_30">Entre 10 y 30 años</option>
                                        <option value="mas_31">Más de 31 años</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Medio de transporte propio</label>
                                    <select name="medio_transporte_propio" class="form-select border-primary">
                                        <option value="" selected>Selecciona medio de transporte</option>
                                        <option value="bicicleta">Bicicleta</option>
                                        <option value="animal">Animal</option>
                                        <option value="motocicleta">Motocicleta</option>
                                        <option value="automovil">Automóvil</option>
                                        <option value="tractocamion">Tractocamión</option>
                                        <option value="ninguno">Ninguno</option>
                                    </select>
                                </div>
                                    {{-- Tenencia de tierra con radio buttons horizontales --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold d-block mb-3">Tenencia de tierra*</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="tenencia_propia" value="propia">
                                            <label class="form-check-label" for="tenencia_propia">Propia</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="tenencia_arriendo" value="arriendo">
                                            <label class="form-check-label" for="tenencia_arriendo">Arriendo</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="aparceria" value="aparceria">
                                            <label class="form-check-label" for="aparceria">Aparcería</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="usufructo" value="usufructo">
                                            <label class="form-check-label" for="usufructo">Usufructo</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="comodato" value="Comodato">
                                            <label class="form-check-label" for="comodato">Comodato</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="ocupacion_de_hecho" value="ocupacion_de_hecho">
                                            <label class="form-check-label" for="ocupacion_de_hecho">Ocupación de hecho</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="propiedad_colectiva" value="propiedad_colectiva">
                                            <label class="form-check-label" for="propiedad_colectiva">Propiedad Colectiva</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="adjudicatario_o_comunero" value="adjudicatario_o_comunero">
                                            <label class="form-check-label" for="adjudicatario_o_comunero">Adjudicatario o Comunero</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="no_sabe" value="no_sabe">
                                            <label class="form-check-label" for="no_sabe">No sabe</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tenencia_tierra" id="tenencia_otro" value="otro">
                                            <label class="form-check-label" for="tenencia_otro">Otro</label>
                                        </div>
                                    </div>
                                </div>
                             <div class="mb-3">
                                       <label class="form-label fw-semibold d-block mb-3">Pertenece a una población especial*</label>

    <select class="form-select border-primary"
            name="pertenencia_poblacion_especial">
        <option value="">Seleccione...</option>

        <option value="Afrocolombiano">Afrocolombiano</option>
        <option value="Campesino">Campesino</option>
        <option value="Indígena">Indígena</option>
        <option value="LGBTIQ+">LGBTIQ+</option>
        <option value="Persona_Mayor">Persona Mayor</option>
        <option value="Cabeza_de_familia">Cabeza de familia</option>
        <option value="Mujer_rural">Mujer rural</option>
        <option value="Desmovilizado">Desmovilizado</option>
        <option value="Reinsertado">Reinsertado</option>
        <option value="Joven_rural">Joven rural</option>
        <option value="Persona_con_discapacidad">Persona con discapacidad</option>
        <option value="Victima_del_conflicto">Víctima del conflicto (RUV)</option>
        <option value="Cuidador/a">Cuidador/a</option>
        <option value="Otra">Otra</option>
    </select>
</div>


                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Le gustaría estudiar</label>
                                    <select name="le_gustaria_estudiar" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Qué le gustaría estudiar</label>
                                    <input type="text" name="que_le_gustaria_estudiar" class="form-control border-primary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Trabaja actualmente</label>
                                    <select name="trabaja_actualmente" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tipo de empleo</label>
                                    <select name="tipo_empleo" class="form-select border-primary">
                                        <option value="">Seleccionar</option>
                                        <option value="formal">Formal</option>
                                        <option value="informal">Informal</option>
                                    </select>
                                </div>
                                
                            

                                {{-- Tipo de contrato con radio buttons horizontales --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold d-block mb-3">Tipo de contrato</label>
                                    <div class="d-flex flex-wrap gap-3 align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_contrato" id="sin_contrato" value="sin_contrato" onclick="mostrarDuracionContrato(false)">
                                            <label class="form-check-label" for="sin_contrato">Sin contrato</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_contrato" id="tiempo_definido" value="tiempo_definido" onclick="mostrarDuracionContrato(false)">
                                            <label class="form-check-label" for="tiempo_definido">Tiempo definido</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_contrato" id="CPS" value="CPS" onclick="mostrarDuracionContrato(true)">
                                            <label class="form-check-label" for="CPS">CPS menor o igual a:</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_contrato" id="otro_contrato" value="otro" onclick="mostrarDuracionContrato(false)">
                                            <label class="form-check-label" for="otro_contrato">Otro tipo de contrato</label>
                                        </div>
                                    </div>

                                    {{-- Select de duración que aparece al seleccionar CPS --}}
                                    <div id="duracion_contrato_box" class="mt-3" style="display:none;">
                                        <label class="form-label">Duración aproximada</label>
                                        <select name="duracion_contrato" id="duracion_contrato" class="form-select border-primary" style="max-width: 300px;">
                                            <option value="">Seleccione…</option>
                                            <option value="3_meses">3 meses</option>
                                            <option value="6_meses">6 meses</option>
                                            <option value="9_meses">9 meses</option>
                                            <option value="11_meses">11 meses</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end pt-3">
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
            background-color: #1e4430 !important;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        .form-check {
            margin-bottom: 0;
        }
    </style>

    <script>
    function mostrarDuracionContrato(mostrar) {
        const box = document.getElementById('duracion_contrato_box');
        const select = document.getElementById('duracion_contrato');

        if (mostrar) {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
            if (select) select.value = "";
        }
    }

    // Función para convertir texto a mayúsculas automáticamente
    function convertirAMayusculas(event) {
        const input = event.target;
        const start = input.selectionStart;
        const end = input.selectionEnd;

        input.value = input.value.toUpperCase();

        // Restaurar la posición del cursor
        input.setSelectionRange(start, end);
    }

    // Aplicar conversión automática a mayúsculas en campos de texto específicos
    document.addEventListener('DOMContentLoaded', function() {
        // Campos que deben estar en mayúsculas
        const camposMayusculas = [
            'nombre_identidad',
            'primer_apellido',
            'segundo_apellido',
            'lugar_aplicacion',
            'finca',
            'municipio_expedicion',
            'departamento_expedicion',
            'municipio_nacimiento',
            'departamento_nacimiento',
            'que_estudio',
            'que_le_gustaria_estudiar'
        ];

        camposMayusculas.forEach(function(campoId) {
            const input = document.querySelector('input[name="' + campoId + '"]');
            if (input) {
                input.addEventListener('input', convertirAMayusculas);
                // También convertir al pegar texto
                input.addEventListener('paste', function(event) {
                    setTimeout(function() {
                        convertirAMayusculas({target: input});
                    }, 0);
                });
            }
        });
    });
    </script>

</x-app-layout>

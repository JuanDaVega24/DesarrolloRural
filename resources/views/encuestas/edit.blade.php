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
                            <i class="bi bi-pencil-square me-2"></i>Editar Encuesta
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle me-1"></i>Modifica los datos de la encuesta
                        </p>
                    </div>

                    <a href="{{ route('encuestas.show', $encuesta->id) }}"
                       class="btn btn-outline-secondary px-4 py-2"
                       style="border-radius:8px; font-weight:500;">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <form action="{{ route('encuestas.update', $encuesta->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    
                    {{-- Columna Izquierda --}}
                    <div class="col-lg-8">
                        
                        {{-- CARD: Información General --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center" 
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-calendar-check fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Información General</h5>
                            </div>
                            <div class="card-body p-4">
                                
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-calendar3 me-1"></i>Fecha Encuesta
                                        </label>
                                        <input type="date" 
                                               class="form-control" 
                                               name="fecha_encuesta" 
                                               value="{{ $encuesta->fecha_encuesta }}"
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-geo-alt me-1"></i>Lugar Aplicación
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="lugar_aplicacion" 
                                               value="{{ $encuesta->lugar_aplicacion }}"
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-pin-map me-1"></i>Corregimiento
                                        </label>
                                        <select class="form-select"
                                                name="corregimiento_id"
                                                id="corregimiento"
                                                required
                                                style="border-radius:8px;">
                                            <option value="">Seleccione...</option>
                                            @foreach($corregimientos as $c)
                                                <option value="{{ $c->id }}" {{ $encuesta->corregimiento_id == $c->id ? 'selected' : '' }}>
                                                    {{ $c->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-signpost me-1"></i>Vereda
                                        </label>
                                        <select class="form-select"
                                                name="vereda_id"
                                                id="vereda"
                                                required
                                                style="border-radius:8px;">
                                            <option value="">Seleccione...</option>
                                            @foreach($veredas as $v)
                                                <option value="{{ $v->id }}" {{ $encuesta->vereda_id == $v->id ? 'selected' : '' }}>
                                                    {{ $v->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-house-door me-1"></i>Finca
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="finca" 
                                               value="{{ $encuesta->finca }}"
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-mountain me-1"></i>Altitud
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="altitud" 
                                               value="{{ $encuesta->altitud }}"
                                               style="border-radius:8px;">
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- CARD: Información del Encuestado --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center" 
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-person-circle fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Información del Encuestado</h5>
                            </div>
                            <div class="card-body p-4">
                                
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-person me-1"></i>Nombre
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="nombre_identidad" 
                                               value="{{ $encuesta->nombre_identidad }}"
                                               required
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-person me-1"></i>Primer Apellido
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="primer_apellido" 
                                               value="{{ $encuesta->primer_apellido }}"
                                               required
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-person me-1"></i>Segundo Apellido
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="segundo_apellido" 
                                               value="{{ $encuesta->segundo_apellido }}"
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-card-text me-1"></i>Tipo Documento
                                        </label>
                                        <select class="form-select" 
                                                name="tipo_documento"
                                                required
                                                style="border-radius:8px;">
                                            <option value="">Seleccione...</option>
                                            <option value="CC" {{ $encuesta->tipo_documento == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                                            <option value="CE" {{ $encuesta->tipo_documento == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                            <option value="TI" {{ $encuesta->tipo_documento == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                                            <option value="PAS" {{ $encuesta->tipo_documento == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-hash me-1"></i>Número Documento
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="numero_documento" 
                                               value="{{ $encuesta->numero_documento }}"
                                               required
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-gender-ambiguous me-1"></i>Género
                                        </label>
                                        <select class="form-select" 
                                                name="genero"
                                                required
                                                style="border-radius:8px;">
                                            <option value="">Seleccione...</option>
                                            <option value="Masculino" {{ $encuesta->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                            <option value="Femenino" {{ $encuesta->genero == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                            <option value="Otro" {{ $encuesta->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-mortarboard me-1"></i>Nivel Educativo
                                        </label>
                                        <select class="form-select" 
                                                name="nivel_educativo"
                                                style="border-radius:8px;">
                                            <option value="">Seleccione...</option>
                                            <option value="Sin estudios" {{ $encuesta->nivel_educativo == 'Sin estudios' ? 'selected' : '' }}>Sin estudios</option>
                                            <option value="Primaria" {{ $encuesta->nivel_educativo == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                                            <option value="Secundaria" {{ $encuesta->nivel_educativo == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                                            <option value="Técnico" {{ $encuesta->nivel_educativo == 'Técnico' ? 'selected' : '' }}>Técnico</option>
                                            <option value="Tecnológico" {{ $encuesta->nivel_educativo == 'Tecnológico' ? 'selected' : '' }}>Tecnológico</option>
                                            <option value="Universitario" {{ $encuesta->nivel_educativo == 'Universitario' ? 'selected' : '' }}>Universitario</option>
                                            <option value="Posgrado" {{ $encuesta->nivel_educativo == 'Posgrado' ? 'selected' : '' }}>Posgrado</option>
                                        </select>
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
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-rulers me-1"></i>Área del Predio
                                        </label>
                                        <div class="input-group" style="border-radius:8px;">
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="area_predio" 
                                                   value="{{ $encuesta->area_predio }}"
                                                   step="0.01"
                                                   style="border-radius:8px 0 0 8px;">
                                            <select class="form-select" 
                                                    name="unidad_medida"
                                                    style="max-width:120px; border-radius:0 8px 8px 0;">
                                                <option value="m²" {{ $encuesta->unidad_medida == 'm²' ? 'selected' : '' }}>m²</option>
                                                <option value="Ha" {{ $encuesta->unidad_medida == 'Ha' ? 'selected' : '' }}>Ha</option>
                                                <option value="Fanegadas" {{ $encuesta->unidad_medida == 'Fanegadas' ? 'selected' : '' }}>Fanegadas</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-bounding-box me-1"></i>Área Disponible
                                        </label>
                                        <div class="input-group" style="border-radius:8px;">
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="area_total_disponible" 
                                                   value="{{ $encuesta->area_total_disponible }}"
                                                   step="0.01"
                                                   style="border-radius:8px 0 0 8px;">
                                            <select class="form-select" 
                                                    name="unidad_medida2"
                                                    style="max-width:120px; border-radius:0 8px 8px 0;">
                                                <option value="m²" {{ $encuesta->unidad_medida2 == 'm²' ? 'selected' : '' }}>m²</option>
                                                <option value="Ha" {{ $encuesta->unidad_medida2 == 'Ha' ? 'selected' : '' }}>Ha</option>
                                                <option value="Fanegadas" {{ $encuesta->unidad_medida2 == 'Fanegadas' ? 'selected' : '' }}>Fanegadas</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-geo-alt me-1"></i>Coordenadas
                                        </label>
                                        <input type="text" 
                                               class="form-control font-monospace" 
                                               name="coordenadas" 
                                               value="{{ $encuesta->coordenadas }}"
                                               placeholder="Ej: 7.1234, -73.1234"
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-key me-1"></i>Tenencia de la Tierra
                                        </label>
                                        <select class="form-select" 
                                                name="tenencia_tierra"
                                                style="border-radius:8px;">
                                            <option value="">Seleccione...</option>
                                            <option value="Propia" {{ $encuesta->tenencia_tierra == 'Propia' ? 'selected' : '' }}>Propia</option>
                                            <option value="Arrendada" {{ $encuesta->tenencia_tierra == 'Arrendada' ? 'selected' : '' }}>Arrendada</option>
                                            <option value="Prestada" {{ $encuesta->tenencia_tierra == 'Prestada' ? 'selected' : '' }}>Prestada</option>
                                            <option value="Otra" {{ $encuesta->tenencia_tierra == 'Otra' ? 'selected' : '' }}>Otra</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-clock-history me-1"></i>Tiempo en la Finca
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="tiempo_viviendo_finca" 
                                               value="{{ $encuesta->tiempo_viviendo_finca }}"
                                               placeholder="Ej: 5 años"
                                               style="border-radius:8px;">
                                    </div>

                                      <div>
                                    <label class="form-label text-muted small text-uppercase fw-semibold">
                                        <i class="bi bi-house-door me-1"></i>Tipo de Tenencia
                                    </label>
                                    <select class="form-select"
                                            name="tipo_tenencia"
                                            style="border-radius:8px;">
                                        <option value="">Seleccionar...</option>
                                        <option value="Propietario" {{ $encuesta->tipo_tenencia == 'Propietario' ? 'selected' : '' }}>Propietario</option>
                                        <option value="Viviente" {{ $encuesta->tipo_tenencia == 'Viviente' ? 'selected' : '' }}>Viviente</option>
                                    </select>
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
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-question-circle me-1"></i>¿Le gustaría estudiar?
                                        </label>
                                        <div class="d-flex gap-3 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="le_gustaria_estudiar" 
                                                       id="estudiarSi" 
                                                       value="1"
                                                       {{ $encuesta->le_gustaria_estudiar ? 'checked' : '' }}>
                                                <label class="form-check-label" for="estudiarSi">
                                                    Sí
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="le_gustaria_estudiar" 
                                                       id="estudiarNo" 
                                                       value="0"
                                                       {{ !$encuesta->le_gustaria_estudiar ? 'checked' : '' }}>
                                                <label class="form-check-label" for="estudiarNo">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            <i class="bi bi-lightbulb me-1"></i>Qué le gustaría estudiar
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="que_le_gustaria_estudiar" 
                                               value="{{ $encuesta->que_le_gustaria_estudiar }}"
                                               style="border-radius:8px;">
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
                                
                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-semibold">
                                        <i class="bi bi-envelope me-1"></i>Correo Electrónico
                                    </label>
                                    <input type="email" 
                                           class="form-control" 
                                           name="correo" 
                                           value="{{ $encuesta->correo }}"
                                           style="border-radius:8px;">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-semibold">
                                        <i class="bi bi-phone me-1"></i>Celular Principal
                                    </label>
                                    <input type="tel" 
                                           class="form-control" 
                                           name="celular_1" 
                                           value="{{ $encuesta->celular_1 }}"
                                           style="border-radius:8px;">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-semibold">
                                        <i class="bi bi-phone me-1"></i>Celular Secundario
                                    </label>
                                    <input type="tel"
                                           class="form-control"
                                           name="celular_2"
                                           value="{{ $encuesta->celular_2 }}"
                                           style="border-radius:8px;">
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
                                
                                <div class="mb-3">
    <label class="form-label text-muted small text-uppercase fw-semibold">
        <i class="bi bi-people me-1"></i>Población Especial
    </label>
    <select class="form-select" 
            name="pertenencia_poblacion_especial"
            style="border-radius:8px;">
        <option value="">Seleccione...</option>

        <option value="Afrocolombiano" {{ $encuesta->pertenencia_poblacion_especial == 'Afrocolombiano' ? 'selected' : '' }}>Afrocolombiano</option>
        <option value="Campesino" {{ $encuesta->pertenencia_poblacion_especial == 'Campesino' ? 'selected' : '' }}>Campesino</option>
        <option value="Indígena" {{ $encuesta->pertenencia_poblacion_especial == 'Indígena' ? 'selected' : '' }}>Indígena</option>
        <option value="LGBTIQ+" {{ $encuesta->pertenencia_poblacion_especial == 'LGBTIQ+' ? 'selected' : '' }}>LGBTIQ+</option>
        <option value="Persona_Mayor" {{ $encuesta->pertenencia_poblacion_especial == 'Persona_Mayor' ? 'selected' : '' }}>Persona_Mayor</option>
        <option value="Cabeza_de_familia" {{ $encuesta->pertenencia_poblacion_especial == 'Cabeza_de_familia' ? 'selected' : '' }}>Cabeza de familia</option>
        <option value="Mujer_rural" {{ $encuesta->pertenencia_poblacion_especial == 'Mujer_rural' ? 'selected' : '' }}>Mujer rural</option>
        <option value="Desmovilizado" {{ $encuesta->pertenencia_poblacion_especial == 'Desmovilizado' ? 'selected' : '' }}>Desmovilizado</option>
        <option value="Reinsertado" {{ $encuesta->pertenencia_poblacion_especial == 'Reinsertado' ? 'selected' : '' }}>Reinsertado</option>
        <option value="Joven_rural" {{ $encuesta->pertenencia_poblacion_especial == 'Joven_rural' ? 'selected' : '' }}>Joven rural</option>
        <option value="Persona_con_discapacidad" {{ $encuesta->pertenencia_poblacion_especial == 'Persona_con_discapacidad' ? 'selected' : '' }}>Persona con discapacidad</option>
        <option value="Victima_del_conflicto" {{ $encuesta->pertenencia_poblacion_especial == 'Victima_del_conflicto' ? 'selected' : '' }}>Víctima del conflicto (RUV)</option>        
        <option value="Cuidador/a" {{ $encuesta->pertenencia_poblacion_especial == 'Cuidador/a' ? 'selected' : '' }}>Cuidador/a</option>
        <option value="Otra" {{ $encuesta->pertenencia_poblacion_especial == 'Otra' ? 'selected' : '' }}>Otra</option>
    </select>
</div>


                            </div>
                        </div>

                        {{-- CARD: Botones de Acción --}}
                        <div class="card shadow-sm border-0" style="border-radius:12px; overflow:hidden; border:2px solid #2d5f3f;">
                            <div class="card-body p-3">
                                <h6 class="mb-3 fw-semibold" style="color:#2d5f3f;">
                                    <i class="bi bi-lightning-charge me-1"></i>Guardar Cambios
                                </h6>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" 
                                            class="btn text-white fw-semibold py-2"
                                            style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); border-radius:8px;">
                                        <i class="bi bi-check-circle me-2"></i>Actualizar Encuesta
                                    </button>
                                    
                                    <a href="{{ route('encuestas.show', $encuesta->id) }}" 
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
        document.getElementById('corregimiento').addEventListener('change', function () {
            const id = this.value;

            fetch(`/encuestas/veredas/${id}`)
                .then(response => response.json())
                .then(data => {
                    const veredaSelect = document.getElementById('vereda');
                    veredaSelect.innerHTML = '<option value="">Seleccione...</option>';

                    data.forEach(v => {
                        veredaSelect.innerHTML += `<option value="${v.id}">${v.nombre}</option>`;
                    });
                });
        });
    </script>

</x-app-layout>

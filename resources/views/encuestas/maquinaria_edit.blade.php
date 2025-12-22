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
                            <i class="bi bi-pencil-square me-2"></i>Editar Maquinaria
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $maquinaria->encuesta->nombre_identidad }} {{ $maquinaria->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $maquinaria->encuesta->id }}
                        </p>
                    </div>

                    <a href="{{ route('maquinaria.show', $maquinaria->id) }}"
                       class="btn btn-outline-secondary px-4 py-2"
                       style="border-radius:8px; font-weight:500;">
                       <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('maquinaria.update', $maquinaria->id) }}" class="bg-white shadow-lg rounded p-4 p-md-5">
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

                        {{-- === SECCIÓN MAQUINARIA ===================================== --}} 
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-hammer fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Maquinaria Agrícola</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Cuenta con maquinaria para el desarrollo de las actividades agropecuarias?
                                        </label>
                                        <select name="maquinaria" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="1" {{ $maquinaria->maquinaria ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ !$maquinaria->maquinaria ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Campos de maquinaria --}}
                                <div id="contenedor-maquinaria" class="mt-3">
                                    <div class="maquinaria-item border rounded p-3 mb-3 shadow-sm bg-white maquinaria-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Datos de la maquinaria</h6>

                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Tipo
                                                </label>
                                                <input type="text" name="tipo_maquinaria[]" class="form-control"
                                                       value="{{ old('tipo_maquinaria.0', $maquinaria->tipo_maquinaria && is_array($maquinaria->tipo_maquinaria) ? ($maquinaria->tipo_maquinaria[0] ?? '') : '') }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Cantidad
                                                </label>
                                                <input type="number" name="cantidad_maquinaria[]" class="form-control"
                                                       value="{{ old('cantidad_maquinaria.0', $maquinaria->cantidad_maquinaria && is_array($maquinaria->cantidad_maquinaria) ? ($maquinaria->cantidad_maquinaria[0] ?? '') : '') }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Antigüedad (años)
                                                </label>
                                                <input type="number" name="antiguedad_maquinaria[]" class="form-control"
                                                       value="{{ old('antiguedad_maquinaria.0', $maquinaria->antiguedad_maquinaria && is_array($maquinaria->antiguedad_maquinaria) ? ($maquinaria->antiguedad_maquinaria[0] ?? '') : '') }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Estado
                                                </label>
                                                <input type="text" name="estado_maquinaria[]" class="form-control"
                                                       value="{{ old('estado_maquinaria.0', $maquinaria->estado_maquinaria && is_array($maquinaria->estado_maquinaria) ? ($maquinaria->estado_maquinaria[0] ?? '') : '') }}"
                                                       style="border-radius:8px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Botón añadir maquinaria --}}
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" id="btn-add-maquinaria">
                                        <i class="fas fa-plus me-1"></i>Añadir maquinaria
                                    </button>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN CONSTRUCCIÓN ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-house fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Construcciones</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Cuenta con construcción para el desarrollo de actividades agropecuarias?
                                        </label>
                                        <select name="tiene_construccion" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="1" {{ $maquinaria->tiene_construccion ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ !$maquinaria->tiene_construccion ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Campos de construcción --}}
                                <div id="contenedor-construccion" class="mt-3">
                                    <div class="construccion-item border rounded p-3 mb-3 shadow-sm bg-white construccion-campos" style="display: none;">
                                        <h6 class="text-muted mb-3 fw-semibold">Datos de la construcción</h6>

                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Tipo
                                                </label>
                                                <input type="text" name="tipo_construccion[]" class="form-control"
                                                       value="{{ old('tipo_construccion.0', $maquinaria->tipo_construccion && is_array($maquinaria->tipo_construccion) ? ($maquinaria->tipo_construccion[0] ?? '') : '') }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Antigüedad (años)
                                                </label>
                                                <input type="number" name="antiguedad_construccion[]" class="form-control"
                                                       value="{{ old('antiguedad_construccion.0', $maquinaria->antiguedad_construccion && is_array($maquinaria->antiguedad_construccion) ? ($maquinaria->antiguedad_construccion[0] ?? '') : '') }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Cantidad
                                                </label>
                                                <input type="number" name="cantidad_construccion[]" class="form-control"
                                                       value="{{ old('cantidad_construccion.0', $maquinaria->cantidad_construccion && is_array($maquinaria->cantidad_construccion) ? ($maquinaria->cantidad_construccion[0] ?? '') : '') }}"
                                                       style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-muted small text-uppercase fw-semibold">
                                                   Área
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" name="area_construccion[]" class="form-control"
                                                           value="{{ old('area_construccion.0', $maquinaria->area_construccion && is_array($maquinaria->area_construccion) ? ($maquinaria->area_construccion[0] ?? '') : '') }}"
                                                           style="border-radius:8px;">
                                                    <span class="input-group-text">MTS²</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Botón añadir construcción --}}
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" id="btn-add-construccion">
                                        <i class="fas fa-plus me-1"></i>Añadir construcción
                                    </button>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN ASOCIACIONES ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-people fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Asociaciones</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Pertenece a asociación?
                                        </label>
                                        <select name="pertenece_asociacion" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="cooperativas" {{ $maquinaria->pertenece_asociacion == 'cooperativas' ? 'selected' : '' }}>Cooperativas</option>
                                            <option value="gremios" {{ $maquinaria->pertenece_asociacion == 'gremios' ? 'selected' : '' }}>Gremios</option>
                                            <option value="asociaciones_comunitarias" {{ $maquinaria->pertenece_asociacion == 'asociaciones_comunitarias' ? 'selected' : '' }}>Asociación de organizaciones comunitarias</option>
                                            <option value="jac" {{ $maquinaria->pertenece_asociacion == 'jac' ? 'selected' : '' }}>JAC</option>
                                            <option value="no_pertenece" {{ $maquinaria->pertenece_asociacion == 'no_pertenece' ? 'selected' : '' }}>No pertenece a ninguna asociación</option>
                                            <option value="ns_nr" {{ $maquinaria->pertenece_asociacion == 'ns_nr' ? 'selected' : '' }}>No sabe / No responde</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 asociacion-campos" style="display: none;">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Nombre de la asociación
                                        </label>
                                        <input type="text" name="nombre_asociacion" class="form-control"
                                               value="{{ old('nombre_asociacion', $maquinaria->nombre_asociacion ?? '') }}"
                                               style="border-radius:8px;">
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Entidad que brinda asesoría
                                        </label>
                                        <select name="entidad_asesoria" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="Gremio" {{ $maquinaria->entidad_asesoria == 'Gremio' ? 'selected' : '' }}>Gremio</option>
                                            <option value="Alcaldía/Umata/Epsea" {{ $maquinaria->entidad_asesoria == 'Alcaldía/Umata/Epsea' ? 'selected' : '' }}>Alcaldía/Umata/Epsea</option>
                                            <option value="Universidad" {{ $maquinaria->entidad_asesoria == 'Universidad' ? 'selected' : '' }}>Universidad</option>
                                            <option value="Almacen agropecuario" {{ $maquinaria->entidad_asesoria == 'Almacen agropecuario' ? 'selected' : '' }}>Almacén agropecuario</option>
                                            <option value="Cooperativa/asociacion" {{ $maquinaria->entidad_asesoria == 'Cooperativa/asociacion' ? 'selected' : '' }}>Cooperativa/Asociación</option>
                                            <option value="Particular" {{ $maquinaria->entidad_asesoria == 'Particular' ? 'selected' : '' }}>Particular</option>
                                            <option value="Otro" {{ $maquinaria->entidad_asesoria == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 entidad-campos" style="display: none;">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Nombre de la entidad
                                        </label>
                                        <input type="text" name="entidad_asesoria_nombre" class="form-control"
                                               value="{{ old('entidad_asesoria_nombre', $maquinaria->entidad_asesoria_nombre ?? '') }}"
                                               style="border-radius:8px;">
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- === SECCIÓN ASESORÍA ===================================== --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-book fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Asesoría Técnica</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Recibió asesoría técnica el último año?
                                        </label>
                                        <select name="recibio_asesoria_ultimo_anio" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="1" {{ $maquinaria->recibio_asesoria_ultimo_anio ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ !$maquinaria->recibio_asesoria_ultimo_anio ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Temas de asesoría --}}
                                @php
                                    $mostrarAsesoria = $maquinaria->recibio_asesoria_ultimo_anio ||
                                        $maquinaria->tema_buenas_practicas_agricolas ||
                                        $maquinaria->tema_buenas_practicas_pecuarias ||
                                        $maquinaria->tema_manejo_ambiental ||
                                        $maquinaria->tema_manejo_suelos ||
                                        $maquinaria->tema_manejo_postcosecha ||
                                        $maquinaria->tema_comercializacion ||
                                        $maquinaria->tema_asociatividad ||
                                        $maquinaria->tema_credito ||
                                        $maquinaria->tema_empresarial ||
                                        $maquinaria->tema_tradicional;
                                @endphp
                                <div class="asesoria-campos" style="display: {{ $mostrarAsesoria ? 'block' : 'none' }};">
                                    <h6 class="fw-semibold mb-3" style="color:#2d5f3f;">Temas recibidos:</h6>

                                    {{-- BPA / BPP --}}
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Buenas Prácticas Agrícolas?
                                            </label>
                                            <select name="tema_buenas_practicas_agricolas" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_buenas_practicas_agricolas == '1' || $maquinaria->tema_buenas_practicas_agricolas === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_buenas_practicas_agricolas == '0' || $maquinaria->tema_buenas_practicas_agricolas === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó Buenas Practicas Agricolas?
                                            </label>
                                            <select name="pago_bpa" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_bpa == '1' || $maquinaria->pago_bpa === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_bpa == '0' || $maquinaria->pago_bpa === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Buenas Prácticas Pecuarias?
                                            </label>
                                            <select name="tema_buenas_practicas_pecuarias" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_buenas_practicas_pecuarias == '1' || $maquinaria->tema_buenas_practicas_pecuarias === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_buenas_practicas_pecuarias == '0' || $maquinaria->tema_buenas_practicas_pecuarias === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó Buenas Prácticas Pecuarias?
                                            </label>
                                            <select name="pago_bpp" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_bpp == '1' || $maquinaria->pago_bpp === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_bpp == '0' || $maquinaria->pago_bpp === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- MA / MS --}}
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Practicas de manejo ambiental?
                                            </label>
                                            <select name="tema_manejo_ambiental" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_manejo_ambiental == '1' || $maquinaria->tema_manejo_ambiental === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_manejo_ambiental == '0' || $maquinaria->tema_manejo_ambiental === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó Practicas de manejo ambiental?
                                            </label>
                                            <select name="pago_ma" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_ma == '1' || $maquinaria->pago_ma === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_ma == '0' || $maquinaria->pago_ma === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Manejo de Suelos?
                                            </label>
                                            <select name="tema_manejo_suelos" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_manejo_suelos == '1' || $maquinaria->tema_manejo_suelos === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_manejo_suelos == '0' || $maquinaria->tema_manejo_suelos === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó Manejo de Suelos?
                                            </label>
                                            <select name="pago_ms" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_ms == '1' || $maquinaria->pago_ms === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_ms == '0' || $maquinaria->pago_ms === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- MPC / COMERCIALIZACION --}}
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Manejo de Postcosecha?
                                            </label>
                                            <select name="tema_manejo_postcosecha" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_manejo_postcosecha == '1' || $maquinaria->tema_manejo_postcosecha === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_manejo_postcosecha == '0' || $maquinaria->tema_manejo_postcosecha === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó Manejo de Postcosecha?
                                            </label>
                                            <select name="pago_mpc" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_mpc == '1' || $maquinaria->pago_mpc === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_mpc == '0' || $maquinaria->pago_mpc === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Comercializacion?
                                            </label>
                                            <select name="tema_comercializacion" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_comercializacion == '1' || $maquinaria->tema_comercializacion === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_comercializacion == '0' || $maquinaria->tema_comercializacion === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó comercializacion?
                                            </label>
                                            <select name="pago_comercializacion" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_comercializacion == '1' || $maquinaria->pago_comercializacion === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_comercializacion == '0' || $maquinaria->pago_comercializacion === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- ASOCIATIVIDAD / CREDITO --}}
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Asociatividad?
                                            </label>
                                            <select name="tema_asociatividad" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_asociatividad == '1' || $maquinaria->tema_asociatividad === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_asociatividad == '0' || $maquinaria->tema_asociatividad === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó Asociatividad?
                                            </label>
                                            <select name="pago_asociatividad" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_asociatividad == '1' || $maquinaria->pago_asociatividad === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_asociatividad == '0' || $maquinaria->pago_asociatividad === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Crédito y financimiento?
                                            </label>
                                            <select name="tema_credito" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_credito == '1' || $maquinaria->tema_credito === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_credito == '0' || $maquinaria->tema_credito === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó Crédito y financimiento?
                                            </label>
                                            <select name="pago_credito" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_credito == '1' || $maquinaria->pago_credito === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_credito == '0' || $maquinaria->pago_credito === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- EMPRESARIAL / TRADICIONAL --}}
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Gestión empresarial?
                                            </label>
                                            <select name="tema_empresarial" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_empresarial == '1' || $maquinaria->tema_empresarial === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_empresarial == '0' || $maquinaria->tema_empresarial === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó Gestión empresarial?
                                            </label>
                                            <select name="pago_empresarial" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_empresarial == '1' || $maquinaria->pago_empresarial === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_empresarial == '0' || $maquinaria->pago_empresarial === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Recibió Conocimiento tradicional o ancestral?
                                            </label>
                                            <select name="tema_tradicional" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->tema_tradicional == '1' || $maquinaria->tema_tradicional === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->tema_tradicional == '0' || $maquinaria->tema_tradicional === false ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label text-muted small text-uppercase fw-semibold">
                                               ¿Pagó conocimiento tradicional o ancestral?
                                            </label>
                                            <select name="pago_tradicional" class="form-select"
                                                    style="border-radius:8px;">
                                                <option value="">Seleccionar</option>
                                                <option value="1" {{ $maquinaria->pago_tradicional == '1' || $maquinaria->pago_tradicional === true ? 'selected' : '' }}>Sí</option>
                                                <option value="0" {{ $maquinaria->pago_tradicional == '0' || $maquinaria->pago_tradicional === false ? 'selected' : '' }}>No</option>
                                            </select>
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
                                        <i class="bi bi-check-circle me-2"></i>Actualizar Maquinaria
                                    </button>

                                    <a href="{{ route('maquinaria.show', $maquinaria->id) }}"
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

        .btn-primary {
            background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%) !important;
            border: none !important;
            color: white !important;
            padding: 0.5rem 1.25rem !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
        }

        .btn-primary:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(74, 124, 47, 0.25) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let maquinariaIndex = 1;
            let construccionIndex = 1;

            // Función helper para mostrar/ocultar campos
            function toggleCampos(selector, mostrar) {
                const elementos = document.querySelectorAll(selector);
                elementos.forEach(elemento => {
                    if (mostrar) {
                        elemento.style.display = 'flex';
                        elemento.style.flexWrap = 'wrap';
                        // Habilitar campos cuando se muestran
                        elemento.querySelectorAll('select, input').forEach(field => {
                            field.disabled = false;
                        });
                    } else {
                        elemento.style.display = 'none';
                        // Deshabilitar campos cuando se ocultan
                        elemento.querySelectorAll('select, input').forEach(field => {
                            field.disabled = true;
                        });
                    }
                });
            }

            // Función para manejar cambios en maquinaria
            function handleMaquinariaChange() {
                const select = document.querySelector('select[name="maquinaria"]');
                const mostrar = select.value === '1';
                toggleCampos('.maquinaria-campos', mostrar);
            }

            // Función para manejar cambios en construcción
            function handleConstruccionChange() {
                const select = document.querySelector('select[name="tiene_construccion"]');
                const mostrar = select.value === '1';
                toggleCampos('.construccion-campos', mostrar);
            }

            // Función para manejar cambios en asociaciones
            function handleAsociacionChange() {
                const select = document.querySelector('select[name="pertenece_asociacion"]');
                const mostrar = select.value !== '' && select.value !== 'no_pertenece' && select.value !== 'ns_nr';
                toggleCampos('.asociacion-campos', mostrar);
            }

             function handleEntidadChange() {
                const select = document.querySelector('select[name="entidad_asesoria"]');
                const mostrar = select.value === 'Otro';
                toggleCampos('.entidad-campos', mostrar);
            }

            // Función para manejar cambios en asesoría
            function handleAsesoriaChange() {
                const select = document.querySelector('select[name="recibio_asesoria_ultimo_anio"]');
                const mostrar = select.value === '1';
                toggleCampos('.asesoria-campos', mostrar);
            }

            // Event listeners para campos condicionales
            document.querySelector('select[name="maquinaria"]').addEventListener('change', handleMaquinariaChange);
            document.querySelector('select[name="tiene_construccion"]').addEventListener('change', handleConstruccionChange);
            document.querySelector('select[name="entidad_asesoria"]').addEventListener('change', handleEntidadChange);
            document.querySelector('select[name="pertenece_asociacion"]').addEventListener('change', handleAsociacionChange);
            document.querySelector('select[name="recibio_asesoria_ultimo_anio"]').addEventListener('change', handleAsesoriaChange);

            // Inicializar estado con delay para asegurar que el DOM esté listo
            setTimeout(() => {
                handleMaquinariaChange();
                handleConstruccionChange();
                handleAsociacionChange();
                handleAsesoriaChange();
                handleEntidadChange();
            }, 100);


            // Función para añadir maquinaria
            document.getElementById('btn-add-maquinaria').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-maquinaria');
                let original = document.querySelector('.maquinaria-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

                // Actualizar índices en los nombres
                nuevo.querySelectorAll('input, select').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d*\]$/, '[' + maquinariaIndex + ']');
                    }
                });

                contenedor.appendChild(nuevo);
                maquinariaIndex++;
            });

            // Función para añadir construcción
            document.getElementById('btn-add-construccion').addEventListener('click', function () {
                let contenedor = document.getElementById('contenedor-construccion');
                let original = document.querySelector('.construccion-item');

                let nuevo = original.cloneNode(true);

                // Limpiar campos
                nuevo.querySelectorAll('input').forEach(i => i.value = "");
                nuevo.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

                // Actualizar índices en los nombres
                nuevo.querySelectorAll('input, select').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d*\]$/, '[' + construccionIndex + ']');
                    }
                });

                contenedor.appendChild(nuevo);
                construccionIndex++;
            });
        });
    </script>

</x-app-layout>

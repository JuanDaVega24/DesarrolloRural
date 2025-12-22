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
                            <i class="bi bi-house-fill me-2"></i>Editar Vivienda
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $vivienda->encuesta->nombre_identidad }} {{ $vivienda->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $vivienda->encuesta_id }}
                        </p>
                    </div>

                    <a href="{{ route('viviendas.show', $vivienda->id) }}"
                       class="btn btn-outline-secondary px-4 py-2"
                       style="border-radius:8px; font-weight:500;">
                       <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <form action="{{ route('viviendas.update', $vivienda->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Columna Principal --}}
                    <div class="col-lg-12">

                        {{-- CARD: Información de la Vivienda --}}
                        <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                            <div class="card-header text-white d-flex align-items-center"
                                 style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                                <i class="bi bi-house-door-fill fs-4 me-2"></i>
                                <h5 class="mb-0 fw-semibold">Datos de la Vivienda</h5>
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

                                    {{-- FILA 1 --}}
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                          Tipo de Vivienda*
                                        </label>
                                        <select name="tipo_vivienda" class="form-select"
                                                style="border-radius:8px;" required>
                                            <option value="">Seleccionar</option>
                                            <option value="casa" {{ $vivienda->tipo_vivienda == 'casa' ? 'selected' : '' }}>Casa</option>
                                            <option value="apartamento" {{ $vivienda->tipo_vivienda == 'apartamento' ? 'selected' : '' }}>Apartamento</option>
                                            <option value="tipo_cuarto" {{ $vivienda->tipo_vivienda == 'tipo_cuarto' ? 'selected' : '' }}>Tipo de Cuarto</option>
                                            <option value="indigena" {{ $vivienda->tipo_vivienda == 'indigena' ? 'selected' : '' }}>Vivienda tradicional indígena</option>
                                            <option value="etnica" {{ $vivienda->tipo_vivienda == 'etnica' ? 'selected' : '' }}>Vivienda étnica</option>
                                            <option value="otro" {{ $vivienda->tipo_vivienda == 'otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Condición de Ocupación*
                                        </label>
                                        <select name="condicion_ocupacion" class="form-select"
                                                style="border-radius:8px;" required>
                                            <option value="">Seleccionar</option>
                                            <option value="ocupada" {{ $vivienda->condicion_ocupacion == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                                            <option value="vivienda_temporal" {{ $vivienda->condicion_ocupacion == 'vivienda_temporal' ? 'selected' : '' }}>Temporal</option>
                                            <option value="desocupada" {{ $vivienda->condicion_ocupacion == 'desocupada' ? 'selected' : '' }}>Desocupada</option>
                                            <option value="ocupada_por_viviente" {{ $vivienda->condicion_ocupacion == 'ocupada_por_viviente' ? 'selected' : '' }}>Ocupada por viviente</option>
                                            <option value="ocupada_por_familia" {{ $vivienda->condicion_ocupacion == 'ocupada_por_familia' ? 'selected' : '' }}>Ocupada por familia</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Material de Pisos*
                                        </label>
                                        <select name="material_piso" class="form-select"
                                                style="border-radius:8px;" required>
                                            <option value="">Seleccionar</option>
                                            <option value="marmol" {{ $vivienda->material_piso == 'marmol' ? 'selected' : '' }}>Mármol</option>
                                            <option value="baldosa" {{ $vivienda->material_piso == 'baldosa' ? 'selected' : '' }}>Baldosa</option>
                                            <option value="alfombra" {{ $vivienda->material_piso == 'alfombra' ? 'selected' : '' }}>Alfombra</option>
                                            <option value="cemento" {{ $vivienda->material_piso == 'cemento' ? 'selected' : '' }}>Cemento</option>
                                            <option value="madera_burda" {{ $vivienda->material_piso == 'madera_burda' ? 'selected' : '' }}>Madera burda</option>
                                            <option value="tierra" {{ $vivienda->material_piso == 'tierra' ? 'selected' : '' }}>Tierra</option>
                                        </select>
                                    </div>

                                    {{-- FILA 2 --}}
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Material de Paredes Exteriores
                                        </label>
                                        <input type="text" name="material_pared_exterior" class="form-control"
                                               value="{{ old('material_pared_exterior', $vivienda->material_pared_exterior) }}"
                                               style="border-radius:8px;">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                            Destino Aguas Residuales
                                        </label>
                                        <select name="destino_aguas_residuales" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="alcantarillado" {{ $vivienda->destino_aguas_residuales == 'alcantarillado' ? 'selected' : '' }}>Alcantarillado</option>
                                            <option value="pozo" {{ $vivienda->destino_aguas_residuales == 'pozo' ? 'selected' : '' }}>Pozo séptico</option>
                                            <option value="pozo_no_funcional" {{ $vivienda->destino_aguas_residuales == 'pozo_no_funcional' ? 'selected' : '' }}>Pozo no funcional</option>
                                            <option value="ninguno" {{ $vivienda->destino_aguas_residuales == 'ninguno' ? 'selected' : '' }}>Ninguno</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                          Combustible Cocina
                                        </label>
                                        <select name="combustible_cocina" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="madera" {{ $vivienda->combustible_cocina == 'madera' ? 'selected' : '' }}>Madera</option>
                                            <option value="propano" {{ $vivienda->combustible_cocina == 'propano' ? 'selected' : '' }}>Propano</option>
                                            <option value="electrico" {{ $vivienda->combustible_cocina == 'electrico' ? 'selected' : '' }}>Eléctrico</option>
                                            <option value="carbon" {{ $vivienda->combustible_cocina == 'carbon' ? 'selected' : '' }}>Carbón</option>
                                            <option value="biogas" {{ $vivienda->combustible_cocina == 'biogas' ? 'selected' : '' }}>Biogás</option>
                                            <option value="solar" {{ $vivienda->combustible_cocina == 'solar' ? 'selected' : '' }}>Solar</option>
                                            <option value="ninguno" {{ $vivienda->combustible_cocina == 'ninguno' ? 'selected' : '' }}>Ninguno</option>
                                        </select>
                                    </div>

                                    {{-- FILA 3 --}}
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                          Medios de Comunicación
                                        </label>
                                        <select name="medios_comunicacion" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="fibra_optica" {{ $vivienda->medios_comunicacion == 'fibra_optica' ? 'selected' : '' }}>Fibra óptica</option>
                                            <option value="internet_satelital" {{ $vivienda->medios_comunicacion == 'internet_satelital' ? 'selected' : '' }}>Internet satelital</option>
                                            <option value="tv" {{ $vivienda->medios_comunicacion == 'tv' ? 'selected' : '' }}>TV</option>
                                            <option value="radio" {{ $vivienda->medios_comunicacion == 'radio' ? 'selected' : '' }}>Radio</option>
                                            <option value="internet" {{ $vivienda->medios_comunicacion == 'internet' ? 'selected' : '' }}>Internet</option>
                                            <option value="telefono_fijo" {{ $vivienda->medios_comunicacion == 'telefono_fijo' ? 'selected' : '' }}>Teléfono fijo</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                          Medios Electrónicos
                                        </label>
                                        <select name="medios_electronicos" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="televisor" {{ $vivienda->medios_electronicos == 'televisor' ? 'selected' : '' }}>Televisor</option>
                                            <option value="computador" {{ $vivienda->medios_electronicos == 'computador' ? 'selected' : '' }}>Computador</option>
                                            <option value="radio" {{ $vivienda->medios_electronicos == 'radio' ? 'selected' : '' }}>Radio</option>
                                            <option value="tablet" {{ $vivienda->medios_electronicos == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                            <option value="celular" {{ $vivienda->medios_electronicos == 'celular' ? 'selected' : '' }}>Celular</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           Tipo de Servicio Sanitario
                                        </label>
                                        <select name="tipo_servicio_sanitario" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="inodoro_alcantarillado" {{ $vivienda->tipo_servicio_sanitario == 'inodoro_alcantarillado' ? 'selected' : '' }}>Inodoro conectado al alcantarillado</option>
                                            <option value="inodoro_pozo" {{ $vivienda->tipo_servicio_sanitario == 'inodoro_pozo' ? 'selected' : '' }}>Inodoro conectado al pozo séptico</option>
                                            <option value="inodo_sin_conexion" {{ $vivienda->tipo_servicio_sanitario == 'inodo_sin_conexion' ? 'selected' : '' }}>Inodoro sin conexión</option>
                                            <option value="letrina" {{ $vivienda->tipo_servicio_sanitario == 'letrina' ? 'selected' : '' }}>Letrina</option>
                                            <option value="inodoro_directo" {{ $vivienda->tipo_servicio_sanitario == 'inodoro_directo' ? 'selected' : '' }}>Inodoro directo</option>
                                            <option value="sin_sanitario" {{ $vivienda->tipo_servicio_sanitario == 'sin_sanitario' ? 'selected' : '' }}>Esta vivienda no tiene servicio sanitario</option>
                                        </select>
                                    </div>

                                    {{-- FILA 4 --}}
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Cuenta con Acueducto Veredal?
                                        </label>
                                        <select name="acueducto_veredal" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="1" {{ $vivienda->acueducto_veredal == 1 ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ $vivienda->acueducto_veredal == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small text-uppercase fw-semibold">
                                           ¿Cuenta con filtro para el consumo?
                                        </label>
                                        <select name="cuenta_con_filtro" class="form-select"
                                                style="border-radius:8px;">
                                            <option value="">Seleccionar</option>
                                            <option value="1" {{ $vivienda->cuenta_con_filtro == 1 ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ $vivienda->cuenta_con_filtro == 0 ? 'selected' : '' }}>No</option>
                                        </select>
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
                                    <i class="bi bi-check-circle me-2"></i>Actualizar Vivienda
                                </button>

                                <a href="{{ route('viviendas.show', $vivienda->id) }}"
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

</x-app-layout>

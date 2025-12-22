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
                            <i class="bi bi-hammer me-2"></i>Detalles de Maquinaria
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person me-1"></i>{{ $maquinaria->encuesta->nombre_identidad }} {{ $maquinaria->encuesta->primer_apellido }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clipboard-check me-1"></i>Encuesta #{{ $maquinaria->encuesta_id }}
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                           <a href="{{ route('inventario_pecuario.show', $maquinaria->encuesta->inventario_pecuario->id) }}"
                           class="btn  px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Inventario Pecuario
                        </a>
                        <a href="{{ route('encuestas.show', $maquinaria->encuesta_id) }}"
                           class="btn px-4 py-2"
                           style="border-color:#2d5f3f; color:#2d5f3f; border-radius:8px; font-weight:500;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Volver a Datos personales
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                {{-- Columna Principal --}}
                <div class="col-lg-8">

                    {{-- MAQUINARIA --}}
                    @if($maquinaria->maquinaria == 1 || $maquinaria->tipo_maquinaria)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-hammer fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Maquinaria Agrícola</h5>
                        </div>
                        <div class="card-body p-4">

                            {{-- Mostrar maquinaria como tabla --}}
                            @php
                                $tiposMaquinaria = $maquinaria->tipo_maquinaria ? json_decode($maquinaria->tipo_maquinaria, true) : [];
                                $cantidades = $maquinaria->cantidad_maquinaria ? json_decode($maquinaria->cantidad_maquinaria, true) : [];
                                $antiguedades = $maquinaria->antiguedad_maquinaria ? json_decode($maquinaria->antiguedad_maquinaria, true) : [];
                                $estados = $maquinaria->estado_maquinaria ? json_decode($maquinaria->estado_maquinaria, true) : [];
                            @endphp

                            @if(!empty($tiposMaquinaria))
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tipo de Maquinaria</th>
                                                <th>Cantidad</th>
                                                <th>Antigüedad (años)</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tiposMaquinaria as $index => $tipo)
                                                <tr>
                                                    <td><strong>{{ $tipo ?? '—' }}</strong></td>
                                                    <td>{{ $cantidades[$index] ?? '—' }}</td>
                                                    <td>{{ $antiguedades[$index] ?? '—' }}</td>
                                                    <td>{{ $estados[$index] ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-hammer text-muted fs-1 mb-2"></i>
                                    <p class="text-muted">No hay información de maquinaria registrada</p>
                                </div>
                            @endif

                        </div>
                    </div>
                    @endif

                    {{-- CONSTRUCCIÓN --}}
                    @if($maquinaria->tiene_construccion || $maquinaria->tipo_construccion)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-house fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Construcciones</h5>
                        </div>
                        <div class="card-body p-4">

                            {{-- Mostrar construcciones como tabla --}}
                            @php
                                $tiposConstruccion = $maquinaria->tipo_construccion ? json_decode($maquinaria->tipo_construccion, true) : [];
                                $cantidadesConstruccion = $maquinaria->cantidad_construccion ? json_decode($maquinaria->cantidad_construccion, true) : [];
                                $antiguedadesConstruccion = $maquinaria->antiguedad_construccion ? json_decode($maquinaria->antiguedad_construccion, true) : [];
                                $areasConstruccion = $maquinaria->area_construccion ? json_decode($maquinaria->area_construccion, true) : [];
                            @endphp

                            @if(!empty($tiposConstruccion))
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tipo de Construcción</th>
                                                <th>Cantidad</th>
                                                <th>Antigüedad (años)</th>
                                                <th>Área (MTS²)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tiposConstruccion as $index => $tipo)
                                                <tr>
                                                    <td><strong>{{ $tipo ?? '—' }}</strong></td>
                                                    <td>{{ $cantidadesConstruccion[$index] ?? '—' }}</td>
                                                    <td>{{ $antiguedadesConstruccion[$index] ?? '—' }}</td>
                                                    <td>{{ $areasConstruccion[$index] ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-house text-muted fs-1 mb-2"></i>
                                    <p class="text-muted">No hay información de construcciones registrada</p>
                                </div>
                            @endif

                        </div>
                    </div>
                    @endif

                    {{-- ASOCIACIONES --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-people-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Asociaciones</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-4">
                                    <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                           Pertenece a Asociación
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">
                                            @php
                                                $asociacionLabels = [
                                                    'cooperativas' => 'Cooperativas',
                                                    'gremios' => 'Gremios',
                                                    'asociaciones_comunitarias' => 'Asociación de organizaciones comunitarias',
                                                    'jac' => 'JAC',
                                                    'no_pertenece' => 'No pertenece a ninguna asociación',
                                                    'ns_nr' => 'No sabe / No responde'
                                                ];
                                            @endphp
                                            @if($maquinaria->pertenece_asociacion && $maquinaria->pertenece_asociacion !== 'no_pertenece' && $maquinaria->pertenece_asociacion !== 'ns_nr')
                                                <span class="badge bg-success">Sí</span>
                                                <br><small class="text-muted">{{ $asociacionLabels[$maquinaria->pertenece_asociacion] ?? $maquinaria->pertenece_asociacion }}</small>
                                                @if($maquinaria->nombre_asociacion)
                                                    <br><small class="text-muted">Nombre: {{ $maquinaria->nombre_asociacion }}</small>
                                                @endif
                                            @elseif($maquinaria->pertenece_asociacion === 'no_pertenece')
                                                <span class="badge bg-secondary">No pertenece</span>
                                            @elseif($maquinaria->pertenece_asociacion === 'ns_nr')
                                                <span class="badge bg-warning">No sabe / No responde</span>
                                            @else
                                                <span class="badge bg-secondary">No especificado</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            Entidad que brinda asesoría
                                        </label>
                                        <p class="mb-0 fs-6 fw-medium">{{ $maquinaria->entidad_asesoria ?? '—' }}
                                            @if($maquinaria->entidad_asesoria_nombre)
                                                <br><small class="text-muted">Nombre: {{ $maquinaria->entidad_asesoria_nombre }}</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ASESORÍA TÉCNICA --}}
                    @if($maquinaria->recibio_asesoria_ultimo_anio)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                        <div class="card-header text-white d-flex align-items-center"
                             style="background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%); padding:1.25rem;">
                            <i class="bi bi-book-fill fs-4 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Asesoría Técnica Recibida</h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-2">
                                {{-- BPA --}}
                                @if($maquinaria->tema_buenas_practicas_agricolas)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Buenas Prácticas Agrícolas</span>
                                            @if($maquinaria->pago_bpa)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- BPP --}}
                                @if($maquinaria->tema_buenas_practicas_pecuarias)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Buenas Prácticas Pecuarias</span>
                                            @if($maquinaria->pago_bpp)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Manejo Ambiental --}}
                                @if($maquinaria->tema_manejo_ambiental)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Manejo Ambiental</span>
                                            @if($maquinaria->pago_ma)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Manejo de Suelos --}}
                                @if($maquinaria->tema_manejo_suelos)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Manejo de Suelos</span>
                                            @if($maquinaria->pago_ms)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Manejo Postcosecha --}}
                                @if($maquinaria->tema_manejo_postcosecha)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Manejo Postcosecha</span>
                                            @if($maquinaria->pago_mpc)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Comercialización --}}
                                @if($maquinaria->tema_comercializacion)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Comercialización</span>
                                            @if($maquinaria->pago_comercializacion)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Asociatividad --}}
                                @if($maquinaria->tema_asociatividad)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Asociatividad</span>
                                            @if($maquinaria->pago_asociatividad)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Crédito --}}
                                @if($maquinaria->tema_credito)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Crédito y Financiamiento</span>
                                            @if($maquinaria->pago_credito)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Gestión Empresarial --}}
                                @if($maquinaria->tema_empresarial)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Gestión Empresarial</span>
                                            @if($maquinaria->pago_empresarial)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Conocimiento Tradicional --}}
                                @if($maquinaria->tema_tradicional)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                            <span class="small fw-semibold">Conocimiento Tradicional</span>
                                            @if($maquinaria->pago_tradicional)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-info">Gratuito</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if(!$maquinaria->tema_bpa && !$maquinaria->tema_bpp && !$maquinaria->tema_ma && !$maquinaria->tema_ms && !$maquinaria->tema_mpc && !$maquinaria->tema_comercializacion && !$maquinaria->tema_asociatividad && !$maquinaria->tema_credito && !$maquinaria->tema_empresarial && !$maquinaria->tema_tradicional)
                                <div class="text-center py-4">
                                    <i class="bi bi-book text-muted fs-1 mb-2"></i>
                                    <p class="text-muted">No hay temas de asesoría registrados</p>
                                </div>
                            @endif

                        </div>
                    </div>
                    @endif

                </div>

                {{-- Columna Derecha --}}
                <div class="col-lg-4">

                    {{-- CARD: Acciones rápidas --}}
                    <div class="card shadow-sm border-0" style="border-radius:12px; overflow:hidden; border:2px solid #2d5f3f;">
                        <div class="card-body p-3">
                            <h6 class="mb-3 fw-semibold" style="color:#2d5f3f;">
                               Acciones
                            </h6>

                         <div class="row g-3">

    <div class="col-6">
        <a class="btn card-btn edit" href="{{ route('maquinaria.edit', $maquinaria->id) }}">
            <i class="bi bi-pencil-square icon"></i>
            <span>Editar Maquinaria</span>
        </a>
    </div>

    <div class="col-6">
        @if($maquinaria->encuesta->gestion_agropecuaria)
            <a class="btn card-btn next" href="{{ route('gestion_agropecuaria.show', $maquinaria->encuesta->gestion_agropecuaria->id) }}">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>Gestion Agropecuaria</span>
            </a>
        @else 
            <a class="btn card-btn next" onclick="continuarAGestionAgropecuaria({{ $maquinaria->encuesta_id }})">
                <i class="bi bi-arrow-right-circle icon"></i>
                <span>Gestion Agropecuaria</span>
            </a>
        @endif
    </div>

    <div class="col-6">
        <button class="btn card-btn print">
            <i class="bi bi-printer icon"></i>
            <span>Exportar</span>
        </button>
    </div>

    <div class="col-6">
        <button class="btn card-btn delete" onclick="eliminarMaquinaria({{ $maquinaria->id }})">
            <i class="bi bi-trash icon"></i>
            <span>Eliminar</span>
        </button>
    </div>

</div>


                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <style>
        .info-item {
            transition: transform 0.2s ease;
        }

        .info-item:hover {
            transform: translateX(3px);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
        }

        .badge {
            font-weight: 500;
        }

    .card-btn {
    width: 100%;
    background: #fff;
    border-radius: 14px;
    padding: 18px;
    font-weight: 600;
    border: none;
    box-shadow: 0 4px 10px rgba(0,0,0,.08);
    transition: .2s ease-in-out;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.card-btn .icon {
    font-size: 24px;
    margin-bottom: 6px;
}

.card-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,.12);
}

/* Colores */
.card-btn.edit { color: #000000; }
.card-btn.next { color: #ffffff; background: #3d6625}
.card-btn.print { color: #ffc107; }
.card-btn.delete { background: #a51e1e; color: #ffffff; }
    </style>

    <script>
    function continuarAGestionAgropecuaria(encuestaId) {
        // Hacer una petición AJAX para establecer el encuesta_id en la sesión
        fetch('/encuestas/establecer-sesion/' + encuestaId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            if (response.ok) {
                // Redirigir a la página de gestión agropecuaria
                window.location.href = '{{ route("encuestas.gestion_agropecuaria") }}';
            } else {
                alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al continuar con la encuesta. Inténtalo de nuevo.');
        });
    }

    function eliminarMaquinaria(id) {
        if (confirm('¿Estás seguro de que quieres eliminar esta información de maquinaria?')) {
            // Crear formulario para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/encuestas/maquinaria/${id}`;

            // Agregar método DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Agregar CSRF token
            const csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '_token';
            csrfField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfField);

            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>

</x-app-layout>

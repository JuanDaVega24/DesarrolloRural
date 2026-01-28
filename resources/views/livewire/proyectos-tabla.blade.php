<div>
   

    {{-- === MINIMAL COLLAPSIBLE FILTERS === --}}
    <div class="filter-minimal">
       

        <div class="filter-body" id="filterBody">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-search me-1"></i> Buscar proyecto
                    </label>
                    <input type="text"
                           id="searchInput"
                           class="form-control"
                           placeholder="Escribe un nombre...">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-flag-fill me-1"></i> Estado
                    </label>
                    <select wire:model.live="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="button" wire:click="limpiarFiltros" class="btn btn-clear">
                        <i class="fas fa-times me-1"></i>Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- === FOCUSED TABLE === --}}
    <div class="table-card">
        <div class="table-header">
            <h4>
                
                Proyectos Registrados
            </h4>
            <span>{{ $proyectos->total() }} proyectos</span>
        </div>

        <div class="table-responsive">
            <table class="table mb-0" id="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Proyecto</th>
                        <th>Año</th>
                        <th>Estado</th>
                        <th class="text-center">Datos</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proyectos as $proyecto)
                        <tr class="data-row">
                            <td><strong>#{{ $proyecto->id }}</strong></td>
                            <td>
                                <strong>{{ $proyecto->nombre }}</strong>
                                @if($proyecto->descripcion)
                                    <br><small class="text-muted">{{ Str::limit($proyecto->descripcion, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $proyecto->ano ?? '-' }}
                            </td>
                            <td>
                                @php
                                    $estadoColor = match($proyecto->estado) {
                                        'Activo' => '#28a745',
                                        'Inactivo' => '#6c757d',
                                        'Finalizado' => '#007bff',
                                        default => '#6c757d'
                                    };
                                @endphp
                                <span class="badge" style="background-color: {{ $estadoColor }};">
                                    {{ $proyecto->estado ?? 'Sin estado' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($proyecto->data)
                                    @php
                                        $data = $proyecto->data;
                                        $filename = 'Cargados';
                                        $totalRows = 0;

                                        // Si es string, intentar decodificar JSON
                                        if (is_string($data)) {
                                            $decoded = json_decode($data, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $data = $decoded;
                                            }
                                        }

                                        // Si es array, acceder a los campos
                                        if (is_array($data)) {
                                            if (isset($data['filename']) && !empty($data['filename'])) {
                                                $filename = $data['filename'];
                                            }
                                            if (isset($data['total_rows'])) {
                                                $totalRows = $data['total_rows'];
                                            }
                                        }
                                    @endphp
                                    <span class="badge btn-white text-dark btn-sm" title="{{ $filename }}">
                                       {{ Str::limit($filename, 70) }}
                                    </span>
                                    <br><small class="text-muted">{{ $totalRows }} filas</small>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Sin datos
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('proyectos.upload-excel', $proyecto) }}"
                                       class="btn badge bg-white text-dark btn-sm"
                                       title="Subir Excel">
                                        <i class="bi bi-file-earmark-spreadsheet"></i> Subir Excel
                                    </a>

                                    @if($proyecto->data)
                                        <a href="{{ route('proyectos.show', $proyecto) }}"
                                           class="btn bg-white btn-sm"
                                           title="Ver tabla">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <button class="btn bg-white btn-sm"
                                                style="opacity: 0.5; cursor: not-allowed;"
                                                disabled
                                                title="Suba un Excel primero">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif

                                    <a href="{{ route('proyectos.edit', $proyecto) }}"
                                       class="btn bg-white btn-sm"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button class="btn btn-danger btn-sm"
                                            title="Eliminar"
                                            onclick="confirmarEliminacion('{{ route('proyectos.destroy', $proyecto) }}', '{{ $proyecto->nombre }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="bi bi-inbox display-4 d-block"></i>
                                <p>No hay proyectos productivos registrados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- === PAGINATION === --}}
    <div class="mt-3">
        {{ $proyectos->links() }}
    </div>

    <script>
        function toggleFilters() {
            const toggle = document.querySelector('.filter-toggle');
            const body = document.getElementById('filterBody');

            toggle.classList.toggle('active');
            body.classList.toggle('active');
        }

        function confirmarEliminacion(url, proyectoNombre) {
            const modal = document.getElementById('exampleModalConfirmacion');
            const titulo = modal.querySelector('.modal-title-govco');
            const texto = modal.querySelector('.modal-text-govco');
            const btnConfirmar = document.querySelector('.btn-eliminar-confirmar');
            const btnCancelar = document.querySelector('.btn-eliminar-cancelar');

            // Personalizar contenido del modal
            titulo.textContent = '¿Eliminar Proyecto?';
            texto.innerHTML = '¿Está seguro de que desea eliminar el proyecto "<strong>' + proyectoNombre + '</strong>"? Esta acción no se puede deshacer.';

            // Mostrar modal y backdrop
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            let backdrop = document.querySelector('.modal-backdrop-govco');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop-govco';
                document.body.appendChild(backdrop);
            }
            backdrop.style.display = 'block';

            // Función para cerrar modal
            const cerrarModal = function() {
                modal.classList.remove('show');
                document.body.style.overflow = '';
                if (backdrop) {
                    backdrop.style.display = 'none';
                }
                document.removeEventListener('keydown', handleEscapeKey);
            };

            // Event listener para Escape
            const handleEscapeKey = function(event) {
                if (event.key === 'Escape') {
                    cerrarModal();
                }
            };
            document.addEventListener('keydown', handleEscapeKey);

            // Configurar botón confirmar
            if (btnConfirmar) {
                const nuevoBtnConfirmar = btnConfirmar.cloneNode(true);
                btnConfirmar.parentNode.replaceChild(nuevoBtnConfirmar, btnConfirmar);
                nuevoBtnConfirmar.addEventListener('click', function() {
                    // Crear formulario de eliminación
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';

                    // Token CSRF
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfToken);

                    // Método DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Enviar formulario
                    document.body.appendChild(form);
                    form.submit();

                    cerrarModal();
                });
            }

            // Configurar botón cancelar
            if (btnCancelar) {
                const nuevoBtnCancelar = btnCancelar.cloneNode(true);
                btnCancelar.parentNode.replaceChild(nuevoBtnCancelar, btnCancelar);
                nuevoBtnCancelar.addEventListener('click', cerrarModal);
            }

            // Cerrar con click en backdrop
            if (backdrop) {
                backdrop.addEventListener('click', cerrarModal);
            }
        }

        // Filtrado del lado del cliente (igual que en show.blade.php)
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const dataRows = document.querySelectorAll('.data-row');
            const showingCount = document.getElementById('showingCount');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    let visibleCount = 0;

                    dataRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (searchTerm === '' || text.includes(searchTerm)) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (showingCount) {
                        showingCount.textContent = visibleCount;
                    }
                });
            }

            // Auto-focus en búsqueda con un pequeño delay
            setTimeout(() => {
                if (searchInput && dataRows.length > 0) {
                    searchInput.focus();
                }
            }, 500);
        });
    </script>
</div>

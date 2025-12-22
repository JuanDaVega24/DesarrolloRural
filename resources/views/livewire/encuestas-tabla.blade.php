<div>
    {{-- === COMPACT STATS === --}}
    <div class="stats-bar">
        <div class="stat-compact">
            <i class="fas fa-database"></i>
            <div class="stat-compact-content">
                <div class="stat-compact-label">Total</div>
                <div class="stat-compact-value">{{ $totalRegistros }}</div>
            </div>
        </div>
        <div class="stat-compact">
            <i class="fas fa-filter"></i>
            <div class="stat-compact-content">
                <div class="stat-compact-label">Filtrados</div>
                <div class="stat-compact-value">{{ $encuestas->total() }}</div>
            </div>
        </div>
    </div>

    {{-- === MINIMAL COLLAPSIBLE FILTERS === --}}
    <div class="filter-minimal">
        <div class="filter-toggle" onclick="toggleFilters()">
            <div class="filter-toggle-title">
                <i class="fas fa-sliders-h"></i>
                <span>Filtros de Búsqueda</span>
                @if($fecha_encuesta || $nombre_identidad || $primer_apellido || $numero_documento || $vereda)
                    <span class="filter-badge">Activos</span>
                @endif
            </div>
            <i class="fas fa-chevron-down filter-toggle-icon"></i>
        </div>

        <div class="filter-body" id="filterBody">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Fecha</label>
                    <input type="date"
                           wire:model.live="fecha_encuesta"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Nombre</label>
                    <input type="text" 
                           wire:model.live.debounce.1200ms="nombre_identidad"
                           class="form-control" 
                           placeholder="Nombre">
                </div>
                <div class="form-group">
                    <label class="form-label">Apellido</label>
                    <input type="text" 
                           wire:model.live.debounce.1200ms="primer_apellido"
                           class="form-control" 
                           placeholder="Apellido">
                </div>
                <div class="form-group">
                    <label class="form-label">Documento</label>
                    <input type="text" 
                           wire:model.live.debounce.1200ms="numero_documento"
                           class="form-control" 
                           placeholder="N° Documento">
                </div>
                <div class="form-group">
                    <label class="form-label">Vereda</label>
                    <select wire:model.live="vereda" class="form-select">
                        <option value="">Todas</option>
                        @foreach($veredas as $v)
                            <option value="{{ $v->nombre }}">{{ $v->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="button" wire:click="limpiarFiltros" class="btn btn-clear">
                    <i class="fas fa-times me-1"></i>Limpiar
                </button>
            </div>
        </div>
    </div>

    {{-- === FOCUSED TABLE === --}}
    <div class="table-card">
        <div class="table-header">
            <h4><i class="fas fa-list me-2"></i>Listado de Caracterizaciones</h4>
            <span>{{ $encuestas->count() }} registros</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th wire:click="ordenar('id')" style="cursor: pointer;">
                            ID
                            @if($sort === 'id')
                                <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort" style="opacity: 0.3;"></i>
                            @endif
                        </th>
                        <th >
                            Encuestador
                           
                        </th>
                        <th wire:click="ordenar('fecha_encuesta')" style="cursor: pointer;">
                            Fecha
                            @if($sort === 'fecha_encuesta')
                                <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort" style="opacity: 0.3;"></i>
                            @endif
                        </th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th wire:click="ordenar('numero_documento')" style="cursor: pointer;">
                            Documento
                            @if($sort === 'numero_documento')
                                <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort" style="opacity: 0.3;"></i>
                            @endif
                        </th>
                        <th wire:click="ordenar('vereda')" style="cursor: pointer;">
                            Vereda
                            @if($sort === 'vereda')
                                <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort" style="opacity: 0.3;"></i>
                            @endif
                        </th>
                         @if(auth()->user()->hasRole('Administrador'))

                        <th class="text-center">Acciones</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                @forelse ($encuestas as $e)
                    <tr>
                        <td><strong>#{{ $e->id }}</strong></td>
                        <td>{{ $e->nombre_encuestador }}</td>
                        <td>{{ \Carbon\Carbon::parse($e->fecha_encuesta)->format('d/m/Y') }}</td>
                        <td>{{ $e->nombre_identidad }}</td>
                        <td>{{ $e->primer_apellido }}</td>
                        <td>{{ $e->numero_documento }}</td>
                        <td>
                            <span class="badge bg-success-subtle">
                                {{ $e->vereda?->nombre ?? 'Sin vereda' }}
                            </span>
                        </td>
                        <td>
                                                     @if(auth()->user()->hasRole('Administrador'))

                            <div class="actions-cell">
                                <a href="{{ route('encuestas.show', $e) }}" 
                                   class="btn btn-white btn-sm" 
                                   title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('encuestas.edit', $e) }}" 
                                   class="btn btn-white btn-sm" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('encuestas.destroy', $e) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Eliminar esta encuesta?')" 
                                      style="display: inline;">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No se encontraron Caracterizaciones</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- === PAGINATION === --}}
    <div class="mt-3">
        {{ $encuestas->links() }}
    </div>

    <script>
        function toggleFilters() {
            const toggle = document.querySelector('.filter-toggle');
            const body = document.getElementById('filterBody');
            
            toggle.classList.toggle('active');
            body.classList.toggle('active');
        }
    </script>
</div>

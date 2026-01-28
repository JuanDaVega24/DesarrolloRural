<x-app-layout>
    @vite(['resources/css/pages/formularios/index.css'])

    <div class="formularios-container">
        <div class="content-wrapper">

            {{-- === HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Formulario de Proyectos</h1>
                    <p>Proyectos creados manualmente que requieren completar el formulario</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                   
                </div>
            </div>

            {{-- === ALERTAS === --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Aquí puedes agregar un modal de éxito si lo deseas
                    console.log('Success:', "{{ session('success') }}");
                });
            </script>
            @endif

            {{-- === GRID DE PROYECTOS MANUALES === --}}
            <div class="proyectos-grid">
                @forelse($proyectosManuales as $proyecto)
                    <a href="{{ route('formularios.show', $proyecto) }}" class="proyecto-card">
                        <div class="card-header">
                           
                            <h3 class="card-title">{{ $proyecto->nombre }}</h3>
                            <span class="card-status">Pendiente</span>
                        </div>

                        <div class="card-content">
                            <div class="card-info">
                                <span class="info-label">Año:</span>
                                <span class="info-value">{{ $proyecto->ano }}</span>
                            </div>

                            <div class="card-info">
                                <span class="info-label">Creado:</span>
                                <span class="info-value">{{ $proyecto->created_at->format('d/m/Y') }}</span>
                            </div>

                            @if($proyecto->descripcion)
                                <div class="card-info" style="border: none; padding-bottom: 0;">
                                    <span class="info-label">Descripción:</span>
                                    <span class="info-value">{{ Str::limit($proyecto->descripcion, 50) }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="card-actions">
                            <span class="btn-card btn-primary-card">
                                <i class="fas fa-edit"></i>
                                Completar
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h3>No hay proyectos pendientes</h3>
                        <p class="text-muted">Todos los proyectos han sido completados o no hay proyectos manuales creados.</p>
                        
                    </div>
                @endforelse
            </div>

            {{-- === PAGINACIÓN === --}}
            @if($proyectosManuales->hasPages())
                <div class="pagination-wrapper">
                    {{ $proyectosManuales->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    

    <div class="py-4" >
        <div class="container">

            {{-- Alertas --}}
            @if (session('ok'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('ok') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
{{-- Header con título y botón --}}
<div class="mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-0" style="color: #2d5f3f; font-weight: 600;">
            <i class="bi bi-people-fill me-2"></i>Lista de Usuarios
        </h3>

        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Crear Usuario
        </a>
    </div>

    {{-- Botón volver debajo del título --}}
    <div class="mt-3">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary px-4">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
    </div>
</div>

           {{-- Buscador y filtros mejorado --}}
<div class="card shadow-sm border-0 mb-4 filtro-card">
    <div class="card-body">

        <form method="GET" action="{{ route('usuarios.index') }}">
            <div class="row g-3 align-items-end">

                {{-- Buscar por nombre --}}
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-success">
                        <i class="bi bi-search me-1"></i> Buscar nombre
                    </label>
                    <input type="text"
                           name="buscar"
                           class="form-control filtro-input"
                           placeholder="Escribe un nombre..."
                           value="{{ request('buscar') }}">
                </div>

                {{-- Filtro por Rol --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-success">
                        <i class="bi bi-people-fill me-1"></i> Rol del usuario
                    </label>
                    <select name="rol" class="form-select filtro-input">
                        <option value="">Todos los roles</option>
                        <option value="Administrador" {{ request('rol') == 'Administrador' ? 'selected' : '' }}>
                            Administrador
                        </option>
                        <option value="Tabulador" {{ request('rol') == 'Tabulador' ? 'selected' : '' }}>
                            Tabulador
                        </option>
                    </select>
                </div>

                {{-- Botón buscar --}}
                <div class="col-md-3 d-grid">
                    <button class="btn btn-primary">
                        <i class="bi bi-funnel-fill me-2"></i> Aplicar filtros
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>


            {{-- Card con tabla --}}
            <div class="card shadow-lg border-0">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #2d5f3f;">
                                <tr>
                                    <th class="text-black fw-semibold py-3">ID</th>
                                    <th class="text-black fw-semibold py-3">Nombre</th>
                                    <th class="text-black fw-semibold py-3">Email</th>
                                    <th class="text-black fw-semibold py-3">Rol</th>
                                    <th class="text-black fw-semibold py-3 text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($usuarios as $u)
                                    <tr>
                                        <td class="align-middle py-3">{{ $u->id }}</td>

                                        <td class="align-middle py-3">
                                            <strong>{{ $u->name }}</strong>
                                        </td>

                                        <td class="align-middle py-3">
                                            <i class="bi bi-envelope me-1 text-muted"></i>{{ $u->email }}
                                        </td>

                                        <td class="align-middle py-3">
                                            <span class="badge"
                                                  style="background-color: #1e5a7d;">
                                                {{ $u->role }}
                                            </span>
                                        </td>

                                        <td class="align-middle py-3 text-center">

                                            {{-- Botón Editar --}}
                                            <a href="{{ route('usuarios.edit', $u) }}"
                                               class="btn btn-sm px-3 me-2"
                                               style="background-color: #ffc107; color: #000; border: none;"
                                               title="Editar usuario">
                                                <i class="bi bi-pencil-square me-1"></i>Editar
                                            </a>

                                            {{-- Botón Eliminar --}}
                                            <form action="{{ route('usuarios.destroy', $u) }}"
                                                  method="POST"
                                                  class="d-inline-block"
                                                  onsubmit="return confirm('¿Está seguro de eliminar este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm px-3"
                                                    style="background-color: #dc3545; color: white; border: none;"
                                                    title="Eliminar usuario">
                                                    <i class="bi bi-trash-fill me-1"></i>Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                            <span class="text-muted fs-5">
                                                No hay usuarios registrados.
                                            </span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

            {{-- Paginación si aplica --}}
            @if(method_exists($usuarios, 'links'))
                <div class="mt-4">
                    {{ $usuarios->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Estilos personalizados --}}
    <style>
        :root {
            --verde: #4A7C2F;
            --verde-hover: #3d6625;
            --verde-claro: #E8F5E0;
            --azul: #3366CC;
            --azul-hover: #2952a3;
            --azul-claro: #E3ECFA;
            --negro: #1A1A1A;
            --gris: #666666;
            --gris-claro: #f8f9fa;
            --gris-medio: #e9ecef;
            --beige: #F8F6F3;
            --blanco: #FFFFFF;
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

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .card {
            transition: all 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            font-size: 0.85rem;
        }

        thead th {
            border: none !important;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

           /* Card del filtro */
    .filtro-card {
        border-left: 5px solid #2d5f3f;
        border-radius: 10px;
        background: #fdfdfd;
    }

    /* Inputs */
    .filtro-input {
        border-radius: 6px;
        border: 1px solid #cfd8d3;
        padding: 10px;
        transition: all 0.2s ease;
    }

    .filtro-input:focus {
        border-color: #2d5f3f;
        box-shadow: 0 0 0 0.15rem rgba(45, 95, 63, 0.25);
    }

    /* Botón */
    .filtro-btn {
        background-color: #2d5f3f;
        border-radius: 6px;
        padding: 10px 12px;
        transition: 0.3s ease;
    }

    .filtro-btn:hover {
        background-color: #244d34;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    </style>

</x-app-layout>

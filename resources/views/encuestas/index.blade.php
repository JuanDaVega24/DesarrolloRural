<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .encuestas-container {
            padding: 1.5rem 0;
        }

        .content-wrapper {
            max-width: 1600px;
            margin: auto;
            padding: 0 1.5rem;
        }

        /* === COMPACT HEADER === */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-content h1 {
            font-size: 2.3rem;
            font-weight: 800;
            color: var(--negro);
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header-content p {
            color: var(--gris);
            font-size: 0.875rem;
            margin: 0.25rem 0 0 0;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        /* === COMPACT BUTTONS === */
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

        .btn-secondary {
            background: white !important;
            border: 1px solid var(--gris-medio) !important;
            color: var(--gris) !important;
            padding: 0.5rem 1.25rem !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
        }

        .btn-secondary:hover {
            border-color: var(--gris) !important;
            color: var(--negro) !important;
        }

        /* === COMPACT ALERT === */
        .alert-success {
            background: var(--verde-claro);
            border: none;
            border-left: 3px solid var(--verde);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        /* === COMPACT STATS === */
        .stats-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .stat-compact {
            background: white;
            border-radius: 0.5rem;
            padding: 0.75rem 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .stat-compact i {
            font-size: 1.25rem;
            color: var(--verde);
            width: 2rem;
            height: 2rem;
            background: var(--verde-claro);
            border-radius: 0.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-compact-content {
            flex: 1;
        }

        .stat-compact-label {
            font-size: 0.75rem;
            color: var(--gris);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.1rem;
        }

        .stat-compact-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--negro);
            line-height: 1;
        }

        /* === MINIMAL FILTERS === */
        .filter-minimal {
            background: white;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            margin-bottom: 1rem;
        }

        .filter-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .filter-toggle-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: var(--negro);
            font-size: 0.95rem;
        }

        .filter-toggle-title i {
            color: var(--azul);
            font-size: 1.1rem;
        }

        .filter-badge {
            background: var(--azul-claro);
            color: var(--azul);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .filter-toggle-icon {
            color: var(--gris);
            transition: transform 0.3s ease;
        }

        .filter-toggle.active .filter-toggle-icon {
            transform: rotate(180deg);
        }

        .filter-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease;
        }

        .filter-body.active {
            max-height: 500px;
            padding-top: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--negro);
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-control, .form-select {
            border: 1px solid var(--gris-medio);
            border-radius: 0.4rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--azul);
            box-shadow: 0 0 0 3px var(--azul-claro);
            outline: none;
        }

        .filter-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--gris-claro);
            margin-top: 0.75rem;
        }

        .btn-clear {
            background: white;
            border: 1px solid var(--gris-medio);
            color: var(--gris);
            padding: 0.5rem 1.25rem;
            border-radius: 0.4rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-clear:hover {
            border-color: var(--gris);
            color: var(--negro);
        }

        /* === TABLE FOCUSED === */
        .table-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }

        .table-header {
            background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1rem;
        }

        .table-header span {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.35rem 0.75rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            margin: 0;
            width: 100%;
        }

        .table thead {
            background: var(--gris-claro);
        }

        .table thead th {
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: var(--negro);
            padding: 0.875rem 1rem;
            border: none;
            cursor: pointer;
            user-select: none;
        }

        .table thead th:hover {
            background: var(--gris-medio);
        }

        .table thead th a {
            color: var(--gris);
            text-decoration: none;
            margin-left: 0.25rem;
            font-size: 0.7rem;
        }

        .table thead th a:hover {
            color: var(--azul);
        }

        .table tbody td {
            padding: 0.875rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gris-claro);
            font-size: 0.9rem;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: var(--gris-claro);
        }

        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .bg-success-subtle {
            background-color: var(--verde-claro) !important;
            color: var(--verde) !important;
        }

        .actions-cell {
            display: flex;
            justify-content: center;
            gap: 0.35rem;
        }

        .btn-sm {
            padding: 0.4rem 0.65rem;
            border-radius: 0.35rem;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .btn-white {
            background: white;
            border: 1px solid var(--gris-medio);
            color: var(--gris);
        }

        .btn-white:hover {
            border-color: var(--azul);
            color: var(--azul);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #dc3545;
            border: none;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--gris-medio);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: var(--gris);
            font-size: 0.95rem;
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
            }

            .stats-bar {
                flex-direction: column;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="encuestas-container">
        <div class="content-wrapper">

            {{-- === COMPACT HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">Gestión de Caracterizaciones</h1>
                    <p class="page-subtitle">Administra y consulta las Caracterizaciones registradas en el sistema</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                    <a href="{{ route('encuestas.datos_personales') }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.9rem;">
                        <i class="fas fa-plus me-1"></i>Nueva Caracterizacion
                    </a>
                </div>
            </div>

            {{-- === SUCCESS ALERT === --}}
            @if(session('success'))
            <div class="alert alert-success d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            {{-- === LIVEWIRE COMPONENT === --}}
            @livewire('encuestas-tabla')

        </div>
    </div>

</x-app-layout>

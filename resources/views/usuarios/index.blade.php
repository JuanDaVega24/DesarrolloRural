<x-app-layout>
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
             --govcolor-black: #000000;
            --govcolor-cobalt: #0943B5;
            --govcolor-havelock-lue: #4672C8;
            --govcolor-matterhorn: #4C4C4C;
            --govcolor-white: #FFFFFF;
            --govcolor-green: #158361;
            --govcolor-red: #A80521;
            --govcolor-svg-cobalt: invert(20%) sepia(53%) saturate(3248%) hue-rotate(212deg) brightness(97%) contrast(107%);
            --govcolor-svg-green: invert(35%) sepia(93%) saturate(345%) hue-rotate(110deg) brightness(98%) contrast(98%);
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .usuarios-container {
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

        .alert-danger {
            background: #ffe5e5;
            border: none;
            border-left: 3px solid #dc3545;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        /* === MINIMAL FILTERS === */
        .filter-minimal {
            background: white;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            margin-bottom: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 2fr 1.5fr 1fr;
            gap: 0.75rem;
            align-items: end;
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
            font-size: 1rem;
        }

        .badge-admin {
            background-color: #ffffff !important;
            color: rgb(0, 0, 0) !important;
        }

        .badge-tabulador {
            background-color: #ffffff !important;
            color: rgb(0, 0, 0) !important;
        }

        .actions-cell {
            display: flex;
            justify-content: center;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 0.4rem 0.65rem;
            border-radius: 0.35rem;
            font-size: 0.8rem;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
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

            .form-row {
                grid-template-columns: 1fr;
            }

            .actions-cell {
                flex-direction: column;
            }

            .btn-sm {
                width: 100%;
            }
        }

        /* =================== MODAL GOV.CO =================== */
        :root {
            --govcolor-black: #000000;
            --govcolor-cobalt: #0943B5;
            --govcolor-havelock-lue: #4672C8;
            --govcolor-matterhorn: #4C4C4C;
            --govcolor-white: #FFFFFF;
            --govcolor-green: #158361;
            --govcolor-red: #A80521;
            --govcolor-svg-green: invert(35%) sepia(93%) saturate(345%) hue-rotate(110deg) brightness(98%) contrast(98%);
        }

        @font-face {
            font-family: 'Nunito Sans';
            src: url('../assets/fonts/Nunito_Sans/static/NunitoSans-Regular.ttf');
            font-weight: 400;
        }

        @font-face {
            font-family: 'Nunito Sans';
            src: url('../assets/fonts/Nunito_Sans/static/NunitoSans-Bold.ttf');
            font-weight: 700;
        }

        @font-face {
            font-family: 'Verdana-Regular';
            src: url('../assets/fonts/Verdana/static/Verdana-Regular.ttf');
        }

       .modal-container-govco {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            z-index: 1060 !important;
            width: 100% !important;
            height: 100% !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            outline: 0 !important;
            display: none !important;
        }

        .modal-container-govco.show {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .modal-backdrop-govco {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            z-index: 1055 !important;
            width: 100vw !important;
            height: 100vh !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }

        .modal-dialog-govco {
            width: 100%;
            position: relative;
            pointer-events: none;
            margin: 0 !important;
        }

        .modal-content-govco {
            height: 100%;
            box-shadow: 0px 3px 6px #00000029;
            border-radius: 0.313rem !important;
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: var(--govcolor-white);
            border: 1px solid var(--govcolor-black) !important;
            outline: 0;
        }

        .modal-header-govco {
            border-bottom: none !important;
            padding: 3.5rem 3.5rem 0px 3.5rem !important;
            justify-content: flex-end !important;
            display: flex;
        }

        .modal-body-govco {
            margin: 0px 3.5rem auto 3.5rem !important;
            padding: 0px !important;
        }

        .modal-title-govco {
            font-family: 'Nunito Sans';
            color: var(--govcolor-cobalt);
            font-size: 2.125rem;
            margin: 0px;
            margin-bottom: 0.625rem !important;
            font-weight: 700;
            line-height: 42px;
        }

        .modal-text-govco {
            font-family: 'Verdana-Regular';
            font-size: 15px;
            color: var(--govcolor-matterhorn);
            margin-bottom: 1.875rem;
            line-height: 1.375rem;
        }

        .modal-footer-govco {
            margin: 0px 4.688rem auto 4.688rem;
            padding-bottom: 3.5rem !important;
            border-top: none !important;
            display: flex;
        }

        .modal-buttons-govco {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .btn-modal-govco {
            font-family: 'Verdana-Regular';
            border-radius: 999px !important;
            background-color: var(--govcolor-cobalt) !important;
            border: 2px solid var(--govcolor-cobalt) !important;
            color: var(--govcolor-white) !important;
            padding: 12px 48px !important;
            font-size: 16px !important;
            height: 48px;
            min-width: 180px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: none !important;
        }

        .btn-modal-govco.btn-contorno {
            background-color: var(--govcolor-white) !important;
            color: var(--govcolor-cobalt) !important;
            border: 2px solid var(--govcolor-cobalt) !important;
        }

        .btn-modal-govco.btn-contorno:hover,
        .btn-modal-govco.btn-contorno:focus {
            background-color: var(--govcolor-havelock-lue) !important;
            color: var(--govcolor-white) !important;
        }

        .btn-modal-govco:hover {
            background-color: var(--govcolor-havelock-lue) !important;
            border-color: var(--govcolor-havelock-lue) !important;
        }

        .btn-modal-govco:focus {
            outline: 2px solid var(--govcolor-black) !important;
            outline-offset: 2.4px;
            background-color: var(--govcolor-havelock-lue) !important;
        }

        .center-elements-govco {
            text-align: center;
        }

        .modal-icon {
            margin-bottom: 16px;
        }

        .modal-text-center-govco {
            margin-bottom: 1.875rem;
            padding-right: 0px;
        }

        .success-govco {
            width: 100%;
            margin-bottom: 1.5rem !important;
            color: var(--govcolor-green);
        }

        .confirmation-govco {
            width: 100%;
            margin-bottom: 1.5rem !important;
            color: var(--govcolor-cobalt);
        }

        .govco-icon.govco-check-circle {
            background-image: url(/assets/icons/check-circle.svg);
            min-width: 4.375rem;
            min-height: 4.375rem;
            display: inline-block;
            background-repeat: no-repeat;
            background-size: 4.375rem 4.375rem;
            filter: var(--govcolor-svg-green);
        }

        .govco-icon.govco-info-circle {
            background-image: url(/assets/icons/info-circle.svg);
            min-width: 4.375rem;
            min-height: 4.375rem;
            display: inline-block;
            background-repeat: no-repeat;
            background-size: 4.375rem 4.375rem;
            filter: var(--govcolor-svg-cobalt);
        }

        @media(min-width:576px) {
            .modal-dialog-govco {
                max-width: 540px !important;
            }
        }

        @media(max-width:480px) {
            .modal-header-govco {
                padding: 1.5rem 1.5rem 0px 1.5rem !important;
            }

            .modal-body-govco {
                margin: 0px 1.5rem auto 1.5rem !important;
                padding-top: 1rem !important;
            }

            .modal-title-govco {
                font-size: 1.625rem;
                margin-bottom: 0.5rem !important;
            }

            .modal-text-govco {
                font-size: 0.875rem;
                margin-bottom: 1.5rem !important;
            }

            .modal-footer-govco {
                margin: 0px 1.5rem auto 1.5rem;
                padding-bottom: 3rem !important;
            }

            .modal-buttons-govco {
                display: flex;
                justify-content: center;
                gap: 1rem;
        }

        .btn-modal-govco {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
    </style>

    <div class="usuarios-container">
        <div class="content-wrapper">

            {{-- === COMPACT HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Gestión de Usuarios</h1>
                    <p>Administra y consulta los usuarios del sistema</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Crear Usuario
                    </a>
                </div>
            </div>

            {{-- === ALERTS === --}}
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

            {{-- === MINIMAL FILTERS === --}}
            <div class="filter-minimal">
                <form method="GET" action="{{ route('usuarios.index') }}">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-search me-1"></i> Buscar usuario
                            </label>
                            <input type="text"
                                   name="buscar"
                                   class="form-control"
                                   placeholder="Escribe un nombre..."
                                   value="{{ request('buscar') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-people-fill me-1"></i> Rol del usuario
                            </label>
                            <select name="rol" class="form-select">
                                <option value="">Todos los roles</option>
                                <option value="Administrador" {{ request('rol') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                <option value="Tabulador" {{ request('rol') == 'Tabulador' ? 'selected' : '' }}>Tabulador</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-funnel-fill me-1"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- === TABLE FOCUSED === --}}
            <div class="table-card">
                <div class="table-header">
                    <h4>
                        <i class="bi bi-people-fill me-2"></i>
                        Usuarios Registrados
                    </h4>
                    <span>{{ $usuarios->total() ?? count($usuarios) }} usuarios</span>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuarios as $u)
                                <tr>
                                    <td>{{ $u->id }}</td>
                                    <td>
                                        <strong>{{ $u->name }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-envelope me-1 text-muted"></i>{{ $u->email }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $u->role == 'Administrador' ? 'badge-admin' : 'badge-tabulador' }}">
                                            {{ $u->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions-cell">
                                            <a href="{{ route('usuarios.edit', $u) }}"
                                               class="btn bg-white btn-sm"
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
 
                                            <form method="POST" action="{{ route('usuarios.destroy', $u) }}" onsubmit="confirmarEliminacion(event, '{{ addslashes($u->name) }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <i class="bi bi-inbox display-4 d-block"></i>
                                        <p>No hay usuarios registrados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paginación --}}
            @if(method_exists($usuarios, 'links'))
                <div class="mt-3">
                    {{ $usuarios->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- === JAVASCRIPT === --}}
    <script>
        // Función para mostrar modal de éxito
        window.mostrarModalGovco = function(modalId, mensaje) {
            const modal = document.getElementById(modalId);
            const titulo = modal.querySelector('.modal-title-govco');
            const texto = modal.querySelector('.modal-text-govco');
            const btnAceptar = modal.querySelector('.btn-exito-aceptar');

            // Personalizar según el mensaje
            if (mensaje.includes('creado') || mensaje.includes('guardado') || mensaje.includes('registrado')) {
                titulo.textContent = '¡Usuario Creado!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('eliminado')) {
                titulo.textContent = '¡Usuario Eliminado!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('actualizado') || mensaje.includes('modificado')) {
                titulo.textContent = '¡Usuario Actualizado!';
                texto.textContent = mensaje;
            } else {
                titulo.textContent = '¡Operación Exitosa!';
                texto.textContent = mensaje;
            }

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            let backdrop = document.querySelector('.modal-backdrop-govco');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop-govco';
                document.body.appendChild(backdrop);
            }
            backdrop.style.display = 'block';

            const cerrarModal = function() {
                modal.classList.remove('show');
                document.body.style.overflow = '';
                if (backdrop) backdrop.style.display = 'none';
                document.removeEventListener('keydown', handleEscapeKey);
            };

            const handleEscapeKey = function(event) {
                if (event.key === 'Escape') cerrarModal();
            };
            document.addEventListener('keydown', handleEscapeKey);

            if (btnAceptar) {
                const nuevoBtn = btnAceptar.cloneNode(true);
                btnAceptar.parentNode.replaceChild(nuevoBtn, btnAceptar);
                nuevoBtn.addEventListener('click', cerrarModal);
            }

            if (backdrop) backdrop.addEventListener('click', cerrarModal);
        };

        // Función para confirmar eliminación
        function confirmarEliminacion(event, usuarioNombre) {
            event.preventDefault(); // Prevenir el envío del formulario
            const formParaEliminar = event.currentTarget; // El formulario que disparó el evento

            const modal = document.getElementById('exampleModalConfirmacion');
            const titulo = modal.querySelector('.modal-title-govco');
            const texto = modal.querySelector('.modal-text-govco');
            const btnConfirmar = document.querySelector('.btn-eliminar-confirmar');
            const btnCancelar = document.querySelector('.btn-eliminar-cancelar');

            // Personalizar contenido del modal
            titulo.textContent = '¿Eliminar Usuario?';
            texto.innerHTML = '¿Está seguro de que desea eliminar al usuario "<strong>' + usuarioNombre + '</strong>"? Esta acción no se puede deshacer.';

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
                    formParaEliminar.submit(); // Enviar el formulario original
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

        // Mostrar modal de éxito al cargar la página si hay mensaje de sesión
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('ok'))
                mostrarModalGovco('exampleModalExito', "{{ session('ok') }}");
            @endif
        });
    </script>

    {{-- === MODAL GOV.CO - Confirmación === --}}
    <div class="modal-container-govco" id="exampleModalConfirmacion" tabindex="-1" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-govco">
            <div class="modal-content modal-content-govco">
                <div class="modal-header modal-header-govco">
                    <button type="button" disabled class="btn-close" aria-label="cerrar"></button>
                </div>
                <div class="modal-body modal-body-govco center-elements-govco">
                    <div class="modal-icon">
                        <span class="govco-icon govco-info-circle"></span>
                    </div>
                    <h3 class="modal-title-govco confirmation-govco">
                        ¿Eliminar Usuario?
                    </h3>
                    <p class="modal-text-govco modal-text-center-govco">
                        ¿Está seguro de que desea eliminar este usuario? Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="modal-footer-govco">
                    <div class="modal-buttons-govco">
                        <button type="button" class="btn-modal-govco btn-eliminar-confirmar">
                            Eliminar
                        </button>
                        <button type="button" class="btn-modal-govco btn-contorno btn-eliminar-cancelar">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === MODAL GOV.CO - Éxito === --}}
    <div class="modal-container-govco" id="exampleModalExito" tabindex="-1" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-govco">
            <div class="modal-content modal-content-govco">
                <div class="modal-header modal-header-govco">
                    <button type="button" disabled class="btn-close" aria-label="cerrar"></button>
                </div>
                <div class="modal-body modal-body-govco center-elements-govco">
                    <div class="modal-icon">
                        <span class="govco-icon govco-check-circle"></span>
                    </div>
                    <h3 class="modal-title-govco success-govco">
                        ¡Operación Exitosa!
                    </h3>
                    <p class="modal-text-govco modal-text-center-govco">
                        La operación se realizó correctamente.
                    </p>
                </div>
                <div class="modal-footer-govco">
                    <div class="modal-buttons-govco">
                        <button type="button" class="btn-modal-govco btn-exito-aceptar">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

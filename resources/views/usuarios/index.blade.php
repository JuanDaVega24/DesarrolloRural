<x-app-layout>
    @vite(['resources/css/pages/usuarios/index.css'])

    
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

                                            <button class="btn btn-danger btn-sm"
                                                    title="Eliminar"
                                                    onclick="confirmarEliminacion('{{ route('usuarios.destroy', $u) }}', '{{ $u->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
        function confirmarEliminacion(url, usuarioNombre) {
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

        // Mostrar modal de éxito al cargar la página si hay mensaje de sesión
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('ok'))
                mostrarModalGovco('exampleModalExito', "{{ session('ok') }}");
            @endif
        });
    </script>

    {{-- === MODAL GOV.CO - Confirmación === --}}
    <div class="container-modal-govco">
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
    </div>

    {{-- === MODAL GOV.CO - Éxito === --}}
    <div class="container-modal-govco">
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
    </div>

</x-app-layout>

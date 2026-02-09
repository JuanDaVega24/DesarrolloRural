<x-app-layout>

    @vite(['resources/css/pages/usuarios/edit.css'])

    <div class="d-flex justify-content-center mt-4 mb-5">

        <div class="login-box" style="max-width:520px; width:100%;">
               <div class="escudo">
                    <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo">
                </div>
            <h2 class="login-title">Editar Usuario</h2>
            <p class="login-subtitle">Modificar la información del usuario</p>

            {{-- Mensajes ocultos para el toast --}}
            @if ($errors->any())
                <div class="alert alert-danger d-none">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('usuarios.update', $usuario) }}" method="POST" id="editUserForm">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" name="name" class="form-control"
                           placeholder="Nombre completo"
                           value="{{ old('name', $usuario->name) }}" required>
                </div>

                {{-- Email --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control"
                           placeholder="Correo electrónico"
                           value="{{ old('email', $usuario->email) }}" required>
                </div>

                {{-- Nueva Contraseña --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control"
                           placeholder="Nueva contraseña (opcional)">
                </div>

                {{-- Rol --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-id-badge input-icon"></i>
                    <select name="role" class="form-control" required>
                        <option value="">Seleccione el rol</option>
                        <option value="Administrador" {{ old('role', $usuario->role) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Tabulador" {{ old('role', $usuario->role) == 'Tabulador' ? 'selected' : '' }}>Tabulador</option>
                    </select>
                </div>

                <div class="mb-3 position-relative" id="permisoCaracterizacionContainer">
                    <i class="fa-solid fa-shield input-icon"></i>
                    <select name="caracterizacion_permiso" class="form-control" required>
                        <option value="0" {{ old('caracterizacion_permiso', (int)($usuario->caracterizacion_permiso)) === 0 ? 'selected' : '' }}>No tiene permisos</option>
                        <option value="1" {{ old('caracterizacion_permiso', (int)($usuario->caracterizacion_permiso)) === 1 ? 'selected' : '' }}>Tiene permiso de Caracterización</option>
                    </select>
                </div>

                {{-- Botón Actualizar --}}
                <button class="btn-login mt-3">
                    <i class="fa-solid fa-save me-2"></i> Actualizar Usuario
                </button>

                {{-- Botón Cancelar --}}
                <a href="{{ route('usuarios.index') }}"
                   class="btn-cancelar mt-3 d-block text-center"
                   style="background:#6c757d;">
                    <i class="fa-solid fa-arrow-left me-2"></i> Cancelar
                </a>

            </form>
        </div>
    </div>

    <script>
        (function(){
            const roleSelect = document.querySelector('select[name="role"]');
            const permisoContainer = document.getElementById('permisoCaracterizacionContainer');
            const toggle = () => {
                const isAdmin = roleSelect.value === 'Administrador';
                permisoContainer.style.display = isAdmin ? 'none' : '';
                const permisoSelect = permisoContainer.querySelector('select[name="caracterizacion_permiso"]');
                if (isAdmin) {
                    permisoSelect.value = '0';
                }
            };
            if (roleSelect && permisoContainer) {
                toggle();
                roleSelect.addEventListener('change', toggle);
            }
        })();
    </script>

</x-app-layout>

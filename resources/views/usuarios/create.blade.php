<x-app-layout>
  
@vite(['resources/css/pages/usuarios/create.css'])

    <div class="d-flex justify-content-center mt-4 mb-5">

        <div class="login-box" style="max-width:520px; width:100%;">
               <div class="escudo">
                    <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo">
                </div>
            <h2 class="login-title">Crear Usuario</h2>
            <p class="login-subtitle">Registrar un nuevo usuario en el sistema</p>

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

            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                {{-- Nombre --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" name="name" class="form-control"
                           placeholder="Nombre completo"
                           value="{{ old('name') }}" required>
                </div>

                {{-- Email --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control"
                           placeholder="Correo electrónico"
                           value="{{ old('email') }}" required>
                </div>

                {{-- Contraseña --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control"
                           placeholder="Contraseña" required>
                </div>

                {{-- Rol --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-id-badge input-icon"></i>
                    <select name="role" class="form-control" required>
                        <option value="">Seleccione el rol</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Tabulador">Tabulador</option>
                    </select>
                </div>
                
                <div class="mb-3 position-relative" id="permisoCaracterizacionContainer">
                    <i class="fa-solid fa-shield input-icon"></i>
                    <select name="caracterizacion_permiso" class="form-control" required>
                        <option value="0">No tiene permisos</option>
                        <option value="1">Tiene permiso de Caracterización</option>
                    </select>
                </div>

                {{-- Botón Crear --}}
                <button class="btn-login mt-3">
                    <i class="fa-solid fa-plus me-2"></i> Crear Usuario
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

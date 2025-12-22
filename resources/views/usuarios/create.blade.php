<x-app-layout>
  
    <div class="d-flex justify-content-center mt-4 mb-5">

        <div class="login-box" style="max-width:520px; width:100%;">
               <div class="escudo">
                    <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo">
                </div>
            <h2 class="login-title">Crear Usuario</h2>
            <p class="login-subtitle">Registrar un nuevo usuario en el sistema</p>

            {{-- Mensajes --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-2">
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

                {{-- Botón Crear --}}
                <button class="btn-login mt-3">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Usuario
                </button>

                {{-- Botón Cancelar --}}
                <a href="{{ route('usuarios.index') }}"
                   class="btn-primary mt-3 d-block text-center"
                   style="background:#6c757d;">
                    <i class="fa-solid fa-arrow-left me-2"></i> Cancelar
                </a>

            </form>
        </div>
    </div>

    {{-- ESTILOS DEL LOGIN REUSADOS --}}
    <style>
        :root {
            --verde-principal: #4A7C2F;
            --azul-govco: #3366CC;
            --gris-texto: #333333;
             --verde: #4A7C2F;
            --verde-hover: #3d6625;
        }

        .login-box {
            background: white;
            padding: 40px 35px;
            border-radius: 18px;
            box-shadow: 0 8px 35px rgba(0,0,0,0.15);
            animation: floatIn 0.6s ease-out;
        }

        @keyframes floatIn {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .login-title {
            text-align: center;
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--gris-texto);
            margin-bottom: 8px;
        }

        .login-subtitle {
            text-align: center;
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 45px;
            border: 2px solid #ddd;
            transition: .3s;
        }

        .form-control:focus {
            border-color: var(--azul-govco);
            box-shadow: 0 0 10px rgba(51, 102, 204, 0.3);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: var(--azul-govco);
            font-size: 1.1rem;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%) !important;
            border: none;
            padding: 10px;
            color: white;
            font-weight: 500;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: .3s;
        }

        .btn-login:hover {
            background: #2f5a1f;
            transform: translateY(-3px);
            box-shadow: 0 7px 18px rgba(0,0,0,0.2);
        }
        .btn-primary {
            width: 100%;
            border: none !important;
            color: white !important;
            padding: 0.5rem 1.2rem !important;
            border-radius: 0.5rem !important;
            font-weight: 700 !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
        }

        .btn-primary:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(74, 124, 47, 0.25) !important;
        }
        .escudo {
            width: 150px;
            height: 120px;
            margin: 0 auto;
                margin-bottom: 2%;


            display: flex;
            justify-content: center;
            align-items: center;

            /* ❗ SIN FONDO, SIN BORDES, SIN SOMBRA */
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }

            .escudo img {
            width: 180px;
            height: 150px;
            object-fit: contain;
            background: transparent !important;
        }

        

    </style>

</x-app-layout>

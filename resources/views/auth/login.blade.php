<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Alcaldía de Bucaramanga</title>

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Work Sans Font -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --verde-principal: #4A7C2F;
            --azul-govco: #3366CC;
            --gris-texto: #333333;
            --gris-claro: #F5F5F5;
        }

        body {
            background: linear-gradient(135deg, #f0f4f8, #ffffff);
            font-family: 'Work Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 60px;
        }

        .login-box {
            margin-top: 50px;
            width: 100%;
            max-width: 420px;
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
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--gris-texto);
            margin-bottom: 10px;
        }

        .login-subtitle {
            text-align: center;
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 30px;
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
            left: 12px;
            transform: translateY(-50%);
            color: var(--azul-govco);
            font-size: 1rem;
            opacity: 0.9;
        }

        .btn-login {
            width: 100%;
            background: var(--verde-principal);
            border: none;
            padding: 13px;
            color: white;
            font-weight: 700;
            border-radius: 12px;
            font-size: 1.1rem;
            margin-top: 10px;
            transition: .3s;
        }

        .btn-login:hover {
            background: #2f5a1f;
            transform: translateY(-3px);
            box-shadow: 0 7px 18px rgba(0,0,0,0.2);
        }

        .forgot-link {
            text-decoration: none;
            color: var(--azul-govco);
            font-weight: 600;
            font-size: 0.9rem;
            transition: .2s;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .gov-header {
            background-color: var(--azul-govco);
            padding: 12px 0;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .gov-header .gov-logo {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
        }

        .alcaldia-header {
            margin-top: 20px;
            text-align: center;
        }

        .escudo {
            width: 150px;
            height: 120px;
            margin: 0 auto;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 5rem;
        }
    </style>
</head>
<body>

<!-- GOV.CO HEADER -->
<div class="gov-header">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="gov-logo">
            <i class="fas fa-landmark"></i> GOV.CO
        </span>
    </div>
</div>

<div class="login-box">

    <div class="alcaldia-header">
        <div class="escudo">
    <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Escudo" style="width:150px; height:120px;">
</div>
    </div>

    <h2 class="login-title">Iniciar Sesión</h2>
    <p class="login-subtitle">Accede al Sistema de Información</p>

    <!-- Formulario Jetstream/Login -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3 position-relative">
            <i class="fa-solid fa-user input-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required autofocus>
        </div>

        <!-- Password -->
        <div class="mb-3 position-relative">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        </div>

        <button type="submit" class="btn-login">
            <i class="fa-solid fa-right-to-bracket me-2"></i> Entrar
        </button>

        @if (Route::has('password.request'))
        <div class="text-center mt-3">
            <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
        </div>
        @endif

    </form>
    @if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

</div>

</body>
</html>

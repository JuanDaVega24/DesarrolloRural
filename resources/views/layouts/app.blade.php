<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Desarrollo Rural') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- App CSS / JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Modern UI -->
    <style>
        :root {
            --verde-principal: #4A7C2F;
            --azul-govco: #3366CC;
            -- gris-suave: #f5f7fa;
            --gris-texto: #333333;
            --radius-lg: 14px;
        }

        body { 
            background-color: var(--gris-suave);
            font-family: 'Work Sans', sans-serif;
            color: var(--gris-texto);
        }

        /* ============================ */
        /*   TOP GOV HEADER MODERNO    */
        /* ============================ */
        .gov-header {
            background: linear-gradient(90deg, var(--azul-govco), #274a99);
            padding: 12px 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .gov-logo {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: .2s;
        }

        .gov-logo:hover {
            opacity: .8;
        }

        /* ============================ */
        /*   ALCALDIA HEADER MODERNO   */
        /* ============================ */
        .alcaldia-header {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid #d9d9d9;
            box-shadow: 0 2px 4px rgba(0,0,0,0.07);
        }

        .escudo {
            width: 75px;
            height: 75px;
            border-radius: 20px;
            background: linear-gradient(140deg, var(--verde-principal), var(--azul-govco));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 3px 7px rgba(0, 0, 0, 0.25);
        }

        .alcaldia-name {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0;
            color: #2b2b2b;
            letter-spacing: .5px;
        }

        .alcaldia-title {
            margin: 0;
            font-size: .9rem;
            text-transform: uppercase;
            color: #666;
        }

        /* ==================================== */
        /*         NAV + PERFIL MODERNO         */
        /* ==================================== */
        nav.navbar {
            border-bottom: 2px solid var(--verde-principal);
            background: white !important;
            padding: 12px 0;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }

        /* Dropdown moderno Jetstream */
        .dropdown-menu {
            border-radius: var(--radius-lg);
            padding: 10px;
            border: 1px solid #ececec;
            box-shadow: 0 4px 10px rgba(0,0,0,.08);
        }

        .dropdown-item:hover {
            background-color: var(--gris-suave);
        }

        /* ============================ */
        /*       CONTENIDO PÁGINA      */
        /* ============================ */
        main {
            padding-top: 25px;
        }

        header.bg-white {
            border-radius: var(--radius-lg);
            background: white !important;
            padding: 15px 0;
            margin-bottom: 20px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.06);
        }

        /* ============================ */
        /*    INPUTS EN MAYÚSCULAS     */
        /* ============================ */
        input[type="text"],
        textarea {
            text-transform: uppercase !important;
        }

        /* ============================ */
        /*      BOTONES PRIMARIOS      */
        /* ============================ */
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
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
        }

        .btn-primary:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(74, 124, 47, 0.25) !important;
        }
    </style>
</head>

<body class="font-sans antialiased">

    {{-- Banner Jetstream --}}
    <x-banner />

    <!-- GOV.CO Header -->
    <div class="gov-header shadow-sm">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
            <a href="#" class="gov-logo">
                <i class="fas fa-landmark"></i> GOV.CO
            </a>
        </div>
    </div>

    <!-- Alcaldía Header -->
    <div class="alcaldia-header">
        
                    @livewire('navigation-menu')

    </div>

    <!-- NAV Jetstream (perfil y logout) -->
    

    <!-- Page Heading -->
    @if (isset($header))
    <header class="bg-white shadow-sm container">
        {{ $header }}
    </header>
    @endif

    <!-- Page Content -->
    <main>
        <div class="container">
            {{ $slot }}
        </div>
    </main>

    @stack('modals')
    @livewireScripts

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <!-- Script para convertir texto a mayúsculas -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para convertir inputs de texto a mayúsculas
            function convertToUppercase(event) {
                const input = event.target;
                if (input.tagName === 'INPUT' && (input.type === 'text' || input.type === 'textarea')) {
                    const start = input.selectionStart;
                    const end = input.selectionEnd;
                    input.value = input.value.toUpperCase();
                    // Restaurar la posición del cursor
                    input.setSelectionRange(start, end);
                }
            }

            // Aplicar a todos los inputs existentes y futuros
            document.addEventListener('input', convertToUppercase);
            document.addEventListener('change', convertToUppercase);

            // También convertir al pegar texto
            document.addEventListener('paste', function(event) {
                const input = event.target;
                if (input.tagName === 'INPUT' && (input.type === 'text' || input.type === 'textarea')) {
                    setTimeout(() => {
                        input.value = input.value.toUpperCase();
                    }, 0);
                }
            });
        });
    </script>

</body>
</html>

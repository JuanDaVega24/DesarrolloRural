<x-app-layout>
   

    {{-- Estilos en línea para asegurar que se carguen --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --verde: #4A7C2F;
            --verde-claro: #E8F5E0;
            --azul: #3366CC;
            --azul-claro: #E3ECFA;
            --negro: #1A1A1A;
            --gris: #666666;
            --beige: #F8F6F3;
            --blanco: #FFFFFF;
        }

        .dashboard-container {
            background-color: var(--beige);
            min-height: 100vh;
        }

        .modules-section {
            padding: 1rem 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--negro);
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
            text-align: center;
        }

        .section-subtitle {
            color: var(--gris);
            font-size: 1.125rem;
            font-weight: 400;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.5rem;
        }
        .card-icon img.icon-img {

    object-fit: contain;
    pointer-events: none;
}

/* ❗ elimina el cuadro de fondo */
.card-icon.verde,
.card-icon.azul {
    background-color: transparent !important;
    box-shadow: none !important;
}


        @media (min-width: 768px) {
            .modules-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .module-card {
            background: var(--blanco);
            border-radius: 1rem;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
        }

        .module-card:not(.module-card-disabled):hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 0, 0, 0.08);
        }

        .module-card-disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }

        .card-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 0;
            transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-accent.verde {
            background: linear-gradient(180deg, var(--verde), #6ba349);
        }

        .card-accent.azul {
            background: linear-gradient(180deg, var(--azul), #5B8FE5);
        }

        .module-card:not(.module-card-disabled):hover .card-accent {
            height: 100%;
        }

        .card-icon {
            width: 4rem;
            height: 5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .card-icon.verde {
            background-color: var(--verde-claro);
            color: var(--verde);
        }

        .card-icon.azul {
            background-color: var(--azul-claro);
            color: var(--azul);
        }

        .module-card:not(.module-card-disabled):hover .card-icon {
            transform: scale(1.05);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--negro);
            margin-bottom: 0.75rem;
            letter-spacing: -0.3px;
        }

        .card-description {
            color: var(--gris);
            font-size: 0.9125rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .card-footer {
            margin-top: auto;
         display: flex;
    justify-content: flex-end;  /* 👉 Envía el contenido hacia la derecha */
    padding-top: 10px;
    
    
        }

        .card-link {
            color: var(--azul);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: gap 0.3s ease;
            font-size: 0.9375rem;
                        background: linear-gradient(180deg, var(--verde), #6ba349);
            color: var(--verde);

            
        }

        .card-link.disabled {
            color: var(--gris);
        }

        .module-card:not(.module-card-disabled):hover .card-link {
            gap: 0.75rem;
        }

        .card-link i {
            font-size: 0.875rem;
            transition: transform 0.3s ease;
            
        }

        .module-card:not(.module-card-disabled):hover .card-link i {
            transform: translateX(2px);
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 1.875rem;
            }
            
            .section-subtitle {
                font-size: 1rem;
            }

            .module-card {
                padding: 1.5rem;
            }

            .card-icon {
                width: 3.5rem;
                height: 3.5rem;
                font-size: 1.5rem;
            }
   



            .card-title {
                font-size: 1.25rem;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .module-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .module-card:nth-child(1) { animation-delay: 0.1s; }
        .module-card:nth-child(2) { animation-delay: 0.15s; }
        .module-card:nth-child(3) { animation-delay: 0.2s; }
        .module-card:nth-child(4) { animation-delay: 0.25s; }

           .alcaldia-header {
            margin-top: 1px;
            text-align: center;
        }

         .escudo {
            width: 300px;
            height: 150px;
            margin: 0 auto;
                margin-bottom: 1%;


            display: flex;
            justify-content: center;
            align-items: center;

            /* ❗ SIN FONDO, SIN BORDES, SIN SOMBRA */
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }

            .escudo img {
            width: 200px;
            height: 160px;
            object-fit: contain;
            background: transparent !important;
        }


    </style>

    <div class="dashboard-container py-12" style="background-color: #f8f9fa;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="modules-section">
                <div class="text-center mb-12">
                       <div class="escudo">
                    <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo">
                </div>

                    <h2 class="section-title">Módulos del Sistema</h2>
                    <p class="section-subtitle">Accede a las diferentes herramientas de gestión</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4"  >
                    {{-- Caracterización --}}
                   <a href="{{ route('encuestas.index') }}" class="module-card group">
    <div class="card-accent verde"></div>

    <div class="card-icon verde">
    <img src="{{ asset('images/icon-caracterizacion.png') }}" alt="Icono Caracterización" class="icon-img">
    </div>

    <h3 class="card-title">Caracterización</h3>

    <p class="card-description">
        Sistema integral para el registro, análisis y gestión de datos demográficos y socioeconómicos de la población bucaramanguesa.
    </p>

    <div class="card-footer">
        <span class="card-link btn btn-info px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
</a>
 @if(auth()->user()->hasRole('Administrador'))

                    {{-- Proyectos Productivos --}}
                    <a href="{{ route('proyectos.index') }}" class="module-card group">
                        <div class="card-accent azul"></div>
                      <div class="card-icon verde">
    <img src="{{ asset('images/icono-proyectos.png') }}" alt="Icono Caracterización" class="icon-img">
    </div>
                        <h3 class="card-title">Proyectos Productivos</h3>
                        <p class="card-description">
Iniciativas planificadas que buscan desarrollar actividades agrícolas, pecuarias o mixtas con el objetivo de generar ingresos, mejorar las condiciones de vida de los productores y promover el desarrollo rural.                        </p>
                            <div class="card-footer ">
        <span class="card-link btn btn-info px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
                    </a>

                    {{-- Filtros y Búsquedas --}}
                    <div class="module-card module-card-disabled group">
                        <div class="card-accent verde"></div>
                        <div class="card-icon verde">
                            <i class="fas fa-filter"></i>
                        </div>
                        <h3 class="card-title">Filtros y Búsquedas Avanzadas</h3>
                        <p class="card-description">
                            Herramientas especializadas para búsqueda y segmentación de información con consultas personalizadas.
                        </p>
                           <div class="card-footer ">
        <span class="card-link btn btn-info px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
                    </div>

                    {{-- Reportes e Informes --}}
                    <div class="module-card module-card-disabled group">
                        <div class="card-accent azul"></div>
                        <div class="card-icon azul">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="card-title">Reportes e Informes</h3>
                        <p class="card-description">
                            Generación automática de documentos, informes ejecutivos y análisis estadísticos configurables.
                        </p>
                            <div class="card-footer ">
        <span class="card-link btn btn-info px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
               
                    </div>
                     
                         {{-- CRUD de Usuarios --}}
                      
<a href="{{ route('usuarios.index') }}" class="module-card group">
    <div class="card-accent azul"></div>
       <div class="card-icon verde">
    <img src="{{ asset('images/icono-usuarios.png') }}" alt="Icono Caracterización" class="icon-img">
    </div>
    <h3 class="card-title">Gestión de Usuarios</h3>
    <p class="card-description">
        Administrar usuarios registrados: creación, edición, eliminación y control de acceso.
    </p>
        <div class="card-footer ">
        <span class="card-link btn btn-info px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
</a>
@endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --verde: #4A7C2F;
            --verde-claro: #E8F5E0;
            --azul: #3366CC;
            --azul-claro: #E3ECFA;
            --negro: #1A1A1A;
            --gris: #666666;
            --beige: #F8F6F3;
            --blanco: #FFFFFF;
        }

        body {
            background-color: var(--beige);
        }

        .modules-section {
            padding: 2rem 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--negro);
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
        }

        .section-subtitle {
            color: var(--gris);
            font-size: 1.125rem;
            font-weight: 400;
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .gap-6 {
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .module-card {
            background: var(--blanco);
            border-radius: 1rem;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .module-card:not(.module-card-disabled):hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 0, 0, 0.08);
        }

        .module-card-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .card-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 0;
            transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-accent.verde {
            background: linear-gradient(180deg, var(--verde), #6BA349);
        }

        .card-accent.azul {
            background: linear-gradient(180deg, var(--azul), #5B8FE5);
        }

        .module-card:not(.module-card-disabled):hover .card-accent {
            height: 100%;
        }

        .card-icon {
            width: 4rem;
            height: 5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .card-icon.verde {
            background-color: var(--verde-claro);
            color: var(--verde);
        }

        .card-icon.azul {
            background-color: var(--azul-claro);
            color: var(--azul);
        }

        .module-card:not(.module-card-disabled):hover .card-icon {
            transform: scale(1.05);
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--negro);
            margin-bottom: 0.75rem;
            letter-spacing: -0.3px;
        }

        .card-description {
            color: var(--gris);
            font-size: 0.8125rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .card-footer {
            margin-top: auto;
        }

        .card-link {
            color: var(--azul);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: gap 0.3s ease;
            font-size: 0.9375rem;
        }

        .card-link.disabled {
            color: var(--gris);
        }

        .module-card:not(.module-card-disabled):hover .card-link {
            gap: 0.75rem;
        }

        .card-link i {
            font-size: 0.875rem;
            transition: transform 0.3s ease;
        }

        .module-card:not(.module-card-disabled):hover .card-link i {
            transform: translateX(2px);
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 1.875rem;
            }
            
            .section-subtitle {
                font-size: 1rem;
            }

            .module-card {
                padding: 1.5rem;
            }

            .card-icon {
                width: 3.5rem;
                height: 3.5rem;
                font-size: 1.5rem;
            }

            .card-title {
                font-size: 1.25rem;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .module-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .module-card:nth-child(1) { animation-delay: 0.1s; }
        .module-card:nth-child(2) { animation-delay: 0.15s; }
        .module-card:nth-child(3) { animation-delay: 0.2s; }
        .module-card:nth-child(4) { animation-delay: 0.25s; }
    </style>
    @endpush
</x-app-layout>

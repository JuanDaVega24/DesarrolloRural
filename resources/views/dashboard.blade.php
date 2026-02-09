<x-app-layout>

@vite(['resources/css/pages/dashboard.css'])




    <div class="dashboard-container py-12" style="background-color: #f8f9fa;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                       <div class="escudo">
                    <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo del Desarrollo del campo">
                </div>

                    <h2 class="section-title">Sistema de Información</h2>
                                        <h2 class="section-subtitle">Programa Sector Agricultura y Desarrollo Rural</h2>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4"  >
                    

                    {{-- Caracterización --}}
                    @php
                        $isAdmin = auth()->user()->hasRole('Administrador');
                        $isTabulador = auth()->user()->hasRole('Tabulador');
                        $hasPermiso = (auth()->user()->caracterizacion_permiso ?? false);
                        $goToFormulario = $isTabulador && $hasPermiso;
                    @endphp
                    @if($goToFormulario)
                   <a href="{{ route('caracterizaciones.formulario.show') }}" class="module-card group">
    <div class="card-accent verde"></div>

    <div class="card-icon verde">
    <img src="{{ asset('images/icono-caracterizacion.png') }}" alt="Icono Caracterización" class="icon-img">
    </div>

    <h3 class="card-title">Caracterización</h3>

    <p class="card-description">
        Sistema integral para el registro, análisis y gestión de datos demográficos y socioeconómicos de la población bucaramanguesa.
    </p>

    <div class="card-footer">
        <span class="card-link btn px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
</a>
                    @elseif($isAdmin)
                   <a href="{{ route('caracterizaciones.index') }}" class="module-card group">
    <div class="card-accent verde"></div>

    <div class="card-icon verde">
    <img src="{{ asset('images/icono-caracterizacion.png') }}" alt="Icono Caracterización" class="icon-img">
    </div>

    <h3 class="card-title">Caracterización</h3>

    <p class="card-description">
        Sistema integral para el registro, análisis y gestión de datos demográficos y socioeconómicos de la población bucaramanguesa.
    </p>

    <div class="card-footer">
        <span class="card-link btn px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
</a>
                    @else
                    <div class="module-card group" aria-disabled="true" title="Sin permisos para acceder" style="pointer-events: none; opacity: 0.6; filter: grayscale(0.3);">
    <div class="card-accent verde"></div>

    <div class="card-icon verde">
    <img src="{{ asset('images/icono-caracterizacion.png') }}" alt="Icono Caracterización" class="icon-img">
    </div>

    <h3 class="card-title">Caracterización</h3>

    <p class="card-description">
        Sistema integral para el registro, análisis y gestión de datos demográficos y socioeconómicos de la población bucaramanguesa.
    </p>

    <div class="card-footer">
        <span class="card-link btn px-4 py-2 text-white  ">
            Sin permisos
            <i class="fas fa-lock ms-2"></i>
        </span>
    </div>
</div>
                    @endif

                    {{-- Formulario de Proyectos Productivos --}}
                    <a href="{{ route('formularios.index') }}" class="module-card group">
                        <div class="card-accent verde"></div>
                        <div class="card-icon personalizado verde">
    <img src="{{ asset('images/icono-formulario.png') }}" alt="Icono Formularios" class="icon-img">
                        </div>
                        <h3 class="card-title">Formulario de Proyectos</h3>
                        <p class="card-description">
                            Crear proyectos productivos individualmente completando un formulario con los campos requeridos.
                        </p>
                        <div class="card-footer">
                            <span class="card-link btn px-4 py-2 text-white">
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
        <span class="card-link btn px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
                    </a>

                    {{-- Filtros y Búsquedas --}}
                    <a href="{{ route('filtros.index') }}" class="module-card group">
                        <div class="card-accent verde"></div>
                        <div class="card-icon personalizado verde">
    <img src="{{ asset('images/icono-reportes.png') }}" alt="Icono Reportes" class="icon-img">
                        </div>
                        <h3 class="card-title">Filtros y Búsquedas Avanzadas</h3>
                        <p class="card-description">
                            Herramientas especializadas para búsqueda y segmentación de información con consultas personalizadas.
                        </p>
                           <div class="card-footer ">
        <span class="card-link btn px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
                    </a>

                    {{-- Reportes e Informes --}}
                    <a href="{{ route('reportes.index') }}" class="module-card group">
                        <div class="card-accent azul"></div>
                        <div class="card-icon azul">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="card-title">Reportes y Estadisticas</h3>
                        <p class="card-description">
                            Generación automática de estadisitcas basadas en las caracterizaciones y proyectos productivos.                        </p>
                            <div class="card-footer ">
        <span class="card-link btn px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>

                    </a>
                     
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
        <span class="card-link btn px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
</a>
@endif
                </div>
            
        </div>

       




</x-app-layout>

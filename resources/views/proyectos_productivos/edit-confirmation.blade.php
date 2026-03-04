<x-app-layout>


    <div class="d-flex justify-content-center mt-4 mb-5">

        <div class="login-box" style="max-width:600px; width:100%;">

            {{-- Logo --}}
            <div class="escudo">
                <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo">
            </div>

            <h2 class="login-title">Proyecto Actualizado</h2>
            <p class="login-subtitle">Configuración del proyecto completada</p>

            {{-- Mensaje de éxito --}}
            <div class="alert alert-success mt-3">
                <i class="fa-solid fa-check-circle me-2"></i>
                <strong>¡Proyecto actualizado exitosamente!</strong>
            </div>

            {{-- Información del proyecto --}}
            <div class="mb-4 p-3 bg-light rounded">
                <h5 class="mb-2">Detalles del Proyecto:</h5>
                <p><strong>Nombre:</strong> {{ $proyecto->nombre }}</p>
                <p><strong>Año:</strong> {{ $proyecto->ano }}</p>
                <p><strong>Método de Creación:</strong> 
                    <span class="badge bg-success">{{ $proyecto->origen === 'manual' ? 'Manual' : 'Excel' }}</span>
                </p>
            </div>

            {{-- Acciones según el método --}}
            @if($proyecto->origen === 'manual')
                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    <strong>Próximo paso:</strong> Configure su formulario personalizado con las preguntas específicas para este proyecto.
                </div>

                {{-- Botón para ir al constructor --}}
                <a href="{{ route('proyectos.constructor', $proyecto) }}"
                   class="btn-login w-100 mb-3">
                    <i class="fa-solid fa-cogs me-2"></i> Configurar Formulario Personalizado
                </a>
            @else
                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    <strong>Próximo paso:</strong> Suba su archivo Excel con los datos del proyecto.
                </div>

                {{-- Botón para subir Excel --}}
                <a href="{{ route('proyectos.upload-excel', $proyecto) }}"
                   class="btn-login w-100 mb-3">
                    <i class="fa-solid fa-file-excel me-2"></i> Subir Archivo Excel
                </a>
            @endif

            {{-- Botón para volver al listado --}}
            <a href="{{ route('proyectos.index') }}"
               class="btn-cancelar w-100">
                <i class="fa-solid fa-arrow-left me-2"></i> Volver al Listado de Proyectos
            </a>

        </div>
    </div>

</x-app-layout>
<x-app-layout>


    <div class="d-flex justify-content-center mt-4 mb-5">

        <div class="login-box" style="max-width:520px; width:100%;">

            {{-- Logo --}}
            <div class="escudo">
                <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo">
            </div>

            <h2 class="login-title">Crear Caracterización</h2>
            <p class="login-subtitle">Registrar una nueva caracterización rural</p>

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-2">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('caracterizaciones.store') }}" method="POST">
                @csrf

                {{-- Nombre de la caracterización --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-file-alt input-icon"></i>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           placeholder="Nombre de la caracterización"
                           value="{{ old('nombre') }}"
                           required>
                </div>

                {{-- Año de la caracterización --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-calendar input-icon"></i>
                    <input type="number"
                           name="ano"
                           class="form-control"
                           placeholder="Año de la caracterización"
                           value="{{ old('ano', date('Y')) }}"
                           min="1900"
                           max="{{ date('Y') + 10 }}">
                </div>

                {{-- Botón Crear --}}
                <button class="btn-login mt-3">
                    <i class="fa-solid fa-plus me-2"></i> Crear Caracterización
                </button>

                {{-- Botón Cancelar --}}
                <a href="{{ route('caracterizaciones.index') }}"
                   class="btn-cancelar mt-3">
                    <i class="fa-solid fa-arrow-left me-2"></i> Cancelar
                </a>

            </form>
        </div>
    </div>

  
</x-app-layout>

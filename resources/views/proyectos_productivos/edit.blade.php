<x-app-layout>

    @vite(['resources/css/pages/proyectos-productivos/create.css'])

    <div class="d-flex justify-content-center mt-4 mb-5">

        <div class="login-box" style="max-width:520px; width:100%;">

            {{-- Logo --}}
            <div class="escudo">
                <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo">
            </div>

            <h2 class="login-title">Editar Proyecto Productivo</h2>
            <p class="login-subtitle">Actualizar información del proyecto productivo</p>

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

            <form action="{{ route('proyectos.update', $proyecto) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nombre del proyecto --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-seedling input-icon"></i>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           placeholder="Nombre del proyecto productivo"
                           value="{{ old('nombre', $proyecto->nombre) }}"
                           required>
                </div>

                {{-- Año del proyecto --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-calendar input-icon"></i>
                    <input type="number"
                           name="ano"
                           class="form-control"
                           placeholder="Año del proyecto"
                           value="{{ old('ano', $proyecto->ano) }}"
                           min="1900"
                           max="{{ date('Y') + 10 }}">
                </div>

                {{-- Método de creación --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-cog input-icon"></i>
                    <select name="metodo_creacion" class="form-control" required>
                        <option value="">Seleccione método de creación</option>

                        <option value="manual"
                            {{ old('metodo_creacion', $proyecto->metodo_creacion) == 'manual' ? 'selected' : '' }}>
                            Manual - Llenar formulario individual
                        </option>

                        <option value="excel"
                            {{ old('metodo_creacion', $proyecto->metodo_creacion) == 'excel' ? 'selected' : '' }}>
                            Excel - Subir archivo masivo
                        </option>
                    </select>
                </div>

                {{-- Botón Guardar --}}
                <button class="btn-login mt-3">
                    <i class="fa-solid fa-save me-2"></i> Guardar Cambios
                </button>

                {{-- Botón Cancelar --}}
                <a href="{{ route('proyectos.index') }}"
                   class="btn-cancelar mt-3">
                    <i class="fa-solid fa-arrow-left me-2"></i> Cancelar
                </a>

            </form>
        </div>
    </div>

</x-app-layout>

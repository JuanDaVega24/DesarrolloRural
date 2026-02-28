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

                {{-- Descripción del proyecto --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-align-left input-icon"></i>
                    <textarea name="descripcion"
                              class="form-control"
                              placeholder="Descripción del proyecto (opcional)"
                              rows="3">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                </div>

                {{-- Método de creación --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Método de Creación</label>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="metodo_creacion" id="metodo_manual" value="manual"
                               {{ old('metodo_creacion', $proyecto->origen) == 'manual' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="metodo_manual">
                            <strong>Manual</strong>
                            <small class="text-muted">Crear formulario personalizado con preguntas específicas</small>
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodo_creacion" id="metodo_excel" value="excel"
                               {{ old('metodo_creacion', $proyecto->origen) == 'excel' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="metodo_excel">
                            <strong>Excel</strong>
                            <small class="text-muted">Subir archivo Excel con datos masivos</small>
                        </label>
                    </div>
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

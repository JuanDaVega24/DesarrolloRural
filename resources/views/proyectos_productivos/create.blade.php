<x-app-layout>
    @vite(['resources/css/pages/proyectos-productivos/create.css'])

    <div class="d-flex justify-content-center mt-4 mb-5">
        <div class="login-box" style="max-width: 600px; width: 100%;">

            {{-- Logo --}}
            <div class="escudo">
                <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Logo">
            </div>

            <h2 class="login-title">Crear Proyecto Productivo</h2>
            <p class="login-subtitle">Seleccione el método de creación para su proyecto</p>

            {{-- Mensajes de error --}}
            @if (session('error'))
                <div class="alert alert-danger mt-2">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mt-2">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('proyectos.store') }}" method="POST">
                @csrf

                {{-- Nombre del Proyecto --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-tag input-icon"></i>
                    <input type="text" name="nombre" class="form-control" 
                           placeholder="Nombre del proyecto" value="{{ old('nombre') }}" required>
                </div>

                {{-- Año del Proyecto --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-calendar input-icon"></i>
                    <input type="number" name="ano" class="form-control" 
                           placeholder="Año del proyecto (opcional)" 
                           value="{{ old('ano', date('Y')) }}" min="1900" max="{{ date('Y') + 10 }}">
                </div>

                {{-- Descripción --}}
                <div class="mb-3 position-relative">
                    <i class="fa-solid fa-align-left input-icon"></i>
                    <textarea name="descripcion" class="form-control" rows="3" 
                              placeholder="Descripción del proyecto (opcional)">{{ old('descripcion') }}</textarea>
                </div>

                {{-- Método de Creación --}}
                <div class="mb-4">
                    <label class="form-label fw-bold mb-3 d-block">
                        <i class="fa-solid fa-cogs me-2"></i>Método de Creación
                    </label>
                    
                    <div class="method-options">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="metodo_creacion" id="metodo_manual" value="manual" required>
                            <label class="form-check-label" for="metodo_manual">
                                <strong>Formulario Manual</strong><br>
                                <small class="text-muted">Cree un formulario personalizado con las preguntas que necesite para su proyecto.</small>
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_creacion" id="metodo_excel" value="excel" required>
                            <label class="form-check-label" for="metodo_excel">
                                <strong>Archivo Excel</strong><br>
                                <small class="text-muted">Suba un archivo Excel con los datos de su proyecto.</small>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn-login">
                        <i class="fa-solid fa-plus me-2"></i>Crear Proyecto
                    </button>
                    
                    <a href="{{ route('proyectos.index') }}" class="btn-cancelar">
                        <i class="fa-solid fa-arrow-left me-2"></i>Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-app-layout>

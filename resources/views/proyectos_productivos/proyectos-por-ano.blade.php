<x-app-layout>
    
   @vite(['resources/css/pages/proyectos-productivos/proyectos-por-ano.css'])

    

    <div class="proyectos-container">
        <div class="content-wrapper">

            {{-- === COMPACT HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Proyectos {{ $ano }}</h1>
                    <p>Proyectos productivos registrados para el año {{ $ano }}</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver a la Gestión de Proyectos
                    </a>
                    
                </div>
            </div>

            {{-- === ERROR ALERT === --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- === SUCCESS ALERT (modal) === --}}
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    mostrarModalGovco('exampleModalExito', "{{ session('success') }}");
                });
            </script>
            @endif

            {{-- === LIVEWIRE COMPONENT === --}}
            @livewire('proyectos-tabla', ['ano' => $ano])

        </div>
    </div>

    {{-- === MODAL GOV.CO - Confirmación === --}}
    <div class="modal-container-govco" id="exampleModalConfirmacion" tabindex="-1" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-govco">
            <div class="modal-content modal-content-govco">
                <div class="modal-header modal-header-govco">
                    <button type="button" disabled class="btn-close" aria-label="cerrar"></button>
                </div>
                <div class="modal-body modal-body-govco center-elements-govco">
                    <div class="modal-icon">
                        <span class="govco-icon govco-info-circle"></span>
                    </div>
                    <h3 class="modal-title-govco confirmation-govco">
                        ¿Eliminar Proyecto?
                    </h3>
                    <p class="modal-text-govco modal-text-center-govco">
                        ¿Está seguro de que desea eliminar este proyecto? Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="modal-footer-govco">
                    <div class="modal-buttons-govco">
                        <button type="button" class="btn-modal-govco btn-eliminar-confirmar">
                            Eliminar
                        </button>
                        <button type="button" class="btn-modal-govco btn-contorno btn-eliminar-cancelar">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === MODAL GOV.CO - Éxito === --}}
    <div class="modal-container-govco" id="exampleModalExito" tabindex="-1" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-govco">
            <div class="modal-content modal-content-govco">
                <div class="modal-header modal-header-govco">
                    <button type="button" disabled class="btn-close" aria-label="cerrar"></button>
                </div>
                <div class="modal-body modal-body-govco center-elements-govco">
                    <div class="modal-icon">
                        <span class="govco-icon govco-check-circle"></span>
                    </div>
                    <h3 class="modal-title-govco success-govco">
                        ¡Operación Exitosa!
                    </h3>
                    <p class="modal-text-govco modal-text-center-govco">
                        La operación se realizó correctamente.
                    </p>
                </div>
                <div class="modal-footer-govco">
                    <div class="modal-buttons-govco" style="justify-content: center;">
                        <button type="button" class="btn-modal-govco btn-exito-aceptar">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === JAVASCRIPT === --}}
    <script>
        // Función para mostrar modal de éxito
        window.mostrarModalGovco = function(modalId, mensaje) {
            const modal = document.getElementById(modalId);
            const titulo = modal.querySelector('.modal-title-govco');
            const texto = modal.querySelector('.modal-text-govco');
            const btnAceptar = modal.querySelector('.btn-exito-aceptar');

            // Personalizar según el mensaje
            if (mensaje.includes('creado') || mensaje.includes('guardado') || mensaje.includes('registrado')) {
                titulo.textContent = '¡Proyecto Creado!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('eliminado')) {
                titulo.textContent = '¡Proyecto Eliminado!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('actualizado') || mensaje.includes('subido')) {
                titulo.textContent = '¡Proyecto Actualizado!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('Excel') || mensaje.includes('datos')) {
                titulo.textContent = '¡Datos Cargados!';
                texto.textContent = mensaje;
            } else {
                titulo.textContent = '¡Operación Exitosa!';
                texto.textContent = mensaje;
            }

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            let backdrop = document.querySelector('.modal-backdrop-govco');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop-govco';
                document.body.appendChild(backdrop);
            }
            backdrop.style.display = 'block';

            const cerrarModal = function() {
                modal.classList.remove('show');
                document.body.style.overflow = '';
                if (backdrop) backdrop.style.display = 'none';
                document.removeEventListener('keydown', handleEscapeKey);
            };

            const handleEscapeKey = function(event) {
                if (event.key === 'Escape') cerrarModal();
            };
            document.addEventListener('keydown', handleEscapeKey);

            if (btnAceptar) {
                const nuevoBtn = btnAceptar.cloneNode(true);
                btnAceptar.parentNode.replaceChild(nuevoBtn, btnAceptar);
                nuevoBtn.addEventListener('click', cerrarModal);
            }

            if (backdrop) backdrop.addEventListener('click', cerrarModal);
        };

        // Función para confirmar eliminación
        function confirmarEliminacion(url, proyectoNombre) {
            const modal = document.getElementById('exampleModalConfirmacion');
            const titulo = modal.querySelector('.modal-title-govco');
            const texto = modal.querySelector('.modal-text-govco');
            const btnConfirmar = document.querySelector('.btn-eliminar-confirmar');
            const btnCancelar = document.querySelector('.btn-eliminar-cancelar');

            // Personalizar contenido del modal
            titulo.textContent = '¿Eliminar Proyecto?';
            texto.innerHTML = '¿Está seguro de que desea eliminar el proyecto "<strong>' + proyectoNombre + '</strong>"? Esta acción no se puede deshacer.';

            // Mostrar modal y backdrop
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            let backdrop = document.querySelector('.modal-backdrop-govco');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop-govco';
                document.body.appendChild(backdrop);
            }
            backdrop.style.display = 'block';

            // Función para cerrar modal
            const cerrarModal = function() {
                modal.classList.remove('show');
                document.body.style.overflow = '';
                if (backdrop) {
                    backdrop.style.display = 'none';
                }
                document.removeEventListener('keydown', handleEscapeKey);
            };

            // Event listener para Escape
            const handleEscapeKey = function(event) {
                if (event.key === 'Escape') {
                    cerrarModal();
                }
            };
            document.addEventListener('keydown', handleEscapeKey);

            // Configurar botón confirmar
            if (btnConfirmar) {
                const nuevoBtnConfirmar = btnConfirmar.cloneNode(true);
                btnConfirmar.parentNode.replaceChild(nuevoBtnConfirmar, btnConfirmar);
                nuevoBtnConfirmar.addEventListener('click', function() {
                    // Crear formulario de eliminación
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';

                    // Token CSRF
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfToken);

                    // Método DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Enviar formulario
                    document.body.appendChild(form);
                    form.submit();

                    cerrarModal();
                });
            }

            // Configurar botón cancelar
            if (btnCancelar) {
                const nuevoBtnCancelar = btnCancelar.cloneNode(true);
                btnCancelar.parentNode.replaceChild(nuevoBtnCancelar, btnCancelar);
                nuevoBtnCancelar.addEventListener('click', cerrarModal);
            }

            // Cerrar con click en backdrop
            if (backdrop) {
                backdrop.addEventListener('click', cerrarModal);
            }
        }
    </script>

</x-app-layout>

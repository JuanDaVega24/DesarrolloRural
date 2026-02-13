<x-app-layout>
    <style>
        /* === ESTILOS ESPECÍFICOS DE ESTA PÁGINA === */

        .card-link {
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: gap 0.3s ease;
            font-size: 0.9375rem;
            background: linear-gradient(180deg, var(--verde), #6ba349);
        }
        
        /* === PANEL VALIDAR PROYECTOS === */
        .validacion-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 1rem;
            
       
 
          
        }

        .filtros-panel {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 180px;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,.15);
            border-color: var(--verde);
        }

        .filtros-panel:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,.15);
            border-color: var(--verde-hover);
        }

        .validacion-icon {
            font-size: 3rem;
            color: var(--verde);
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .filtros-panel:hover .validacion-icon {
            transform: scale(1.1);
            color: var(--verde-hover);
        }

        .comparar-icon {
            font-size: 3rem;
            color: var(--govcolor-cobalt);
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .filtros-panel:hover .comparar-icon {
            transform: scale(1.1);
            color: var(--govcolor-havelock-lue);
        }

        .validacion-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--negro);
            margin-bottom: 0.75rem;
            letter-spacing: -0.3px;
        }

        .validacion-description {
            color: var(--gris);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .validacion-stats {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--azul);
            font-size: 1.1rem;
        }

        .validacion-stats i {
            color: var(--azul);
        }
         .card-footer {
            margin-top: auto;
         display: flex;
    justify-content: flex-end;  /* 👉 Envía el contenido hacia la derecha */
    padding-top: 10px;
    
    
        }

        /* === RESPONSIVE PANELS === */
        @media (max-width: 768px) {
            .validacion-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .filtros-panel {
                padding: 1.5rem;
                min-height: 160px;
            }

            .validacion-icon {
                font-size: 2.5rem;
            }
            .comparar-icon {
                font-size: 2.5rem;
            }

            .validacion-title {
                font-size: 1.25rem;
            }
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
            }
        }

     
        @font-face {
            font-family: 'Nunito Sans';
            src: url('/assets/fonts/Nunito_Sans/static/NunitoSans-Regular.ttf');
            font-weight: 400;
        }

        @font-face {
            font-family: 'Nunito Sans';
            src: url('/assets/fonts/Nunito_Sans/static/NunitoSans-Bold.ttf');
            font-weight: 700;
        }

        @font-face {
            font-family: 'Verdana-Regular';
            src: url('/assets/fonts/Verdana/static/Verdana-Regular.ttf');
        }

        .modal-container-govco {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            z-index: 1060 !important;
            width: 100% !important;
            height: 100% !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            outline: 0 !important;
            display: none !important;
        }

        .modal-container-govco.show {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .modal-container-govco .modal-backdrop-govco {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            z-index: 1055 !important;
            width: 100vw !important;
            height: 100vh !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }

        .modal-dialog-govco {
            width: 100%;
            position: relative;
            pointer-events: none;
            margin: 0 !important;
        }

        .modal-content-govco {
            height: 100%;
            box-shadow: 0px 3px 6px #00000029;
            border-radius: 0.313rem !important;
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: var(--govcolor-white);
            border: 1px solid var(--govcolor-black) !important;
            outline: 0;
        }

        .modal-header-govco {
            border-bottom: none !important;
            padding: 3.5rem 3.5rem 0px 3.5rem !important;
            justify-content: flex-end !important;
            display: flex;
        }

        .modal-body-govco {
            margin: 0px 3.5rem auto 3.5rem !important;
            padding: 0px !important;
        }

        .modal-title-govco {
            font-family: 'Nunito Sans';
            color: var(--govcolor-cobalt);
            font-size: 2.125rem;
            margin: 0px;
            margin-bottom: 0.625rem !important;
            font-weight: 700;
            line-height: 42px;
        }

        .modal-text-govco {
            font-family: 'Verdana-Regular';
            font-size: 15px;
            color: var(--govcolor-matterhorn);
            margin-bottom: 1.875rem;
            line-height: 1.375rem;
        }

        .modal-footer-govco {
            margin: 0px 4.688rem auto 4.688rem;
            padding-bottom: 3.5rem !important;
            border-top: none !important;
            display: flex;
        }

        .modal-buttons-govco {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .btn-modal-govco {
            font-family: 'Verdana-Regular';
            border-radius: 999px !important;
            background-color: var(--govcolor-cobalt) !important;
            border: 2px solid var(--govcolor-cobalt) !important;
            color: var(--govcolor-white) !important;
            padding: 12px 48px !important;
            font-size: 16px !important;
            height: 48px;
            min-width: 180px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: none !important;
        }

        .btn-modal-govco.btn-contorno {
            background-color: var(--govcolor-white) !important;
            color: var(--govcolor-cobalt) !important;
            border: 2px solid var(--govcolor-cobalt) !important;
        }

        .btn-modal-govco.btn-contorno:hover,
        .btn-modal-govco.btn-contorno:focus {
            background-color: var(--govcolor-havelock-lue) !important;
            color: var(--govcolor-white) !important;
        }

        .btn-modal-govco:hover {
            background-color: var(--govcolor-havelock-lue) !important;
            border-color: var(--govcolor-havelock-lue) !important;
        }

        .btn-modal-govco:focus {
            outline: 2px solid var(--govcolor-black) !important;
            outline-offset: 2.4px;
            background-color: var(--govcolor-havelock-lue) !important;
        }

        .center-elements-govco {
            text-align: center;
        }

        .modal-icon {
            margin-bottom: 16px;
        }

        .modal-text-center-govco {
            margin-bottom: 1.875rem;
            padding-right: 0px;
        }

        .success-govco {
            width: 100%;
            margin-bottom: 1.5rem !important;
            color: var(--govcolor-green);
        }

        .confirmation-govco {
            width: 100%;
            margin-bottom: 1.5rem !important;
            color: var(--govcolor-cobalt);
        }

        .govco-icon.govco-check-circle {
            background-image: url(/assets/icons/check-circle.svg);
            min-width: 4.375rem;
            min-height: 4.375rem;
            display: inline-block;
            background-repeat: no-repeat;
            background-size: 4.375rem 4.375rem;
            filter: var(--govcolor-svg-green);
        }

        .govco-icon.govco-info-circle {
            background-image: url(/assets/icons/info-circle.svg);
            min-width: 4.375rem;
            min-height: 4.375rem;
            display: inline-block;
            background-repeat: no-repeat;
            background-size: 4.375rem 4.375rem;
            filter: var(--govcolor-svg-cobalt);
        }

        @media(min-width:576px) {
            .modal-dialog-govco {
                max-width: 540px !important;
            }
        }

        @media(max-width:480px) {
            .modal-header-govco {
                padding: 1.5rem 1.5rem 0px 1.5rem !important;
            }

            .modal-body-govco {
                margin: 0px 1.5rem auto 1.5rem !important;
                padding-top: 1rem !important;
            }

            .modal-title-govco {
                font-size: 1.625rem;
                margin-bottom: 0.5rem !important;
            }

            .modal-text-govco {
                font-size: 0.875rem;
                margin-bottom: 1.5rem !important;
            }

            .modal-footer-govco {
                margin: 0px 1.5rem auto 1.5rem;
                padding-bottom: 3rem !important;
            }

            .modal-buttons-govco {
                display: flex;
                justify-content: center;
                gap: 1rem;
            }

            .btn-modal-govco {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>

    <div class="filtros-container">
        <div class="content-wrapper">

            {{-- === COMPACT HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Filtros y Búsquedas</h1>
                    <p>Administra y configura los filtros y Búsquedas avanzadas del sistema</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
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

            {{-- === PANEL VALIDAR PROYECTOS === --}}
            <div class="validacion-grid">
                <a href="{{ route('filtros.validar-proyectos') }}" class="filtros-panel">
                    <div class="validacion-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="validacion-title">Buscar CZ en Proyectos productivos</h3>
                    <p class="validacion-description">
                        Buscar la existencia de Caracterización en los personas inscritas a proyectos productivos registrados en el sistema. 
                    </p>
                      <div class="card-footer">
        <span class="card-link btn px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
                </a>

                   <a href="{{ route('filtros.comparar-proyectos') }}" class="filtros-panel">
                    <div class="comparar-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <h3 class="validacion-title">Comparar Proyectos Productivos</h3>
                    <p class="validacion-description">
                        Buscar la existencia de una persona inscrita en diferentes proyectos
                    </p>
                      <div class="card-footer">
        <span class="card-link btn px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
                </a>

                {{-- Nuevo panel: Cultivos por Corregimientos y Veredas --}}
                <a href="{{ route('filtros.cultivos-corregimiento') }}" class="filtros-panel">
                    <div class="validacion-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3 class="validacion-title">Cultivos por corregimientos y veredas de la cz</h3>
                    <p class="validacion-description">
                        Visualizar gráficos y datos cuantitativos sobre los cultivos existentes por cada corregimiento y vereda.
                    </p>
                      <div class="card-footer">
        <span class="card-link btn px-4 py-2 text-white  ">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
                </a>
            </div>

        </div>
    </div>

    {{-- === MODAL GOV.CO - Confirmación === --}}
    <div class="container-modal-govco">
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
                            ¿Confirmar Acción?
                        </h3>
                        <p class="modal-text-govco modal-text-center-govco">
                            ¿Está seguro de que desea realizar esta acción?
                        </p>
                    </div>
                    <div class="modal-footer-govco">
                        <div class="modal-buttons-govco">
                            <button type="button" class="btn-modal-govco">
                                Confirmar
                            </button>
                            <button type="button" class="btn-modal-govco btn-contorno">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === MODAL GOV.CO - Éxito === --}}
    <div class="container-modal-govco">
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
                            <button type="button" class="btn-modal-govco">
                                Aceptar
                            </button>
                        </div>
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
                titulo.textContent = '¡Operación Creada!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('eliminado')) {
                titulo.textContent = '¡Operación Eliminada!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('actualizado') || mensaje.includes('subido')) {
                titulo.textContent = '¡Operación Actualizada!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('Excel') || mensaje.includes('datos')) {
                titulo.textContent = '¡Datos Procesados!';
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
    </script>

</x-app-layout>

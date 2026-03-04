<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Desarrollo Rural') }}</title>

    <!-- Script de Accesibilidad - Ejecutar ANTES de otros scripts para evitar bloqueos -->
    <script>
        (function() {
            console.log('=== SCRIPT HEAD: Iniciando restauración de accesibilidad ===');
            
            try {
                // Restaurar escala de fuente inmediatamente
                const contadorGuardado = parseInt(localStorage.getItem('accesibilidad_contador_fuente')) || 0;
                console.log('Contador guardado en localStorage:', contadorGuardado);
                
                if (contadorGuardado !== 0) {
                    const escala = 1 + (contadorGuardado * 0.0625);
                    document.documentElement.style.fontSize = (escala * 16) + 'px';
                    console.log('✓ Escala de fuente APLICADA:', escala, '(', (escala * 16) + 'px', ')');
                } else {
                    console.log('- No hay escala guardada (contador = 0)');
                }
                
                // Restaurar alto contraste inmediatamente
                const altoContrasteGuardado = localStorage.getItem('accesibilidad_alto_contraste');
                console.log('Alto contraste guardado:', altoContrasteGuardado);
                if (altoContrasteGuardado === 'true') {
                    document.documentElement.classList.add('alto-contraste');
                    console.log('✓ Alto contraste APLICADO');
                }
                
                // Restaurar contraste GOV.CO - esperar a que body exista
                const contrasteGuardado = localStorage.getItem('accesibilidad_contraste');
                console.log('Contraste GOV.CO guardado:', contrasteGuardado);
                if (contrasteGuardado === 'true') {
                    // En head body podría no existir, usar callback
                    if (document.body) {
                        document.body.classList.add('contrast-govco');
                        console.log('✓ Contraste GOV.CO APLICADO (en head)');
                    } else {
                        // Si body no existe, esperar
                        document.addEventListener('DOMContentLoaded', function() {
                            document.body.classList.add('contrast-govco');
                            console.log('✓ Contraste GOV.CO APLICADO (en DOMContentLoaded)');
                        });
                    }
                }
                
                console.log('=== localStorage completo:', {
                    contador: localStorage.getItem('accesibilidad_contador_fuente'),
                    altoContraste: localStorage.getItem('accesibilidad_alto_contraste'),
                    contraste: localStorage.getItem('accesibilidad_contraste')
                });
            } catch (e) {
                console.error('Error en restauración de accesibilidad:', e);
            }
        })();
    </script>
<!-- Agregar aquí la etiqueta del favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('images/logo-DesarrolloDelCampo.png') }}">


    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.0/css/all.min.css">

    <!-- App CSS / JS -->
    @vite(['resources/css/app.css', 'resources/css/custom-theme.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Modern UI - GOV.CO Styles -->
    <style>
        /* GOV.CO specific styles - kept in layout for global use */
        :root {
          --govcolor-cobalt: #0943B5;
          --govcolor-white: #FFFFFF;
          --govcolor-matterhorn: #4C4C4C;
          --govcolor-havelock-lue: #4672C8;
          --govcolor-black: #000000;
          --govcolor-solitude: #E5ECF8;
          --govcolor-green: #158361;
          --govcolor-red: #A80521;
          --govcolor-white-smoke: #F4F4F4;
          --govcolor-svg-green: invert(35%) sepia(93%) saturate(345%) hue-rotate(110deg) brightness(98%) contrast(98%);
        }

        /* Font definitions */
        @font-face {
          font-family: 'Verdana-Regular';
          src: url('{{ asset('assets/fonts/Verdana/static/Verdana-Regular.ttf') }}');
        }

        @font-face {
          font-family: 'Verdana-Bold';
          src: url('{{ asset('assets/fonts/Verdana/static/Verdana-Bold.ttf') }}');
        }

        @font-face {
            font-family: 'Nunito_Sans-SemiBold';
            src: url('{{ asset('assets/fonts/Nunito_Sans/static/NunitoSans-SemiBold.ttf') }}');
        }

        @font-face {
            font-family: "govco-fontv5";
            src: url("{{ asset('assets/icons/fonts/gov-co-font.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        /* GOV.CO Icons */
        .govco-icon::after {
            font-family: "govco-fontv5";
        }

        .govco-icon.govco-times:after {
            content: "\ea95";
        }

        .govco-icon.govco-times-cancel:after {
            content: "\ea94";
        }

        .govco-svg.govco-check-circle {
            background-image: url(/assets/icons/check-circle.svg);
        }

        .govco-svg.govco-times-cancel {
            background-image: url(/assets/icons/times-cancel.svg);
        }

        /* Top bar */
        .barra-superior-govco {
          background-color: var(--govcolor-cobalt);
          width: 100%;
          height: 3.5rem;
          padding-left: 3.75rem;
          position: relative;
          display: flex;
          align-items: center;
        }

        .barra-superior-govco a {
          content: url('https://cdn.www.gov.co/layout-govco-v5/assets/images/logo.svg');
          height: calc(1.5rem * 1.5);
        }

        .barra-superior-govco a:focus-visible {
          outline: 0.125rem solid var(--govcolor-white);
          border-radius: 0.313rem;
        }

        .barra-superior-govco .idioma-btn-barra-superior-govco {
          height: 1.5rem;
          width: 1.5rem;
          border-radius: 0.313rem;
          background-color: var(--govcolor-white);
          cursor: pointer;
          padding: 0;
          border: 0.063rem solid var(--govcolor-white);
          font-size: 0.625rem;
          position: absolute;
          right: 5.375rem;
          top: 1rem;
        }

        .barra-superior-govco .idioma-btn-barra-superior-govco:hover,
        .barra-superior-govco .idioma-btn-barra-superior-govco:focus-visible {
          background-color: var(--govcolor-havelock-lue);
        }

        .barra-superior-govco .idioma-btn-barra-superior-govco:focus-visible {
          outline: 0.063rem solid var(--govcolor-white);
          outline-offset: max(0.188rem, 0.188rem);
        }

        .barra-superior-govco .idioma-btn-barra-superior-govco::before {
          font-family: "Nunito_Sans-Regular";
          content: 'EN';
          color: var(--govcolor-cobalt);
          font-size: 12px;
        }

        .barra-superior-govco .idioma-btn-barra-superior-govco:hover::before,
        .barra-superior-govco .idioma-btn-barra-superior-govco:focus-visible::before {
          color: var(--govcolor-white);
        }

        @media (max-width: 600px) {
          .barra-superior-govco {
            justify-content: center;
            padding: 0;
          }

          .barra-superior-govco .idioma-btn-barra-superior-govco {
            right: 1.25rem;
          }
        }

        /* Back to top button */
        .volver-arriba-govco {
          color: var(--govcolor-white);
          width: 3.375rem;
          height: 3.375rem;
          border-radius: 50%;
          background-color: var(--govcolor-cobalt);
          box-shadow: 0.25rem 0.25rem 0.375rem #B5C7E9;
          transition: all 300ms;
          text-align: center;
          border: 0;
          padding: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 0.625rem;
          overflow: hidden;
        }

        .volver-arriba-govco::before {
          content: "";
          min-width: 2.25rem;
          min-height: 2.25rem;
          border-radius: 50%;
          background-color: var(--govcolor-white);
          display: block;
        }

        .volver-arriba-govco span.govco-expand_circle_up {
          background-image: url(/assets/icons/angle-up.svg);
          min-width: 1rem;
          min-height: 1rem;
          display: inline-block;
          background-repeat: no-repeat;
          background-size: 1rem 1rem;
        }

        .volver-arriba-govco span {
          min-width: 1.5rem!important;
          min-height: 1.5rem!important;
          background-size: 1.5rem 1.5rem!important;
          position: absolute;
          filter: invert(21%) sepia(98%) saturate(1529%) hue-rotate(209deg) brightness(95%) contrast(118%);
        }

        .volver-arriba-govco:hover,
        .volver-arriba-govco:focus-visible {
          width: 7.375rem;
          height: 3.375rem;
          background-color: var(--govcolor-havelock-lue);
          color: var(--govcolor-white);
          border-radius: 1.688rem 0.625rem 0.625rem 1.688rem;
          text-align: left;
          transition: all 300ms;
          justify-content: flex-start;
          padding: 0 0.625rem 0 0.5rem;
        }

        .volver-arriba-govco:focus-visible {
          outline: max(0.125rem, 0.125rem) solid black;
          outline-offset: max(0.188rem, 0.188rem);
        }

        .volver-arriba-govco:hover span,
        .volver-arriba-govco:focus-visible span {
          margin-left: 0.375rem;
        }

        .volver-arriba-govco:hover::after,
        .volver-arriba-govco:focus-visible::after {
          content: "Volver arriba";
          color: var(--govcolor-white);
          font-family: 'Verdana-Regular';
          font-size: 16px;
          text-align: center;
          line-height: 1.2;
        }

        /* GOV.CO Buttons */
        .btn-govco {
            border-radius: 1.563rem;
            font-family: 'Verdana-Regular';
            font-size: 16px;
            padding: 0.688rem 0.938rem;
            border-width: 0.125rem;
            border-style: solid;
            text-align: center;
            text-decoration: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            line-height: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-govco.fill-btn-govco,
        .btn-govco.outline-btn-govco {
            border-color: var(--govcolor-cobalt);
        }

        .btn-govco.outline-btn-govco,
        .btn-govco.fill-secundary-btn-govco {
            background-color: var(--govcolor-white);
            color: var(--govcolor-cobalt);
        }

        .btn-govco.fill-btn-govco:hover,
        .btn-govco.fill-btn-govco:focus-visible,
        .btn-govco.outline-btn-govco:hover,
        .btn-govco.outline-btn-govco:focus-visible {
            background-color: var(--govcolor-havelock-lue);
            border-color: var(--govcolor-havelock-lue);
            color: var(--govcolor-white);
        }

        .btn-govco.fill-btn-govco:focus-visible,
        .btn-govco.outline-btn-govco:focus-visible,
        .btn-govco.fill-secundary-btn-govco:focus-visible,
        .btn-govco.outline-secundary-btn-govco:focus-visible {
            outline: max(0.125rem, 0.125rem) solid var(--govcolor-black);
            outline-offset: max(0.125rem, 0.188rem);
        }

        /* GOV.CO Toast */
        .container-toast-govco {
            width: 100% !important;
            min-height: 100px !important;
            max-width: 386px !important;
            background-color: var(--govcolor-white) !important;
            box-shadow: 0px 4px 0px color-mix(in srgb, var(--govcolor-cobalt) 14%, transparent) !important;
            display: block !important;
            border-radius: 5px !important;
            padding: 0px !important;
            line-height: 21px !important;
            margin: 16px !important;
            position: static !important;
            font-size: 14px;
        }

        .container-toast-govco .govco-svg {
            min-width: 1.5rem;
            min-height: 1.5rem;
            display: inline-block;
            background-repeat: no-repeat;
            background-size: 1.5rem 1.5rem;
            filter: var(--govcolor-svg-green);
        }

        .container-toast-govco.error {
            border: 1px solid var(--govcolor-red) !important;
        }

        .container-toast-govco.success {
            border: 1px solid var(--govcolor-green) !important;
        }

        .container-toast-govco .govco-icon.govco-times {
            font-size: 0.75rem;
        }

        .container-toast-govco .govco-icon.govco-times.error {
            color: var(--govcolor-red);
        }

        .container-toast-govco .toast-header-error-govco {
            color: var(--govcolor-red);
            background: #EECDD2 0% 0% no-repeat padding-box;
        }

        .container-toast-govco .toast-small-govco {
            font-family: "Verdana-Regular";
            font-size: 12px;
            margin-right: 0.5rem;
        }

        .container-toast-govco .toast-small-govco.error {
            color: var(--govcolor-red);
        }

        .container-toast-govco .toast-body-govco {
            font-family: "Verdana-Regular";
            font-size: 14px;
            color: var(--govcolor-matterhorn);
            text-align: left;
            text-shadow: 0px 3px 6px color-mix(in srgb, var(--govcolor-cobalt) 16%, transparent);
        }

        .container-toast-govco .toast-title-govco {
            font-family: "Nunito_Sans-SemiBold";
            font-size: 20px;
            color: var(--govcolor-black);
            text-overflow: ellipsis;
            display: -webkit-box;
            overflow: hidden;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .container-toast-govco .govco-icon.fs-mr {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }

        .container-toast-govco .govco-svg.fs-mr {
            margin-right: 0.5rem;
        }

        .container-toast-interactivo {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 2
        }

        .container-toast-govco .toast-body-govco p {
            text-overflow: ellipsis;
            display: -webkit-box;
            overflow: hidden;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            margin: 0;
        }

        .container-toast-govco div a:focus-visible {
            outline: max(0.125rem, 0.125rem) solid var(--govcolor-black);
            outline-offset: max(0.125rem, 0.188rem);
        }
        .container-toast-govco div a:focus-visible .info {
            color: var(--govcolor-havelock-lue) !important;
        }
        .container-toast-govco div a:focus-visible .success {
            color: #94B2AA !important;
        }
        .container-toast-govco div a:focus-visible .error {
            color: #C49198 !important;
        }
        .container-toast-govco .govco-times.info:hover,
        .container-toast-govco .govco-times.info:focus-visible {
            color: var(--govcolor-havelock-lue)
        }
        .container-toast-govco .govco-times.success:hover,
        .container-toast-govco .govco-times.success:focus-visible {
            color: #94B2AA
        }
        .container-toast-govco .govco-times.error:hover,
        .container-toast-govco .govco-times.error:focus-visible {
            color: #C49198
        }

        @media(max-width:480px) {
            .container-toast-govco .govco-icon::after {
                font-size: 1.5rem !important;
            }

            .container-toast-govco .govco-icon.govco-times::after {
                font-size: 1rem !important;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
  <div class="barra-superior-govco">
  <a href="https://www.gov.co/" target="_blank" rel="noopener" aria-label="Portal del Estado Colombiano - GOV.CO"><img src="https://cdn.www.gov.co/layout-govco-v5/assets/images/logo.svg" alt="logo"></a> 
</div>


    {{-- Banner Jetstream --}}
    <x-banner />


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

    <!-- Script del botón volver arriba -->
    <script>

function methodAssign(event, method, elements) {
  for (let i of elements) {
    i.addEventListener(event, method, false);
  }
}

function addEventHandler(el, evt, sel, handler) {
  for (const currEvt of evt.split(' ')) {
    el.addEventListener(currEvt, function (event) {
      let t = event.target;
      while (t && t !== this) {
        for (const currSel of sel.split(',') ) {
          if (t.matches(currSel)) {
            handler.call(t, event);
          }
        }
        t = t.parentNode;
      }
    });
  }
}

(function() {
  window.addEventListener("load", function () {
    initBackGoToUp();
  });
})();

function initBackGoToUp() {
  addEventsBackGoToUp();

  addEventHandler(
    document.body,
    'click keydown',
    '.volver-arriba-govco',
    function(event) {
      addEventsBackGoToUp(event);
    }
  );
}

function addEventsBackGoToUp(event = null) {
  const backGoToUpElements = document.querySelectorAll(".volver-arriba-govco:not(.actived-events-govco)");

  if (backGoToUpElements.length > 0) {
    backGoToUpElements.forEach((element) => element.classList.add('actived-events-govco'));
    methodAssign("click", backGoToUp, backGoToUpElements);
  }

  if (event == null || backGoToUpElements.length == 0) {
    return false;
  }

  let element = '';
  if (event.target.classList.contains('button.volver-arriba-govco')) {
    element = event.target;
  } else if (event.target.closest('button.volver-arriba-govco')) {
    element = event.target.closest('button');
  }

  if (element) {
    element.click();
  }
}

function backGoToUp() {
  document.body.scrollTop = document.documentElement.scrollTop = 0;
}


    </script>

    <!-- Botón volver arriba -->
    <button class="volver-arriba-govco position-fixed" aria-label="Volver arriba" style="bottom: 20px; right: 20px; z-index: 1200;">
      <span class="govco-expand_circle_up"></span>
    </button>

    <!-- Barra de Accesibilidad GOV.CO -->
    <div class="barra-accesibilidad-govco-container">
        <div class="barra-accesibilidad-govco">
            <button class="contrast" aria-label="Cambiar contraste">
                <span class="govco-contrast"></span>
            </button>
            <button class="decrease-font-size" aria-label="Disminuir letra" data-decrease-limit="-5">
                <span class="govco-font-minimize"></span>
            </button>
            <button class="increase-font-size" aria-label="Aumentar letra" data-increase-limit="5">
                <span class="govco-font-maximize"></span>
            </button>
            <button class="sign-language" aria-label="Lenguaje de señas" onclick="window.open('https://ticsinbarreras.mintic.gov.co/791/w3-propertyvalue-339742.html', '_blank')">
                 <span class="govco-sign-language"></span>
            </button>
         
            
        </div>
    </div>

    <!-- Notificación Tipo Tostada Negativa -->
    <div class="container-toast-interactivo d-none">
        <div class="toast container-toast-govco error show" id="fixedtoast" data-autohide="false" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="toast-header toast-header-error-govco">
                <span class="govco-icon govco-times-cancel fs-mr" aria-label="info"></span>
                <strong class="me-auto toast-title-govco">Error de validación</strong>
                <small class="toast-small-govco error">Ahora</small>
                <a href="javascript:void(0)" role="button" data-bs-dismiss="modal"
                    class="close-btn-toast" aria-label="Close" aria-expanded="false">
                    <span class="govco-icon govco-times error"></span>
                </a>
            </div>
            <div class="toast-body toast-body-govco">
                <p id="toast-message">Por favor complete todos los campos requeridos.</p>
            </div>
        </div>
    </div>

    <!-- Notificación Tipo Tostada Positiva (Éxito) -->
    <div class="container-toast-interactivo d-none">
        <div class="toast container-toast-govco success show" id="successtoast" data-autohide="false" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <span class="govco-icon govco-check-circle fs-mr" aria-label="success"></span>
                <strong class="me-auto toast-title-govco">¡Éxito!</strong>
                <small class="toast-small-govco">Ahora</small>
                <a href="javascript:void(0)" role="button" data-bs-dismiss="modal"
                    class="close-btn-toast" aria-label="Close" aria-expanded="false">
                    <span class="govco-icon govco-times"></span>
                </a>
            </div>
            <div class="toast-body toast-body-govco">
                <p id="success-toast-message">Operación completada exitosamente.</p>
            </div>
        </div>
    </div>

    <footer>

     <div class="pie-pagina-govco">
  <div class="first-section">
    <h4>Alcaldia de Bucaramanga</h4>
    <div class="logo-container">
      <span class="govco-logo-potencia"></span>
      <span class="separator"></span>
      <span class="govco-logo-entidad"></span>
    </div>
    <h5>Sede principal</h5>
    <ul class="contact-data-container">
      <li>
        <p>Dirección: Carrera 11 # 34-52, Bucaramanga, Santander, Colombia</p>
      </li>
      <li>
        <p>Código postal: 680006. Código Dane: 68001.</p>
      </li>
      <li>
        <p>Horario de atención: Lunes a jueves de 7:30 a.m. a 12:00 m y de 1:00 p.m. a 5:00 p.m. / Viernes</p>
       
        

        <p>jornada continua en el horario de 7:00 a.m. a 4:00 p.m., con 30 minutos de descanso al medio día.</p>
      </li>
      <li>
        <p>Teléfono conmutador: +57 (607) 633 70 00</p>
      </li>
      <li>
        <p>Línea gratuita: +57 (607) 652 55 55</p>
      </li>
      <li>
      <p>
          Línea anticorrupción:
          <a href="https://canaldenuncia.bucaramanga.gov.co" class="btn-govco link-btn-govco" aria-label="Permite enviar correo" target="_blank">https://canaldenuncia.bucaramanga.gov.co/</a>
        </p>
    </li>
      <li>
        <p>
          Correo institucional:
          <a href="mailto: contactenos@bucaramanga.gov.co" class="btn-govco link-btn-govco" aria-label="Permite enviar correo" target="_blank">contactenos@bucaramanga.gov.co</a>
        </p>
      </li>
      <li>
        <p>
          Correo de notificaciones judiciales:
          <a href="mailto:notificaciones@bucaramanga.gov.co" class="btn-govco link-btn-govco" aria-label="Permite enviar correo" target="_blank">notificaciones@bucaramanga.gov.co</a>
        </p>
      </li>
    </ul>
   
    <div class="data-container">
      <ul class="data">
        <h6>Sector Agricultura y Desarrollo Rural</h6>
        <li>
          <p>Dirección: Carrera 11 # 34-52, Piso 3, Bucaramanga, Santander</p>
        </li>
        <li>
          <p>Horario de atención: Lunes a viernes </p>
          <p>xx:xx a.m. - xx:xx p.m.</p>
        </li>
      </ul>
      <ul class="data">
        <h6>Contacto</h6>
        <li>
          <p>Teléfono conmutador: +57(xx) xxx xx xx </p>
        </li>
        <li>
          <p>
            Correo institucional:
            <a href="mailto:ministerio@ministerio.gov.co" class="btn-govco link-btn-govco" aria-label="Permite enviar correo" target="_blank">ministerio@ministerio.gov.co</a>
          </p>
        </li>
      </ul>
      
    </div>
    
  </div>
  <div class="second-section">
    <span class="govco-logo"  onclick="window.open('https://www.gov.co/', '_blank')"></span>
    <span class="separator"></span>
    <span class="govco-co"></span>
  </div>
</div>
    </div>
</footer>

    <style>
        .barra-accesibilidad-govco-container {
            position: fixed;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            z-index: 1050;
        }

        .barra-accesibilidad-govco {
            background-color: var(--govcolor-cobalt);
            display: flex;
            flex-direction: column;
            border-radius: 0.625rem 0 0 0.625rem;
            width: 3rem;
            height: 8.938rem;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 0;
            position: relative;
        }

        .barra-accesibilidad-govco button {
            width: 3rem;
            height: 2.5rem;
            border: 0;
            padding: 0;
            background-color: var(--govcolor-cobalt);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-family: 'Verdana-Regular';
        }

        .barra-accesibilidad-govco button span {
            z-index: 1;
            filter: invert(21%) sepia(98%) saturate(1529%) hue-rotate(209deg) brightness(95%) contrast(118%);
            min-width: 1rem;
            min-height: 1rem;
            display: inline-block;
            background-repeat: no-repeat;
            background-size: 1rem 1rem;
        }

        .barra-accesibilidad-govco button span.govco-contrast {
            background-image: url(/assets/icons/adjust.svg);
        }
        .barra-accesibilidad-govco button span.govco-font-minimize {
            background-image: url(/assets/icons/font-minimize.svg);
        }

        .barra-accesibilidad-govco button span.govco-font-maximize {
            background-image: url(/assets/icons/font-maximize.svg);
        }

        .barra-accesibilidad-govco button span.govco-sign-language {
            background-image: url(/assets/icons/channels-616_icon_centro_relevo.svg);
        }

        .barra-accesibilidad-govco button.sign-language::before {
            content: "Lenguaje de señas";
        }

        .barra-accesibilidad-govco button::before {
            position: absolute;
            right: 0;
            min-width: 10.75rem;
            height: 2.5rem;
            padding-right: 3rem;
            border-radius: 0.625rem 0 0 0.625rem;
            align-items: center;
            padding-left: 0.625rem;
            color: var(--govcolor-white);
            background-color: var(--govcolor-havelock-lue);
            white-space: nowrap;
            display: none;
            opacity: 0;
        }

        .barra-accesibilidad-govco button::after {
            content: "";
            width: 1.5rem;
            height: 1.5rem;
            background-color: var(--govcolor-white);
            border: 0;
            border-radius: 0.313rem;
            position: absolute;
            display: block;
        }

        .barra-accesibilidad-govco button.contrast::before {
            content: "Contraste";
        }

        .barra-accesibilidad-govco button.decrease-font-size::before {
            content: "Reducir letra";
        }

        .barra-accesibilidad-govco button.increase-font-size::before {
            content: "Aumentar letra";
        }

        .barra-accesibilidad-govco button:hover,
        .barra-accesibilidad-govco button:focus-visible {
            background-color: var(--govcolor-havelock-lue);
        }

        .barra-accesibilidad-govco button:hover::before,
        .barra-accesibilidad-govco button:focus-visible::before {
            opacity: 1;
            display: flex;
        }

        .barra-accesibilidad-govco button:focus-visible {
            outline: 0;
        }

        .barra-accesibilidad-govco button:focus-visible::before {
            outline: max(0.125rem, 0.125rem) solid var(--govcolor-black);
            outline-offset: max(0.125rem, 0.125rem);
        }

        .barra-accesibilidad-govco button.active {
            background-color: var(--govcolor-tropical-blue);
            box-shadow: 0 0.188rem 0.375rem #00000029;
        }

        .contrast-govco .accesibility-example {
            background-color: var(--govcolor-black);
        }

        .contrast-govco .accesibility-example p {
            color: var(--govcolor-white);
        }
    </style>

      <script>
        // Funciones de accesibilidad con localStorage
        function methodAssign(event, method, elements) {
            for (let i of elements) {
                i.addEventListener(event, method, false);
            }
        }

        function addEventHandler(el, evt, sel, handler) {
            for (const currEvt of evt.split(' ')) {
                el.addEventListener(currEvt, function (event) {
                    let t = event.target;
                    while (t && t !== this) {
                        for (const currSel of sel.split(',') ) {
                            if (t.matches(currSel)) {
                                handler.call(t, event);
                            }
                        }
                        t = t.parentNode;
                    }
                });
            }
        }

        // Función para guardar el estado de accesibilidad
        function guardarTamañoLetra() {
            // Guardar el estado actual del contraste
            const contrasteActivo = document.body.classList.contains('contrast-govco');
            localStorage.setItem('accesibilidad_contraste', contrasteActivo);

            // Guardar el estado del alto contraste
            const altoContrasteActivo = document.documentElement.classList.contains('alto-contraste');
            localStorage.setItem('accesibilidad_alto_contraste', altoContrasteActivo);

            // Guardar el contador actual del tamaño de fuente
            localStorage.setItem('accesibilidad_contador_fuente', window.accesibilityBarCounterFontSize || 0);
            
            console.log('✓ GUARDADO EN LOCALSTORAGE:', {
                contador: window.accesibilityBarCounterFontSize || 0,
                altoContraste: altoContrasteActivo,
                contraste: contrasteActivo
            });
        }

        // Función para aplicar el estado guardado de accesibilidad
        function aplicarTamañoLetraGuardado() {
            // Aplicar contraste guardado
            const contrasteGuardado = localStorage.getItem('accesibilidad_contraste');
            if (contrasteGuardado === 'true') {
                document.body.classList.add('contrast-govco');
            }

            // Aplicar alto contraste guardado
            const altoContrasteGuardado = localStorage.getItem('accesibilidad_alto_contraste');
            if (altoContrasteGuardado === 'true') {
                document.documentElement.classList.add('alto-contraste');
            }

            // Aplicar contador de fuente guardado y reconstruir cambios
            const contadorGuardado = parseInt(localStorage.getItem('accesibilidad_contador_fuente')) || 0;
            accesibilityBarCounterFontSize = contadorGuardado; // Actualizar variable global
            if (contadorGuardado !== 0) {
                console.log('Restaurando escala de fuente con contador:', contadorGuardado);
                aplicarEscalaFuente(contadorGuardado);
            }

            updateActiveButtons();
        }

        // Funciones para el Toast de notificación
        function updateActiveButtons() {
            // Remover clase active de todos los botones
            document.querySelectorAll('.barra-accesibilidad-govco button').forEach(btn => {
                btn.classList.remove('active');
            });

            // Activar contraste si está activo
            if (document.body.classList.contains('contrast-govco')) {
                document.querySelector('.barra-accesibilidad-govco button.contrast')?.classList.add('active');
            }

            // Activar alto contraste si está activo
            if (document.documentElement.classList.contains('alto-contraste')) {
                document.querySelector('.barra-accesibilidad-govco button.contrast')?.classList.add('active');
            }

            // Activar botones de fuente según el contador
            const counter = window.accesibilityBarCounterFontSize || 0;
            if (counter < 0) {
                document.querySelector('.barra-accesibilidad-govco button.decrease-font-size')?.classList.add('active');
            } else if (counter > 0) {
                document.querySelector('.barra-accesibilidad-govco button.increase-font-size')?.classList.add('active');
            }
        }

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Aplicar configuración guardada primero
            aplicarTamañoLetraGuardado();
            
            // Inicializar barra de accesibilidad después
            initAccessibilityBar();
        });

        function initAccessibilityBar() {
            addEventsAccessibilityBar();

            addEventHandler(
                document.body,
                'click keydown',
                '.barra-accesibilidad-govco',
                function(event) {
                    addEventsAccessibilityBar(event);
                }
            );
        }

        function addEventsAccessibilityBar(event = null) {
            const bars = document.querySelectorAll('.barra-accesibilidad-govco:not(.actived-events-govco)');
            for (const bar of bars) {
                bar.classList.add('actived-events-govco');

                const constrast = bar.querySelectorAll('button.contrast');
                methodAssign("click", activeContrast, constrast);

                const decrease = bar.querySelectorAll('button.decrease-font-size');
                methodAssign("click", activeFontSize, decrease);

                const increase = bar.querySelectorAll('button.increase-font-size');
                methodAssign("click", activeFontSize, increase);
            }

            if (event == null || bars.length == 0) {
                return false;
            }

            let element = '';
            if (event.target.classList.contains('button.contrast', 'button.decrease-font-size', 'button.increase-font-size')) {
                element = event.target;
            } else if (event.target.closest('.barra-accesibilidad-govco')) {
                element = event.target.closest('button');
            }

            if (element) {
                element.click();
            }
        }

        function activeContrast() {
            const htmlElement = document.documentElement;
            const bodyElement = document.querySelector('body');
            
            // Toggle del alto contraste (clase en html)
            if (htmlElement.classList.contains('alto-contraste')) {
                htmlElement.classList.remove('alto-contraste');
            } else {
                htmlElement.classList.add('alto-contraste');
            }
            
            // Mantener compatibilidad con clase antigua en body
            if (bodyElement.classList.contains('contrast-govco')) {
                bodyElement.classList.remove('contrast-govco');
            } else {
                bodyElement.classList.add('contrast-govco');
            }
            
            activeButtonAccessibility(this);
            guardarTamañoLetra(); // Guardar estado
        }

        window.accesibilityBarCounterFontSize = parseInt(localStorage.getItem('accesibilidad_contador_fuente')) || 0;

        function activeFontSize() {
            let addition = this.classList.contains('decrease-font-size') ? -1 : 1;
            const decreaseLimit = parseInt(this.getAttribute('data-decrease-limit')) || -5;
            const increaseLimit = parseInt(this.getAttribute('data-increase-limit')) || 5;

            window.accesibilityBarCounterFontSize += addition;

            if (window.accesibilityBarCounterFontSize >= decreaseLimit && window.accesibilityBarCounterFontSize <= increaseLimit) {
                aplicarEscalaFuente(window.accesibilityBarCounterFontSize);
            } else {
                window.accesibilityBarCounterFontSize = addition > 0 ? increaseLimit : decreaseLimit;
                aplicarEscalaFuente(window.accesibilityBarCounterFontSize);
            }

            activeButtonAccessibility(this);
            guardarTamañoLetra(); // Guardar estado
        }

        function aplicarEscalaFuente(contador) {
            // Escala global: cada unidad = 6.25% de cambio
            // -5 = 68.75% (muy pequeño), +5 = 131.25% (muy grande)
            const escala = 1 + (contador * 0.0625);
            console.log('Aplicando escala de fuente:', escala, 'para contador:', contador);
            document.documentElement.style.fontSize = (escala * 16) + 'px';
        }

        function activeButtonAccessibility(element) {
            element.parentNode.querySelector('.active')?.classList.remove('active');
            element.classList.add('active');
        }

        // Funciones para el Toast de notificación
        function openToast() {
          // Abrir el contenedor del toast de error
          const errorToastContainer = document.getElementById('fixedtoast').closest('.container-toast-interactivo');
          if (errorToastContainer) {
              errorToastContainer.classList.remove('d-none');
              document.getElementById('fixedtoast').classList.add('show');
          }
        }

        function closeToast() {
            // Solo cerrar el toast de error (primer contenedor)
            const errorToast = document.querySelector('.container-toast-interactivo:first-child');
            if (errorToast) {
                errorToast.classList.add('d-none');
                errorToast.firstElementChild.classList.remove('show');
            }
        }

        // Función para mostrar errores de validación en el toast
        function showValidationErrors() {
            // Buscar errores tanto en alertas generales como en campos individuales
            const generalErrors = document.querySelectorAll('.alert-danger li');
            const fieldErrors = document.querySelectorAll('.text-danger');

            const allErrors = [...generalErrors, ...fieldErrors];

            if (allErrors.length > 0) {
                const messages = Array.from(allErrors).map(el => el.textContent.trim()).filter(msg => msg).join('\n');
                document.getElementById('toast-message').textContent = messages;
                openToast();

                // Cerrar automáticamente después de 5 segundos
                setTimeout(closeToast, 5000);
            }
        }

        // Función para mostrar toast de éxito
        function showSuccessToast(message) {
            const successToastMessage = document.getElementById('success-toast-message');
            if (successToastMessage) {
                successToastMessage.textContent = message;
                openSuccessToast();

                // Cerrar automáticamente después de 4 segundos
                setTimeout(closeSuccessToast, 4000);
            }
        }

        // Función para abrir toast de éxito
        function openSuccessToast() {
            // Abrir el contenedor del toast de éxito
            const successToastContainer = document.getElementById('successtoast').closest('.container-toast-interactivo');
            if (successToastContainer) {
                successToastContainer.classList.remove('d-none');
                document.getElementById('successtoast').classList.add('show');
            }
        }

        // Función para cerrar toast de éxito
        function closeSuccessToast() {
            // Cerrar el contenedor del toast de éxito
            const successToastContainer = document.getElementById('successtoast').closest('.container-toast-interactivo');
            if (successToastContainer) {
                successToastContainer.classList.add('d-none');
                document.getElementById('successtoast').classList.remove('show');
            }
        }

        // Función para ocultar todos los toasts (utilidad general)
        function ocultarTodosLosToasts() {
            const allToasts = document.querySelectorAll('.container-toast-interactivo');
            allToasts.forEach(toast => {
                toast.classList.add('d-none');
                toast.firstElementChild?.classList.remove('show');
            });
        }

        // Ejecutar cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            // Aplicar configuración guardada de accesibilidad
            aplicarTamañoLetraGuardado();
            
            // Mostrar toast si hay errores de validación
            showValidationErrors();
        });
    </script>

</body>
</html>

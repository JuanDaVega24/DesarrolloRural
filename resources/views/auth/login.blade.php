<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Alcaldía de Bucaramanga</title>

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Work Sans Font -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --verde-principal: #4A7C2F;
            --azul-govco: #3366CC;
            --gris-texto: #333333;
            --gris-claro: #F5F5F5;
              --govcolor-cobalt: #0943B5;
  --govcolor-white: #FFFFFF;
  --govcolor-havelock-lue: #4672C8;
        }


/* Nunito_Sans-Regular */
@font-face {
  font-family: 'Nunito_Sans-Regular';
  src: url('../assets/fonts/Nunito_Sans/static/NunitoSans-Regular.ttf');
}


        body {
            background: linear-gradient(135deg, #f0f4f8, #ffffff);
            font-family: 'Work Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .login-box {
            margin-top: 120px;
            width: 100%;
            height: auto;
            min-height: 700px;
            max-width: 640px;
            background: white;
            padding: 40px 35px;
            border-radius: 18px;
            box-shadow: 0 8px 35px rgba(0,0,0,0.15);
            animation: floatIn 0.6s ease-out;
        }

        @keyframes floatIn {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .login-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--gris-texto);
            margin-bottom: 10px;
        }

        .login-subtitle {
            text-align: center;
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 45px;
            border: 2px solid #ddd;
            transition: .3s;
        }

        .form-control:focus {
            border-color: var(--azul-govco);
            box-shadow: 0 0 10px rgba(51, 102, 204, 0.3);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: var(--azul-govco);
            font-size: 1rem;
            opacity: 0.9;
        }

        .btn-login {
            width: 100%;
            background: var(--verde-principal);
            border: none;
            padding: 13px;
            color: white;
            font-weight: 700;
            border-radius: 12px;
            font-size: 1.1rem;
            margin-top: 10px;
            transition: .3s;
        }

        .btn-login:hover {
            background: #2f5a1f;
            transform: translateY(-3px);
            box-shadow: 0 7px 18px rgba(0,0,0,0.2);
        }

        .forgot-link {
            text-decoration: none;
            color: var(--azul-govco);
            font-weight: 600;
            font-size: 0.9rem;
            transition: .2s;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .gov-header {
            background-color: var(--azul-govco);
            padding: 12px 0;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .gov-header .gov-logo {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
        }

        .alcaldia-header {
            margin-top: 20px;
            text-align: center;
        }

        .escudo {
            width: 150px;
            height: 200px;
            margin: 0 auto;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 5rem;
        }

     

.barra-superior-govco {
  background-color: var(--govcolor-cobalt);
  width: 100%;
  height: 3.5rem;
  padding-left: 3.75rem;
  position: fixed;
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

        /* Ajustar el body para que no se oculte bajo la barra fija */
       
        
    </style>
</head>
<body>

<!-- GOV.CO HEADER 
<div class="gov-header">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="gov-logo">
            <i class="fas fa-landmark"></i> GOV.CO
        </span>
    </div>
</div>

-->

<div class="barra-superior-govco">
  <a href="https://www.gov.co/" target="_blank" rel=noopener
    aria-label="Portal del Estado Colombiano - GOV.CO"><img src="{{ asset('images/logo.svg') }}" alt="logo"></a> 

</div>

<div class="login-box">

    <div class="alcaldia-header">
        <div class="escudo">
    <img src="{{ asset('images/logo-DesarrolloDelCampo.png') }}" alt="Escudo" style="width:150px; height:120px;">
</div>
    </div>

    <h2 class="login-title">Iniciar Sesión</h2>
    <p class="login-subtitle">Accede al Sistema de Información</p>

    <!-- Formulario Jetstream/Login -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3 position-relative">
            <i class="fa-solid fa-user input-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required autofocus>
        </div>

        <!-- Password -->
        <div class="mb-3 position-relative">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        </div>

        <button type="submit" class="btn-login">
            <i class="fa-solid fa-right-to-bracket me-2"></i> Entrar
        </button>

     

    </form>
    @if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

</div>

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
        font-family: 'Nunito_Sans-Regular';
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
        background-image: url('{{ asset('assets/icons/adjust.svg') }}');
    }
    .barra-accesibilidad-govco button span.govco-font-minimize {
        background-image: url('{{ asset('assets/icons/font-minimize.svg') }}');
    }

    .barra-accesibilidad-govco button span.govco-font-maximize {
        background-image: url('{{ asset('assets/icons/font-maximize.svg') }}');
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

     .barra-accesibilidad-govco button span.govco-sign-language {
            background-image: url(/assets/icons/channels-616_icon_centro_relevo.svg);
        }
    .barra-accesibilidad-govco button.sign-language::before {
            content: "Lenguaje de señas";
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
        outline: max(0.125rem, 0.125rem) solid var(--govcolor-cobalt);
        outline-offset: max(0.188rem, 0.188rem);
    }

    .barra-accesibilidad-govco button.active {
        background-color: #B5C7E9;
        box-shadow: 0 0.188rem 0.375rem #00000029;
    }

    .contrast-govco .accesibility-example {
        background-color: var(--govcolor-cobalt);
    }

    .contrast-govco .accesibility-example p {
        color: var(--govcolor-white);
    }

    

    /* Verdana-Regular */
    @font-face {
      font-family: 'Nunito_Sans-Regular';
      src: url('{{ asset('assets/fonts/Nunito_Sans/static/NunitoSans-Regular.ttf') }}');
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
    if (contadorGuardado !== 0) {
        window.accesibilityBarCounterFontSize = contadorGuardado;
        // Aplicar todos los cambios acumulados
        aplicarCambiosPersistentes(contadorGuardado);
    }

    updateActiveButtons();
}

// Función para aplicar cambios persistentes de fuente
function aplicarCambiosPersistentes(contador) {
    const elementos = document.querySelectorAll('body *');
    elementos.forEach(elemento => {
        // Aplicar el cambio acumulado (contador * 1px por click)
        const cambioTotal = contador * 1; // 1px por cada unidad del contador
        if (cambioTotal !== 0) {
            let fontSize = getFontSize(elemento);
            fontSize = (fontSize + cambioTotal) + 'px';
            elemento.style.fontSize = fontSize;
        }
    });
}

function updateActiveButtons() {
    // Remover clase active de todos los botones
    document.querySelectorAll('.barra-accesibilidad-govco button').forEach(btn => {
        btn.classList.remove('active');
    });

    // Activar contraste si está activo
    if (document.body.classList.contains('contrast-govco')) {
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
    initAccessibilityBar();
    // Aplicar configuración guardada con un pequeño delay para asegurar que todo esté renderizado
    setTimeout(aplicarTamañoLetraGuardado, 50);
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

let accesibilityBarCounterFontSize = 0;

function activeFontSize() {
    let addition = this.classList.contains('decrease-font-size') ? -1 : 1;
    const decreaseLimit = parseInt(this.getAttribute('data-decrease-limit')) || -5;
    const increaseLimit = parseInt(this.getAttribute('data-increase-limit')) || 5;

    accesibilityBarCounterFontSize += addition;

    if (accesibilityBarCounterFontSize >= decreaseLimit && accesibilityBarCounterFontSize <= increaseLimit) {
        const elements = document.querySelectorAll('body *');
        for (const element of elements) {
            changeFontSize(element, addition);
        }
    } else {
        accesibilityBarCounterFontSize = addition > 0 ? increaseLimit : decreaseLimit;
    }

    activeButtonAccessibility(this);
    guardarTamañoLetra(); // Guardar estado
}

function changeFontSize(element, increse) {
    let fontSize = getFontSize(element);
    fontSize = (fontSize + increse) + 'px';
    element.style.fontSize = fontSize;
}

function getFontSize(element) {
    const fontSize = window.getComputedStyle(element, null).getPropertyValue('font-size');
    return parseFloat(fontSize);
}

function activeButtonAccessibility(element) {
    element.parentNode.querySelector('.active')?.classList.remove('active');
    element.classList.add('active');
}


</script>

</body>
</html>

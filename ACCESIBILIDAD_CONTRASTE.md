# Implementación de Modo de Alto Contraste

## Descripción
Se ha implementado un sistema de modo de alto contraste (accesibilidad) que invierte los colores de toda la aplicación para mejorar la legibilidad de usuarios con deficiencias visuales. El cambio persiste en todas las páginas mediante localStorage.

## Cambios Realizados

### 1. CSS Global (resources/css/custom-theme.css)
Se agregó una nueva sección **"17. MODO DE ALTO CONTRASTE - ACCESIBILIDAD"** al final del archivo (líneas 787-846):

**Características:**
- Selector `html.alto-contraste` que invierte todos los colores CSS
- Variables invertidas para:
  - Colores primarios: --verde, --azul, --negro
  - Colores neutrales: --blanco, --gris
  - Colores GOV.CO: --govcolor-cobalt, --govcolor-green, --govcolor-red
  - Colores adicionales: --rojo, --naranja, --beige
- Usa `filter: invert(1) hue-rotate(180deg)` para invertir colores de manera inteligente
- Protege imágenes, videos y elementos multimedia para que no se invierta su contenido accidentalmente

**Ejemplo de CSS:**
```css
html.alto-contraste {
    --verde: #B5839D;
    --azul: #CC99FF;
    --negro: #E8E8E8;
    --blanco: #000000;
    background-color: var(--blanco);
    color: var(--negro);
    filter: invert(1) hue-rotate(180deg);
}

html.alto-contraste img,
html.alto-contraste video,
html.alto-contraste canvas,
html.alto-contraste svg {
    filter: invert(1) hue-rotate(180deg);
}
```

### 2. JavaScript (resources/views/layouts/app.blade.php)

#### a) Función `guardarTamañoLetra()` (línea 787)
Actualizada para guardar el estado del alto contraste en localStorage:
```javascript
const altoContrasteActivo = document.documentElement.classList.contains('alto-contraste');
localStorage.setItem('accesibilidad_alto_contraste', altoContrasteActivo);
```

#### b) Función `aplicarTamañoLetraGuardado()` (línea 803)
Restaura el estado del alto contraste al cargar la página:
```javascript
const altoContrasteGuardado = localStorage.getItem('accesibilidad_alto_contraste');
if (altoContrasteGuardado === 'true') {
    document.documentElement.classList.add('alto-contraste');
}
```

#### c) Función `updateActiveButtons()` (línea 852)
Marca el botón como activo cuando el modo de alto contraste está habilitado:
```javascript
if (document.documentElement.classList.contains('alto-contraste')) {
    document.querySelector('.barra-accesibilidad-govco button.contrast')?.classList.add('active');
}
```

#### d) Función `activeContrast()` (línea 916)
Maneja el toggle del alto contraste:
```javascript
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
```

## Funcionalidad

### Flujo de Uso
1. **Usuario hace clic en botón de contraste**
   - Ubicado en la barra de accesibilidad GOV.CO (esquina superior derecha)
   - Selector: `.contrast` en `<div class="barra-accesibilidad-govco-container">`

2. **El JavaScript ejecuta `activeContrast()`**
   - Agrega/elimina clase `alto-contraste` al elemento `<html>`
   - Guarda el estado en `localStorage.setItem('accesibilidad_alto_contraste', true/false)`
   - Marca el botón como "activo" visualmente

3. **Los CSS se aplican automáticamente**
   - El selector `html.alto-contraste` invierte todos los colores
   - Las imágenes se reinvierten para verse normales
   - Los elementos sensibles al cambio se reinvierten (SVG, canvas, video)

4. **Persistencia entre páginas**
   - Al navegar a otra página, `aplicarTamañoLetraGuardado()` se ejecuta
   - Restaura el estado guardado en localStorage
   - El modo de alto contraste se mantiene activo

## Archivos Modificados

1. **resources/css/custom-theme.css**
   - Líneas 783-846: Nuevo bloque "MODO DE ALTO CONTRASTE"
   - Variables invertidas para todos los colores
   - Reglas para mantener contenido multimedia legible

2. **resources/views/layouts/app.blade.php**
   - Línea 787: `guardarTamañoLetra()` - Agregar guardado de alto-contraste
   - Línea 803: `aplicarTamañoLetraGuardado()` - Agregar aplicación de alto-contraste
   - Línea 852: `updateActiveButtons()` - Agregar verificación de alto-contraste
   - Línea 916: `activeContrast()` - Reescrita para manejar alto-contraste en html element

## Notas Técnicas

- **Clase aplicada a:** `<html>` element, no al `<body>`
  - Esto permite que los estilos se apliquen a toda la página incluido el iframe si existe
  - Proporciona mayor alcance que aplicarlo solo al body

- **Filter: invert(1) hue-rotate(180deg)**
  - `invert(1)`: Invierte todos los colores RGB
  - `hue-rotate(180deg)`: Rota el matiz para que los complementarios sean los opuestos
  - Combinados crean una inversión inteligente que mantiene el contraste

- **localStorage:**
  - Clave: `accesibilidad_alto_contraste`
  - Valor: `'true'` o `'false'` (strings)
  - Persiste entre navegaciones y cierres de navegador
  - Específico por dominio (cada sitio tiene su propio valor)

- **Compatibilidad:**
  - Se mantiene la clase `contrast-govco` en body para compatibilidad con código existente
  - El CSS antiguo (GOV.CO) sigue funcionando

## Prueba de Funcionamiento

### Pasos para verificar:
1. Navegar a cualquier página de la aplicación
2. Hacer clic en el botón "Contraste" (barra de accesibilidad GOV.CO)
3. Verificar que:
   - Los colores se invierten
   - El botón muestra estado "activo"
   - Al navegar a otra página, el modo se mantiene
   - Al recargar la página (F5), el modo persiste
   - Cerrar y abrir navegador mantiene el estado

## Mejoras Futuras (Opcional)

- Agregar animación suave en la transición de colores
- Agregar más opciones de contraste (alto, muy alto, bajo)
- Integrar con preferencias del navegador (prefers-contrast media query)
- Agregar sonido de confirmación cuando se activa/desactiva
- Crear panel de accesibilidad expandible con más opciones


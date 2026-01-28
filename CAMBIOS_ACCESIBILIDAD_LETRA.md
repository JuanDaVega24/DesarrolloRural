# Persistencia de Cambios de Tamaño de Letra - Accesibilidad

## Descripción
Se ha mejorado el sistema de accesibilidad para que los cambios de tamaño de letra **persistan en todas las páginas** cuando el usuario los activa. Ahora los botones de aumentar/disminuir tamaño de fuente mantienen el cambio al navegar entre páginas.

## Problemas Solucionados

1. **Variable Global no se Inicializaba**: `accesibilityBarCounterFontSize` ahora se inicializa desde localStorage
2. **Función de Restauración Deficiente**: Mejorada `aplicarCambiosPersistentes()` para aplicar cambios correctamente
3. **Orden de Ejecución**: Ahora `aplicarTamañoLetraGuardado()` se ejecuta ANTES de `initAccessibilityBar()`
4. **Botones sin Estado**: Ahora `updateActiveButtons()` se llama siempre al cargar la página

## Cambios Técnicos Realizados

### 1. Inicialización de Variable Global (Línea 936)
**Antes:**
```javascript
let accesibilityBarCounterFontSize = 0;
```

**Después:**
```javascript
let accesibilityBarCounterFontSize = parseInt(localStorage.getItem('accesibilidad_contador_fuente')) || 0;
```

**Beneficio:** La variable recupera el valor guardado al cargar cualquier página, manteniendo el contador correcto.

---

### 2. Función `aplicarTamañoLetraGuardado()` (Línea 803)
**Cambios:**
- Se agregó asignación explícita: `accesibilityBarCounterFontSize = contadorGuardado;`
- Se llama a `updateActiveButtons()` al final para mostrar estado correcto de botones

**Resultado:** Los botones de aumentar/disminuir muestran el estado "activo" correcto al cargar la página.

---

### 3. Función `aplicarCambiosPersistentes()` (Línea 819)
**Mejora:**
- Eliminado cálculo innecesario de `cambioTotal`
- Aplicación más directa: `let nuevoTamanio = fontSize + contador;`
- Se agregó validación de `contador === 0`

**Beneficio:** Cambios más confiables y directos al restaurar tamaños guardados.

---

### 4. Orden de Ejecución en DOMContentLoaded (Línea 1005)
**Antes:**
```javascript
document.addEventListener('DOMContentLoaded', function() {
    initAccessibilityBar();
    setTimeout(aplicarTamañoLetraGuardado, 50); // Delay innecesario
});
```

**Después:**
```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar configuración guardada primero
    aplicarTamañoLetraGuardado();
    
    // Inicializar barra de accesibilidad después
    initAccessibilityBar();
});
```

**Beneficio:** Configuración se aplica antes de inicializar eventos, sin delays.

---

## Flujo de Funcionamiento

### Primer Acceso a Página
```
1. DOMContentLoaded se dispara
2. aplicarTamañoLetraGuardado() se ejecuta
   - Obtiene contador de localStorage
   - Asigna a accesibilityBarCounterFontSize
   - Aplica cambios de tamaño
   - Marca botones como activos
3. initAccessibilityBar() agrega event listeners
```

### Usuario Hace Clic en Botón
```
1. activeFontSize() se ejecuta
2. Cambia accesibilityBarCounterFontSize
3. Aplica cambios a todos los elementos
4. Marca botón como activo
5. guardarTamañoLetra() guarda en localStorage
```

### Navega a Otra Página
```
1. Nueva página carga (DOMContentLoaded)
2. aplicarTamañoLetraGuardado() obtiene valores de localStorage
3. Restaura contador: accesibilityBarCounterFontSize = valor guardado
4. Aplica cambios de tamaño automáticamente
5. Botones muestran estado correcto
```

---

## Comportamiento Esperado

### Pasos de Prueba:
1. ✅ Ir a cualquier página de la aplicación
2. ✅ Hacer clic en botón "Aumentar letra" (+ varias veces)
3. ✅ Verificar que la letra crece y el botón muestra estado "activo"
4. ✅ Navegar a otra página (ej: reportes, formularios)
5. ✅ **El tamaño de letra persiste en la nueva página**
6. ✅ **El botón sigue mostrando estado "activo"**
7. ✅ Recargar la página (F5) - el cambio persiste
8. ✅ Cerrar navegador y volver a entrar - el cambio se mantiene
9. ✅ Hacer clic en "Disminuir letra" (-) restaura el tamaño original
10. ✅ Este cambio también persiste en todas las páginas

---

## Variables localStorage Utilizadas

| Clave | Descripción | Ejemplo |
|-------|-------------|---------|
| `accesibilidad_alto_contraste` | Estado del modo de alto contraste | `'true'` o `'false'` |
| `accesibilidad_contraste` | Estado del contraste GOV.CO | `'true'` o `'false'` |
| `accesibilidad_contador_fuente` | Contador de cambios de tamaño | `'3'` (aumentado 3 veces) |

---

## Archivos Modificados

- **resources/views/layouts/app.blade.php**
  - Línea 803: Mejora de `aplicarTamañoLetraGuardado()`
  - Línea 819: Mejora de `aplicarCambiosPersistentes()`
  - Línea 936: Inicialización de variable global desde localStorage
  - Línea 1005: Cambio de orden de ejecución en DOMContentLoaded

---

## Compatibilidad

✅ Funciona en todos los navegadores modernos que soportan localStorage
✅ Compatible con contraste GOV.CO existente
✅ No interfiere con funcionalidad de alto contraste
✅ Mantiene todos los estilos de accesibilidad previos

---

## Mejoras Futuras (Opcional)

- Agregar animación suave en cambios de tamaño
- Agregar presets (Normal, Grande, Muy Grande)
- Integrar con preferencias del navegador (prefers-font-size)
- Agregar botón "Restablecer" para volver a tamaño original
- Mostrar indicador visual del nivel de zoom actual


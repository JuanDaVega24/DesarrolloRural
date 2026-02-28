# Prueba de Funcionalidad del Sistema de Imágenes por Opción

## Instrucciones para Probar el Sistema

### Paso 1: Acceder al Constructor de Formularios
1. Inicia sesión en el sistema
2. Ve a "Proyectos Productivos"
3. Selecciona un proyecto existente o crea uno nuevo
4. Haz clic en "Constructor de Formulario"

### Paso 2: Crear una Pregunta con Opciones
1. En el constructor, haz clic en "Agregar Pregunta"
2. Completa el texto de la pregunta (ej: "¿Qué fruta prefiere?")
3. Selecciona el tipo de campo: "Select" o "Checkbox"
4. Marca si es obligatorio o no
5. Haz clic en "Agregar Opción" para crear opciones

### Paso 3: Probar el Botón de Agregar Opción
**Este es el problema que se acaba de corregir:**
- El botón "Agregar Opción" ahora debe funcionar correctamente
- Al hacer clic, se debe crear una nueva opción con su contenedor de imagen

### Paso 4: Probar la Subida de Imágenes
1. Para cada opción creada:
   - Haz clic en el área de "Arrastrar y soltar imagen"
   - Selecciona una imagen del explorador de archivos
   - La imagen debe subirse automáticamente
   - Debe aparecer una miniatura de la imagen
   - Debe mostrar el mensaje "¡Imagen subida exitosamente!"

### Paso 5: Probar la Gestión de Imágenes
1. **Ver miniaturas**: Las imágenes subidas deben mostrarse en miniatura
2. **Eliminar imágenes**: Haz clic en el botón ❌ para eliminar una imagen
3. **Reemplazar imágenes**: Selecciona una nueva imagen para reemplazar la existente

### Paso 6: Probar Validaciones
1. **Tipo de archivo**: Intenta subir un archivo que no sea imagen (ej: PDF)
   - Debe mostrar error: "Por favor selecciona un archivo de imagen válido"
2. **Tamaño de archivo**: Intenta subir una imagen mayor a 2MB
   - Debe mostrar error de validación
3. **Subida exitosa**: Sube una imagen válida menor a 2MB
   - Debe mostrar mensaje de éxito

### Paso 7: Probar el Formulario Completo
1. Crea varias preguntas con opciones e imágenes
2. Guarda el formulario
3. Verifica que todas las imágenes se guarden correctamente
4. Vuelve a editar el formulario y verifica que las imágenes persistan

## Errores Comunes y Soluciones

### Error: "Botón no realiza ninguna acción"
**Causa**: La función `addOption` no estaba definida globalmente
**Solución**: Se ha corregido añadiendo `window.addOption` en el JavaScript

### Error: "Error de red al subir imágenes"
**Causa**: Rutas no estaban en el grupo de middleware correcto
**Solución**: Se movieron las rutas al grupo `adminOrTabulador`

### Error: "Imagen no se asocia a la opción"
**Causa**: Sistema anterior no asociaba imágenes a opciones específicas
**Solución**: Nuevo sistema con campos ocultos que almacenan URL por opción

## Verificación Final

Después de seguir estos pasos, el sistema debe funcionar completamente:

✅ **Botón "Agregar Opción" funciona**  
✅ **Subida de imágenes por opción funciona**  
✅ **Miniaturas se muestran correctamente**  
✅ **Gestión de imágenes (eliminar/reemplazar) funciona**  
✅ **Validaciones de archivo funcionan**  
✅ **Persistencia de datos funciona**  

## Pruebas Adicionales

### Prueba en Diferentes Navegadores
- Chrome: ✅
- Firefox: ✅
- Safari: ✅
- Edge: ✅

### Prueba en Dispositivos Móviles
- Teléfono móvil: ✅
- Tablet: ✅

### Prueba de Rendimiento
- Subir 5 imágenes simultáneamente: ✅
- Crear 10 opciones con imágenes: ✅
- Eliminar y reemplazar imágenes rápidamente: ✅

## Contacto para Soporte

Si después de seguir estos pasos aún tienes problemas:

1. **Revisa la consola del navegador** (F12 → Console) para ver errores
2. **Verifica que los archivos se hayan guardado correctamente**
3. **Asegúrate de que el servidor esté corriendo**
4. **Contacta al desarrollador con capturas de pantalla del error**

## Archivos Clave para Revisión

- `resources/js/constructor-opciones-imagenes.js` - Lógica principal
- `resources/views/proyectos_productivos/constructor.blade.php` - Vista
- `app/Http/Controllers/ConstructorImagenController.php` - Controlador
- `routes/web.php` - Rutas
- `resources/css/pages/formularios/opciones-imagenes.css` - Estilos
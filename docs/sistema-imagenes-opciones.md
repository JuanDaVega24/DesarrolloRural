# Sistema de Imágenes por Opción en el Constructor de Formularios

## Descripción General

Se ha implementado un nuevo sistema que permite asociar imágenes a opciones específicas en preguntas de tipo "Select" y "Checkbox" en el constructor de formularios. Este sistema resuelve el problema de subida de imágenes y permite una gestión más precisa de las imágenes por opción.

## Características Principales

### 1. Subida de Imágenes por Opción
- Cada opción de una pregunta puede tener su propia imagen asociada
- Las imágenes se suben individualmente para cada opción
- Soporte para arrastrar y soltar (drag & drop)
- Validación de tipo de archivo y tamaño

### 2. Gestión de Imágenes
- Visualización en miniatura de las imágenes subidas
- Eliminación individual de imágenes
- Actualización automática de índices al agregar/eliminar opciones

### 3. Estructura de Datos
```javascript
// Antes: Array simple de imágenes
["imagen1.jpg", "imagen2.jpg"]

// Ahora: Array asociativo por opción
[
  { 
    texto: "Manzana", 
    imagen: "manzana.jpg" 
  },
  { 
    texto: "Pera", 
    imagen: "pera.jpg" 
  }
]
```

## Archivos Implementados

### Controladores
- `app/Http/Controllers/ConstructorImagenController.php` - Controlador para imágenes del constructor

### Rutas
- `routes/web.php` - Rutas para subir y eliminar imágenes en el constructor

### Vistas
- `resources/views/proyectos_productivos/constructor.blade.php` - Vista del constructor con nuevo sistema

### JavaScript
- `resources/js/constructor-opciones-imagenes.js` - Lógica para manejar imágenes por opción

### Estilos CSS
- `resources/css/pages/formularios/opciones-imagenes.css` - Estilos específicos para el nuevo sistema

## Uso del Sistema

### 1. Crear una Pregunta con Opciones
1. En el constructor de formularios, agregar una nueva pregunta
2. Seleccionar tipo de campo: "Select" o "Checkbox"
3. Hacer clic en "Agregar Opción" para crear opciones

### 2. Asociar Imágenes a Opciones
1. Para cada opción, hacer clic en el área de arrastrar y soltar
2. Seleccionar una imagen del explorador de archivos
3. La imagen se subirá automáticamente y se mostrará en miniatura

### 3. Gestionar Imágenes
- **Ver miniatura**: Las imágenes subidas se muestran en miniatura junto a la opción
- **Eliminar imagen**: Hacer clic en el botón de eliminar (❌) para remover la imagen
- **Reemplazar imagen**: Seleccionar una nueva imagen para reemplazar la existente

## Validación de Imágenes

### Tipos de Archivo Permitidos
- JPEG (.jpg, .jpeg)
- PNG (.png)
- GIF (.gif)

### Límites de Tamaño
- Máximo: 2MB por imagen
- Validación tanto en cliente como en servidor

### Mensajes de Estado
- **Éxito**: "¡Imagen subida exitosamente!"
- **Error**: "Error al subir la imagen" o "Error de red al subir la imagen"
- **Advertencia**: "Subiendo imagen..."

## Estructura HTML Generada

```html
<div class="opcion-item">
    <div class="opcion-input-group">
        <!-- Input de texto de la opción -->
        <input type="text" 
               name="preguntas[0][opciones][0][texto]" 
               class="form-control opcion-texto"
               placeholder="Texto de la opción">
        
        <!-- Contenedor de imagen de la opción -->
        <div class="contenedor-imagen-opcion">
            <!-- Área de arrastrar y soltar -->
            <div class="drop-zone-opcion">
                <input type="file" 
                       class="input-imagen-opcion" 
                       accept="image/*"
                       data-pregunta-index="0"
                       data-opcion-index="0">
            </div>
            
            <!-- Miniatura de la imagen -->
            <div class="miniatura-container" style="display: none;">
                <img class="miniatura-opcion" src="imagen.jpg" alt="Miniatura">
                <button class="btn-eliminar-imagen-opcion">Eliminar</button>
            </div>
            
            <!-- Mensaje de estado -->
            <div class="mensaje-estado-opcion"></div>
        </div>
    </div>
    
    <!-- Campo oculto para la URL de la imagen -->
    <input type="hidden" 
           name="preguntas[0][opciones][0][imagen]" 
           value="url-de-la-imagen.jpg">
</div>
```

## Solución de Problemas

### Error de Red al Subir Imágenes
**Causa**: Las rutas no estaban en el grupo de middleware correcto
**Solución**: Se movieron las rutas del constructor al grupo `adminOrTabulador`

### Imágenes No Asociadas a Opciones
**Causa**: Sistema anterior subía imágenes pero no las asociaba a opciones específicas
**Solución**: Nuevo sistema con campos ocultos que almacenan la URL de la imagen por opción

### Validación de Cédula
**Causa**: Validación en tiempo real mostraba mensajes de bloqueo incorrectos
**Solución**: Mejorada la lógica de validación para distinguir entre proyectos recientes y antiguos

## Mejoras Futuras

1. **Drag & Drop Mejorado**: Permitir arrastrar imágenes directamente al contenedor de opciones
2. **Previsualización en Tiempo Real**: Mostrar vista previa de la imagen antes de subirla
3. **Compresión de Imágenes**: Comprimir imágenes automáticamente para reducir tamaño
4. **Galería de Imágenes**: Permitir seleccionar imágenes de una galería existente
5. **Validación de Duplicados**: Evitar subir imágenes idénticas múltiples veces

## Pruebas Recomendadas

1. **Subida de Imágenes**: Probar con diferentes tipos de archivo y tamaños
2. **Gestión de Opciones**: Agregar, eliminar y reordenar opciones con imágenes
3. **Validación**: Probar con archivos inválidos y tamaños excesivos
4. **Compatibilidad**: Verificar en diferentes navegadores y dispositivos
5. **Rendimiento**: Probar con múltiples opciones e imágenes simultáneas
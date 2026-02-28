# Funcionalidad de Subida de Imágenes en Formularios

Esta funcionalidad permite a los usuarios subir imágenes en las preguntas de formularios de proyectos productivos manuales.

## Características

- **Subida múltiple**: Permite subir hasta 5 imágenes por pregunta
- **Arrastrar y soltar**: Soporte para arrastrar y soltar archivos
- **Validación**: Validación de tipo de archivo (JPEG, PNG, JPG, GIF) y tamaño máximo (2MB)
- **Vista previa**: Miniaturas de las imágenes subidas con opción de eliminación
- **Persistencia**: Las imágenes se almacenan en el sistema de archivos y se asocian a la pregunta

## Componentes

### 1. Modelo de Datos

#### FormularioPregunta
Se añadió el campo `imagenes` al modelo `FormularioPregunta`:

```php
protected $fillable = [
    'proyecto_id',
    'pregunta',
    'tipo_campo',
    'opciones',
    'imagenes', // Nuevo campo
    'es_obligatorio',
    'orden',
];

protected $casts = [
    'opciones' => 'array',
    'imagenes' => 'array', // Nuevo cast
    'es_obligatorio' => 'boolean',
    'orden' => 'integer',
];
```

### 2. Migración

Se creó una migración para añadir el campo de imágenes:

```php
Schema::table('formulario_preguntas', function (Blueprint $table) {
    $table->json('imagenes')->nullable()->after('opciones');
});
```

### 3. Controlador

#### ImagenController

Controlador que maneja las operaciones de subida y gestión de imágenes:

- **upload()**: Sube imágenes a una pregunta
- **destroy()**: Elimina una imagen de una pregunta
- **getImages()**: Obtiene las imágenes de una pregunta

**Rutas:**
- `POST /formularios/{pregunta}/imagenes` - Subir imágenes
- `DELETE /formularios/{pregunta}/imagenes` - Eliminar imagen
- `GET /formularios/{pregunta}/imagenes` - Obtener imágenes

### 4. Estilos

#### CSS (resources/css/pages/formularios/imagenes.css)

Estilos para la interfaz de subida de imágenes:
- Contenedor con borde punteado para arrastrar y soltar
- Miniaturas de imágenes con efectos hover
- Botones de acción y mensajes de estado
- Diseño responsive

### 5. JavaScript

#### formularios-imagenes.js

Clase `FormularioImagenes` que maneja la lógica de subida:
- Manejo de arrastrar y soltar
- Validación de archivos
- Subida mediante AJAX
- Vista previa de imágenes
- Eliminación de imágenes

## Uso

### En el Constructor de Formularios

Cuando se crea una pregunta en el constructor de formularios, se puede habilitar la subida de imágenes:

1. Crear una pregunta con cualquier tipo de campo
2. Las imágenes se pueden subir en el formulario de completado

### En el Formulario de Completado

1. **Seleccionar imágenes**: Hacer clic en el área de arrastrar y soltar o arrastrar archivos
2. **Validación**: El sistema valida el tipo y tamaño de los archivos
3. **Subida**: Las imágenes se suben automáticamente al servidor
4. **Vista previa**: Las imágenes aparecen en miniatura con opción de eliminación
5. **Persistencia**: Las imágenes se guardan en el proyecto y se muestran en el formulario

## Estructura de Almacenamiento

Las imágenes se almacenan en:
```
storage/app/public/proyectos/{proyecto_id}/imagenes/
```

Las URLs de las imágenes se almacenan en el campo `imagenes` de la tabla `formulario_preguntas` como un array JSON.

## Seguridad

- **Validación de tipo**: Solo se permiten imágenes JPEG, PNG, JPG y GIF
- **Límite de tamaño**: Máximo 2MB por imagen
- **Límite de cantidad**: Máximo 5 imágenes por pregunta
- **Acceso restringido**: Solo proyectos manuales pueden tener imágenes
- **Autenticación**: Requiere sesión de usuario autenticado

## Consideraciones

- Las imágenes solo están disponibles para proyectos de origen "manual"
- Las imágenes se eliminan del sistema de archivos cuando se eliminan de la pregunta
- Las URLs de las imágenes son públicas a través del enlace de storage
- El nombre de archivo incluye un timestamp para evitar colisiones

## Ejemplo de Uso

```javascript
// Inicializar la funcionalidad
const formularioImagenes = new FormularioImagenes();

// La clase se inicializa automáticamente cuando el DOM está listo
```

## Personalización

### Cambiar límite de imágenes
```javascript
this.maxImagenes = 10; // Cambiar a 10 imágenes
```

### Cambiar tamaño máximo
```php
// En el controlador
'max:4096' // Cambiar a 4MB
```

### Cambiar tipos de archivo permitidos
```php
// En el controlador
'image/jpeg,image/png,image/jpg,image/gif,image/webp'
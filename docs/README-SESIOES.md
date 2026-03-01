# Sistema de Formularios Concurrentes

## Descripción General

Este sistema resuelve el problema de concurrencia en formularios de proyectos productivos, permitiendo que múltiples usuarios trabajen simultáneamente en el mismo proyecto sin sobrescribirse los datos.

## Problema Resuelto

**Antes:** Cuando varios usuarios intentaban llenar el mismo formulario de proyecto, los datos del último usuario que guardaba sobrescribían los datos de los demás usuarios, causando pérdida de información.

**Después:** Cada usuario trabaja en su propia sesión, los datos se fusionan automáticamente al completar las sesiones, manteniendo toda la información recopilada.

## Arquitectura del Sistema

### 1. Base de Datos

#### Tabla: `formulario_sesiones`
```sql
CREATE TABLE formulario_sesiones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proyecto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    usuario_nombre VARCHAR(255) NOT NULL,
    datos_acumulados JSON NOT NULL,
    estado ENUM('activa', 'completada') DEFAULT 'activa',
    ultima_actividad TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_completacion TIMESTAMP NULL,
    INDEX idx_proyecto_usuario (proyecto_id, usuario_id),
    INDEX idx_ultima_actividad (ultima_actividad),
    FOREIGN KEY (proyecto_id) REFERENCES proyecto_productivos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 2. Componentes del Sistema

#### Modelo: `FormularioSesion`
- Gestiona las sesiones de formulario
- Maneja la persistencia de datos en tiempo real
- Controla el estado de las sesiones

#### Controlador: `FormularioSesionController`
- API REST para manejo de sesiones
- Validación de cédulas en tiempo real
- Fusión automática de datos
- Gestión de usuarios activos

#### Vista: `formularios/sesiones.blade.php`
- Interfaz de usuario para formularios concurrentes
- Muestra usuarios activos en tiempo real
- Validación de cédulas con retroalimentación visual
- Sistema de beneficiarios acumulativo

#### JavaScript: `formularios-sesiones.js`
- Lógica de cliente para manejo de sesiones
- Validación en tiempo real de cédulas
- Guardado automático de datos
- Comunicación con el servidor

### 3. Flujo de Trabajo

#### Paso 1: Acceso al Formulario
1. Usuario accede a un proyecto manual
2. Hace clic en "Formulario Concurrido"
3. Se crea una sesión activa para el usuario
4. Se muestra la interfaz de formulario concurrente

#### Paso 2: Trabajo Concurrente
1. Múltiples usuarios pueden acceder simultáneamente
2. Cada usuario ve la lista de usuarios activos
3. Validación de cédulas en tiempo real
4. Datos se guardan automáticamente cada 30 segundos
5. Beneficiarios se agregan al listado acumulado

#### Paso 3: Completación y Fusión
1. Usuario completa su sesión
2. Sistema fusiona los datos con otros usuarios
3. Se eliminan duplicados por cédula
4. Se actualiza el proyecto con todos los beneficiarios
5. Sesión se marca como completada

## Características Principales

### ✅ Concurrency Segura
- Múltiples usuarios pueden trabajar simultáneamente
- No hay sobrescritura de datos
- Sistema de sesiones aisladas

### ✅ Validación en Tiempo Real
- Validación de cédulas duplicadas
- Mensajes de advertencia claros
- Feedback visual inmediato

### ✅ Fusión Inteligente
- Eliminación automática de duplicados
- Mantenimiento de todos los datos válidos
- Actualización segura del proyecto

### ✅ Interfaz Intuitiva
- Lista de usuarios activos en tiempo real
- Sistema de beneficiarios acumulativo
- Mensajes de estado claros

### ✅ Manejo de Errores
- Conexiones perdidas
- Sesiones expiradas
- Conflictos de datos

## API Endpoints

### Sesiones
- `GET /sesiones/{proyecto}` - Obtener sesión activa
- `PUT /sesiones/{proyecto}/actualizar` - Actualizar datos
- `POST /sesiones/{proyecto}/completar` - Completar sesión
- `POST /sesiones/{proyecto}/fusionar` - Fusionar sesiones

### Validación
- `POST /sesiones/{proyecto}/validar-cedula` - Validar cédula

### Usuarios Activos
- `GET /sesiones/{proyecto}/usuarios-activos` - Listar usuarios activos

## Instalación y Configuración

### 1. Migraciones
```bash
php artisan migrate
```

### 2. Rutas
Las rutas están definidas en `routes/web.php` bajo el prefijo `sesiones`.

### 3. Permisos
El sistema utiliza el middleware de autenticación de Laravel.

### 4. Archivos Necesarios
- `database/migrations/2026_02_28_000000_create_formulario_sesiones_table.php`
- `app/Models/FormularioSesion.php`
- `app/Http/Controllers/FormularioSesionController.php`
- `resources/views/formularios/sesiones.blade.php`
- `resources/js/formularios-sesiones.js`
- `resources/css/pages/formularios/sesiones.css`

## Pruebas

### Prueba Básica
1. Crear proyecto manual
2. Configurar preguntas
3. Acceder al formulario de sesiones
4. Verificar creación de sesión
5. Llenar formulario y completar

### Prueba de Concurrency
1. Abrir formulario en dos navegadores
2. Verificar usuarios activos
3. Validar cédulas duplicadas
4. Completar ambas sesiones
5. Verificar fusión correcta

## Consideraciones de Seguridad

### Validación de Datos
- Validación de cédulas duplicadas
- Control de acceso por usuario autenticado
- Validación de proyectos manuales

### Manejo de Sesiones
- Expiración automática por inactividad
- Limpieza de sesiones antiguas
- Control de concurrencia

### Integridad de Datos
- Eliminación de duplicados por cédula
- Validación de datos antes de fusionar
- Backup de datos originales

## Performance

### Optimizaciones
- Consultas SQL optimizadas
- Uso de índices en base de datos
- Validación en tiempo real con debounce
- Actualización incremental de datos

### Escalabilidad
- Sistema preparado para múltiples usuarios simultáneos
- Gestión eficiente de sesiones
- Fusión de datos optimizada

## Mantenimiento

### Limpieza de Sesiones
```bash
# Comando para limpiar sesiones expiradas
php artisan session:clear-expired
```

### Monitoreo
- Logs de actividad de sesiones
- Estadísticas de uso
- Errores y excepciones

### Actualizaciones
- Sistema modular para fácil mantenimiento
- Documentación completa
- Pruebas automatizadas

## Troubleshooting

### Problemas Comunes

#### Sesiones no se crean
- Verificar autenticación de usuario
- Comprobar permisos del proyecto
- Revisar logs de error

#### Validación de cédulas lenta
- Optimizar consultas SQL
- Verificar índices en base de datos
- Revisar carga del servidor

#### Fusión incorrecta de datos
- Verificar lógica de eliminación de duplicados
- Revisar validación de datos
- Comprobar integridad de sesiones

## Contribución

Para contribuir al sistema:

1. Crear rama de desarrollo
2. Realizar cambios
3. Probar funcionalidad
4. Crear pull request
5. Documentar cambios

## Licencia

Este sistema es parte del proyecto DesarrolloRural y sigue las mismas licencias y políticas del proyecto principal.

## Contacto

Para soporte o consultas sobre el sistema de formularios concurrentes:

- Revisar documentación en `docs/prueba-sesiones.md`
- Consultar logs del sistema
- Verificar configuración del entorno
# Prueba del Sistema de Formularios Concurrentes

## Resumen
Este documento describe cómo probar el nuevo sistema de formularios concurrentes que permite a múltiples usuarios trabajar simultáneamente en el mismo proyecto sin sobrescribirse los datos.

## Arquitectura del Sistema

### Componentes Principales

1. **Base de Datos**
   - Tabla `formulario_sesiones` - Almacena sesiones activas
   - Modelo `FormularioSesion` - Gestiona las sesiones
   - Controlador `FormularioSesionController` - API para manejo de sesiones

2. **Frontend**
   - Vista `formularios/sesiones.blade.php` - Interfaz de usuario
   - JavaScript `formularios-sesiones.js` - Lógica de cliente
   - CSS `sesiones.css` - Estilos de la interfaz

3. **Rutas**
   - `GET /proyectos/{proyecto}/sesiones` - Acceder al formulario concurrente
   - `POST /sesiones/{proyecto}/validar-cedula` - Validación en tiempo real
   - `PUT /sesiones/{proyecto}/actualizar` - Guardado automático
   - `POST /sesiones/{proyecto}/completar` - Finalizar sesión
   - `POST /sesiones/{proyecto}/fusionar` - Fusionar datos

## Pruebas Recomendadas

### 1. Prueba de Creación de Proyecto Manual
```bash
# 1. Crear un proyecto manual
# 2. Configurar preguntas en el constructor
# 3. Verificar que el botón "Formulario Concurrido" aparezca
```

### 2. Prueba de Sesión Individual
```bash
# 1. Acceder al formulario de sesiones
# 2. Verificar que se crea una sesión activa
# 3. Llenar el formulario y agregar beneficiarios
# 4. Comprobar que los datos se guardan automáticamente
# 5. Completar la sesión y verificar la fusión
```

### 3. Prueba de Concurrency (Múltiples Usuarios)
```bash
# 1. Abrir el formulario en dos navegadores diferentes
# 2. Verificar que ambos vean la lista de usuarios activos
# 3. Validar que la validación de cédulas funciona en ambos
# 4. Probar que no hay conflictos al guardar datos
# 5. Completar ambas sesiones y verificar la fusión correcta
```

### 4. Prueba de Validación de Cédulas
```bash
# 1. Ingresar una cédula que ya esté en uso
# 2. Verificar que aparezca el mensaje de advertencia
# 3. Probar con cédulas disponibles
# 4. Comprobar que el sistema detecta duplicados en tiempo real
```

### 5. Prueba de Fusión de Datos
```bash
# 1. Crear dos sesiones con diferentes beneficiarios
# 2. Completar ambas sesiones
# 3. Verificar que los datos se fusionen correctamente
# 4. Comprobar que no haya duplicados
# 5. Validar que la información del proyecto se actualice
```

## Escenarios de Prueba

### Escenario 1: Flujo Normal
1. Usuario A accede al formulario
2. Usuario B accede al formulario simultáneamente
3. Ambos llenan diferentes beneficiarios
4. Ambos completan sus sesiones
5. Los datos se fusionan correctamente

### Escenario 2: Conflicto de Cédulas
1. Usuario A ingresa cédula 12345678
2. Usuario B intenta ingresar la misma cédula
3. Sistema muestra advertencia a Usuario B
4. Usuario B corrige e ingresa cédula diferente
5. Ambos pueden continuar sin problemas

### Escenario 3: Sesión Perdida
1. Usuario abre formulario pero no interactúa
2. Sesión expira por inactividad
3. Usuario intenta guardar datos
4. Sistema maneja la expiración adecuadamente

### Escenario 4: Error de Conexión
1. Usuario está llenando formulario
2. Se pierde conexión a internet
3. Sistema maneja el error y permite reintentar
4. Datos no se pierden al restablecer conexión

## Validación de Resultados

### Verificación de Base de Datos
```sql
-- Verificar sesiones activas
SELECT * FROM formulario_sesiones WHERE proyecto_id = ? AND estado = 'activa';

-- Verificar fusión de datos
SELECT * FROM proyecto_productivos WHERE id = ?;
```

### Verificación de Logs
```bash
# Verificar logs de sesiones
tail -f storage/logs/laravel.log | grep "FormularioSesionController"

# Verificar logs de fusiones
tail -f storage/logs/laravel.log | grep "fusionarDatos"
```

### Verificación de Interfaz
- [ ] Botón "Formulario Concurrido" visible solo en proyectos manuales
- [ ] Lista de usuarios activos actualizada en tiempo real
- [ ] Validación de cédulas en tiempo real
- [ ] Mensajes de advertencia claros
- [ ] Proceso de fusión exitoso

## Posibles Problemas y Soluciones

### Problema: Sesiones no se eliminan
**Solución:** Verificar el cron job de limpieza de sesiones

### Problema: Validación de cédulas lenta
**Solución:** Optimizar consultas SQL y usar índices

### Problema: Fusión incorrecta de datos
**Solución:** Revisar lógica de eliminación de duplicados

### Problema: Conexiones WebSocket fallan
**Solución:** Verificar configuración de broadcasting

## Comandos Útiles

### Limpiar sesiones antiguas
```bash
php artisan session:clear-expired
```

### Verificar estado de sesiones
```bash
php artisan tinker
>>> App\Models\FormularioSesion::where('estado', 'activa')->count();
```

### Probar validación de cédulas
```bash
curl -X POST http://localhost:8000/sesiones/1/validar-cedula \
  -H "Content-Type: application/json" \
  -d '{"cedula": "12345678"}'
```

## Checklist de Pruebas

- [ ] Creación de proyecto manual exitosa
- [ ] Acceso al formulario de sesiones
- [ ] Creación de sesión activa
- [ ] Validación de cédulas en tiempo real
- [ ] Guardado automático de datos
- [ ] Manejo de conflictos de cédulas
- [ ] Completación de sesión
- [ ] Fusión de datos exitosa
- [ ] Eliminación de sesión al completar
- [ ] Múltiples usuarios simultáneos
- [ ] Manejo de errores de conexión
- [ ] Interfaz responsive
- [ ] Mensajes de usuario claros
- [ ] Logs de actividad

## Notas Finales

Este sistema resuelve el problema de concurrencia en formularios permitiendo:
- Múltiples usuarios trabajando simultáneamente
- Validación en tiempo real de cédulas duplicadas
- Fusión automática de datos al completar sesiones
- Interfaz intuitiva con retroalimentación visual
- Manejo robusto de errores y excepciones
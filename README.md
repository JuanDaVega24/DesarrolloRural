# Sistema de Información de Desarrollo Rural

Guía de despliegue y operación para la plataforma de gestión rural de la Alcaldía de Bucaramanga (Laravel 11 + PostgreSQL), alojada en la URL [sistemadesarrollorural.bucaramanga.gov.co](https://sistemadesarrollorural.bucaramanga.gov.co).

## ¿Qué hace esta aplicación?

Permite:

- gestionar proyectos productivos rurales,
- registrar y consultar caracterizaciones,
- diligenciar formularios asociados a proyectos,
- generar reportes y estadísticas para apoyar la toma de decisiones.

## Contacto

Soporte funcional y operativo: **desarrollorural@bucaramanga.gov.co**

## Acceso administrador por defecto

- Usuario administrador: **desarrollorural@bucaramanga.gov.co**
- Clave inicial: **12345678**

> ⚠️ Por seguridad, al primer ingreso se debe cambiar la clave de inmediato.

## Navegación rápida

- [Requerimientos compartidos](#requerimientos-compartidos)
- [Variables de entorno base](#variables-de-entorno-base)
- [Ruta A (recomendada): Docker Compose en VPS](#ruta-a-recomendada-docker-compose-en-vps)
- [Ruta B (alternativa): Apache + PHP en servidor tradicional](#ruta-b-alternativa-apache--php-en-servidor-tradicional)
- [Notas operativas y troubleshooting](#notas-operativas-y-troubleshooting)

## Requerimientos compartidos

- Git
- PostgreSQL
- Node.js 18+

### Extensiones PHP requeridas (cuando aplique PHP nativo)

Para despliegue tradicional con Apache/PHP o tareas locales fuera de contenedor:

- bcmath
- ctype
- curl
- fileinfo
- json
- mbstring
- openssl
- pdo
- pdo_pgsql
- pgsql
- tokenizer
- xml
- zip
- gd *(recomendada si el sistema maneja imágenes)*

> Nota: `mysqli` y `pdo_mysql` no son necesarias si se utiliza solo PostgreSQL.

## Variables de entorno base

Crear `.env` desde [`.env.example`](.env.example):

```bash
cp .env.example .env
```

Ejemplo base de producción:

```env
APP_NAME="Desarrollo Rural"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sistemadesarrollorural.bucaramanga.gov.co
APP_KEY=base64:REEMPLAZAR_CON_LLAVE_REAL

DB_CONNECTION=pgsql
DB_DATABASE=DesarrolloRural
DB_USERNAME=desarrollorural_user
DB_PASSWORD=CAMBIAR_PASSWORD_SEGURO
```

Variables adicionales para Docker Compose:

```env
APP_PORT=8080
RUN_SEEDERS=false
DB_BIND_ADDRESS=127.0.0.1
DB_PORT_FORWARD=5432
HOST_UID=1000
HOST_GID=1000
```

Variables típicas para Apache/PHP tradicional:

```env
DB_HOST=127.0.0.1
DB_PORT=5432
```

> Importante: no versionar `.env` ni exponer secretos en repositorio.

## Ruta A (recomendada): Docker Compose en VPS

Esta ruta usa [`docker-compose.yml`](docker-compose.yml), [`Dockerfile`](Dockerfile) y [`docker/entrypoint.sh`](docker/entrypoint.sh).

### A.1 Prerrequisitos específicos

- Docker Engine
- Docker Compose Plugin
- Puertos permitidos: `80/443` (con proxy) o `8080` (directo)

### A.2 Preparación inicial

```bash
mkdir -p docker-data/app-storage docker-data/postgres docker-data/public-uploads/constructor/temp
sudo chown -R www-data:www-data docker-data/public-uploads
sudo chown -R ${HOST_UID:-1000}:${HOST_GID:-1000} docker-data/app-storage docker-data/postgres
sudo chmod -R 775 docker-data
```

### A.3 Primer despliegue

```bash
docker compose config -q
docker compose up -d --build
```

El entrypoint ejecuta automáticamente:

- espera de base de datos,
- validación/generación de `APP_KEY`,
- `php artisan migrate --force`,
- seed opcional (`RUN_SEEDERS=true`),
- caché de configuración/rutas/vistas/eventos.

### A.4 Backup y restauración PostgreSQL

Restaurar dump custom (archivo [`DesarrolloRural.sql`](DesarrolloRural.sql)):

```bash
set -a; source .env; set +a
docker compose exec -T db psql -U "$DB_USERNAME" -d postgres -c "DROP DATABASE IF EXISTS \"$DB_DATABASE\";"
docker compose exec -T db psql -U "$DB_USERNAME" -d postgres -c "CREATE DATABASE \"$DB_DATABASE\";"
docker compose exec -T db pg_restore -U "$DB_USERNAME" -d "$DB_DATABASE" --no-owner --no-privileges < DesarrolloRural.sql
```

### A.5 Verificación

```bash
docker compose ps
docker compose logs -f app
curl -I http://127.0.0.1:${APP_PORT:-8080}
```

### A.6 Operación diaria

Actualizar versión:

```bash
git pull
docker compose up -d --build
```

Reiniciar/Detener:

```bash
docker compose restart
docker compose down
```

Logs:

```bash
docker compose logs -f app
docker compose logs -f db
```

Comandos Laravel dentro del contenedor:

```bash
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache || true
docker compose exec app php artisan view:cache
```

## Ruta B (alternativa): Apache + PHP en servidor tradicional

### B.1 Requisitos específicos

- PHP 8.3
- Composer 2.x
- Apache 2.4

### B.2 Obtener código fuente

```bash
cd /var/www/
git clone https://github.com/JuanDaVega24/DesarrolloRural.git
cd DesarrolloRural
```

### B.3 Instalar dependencias

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### B.4 Configurar aplicación

```bash
cp .env.example .env
php artisan key:generate
```

Crear base de datos (ejemplo):

```sql
CREATE DATABASE "DesarrolloRural";
```

Migrar y poblar (si aplica):

```bash
php artisan migrate --force
php artisan db:seed --force
```

Importación opcional de estructura/datos adicionales:

```bash
# SQL plano
psql -U "$DB_USERNAME" -d "$DB_DATABASE" -f DesarrolloRural.sql

# Dump custom
pg_restore -U "$DB_USERNAME" -d "$DB_DATABASE" --no-owner --no-privileges DesarrolloRural.sql
```

### B.5 Configuración Apache

Habilitar `mod_rewrite`:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

VirtualHost sugerido:

```apache
<VirtualHost *:80>
    ServerName sistemadesarrollorural.bucaramanga.gov.co
    DocumentRoot /var/www/DesarrolloRural/public

    <Directory /var/www/DesarrolloRural/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Reiniciar Apache:

```bash
sudo systemctl restart apache2
```

### B.6 Storage, permisos y optimización

Enlace de storage:

```bash
php artisan storage:link
```

Permisos:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

Optimización:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Notas operativas y troubleshooting

- Si `php artisan route:cache` falla por rutas duplicadas, la app puede seguir funcionando sin caché de rutas.
- Logs de Laravel: `docker-data/app-storage/logs/laravel.log`.

### Permisos para uploads en Docker (`/var/www/html/public/uploads`)

El volumen `./docker-data/public-uploads:/var/www/html/public/uploads` debe permitir escritura del usuario web (`www-data`) para evitar errores en `/var/www/html/public/uploads/constructor/temp`.

Comandos recomendados después de montar volumen o al solucionar errores de permisos:

```bash
mkdir -p docker-data/public-uploads/constructor/temp
sudo chown -R www-data:www-data docker-data/public-uploads
sudo chmod -R 775 docker-data/public-uploads
sudo find docker-data/public-uploads -type d -exec chmod 775 {} \;
sudo find docker-data/public-uploads -type f -exec chmod 664 {} \;
```

Validación dentro del contenedor:

```bash
docker compose exec app id www-data
docker compose exec app ls -ld /var/www/html/public/uploads /var/www/html/public/uploads/constructor/temp
docker compose exec app stat -c "%U:%G %a %n" /var/www/html/public/uploads /var/www/html/public/uploads/constructor/temp
```

Si persiste el error, reiniciar servicios y volver a aplicar permisos:

```bash
docker compose restart app
sudo chown -R www-data:www-data docker-data/public-uploads
sudo chmod -R 775 docker-data/public-uploads
```

- En `php.ini`, para producción se recomienda:

```ini
memory_limit = 512M
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 120
```

OPcache recomendado:

```ini
opcache.enable=1
opcache.enable_cli=0
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
```

## Verificación funcional final

Validar en la URL desplegada:

- inicio de sesión,
- acceso a módulos principales,
- carga/descarga de archivos (si aplica),
- exportaciones (Excel),
- escritura correcta de logs.

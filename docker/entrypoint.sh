#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/html"

cd "$APP_DIR"

# Empty APP_KEY from container env can block Laravel from reading/generated .env key.
if [[ -z "${APP_KEY:-}" ]]; then
  unset APP_KEY || true
fi

# Ensure Laravel storage tree exists when using host bind mounts (fresh/empty folders)
mkdir -p \
  storage/app/public \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

# Wait for DB if configured
if [[ -n "${DB_HOST:-}" ]]; then
  echo "Waiting for database at ${DB_HOST}:${DB_PORT_HOST:-5432}..."
  until php -r '
    $h=getenv("DB_HOST"); $p=getenv("DB_PORT_HOST") ?: "5432";
    $db=getenv("DB_DATABASE"); $u=getenv("DB_USERNAME"); $pw=getenv("DB_PASSWORD");
    $dsn="pgsql:host=$h;port=$p;dbname=$db";
    try { new PDO($dsn,$u,$pw,[PDO::ATTR_TIMEOUT=>2]); echo "ok\n"; }
    catch (Throwable $e) { exit(1); }
  ' >/dev/null 2>&1; do
    sleep 2
  done
fi

# Ensure key exists
if [[ -n "${APP_KEY:-}" ]]; then
  : # APP_KEY provided via environment; do nothing
else
  # If APP_KEY is not provided as env and .env has no key, generate one
  if ! php artisan key:show >/dev/null 2>&1; then
    php artisan key:generate --force
  fi
fi

# Storage link (idempotent)
php artisan storage:link || true

# Permissions (idempotent)
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Migrate (idempotent)
php artisan migrate --force

# Seed (optional) - controlled by RUN_SEEDERS=true
if [[ "${RUN_SEEDERS:-false}" == "true" ]]; then
  php artisan db:seed --force
fi

# Caches (safe for production)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true
php artisan event:cache || true

exec "$@"

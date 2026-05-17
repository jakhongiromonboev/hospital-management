#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/html"
SQLITE_FILE="${APP_DIR}/database/database.sqlite"
INSTALLED_FLAG="${APP_DIR}/storage/.hms-installed"

cd "${APP_DIR}"

echo "[hms-entrypoint] installing composer deps if missing..."
if [ ! -d "${APP_DIR}/vendor" ] || [ ! -f "${APP_DIR}/vendor/autoload.php" ]; then
    composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
fi

echo "[hms-entrypoint] ensuring sqlite file exists..."
mkdir -p "${APP_DIR}/database"
touch "${SQLITE_FILE}"

echo "[hms-entrypoint] ensuring storage + cache dirs exist..."
mkdir -p \
    "${APP_DIR}/storage/app/public" \
    "${APP_DIR}/storage/framework/cache/data" \
    "${APP_DIR}/storage/framework/sessions" \
    "${APP_DIR}/storage/framework/views" \
    "${APP_DIR}/storage/logs" \
    "${APP_DIR}/bootstrap/cache"

echo "[hms-entrypoint] fixing permissions for www-data..."
chown -R www-data:www-data \
    "${APP_DIR}/storage" \
    "${APP_DIR}/bootstrap/cache" \
    "${APP_DIR}/database" 2>/dev/null || true
chmod -R ug+rwX "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache" "${APP_DIR}/database" 2>/dev/null || true

if [ -z "${APP_KEY:-}" ] && ! grep -qE '^APP_KEY=base64:' "${APP_DIR}/.env" 2>/dev/null; then
    echo "[hms-entrypoint] generating APP_KEY..."
    php artisan key:generate --force
fi

echo "[hms-entrypoint] clearing stale caches..."
php artisan config:clear || true
php artisan view:clear || true
php artisan cache:clear || true

echo "[hms-entrypoint] running migrations..."
php artisan migrate --force

if [ ! -f "${INSTALLED_FLAG}" ]; then
    echo "[hms-entrypoint] first boot — seeding demo data..."
    php artisan db:seed --force
    touch "${INSTALLED_FLAG}"
    chown www-data:www-data "${INSTALLED_FLAG}" 2>/dev/null || true
else
    echo "[hms-entrypoint] demo data already seeded — skipping db:seed."
fi

echo "[hms-entrypoint] caching config + routes + views for prod..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "[hms-entrypoint] handing off to: $*"
exec "$@"

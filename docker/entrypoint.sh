#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/html"
SQLITE_FILE="${APP_DIR}/database/database.sqlite"

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

echo "[hms-entrypoint] removing any stale compiled caches..."
rm -f "${APP_DIR}/bootstrap/cache/config.php" \
      "${APP_DIR}/bootstrap/cache/routes-v7.php" \
      "${APP_DIR}/bootstrap/cache/packages.php" \
      "${APP_DIR}/bootstrap/cache/services.php"

echo "[hms-entrypoint] ensuring APP_KEY is set..."
if ! grep -qE '^APP_KEY=base64:[A-Za-z0-9+/=]+' "${APP_DIR}/.env" 2>/dev/null; then
    NEW_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    if grep -qE '^APP_KEY=' "${APP_DIR}/.env" 2>/dev/null; then
        sed -i "s|^APP_KEY=.*|APP_KEY=${NEW_KEY}|" "${APP_DIR}/.env"
    else
        printf '\nAPP_KEY=%s\n' "${NEW_KEY}" >> "${APP_DIR}/.env"
    fi
    echo "[hms-entrypoint] wrote new APP_KEY to .env"
else
    echo "[hms-entrypoint] APP_KEY already set, leaving it"
fi

echo "[hms-entrypoint] running migrations..."
php artisan migrate --force

echo "[hms-entrypoint] checking if demo seed is needed..."
USER_COUNT="$(sqlite3 "${SQLITE_FILE}" 'SELECT COUNT(*) FROM users;' 2>/dev/null || echo 0)"
if [ "${USER_COUNT}" = "0" ]; then
    echo "[hms-entrypoint] users table empty — seeding demo data..."
    php artisan db:seed --force
else
    echo "[hms-entrypoint] users table has ${USER_COUNT} row(s) — skipping seed."
fi

echo "[hms-entrypoint] caching config + routes + views for prod..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "[hms-entrypoint] handing off to: $*"
exec "$@"

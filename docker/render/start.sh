#!/bin/bash
set -e

cd /var/www/html

echo "==> Building .env from Render environment variables..."
# Always rebuild .env from process env so Render-injected vars
# (DB_HOST, APP_KEY, etc.) override anything baked into the image.
{
    printenv | grep -E '^(APP_|DB_|CACHE_|SESSION_|REDIS_|MAIL_|LOG_|QUEUE_|BROADCAST_|FILESYSTEM_|AWS_|VITE_)' \
        | while IFS='=' read -r key rest; do
            echo "${key}=\"${rest}\""
          done

    # Safe defaults if not provided by Render
    printenv APP_ENV   >/dev/null 2>&1 || echo 'APP_ENV="production"'
    printenv APP_DEBUG >/dev/null 2>&1 || echo 'APP_DEBUG="false"'
} > .env

echo "==> .env written:"
grep -E '^(APP_ENV|APP_DEBUG|DB_CONNECTION|DB_HOST|DB_PORT|DB_DATABASE|DB_USERNAME)=' .env || true

echo "==> Clearing config cache..."
php artisan config:clear 2>/dev/null || true

echo "==> Generating app key if not set..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Linking storage..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

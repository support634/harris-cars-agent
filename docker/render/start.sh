#!/bin/bash
set -euo pipefail

cd /var/www/html

echo "==> Removing any stale bootstrap cache..."
rm -f bootstrap/cache/config.php \
      bootstrap/cache/routes*.php \
      bootstrap/cache/services.php \
      bootstrap/cache/packages.php

echo "==> Building .env from Render environment variables..."
# Always overwrite .env so container env wins over the image.
cat > .env <<EOF
APP_NAME="${APP_NAME:-Harris Cars Service Center}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
LOG_LEVEL="${LOG_LEVEL:-error}"

DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-harris_cars}"
DB_USERNAME="${DB_USERNAME:-harris_user}"
DB_PASSWORD="${DB_PASSWORD:-}"

CACHE_STORE="${CACHE_STORE:-redis}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-redis}"
SESSION_DRIVER="${SESSION_DRIVER:-redis}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"
SESSION_SECURE_COOKIE="${SESSION_SECURE_COOKIE:-true}"

REDIS_HOST="${REDIS_HOST:-redis}"
REDIS_PASSWORD="${REDIS_PASSWORD:-null}"
REDIS_PORT="${REDIS_PORT:-6379}"

MAIL_MAILER="${MAIL_MAILER:-smtp}"
MAIL_HOST="${MAIL_HOST:-localhost}"
MAIL_PORT="${MAIL_PORT:-1025}"
MAIL_USERNAME="${MAIL_USERNAME:-}"
MAIL_PASSWORD="${MAIL_PASSWORD:-}"
MAIL_ENCRYPTION="${MAIL_ENCRYPTION:-tls}"
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-hello@example.com}"
MAIL_FROM_NAME="${MAIL_FROM_NAME:-Harris Cars Service Center}"
MAIL_TO_ADDRESS="${MAIL_TO_ADDRESS:-info@harriscarsservicecenter.com}"

FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"
GOOGLE_MAPS_API_KEY="${GOOGLE_MAPS_API_KEY:-}"
GOOGLE_MAPS_EMBED_URL="${GOOGLE_MAPS_EMBED_URL:-}"
ZOHO_WEBHOOK_SECRET="${ZOHO_WEBHOOK_SECRET:-}"
ZOHO_CONTACT_FORM_EMBED="${ZOHO_CONTACT_FORM_EMBED:-}"
ZOHO_BOOKING_FORM_EMBED="${ZOHO_BOOKING_FORM_EMBED:-}"
ZOHO_REVIEW_FORM_EMBED="${ZOHO_REVIEW_FORM_EMBED:-}"
ZOHO_QUOTE_FORM_EMBED="${ZOHO_QUOTE_FORM_EMBED:-}"
ADMIN_NAME="${ADMIN_NAME:-Harris Admin}"
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@harriscars.com}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-password}"
EOF

echo "==> DB settings:"
grep -E '^DB_' .env

echo "==> Generating app key if not set..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding admin user..."
php artisan db:seed --class=UserSeeder --force

echo "==> Linking storage..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

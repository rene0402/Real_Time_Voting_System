#!/bin/sh
set -e

# ---------------------------------------------------------------------------
# Ensure writable directories exist (important when storage is a mounted vol)
# ---------------------------------------------------------------------------
mkdir -p storage/framework/{sessions,views,cache} \
         storage/logs \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ---------------------------------------------------------------------------
# Laravel bootstrap
# ---------------------------------------------------------------------------

# Generate app key only if APP_KEY is not already set in the environment
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force --no-interaction
fi

# Run pending migrations
php artisan migrate --force --no-interaction

# Cache config/routes/views for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ---------------------------------------------------------------------------
# Start services
# ---------------------------------------------------------------------------

# Start PHP-FPM in the background
php-fpm -D

echo "PHP-FPM started."

# Hand off to Nginx in the foreground (PID 1)
exec nginx -g "daemon off;"

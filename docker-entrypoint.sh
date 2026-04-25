#!/bin/sh
set -e

# Generate app key if not set
php artisan key:generate --force --no-interaction 2>/dev/null || true

# Run migrations
php artisan migrate --force --no-interaction

# Create or update the admin user
php artisan create-admin-user

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start php-fpm in background, then nginx in foreground
php-fpm -D
exec nginx -g "daemon off;"

FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache nginx nodejs npm mysql-client \
    libpng-dev libjpeg-turbo-dev freetype-dev zip unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd bcmath opcache pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy and install Node dependencies, build assets
COPY package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm ci && npm run build && rm -rf node_modules

# Copy the rest of the application
COPY . .

# Run composer scripts that were skipped
RUN composer run-script post-autoload-dump

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Nginx config
RUN printf 'server {\n\
    listen ${PORT:-80};\n\
    root /var/www/html/public;\n\
    index index.php;\n\
    location / { try_files $uri $uri/ /index.php?$query_string; }\n\
    location ~ \\.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
}\n' > /etc/nginx/http.d/default.conf

COPY docker-entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

# Railway overrides the start command via the dashboard. The CMD below is the
# canonical start command that Railway should be configured to use:
#
#   sh -c "php bootstrap/create-admin.php && php -S 0.0.0.0:$PORT -t public"
#
# Running create-admin.php first ensures the admin account (ADMIN_EMAIL /
# ADMIN_PASSWORD) is upserted on every deploy before the web server starts.
# The ENTRYPOINT is kept so that plain `docker run` (without a Railway
# start-command override) still goes through the full entrypoint script.
ENTRYPOINT ["entrypoint.sh"]
CMD ["sh", "-c", "php bootstrap/create-admin.php && php -S 0.0.0.0:$PORT -t public"]

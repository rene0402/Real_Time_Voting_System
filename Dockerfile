FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache nginx nodejs npm mysql-client \
    libpng-dev libjpeg-turbo-dev freetype-dev zip unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd bcmath opcache pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

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

# Copy Nginx config and entrypoint
COPY nginx.conf /etc/nginx/nginx.conf
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["entrypoint.sh"]

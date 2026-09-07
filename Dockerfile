FROM php:8.4-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    nginx \
    gettext-base \
    && docker-php-ext-install pdo_pgsql pgsql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p \
    storage/logs \
    storage/framework/sessions \
    storage/framework/cache \
    storage/framework/views

RUN chmod -R 775 storage bootstrap/cache

RUN chown -R www-data:www-data /var/www/html

COPY nginx.conf /etc/nginx/nginx.conf

EXPOSE 8080


CMD ["sh", "-c", "echo PORT=$PORT && envsubst '$PORT' < /etc/nginx/nginx.conf > /tmp/nginx.conf && mv /tmp/nginx.conf /etc/nginx/nginx.conf && echo '--- NGINX CONFIG ---' && cat /etc/nginx/nginx.conf && echo '--- NGINX TEST ---' && nginx -t && echo '--- START PHP-FPM ---' && php-fpm -D && echo '--- START NGINX ---' && nginx -g 'daemon off;'"]
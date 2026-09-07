FROM php:8.4-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    wget \
    supervisor \
    gettext-base \
    libpq-dev \
    libzip-dev \
    nginx \
    && docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    zip \
    pcntl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p \
    storage/logs \
    storage/framework/sessions \
    storage/framework/cache \
    storage/framework/views \
    /run/php-fpm \
    /var/log/supervisor \
    /var/log/nginx

RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data /var/www/html

COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php-fpm.conf /usr/local/etc/php-fpm.d/docker.conf
COPY entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

EXPOSE 8080

CMD ["/entrypoint.sh"]
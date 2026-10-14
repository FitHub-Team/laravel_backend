
#!/bin/bash
set -e

cd /var/www/html

echo "========================================="
echo " SuperFit API - PHP 8.4 Container"
echo "========================================="

echo "PORT: ${PORT:-8080}"
echo "APP_ENV: ${APP_ENV:-production}"

# 1. Prepare Laravel directories
echo "Preparing Laravel storage..."

mkdir -p \
    storage/logs \
    storage/framework/sessions \
    storage/framework/cache/data \
    storage/framework/views \
    storage/app/public \
    bootstrap/cache \
    /var/log/php-fpm \
    /var/log/nginx \
    /var/log/supervisor

touch storage/logs/laravel.log

# 2. Fix runtime permissions
echo "Fixing permissions..."

chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    /var/log/php-fpm

chmod -R ug+rwX storage bootstrap/cache

echo "Storage permissions configured."

# 3. Environment configuration
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "Creating .env from example..."
        cp .env.example .env
    else
        echo "No .env file found. Using Render environment."
    fi
fi

if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY is not set in Render Environment."

    if [ -f .env ]; then
        php artisan key:generate --force
    else
        echo "ERROR: Configure APP_KEY in Render."
        exit 1
    fi
fi

# 4. Clear Laravel configuration
echo "Clearing Laravel configuration..."

php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. Prepare public storage link
echo "Checking public storage link..."

if [ ! -L public/storage ]; then
    php artisan storage:link
fi

# 6. Wait for PostgreSQL
echo "Checking PostgreSQL connectivity..."

if [ -n "${DB_HOST:-}" ]; then
    DB_READY=false

    for attempt in $(seq 1 15); do
        echo "Database readiness check $attempt/15..."

        if pg_isready \
            -h "$DB_HOST" \
            -p "${DB_PORT:-5432}" \
            -U "${DB_USERNAME:-postgres}" \
            -d "${DB_DATABASE:-postgres}"; then

            DB_READY=true
            break
        fi

        sleep 2
    done

    if [ "$DB_READY" != "true" ]; then
        echo "WARNING: PostgreSQL readiness check failed."
    fi
fi

# 7. Laravel migrations
echo "Checking database migrations..."

if php artisan migrate --force; then
    echo "Database migrations completed."
else
    echo "WARNING: Database migrations failed."
    echo "Check PostgreSQL settings and Render database status."
fi

# 8. Cache application configuration
echo "Caching Laravel configuration..."

php artisan config:cache

# 9. Configure Nginx port
echo "Configuring Nginx..."

sed -i \
    "s/listen 8080/listen ${PORT:-8080}/" \
    /etc/nginx/nginx.conf

nginx -t

# 10. Final permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

echo "========================================="
echo " Starting SuperFit services"
echo "========================================="

exec /usr/bin/supervisord \
    -c /etc/supervisor/conf.d/supervisord.conf

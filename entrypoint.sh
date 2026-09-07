#!/bin/bash
set -e

echo "========================================="
echo "🚀 SuperFit API - PHP 8.4 Container"
echo "========================================="
echo ""
echo "📋 Environment:"
echo "  PORT: ${PORT:-8080}"
echo "  APP_ENV: ${APP_ENV:-production}"

echo ""
echo "📦 Setting up Laravel application..."

if [ ! -f .env ]; then
    echo "⚠️  .env not found, copying from .env.example"
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        echo "❌ .env.example not found!"
        exit 1
    fi
fi

if ! grep -q "^APP_KEY=..\{10\}" .env; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --force
fi

echo ""
echo "🗄️  Database setup..."

if [ ! -z "$DB_HOST" ]; then
    echo "⏳ Waiting for database ($DB_HOST:${DB_PORT:-5432})..."
    max_attempts=30
    attempt=0
    
    while [ $attempt -lt $max_attempts ]; do
        if pg_isready -h "$DB_HOST" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-postgres}" 2>/dev/null; then
            echo "✅ Database is ready!"
            break
        fi
        attempt=$((attempt + 1))
        sleep 1
    done
fi

echo "🔄 Running migrations..."
php artisan migrate --force 2>/dev/null || echo "⚠️  Migration warning (DB might not be ready)"

echo "🧹 Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

echo "⚡ Optimizing application..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true

echo ""
echo "🟦 Configuring PHP-FPM..."
mkdir -p /var/log/php-fpm
chown -R www-data:www-data /var/log/php-fpm

echo ""
echo "🟨 Configuring Nginx..."
sed -i "s/listen 8080/listen ${PORT:-8080}/" /etc/nginx/nginx.conf

echo "  Testing Nginx configuration..."
if nginx -t 2>&1 | grep -q "successful"; then
    echo "  ✅ Nginx config is valid"
else
    echo "  ❌ Nginx config has errors!"
    nginx -t
    exit 1
fi

echo ""
echo "ℹ️  System Information:"
echo "  PHP Version: $(php -v | head -1)"
echo "  Nginx Version: $(nginx -v 2>&1 | cut -d' ' -f3)"
echo "  Laravel Version: $(php artisan --version 2>/dev/null || echo 'Unknown')"
echo "  Working Directory: $(pwd)"
echo "  Port: ${PORT:-8080}"

echo ""
echo "========================================="
echo "🎬 Starting services..."
echo "========================================="
echo ""

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
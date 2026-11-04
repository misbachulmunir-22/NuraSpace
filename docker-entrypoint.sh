#!/bin/sh
set -e

echo "🚀 Booting NuraSpace..."

# If SQLite is requested or no remote database configured, initialize SQLite file
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DATABASE_URL" -a -z "$DB_HOST" ]; then
    echo "📁 Preparing SQLite database..."
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database/database.sqlite
fi

# Ensure storage directories exist and are writable by all users (Apache www-data / HuggingFace UID 1000)
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/app/private/memories \
         /var/www/html/storage/app/public \
         /var/www/html/storage/logs
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Run database migrations automatically in production
if [ "$RUN_MIGRATIONS" != "false" ]; then
    echo "📦 Checking and applying database migrations..."
    php artisan migrate --force --no-interaction || echo "⚠️ Migration warning: check database connectivity."
fi

# Seed initial couple data if requested
if [ "$RUN_SEEDER" = "true" ]; then
    echo "🌱 Seeding initial couple data..."
    php artisan db:seed --force --no-interaction || true
fi

# Link storage
php artisan storage:link --no-interaction || true

# Optimize cache if in production
if [ "$APP_ENV" = "production" ]; then
    echo "⚡ Optimizing Laravel caches..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

echo "💖 NuraSpace is ready to serve love stories on port ${PORT:-80}!"
exec "$@"

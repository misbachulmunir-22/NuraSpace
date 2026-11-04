#!/bin/sh
set -e

# Wait briefly for database connection if DATABASE_URL or DB_HOST is set
echo "🚀 Booting NuraSpace..."

# Run database migrations automatically in production
if [ "$RUN_MIGRATIONS" != "false" ]; then
    echo "📦 Checking and applying database migrations..."
    php artisan migrate --force --no-interaction || echo "⚠️ Migration warning: check database connectivity."
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

echo "💖 NuraSpace is ready to serve love stories!"
exec "$@"

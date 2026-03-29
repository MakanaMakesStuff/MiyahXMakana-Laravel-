#!/bin/sh
set -e

echo "Waiting for database connection..."
# Removed silencer to debug connection errors
until php artisan db:monitor; do
  echo "Database is unavailable - sleeping"
  sleep 2
done

echo "Running database migrations..."
php artisan migrate --force

echo "Caching configuration and routes..."
php artisan optimize

echo "Starting PHP-FPM..."
exec php-fpm
#!/bin/sh
set -e  # Exit immediately if a command fails

# Optional: Install deps if they are missing
if [ ! -d "vendor" ]; then 
    echo "Installing composer dependencies..."
    composer install --no-interaction --quiet
fi

echo "Waiting for database..."
until php artisan db:monitor > /dev/null 2>&1; do
  echo "Database is unavailable - sleeping"
  sleep 2
done

# Now run migration
echo "Running database migrations..."
php artisan migrate --force

# install npm dependencies if node_modules is missing
if [ ! -d "node_modules" ]; then 
	echo "Installing npm dependencies..."
	npm install --silent
fi

# Start Vite in the background
echo "Starting Vite..."
npm run dev & 

# Start PHP-FPM in the foreground
echo "Starting PHP-FPM..."
exec php-fpm
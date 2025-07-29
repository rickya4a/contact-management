#!/bin/sh

# Wait for database
while ! nc -z db 5432; do
    echo "Waiting for PostgreSQL to start..."
    sleep 1
done

# Generate application key if not set
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Start PHP-FPM
php-fpm -D

# Start Nginx
nginx -g "daemon off;"

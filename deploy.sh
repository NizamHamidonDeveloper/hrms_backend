#!/bin/bash

# Railway Deployment Script for Laravel HRMS
echo "Starting Laravel HRMS deployment..."

# Install dependencies
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key if not set
echo "Generating application key..."
php artisan key:generate --force

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache configurations
echo "Clearing and caching configurations..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Install Passport keys if not exists
echo "Setting up Laravel Passport..."
php artisan passport:keys --force

echo "Deployment completed successfully!"

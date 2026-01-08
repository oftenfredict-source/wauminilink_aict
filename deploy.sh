#!/bin/bash

# Deployment script for Waumini Link
# This script should be run on your live server

set -e  # Exit on any error

echo "🚀 Starting deployment..."

# Navigate to project directory
cd /path/to/your/project  # Update this path to your actual project path

# Pull latest changes from GitHub
echo "📥 Pulling latest changes from GitHub..."
git pull origin main

# Install/update dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear and cache configuration
echo "⚙️  Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Adjust user/group as needed

# Clear application cache
echo "🧹 Clearing cache..."
php artisan cache:clear

echo "✅ Deployment completed successfully!"


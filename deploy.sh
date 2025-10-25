#!/bin/bash
set -e

echo "🚀 Starting deployment..."

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Clear and cache config
echo "⚙️  Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force --no-interaction

# Create storage link if not exists
if [ ! -L public/storage ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

# Check QR dependencies
echo "🔍 Checking QR Code dependencies..."
php artisan qr:check || echo "⚠️  QR check warning (non-fatal)"

echo "✅ Deployment complete!"

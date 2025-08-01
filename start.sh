#!/bin/bash

# Tambahan: beri izin folder storage & cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Optional: generate key jika belum
if [ ! -f storage/oauth-private.key ]; then
  php artisan key:generate
fi

# Cache config
php artisan config:clear
php artisan config:cache
php artisan route:cache

# Auto-create upcoming periods on startup
php artisan periods:auto-create-monthly --months=6

# Start Laravel scheduler in background (untuk cron jobs)
# Note: Di production, sebaiknya gunakan proper cron job
nohup php artisan schedule:run --verbose >> storage/logs/scheduler.log 2>&1 &

# Jalankan server Laravel di port Railway
php artisan serve --host=0.0.0.0 --port=${PORT}

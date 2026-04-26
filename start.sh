#!/bin/bash
set -e

echo "🔥🔥🔥 VERSION NUEVA START.SH 🔥🔥🔥"
sleep 5

php artisan migrate --force
php artisan db:seed --force

php artisan serve --host=0.0.0.0 --port=$PORT
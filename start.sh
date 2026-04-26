#!/bin/bash
set -e

echo "🚀 Iniciando aplicación..."

# Cachear config para mejor rendimiento
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones + seeders
echo "📦 Ejecutando migraciones y seeders..."
php artisan migrate --force --seed

# Iniciar servidor
echo "🌐 Levantando servidor..."
php artisan serve --host=0.0.0.0 --port=$PORT
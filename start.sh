#!/bin/bash
set -e

echo "🚀 Iniciando aplicación..."

# 🔥 LIMPIEZA TOTAL (clave)
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 🔍 DEBUG (temporal)
echo "DB HOST:"
php artisan tinker --execute="echo config('database.connections.mysql.host');"

echo "DB NAME:"
php artisan tinker --execute="echo config('database.connections.mysql.database');"

# Cachear config
php artisan config:cache

# Migraciones + seed
echo "📦 Ejecutando migraciones y seeders..."
php artisan migrate --force --seed

# Verificar usuarios
echo "👤 Users count:"
php artisan tinker --execute="echo \App\Models\User::count();"

# Servidor
echo "🌐 Levantando servidor..."
php artisan serve --host=0.0.0.0 --port=$PORT
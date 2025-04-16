#!/bin/bash

echo "🔧 Ejecutando tareas post-deploy para Laravel..."

# Entrar al directorio del proyecto
cd /ruta/a/tu/proyecto || exit

# Limpieza de cachés
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan clear-compiled
php artisan optimize:clear

# Opcional: limpiar OPCache (PHP >= 7.0)
if php -r "exit(function_exists('opcache_reset') ? 0 : 1);"; then
    echo "⚡️ Limpiando OPCache..."
    echo "<?php opcache_reset();" > public/reset_cache.php
    curl -s http://tudominio.com/reset_cache.php > /dev/null
    rm public/reset_cache.php
else
    echo "❌ OPCache no disponible"
fi

# Regenerar autoloads
composer dump-autoload --optimize

# Si usás Docker
if docker info >/dev/null 2>&1; then
    echo "🐳 Reiniciando contenedores Docker..."
    docker-compose restart
fi

echo "✅ Post-deploy completo."

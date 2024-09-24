# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia los archivos del proyecto
COPY . .

# Otorga permisos a los archivos
RUN chown -R www-data:www-data /var/www \
    && a2enmod rewrite

# Instala las dependencias del proyecto
RUN composer install --no-scripts --no-autoloader


# Establecer permisos durante la construcción de la imagen
RUN chown -R www-data:www-data /var/www/ /var/www/
RUN chmod -R 777 /var/www/ /var/www/

# RUN php artisan migrate --seed

# Copia el archivo de configuración de Apache
COPY ./docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Expone el puerto 80 para HTTP
EXPOSE 80

# Define el comando por defecto a ejecutar cuando inicie el contenedor
CMD ["apache2-foreground"]

FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    zip \
    libcurl4 \
    libcurl4-openssl-dev \
    unzip \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd


RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY ./docker/apache/apache-config.conf /etc/apache2/sites-available/000-default.conf

ENV APACHE_DOCUMENT_ROOT /var/www/public

RUN sed -ri -e 's!/var/www!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN docker-php-ext-install pdo_mysql zip curl \
    xml 
RUN docker-php-ext-configure gd --with-freetype --with-jpeg 

USER root

# RUN cp .env.example .env


WORKDIR /var/www
COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install

RUN chown -R www-data:www-data /var/www \
    && chmod 775 -R /var/www 
    

RUN chmod -R 777 /var/www/storage

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

USER www-data

# Expose port 80 for Apache.
EXPOSE 80

# Start Apache web server.
CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]
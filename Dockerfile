FROM php:8.2-fpm

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
WORKDIR /var/www
COPY . .

# Instalar dependencias Laravel
RUN composer install --optimize-autoloader --no-interaction

# Permisos Laravel
RUN chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8080

# Ejecutar Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080
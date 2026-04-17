FROM php:8.2-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        zip \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# App
WORKDIR /var/www
COPY . .

# Instalar PHP deps
RUN composer install --optimize-autoloader --no-interaction

# 🔥 Instalar y compilar frontend
RUN npm install
RUN npm run build

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Puerto
EXPOSE 8080

# Ejecutar app
CMD php artisan serve --host=0.0.0.0 --port=8080
FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones PDO necesarias para Eloquent
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libonig-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring

# Habilitar mod_rewrite de Apache para el enrutamiento de Slim 4
RUN a2enmod rewrite

# Copiar ejecutable oficial de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/api
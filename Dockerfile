FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

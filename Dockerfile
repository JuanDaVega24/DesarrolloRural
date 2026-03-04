# syntax=docker/dockerfile:1

############################
# 1) Build assets (Node)
############################
FROM node:20-bookworm-slim AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

############################
# 2) Install PHP deps (Composer)
############################
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock* ./
# composer:2 build image may not include ext-gd; runtime image installs it.
RUN composer install --no-dev --prefer-dist --no-scripts --no-interaction --no-progress --optimize-autoloader \
    --ignore-platform-req=ext-gd \
    --ignore-platform-req=php
COPY . .

############################
# 3) Runtime (Apache + PHP)
############################
FROM php:8.3-apache

# Laravel public directory as Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# System deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions for Laravel + PostgreSQL + Zip + GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
       pdo pdo_pgsql pgsql zip bcmath gd opcache

# Apache modules
RUN a2enmod rewrite headers

# Point Apache vhost to Laravel public/ directory
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# PHP production-ish defaults (adjust as needed)
COPY docker/php.ini /usr/local/etc/php/conf.d/99-app.ini

# App code
WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

# Ensure apache user can write where needed
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Entry point
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]

FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    curl zip unzip git \
    libpng-dev libxml2-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8080

CMD php artisan config:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT

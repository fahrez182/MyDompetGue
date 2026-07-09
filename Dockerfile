FROM php:8.4-fpm

# 1. Install system dependencies + Node.js (diperlukan untuk NPM/Vite)
RUN apt-get update && apt-get install -y \
    curl zip unzip git \
    libpng-dev libxml2-dev libzip-dev libonig-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# 2. Install PHP Dependencies
RUN composer install --no-dev --optimize-autoloader

# 3. Install NPM Dependencies & Build Aset CSS/JS (Vite/Tailwind)
RUN npm install && npm run build

# 4. Set Permission Folder Laravel
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /app

EXPOSE 8080

# 5. CMD Aman untuk Railway (Clear cache dulu baru di-cache ulang saat runtime)
CMD php artisan config:clear && \
    php artisan view:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php -S 0.0.0.0:$PORT -t public

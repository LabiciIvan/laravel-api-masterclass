FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev \
    libonig-dev libxml2-dev \
    npm nodejs nginx supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Nginx config
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Supervisor config to run PHP-FPM and Nginx together
RUN mkdir -p /var/log/supervisor
COPY --chown=www-data:www-data . /var/www

COPY --chown=www-data:www-data . /var/www
CMD ["sh", "-c", "composer install && php artisan key:generate && php artisan migrate && npm install && npm run build && php artisan serve --host=0.0.0.0 --port=80"]

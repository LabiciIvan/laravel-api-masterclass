# Stage 1: Install Composer dependencies
FROM composer:2.7 AS composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --optimize-autoloader \
    --ignore-platform-reqs

# Stage 2: Install Node dependencies
FROM node:20-alpine AS node

WORKDIR /app

COPY package.json package-lock.json* ./
COPY resources/ ./resources/
COPY vite.config.js ./

RUN npm install && npm run build

# Stage 3: Build the final PHP-FPM image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/laravel-api

# Copy Composer dependencies
COPY --from=composer /app/vendor ./vendor

# Copy built assets
COPY --from=node /app/public ./public

# Copy application files
COPY . .

# Windows-specific permission fixes
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Generate application key (skip if .env exists)
RUN if [ ! -f .env ]; then \
        cp .env.example .env && \
        php artisan key:generate; \
    fi

EXPOSE 9000

CMD ["php-fpm"]
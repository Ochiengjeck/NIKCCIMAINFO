FROM php:8.4-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    oniguruma-dev \
    libxml2-dev \
    sqlite-dev \
    postgresql-dev \
    zip \
    unzip \
    nodejs \
    npm

# Configure GD with freetype + jpeg support
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install required PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pdo_sqlite \
    gd \
    mbstring \
    zip \
    opcache \
    bcmath \
    pcntl \
    xml \
    ctype \
    fileinfo \
    tokenizer

# Install Composer 2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first (layer cache)
COPY composer.json composer.lock ./
RUN composer install \
    --optimize-autoloader \
    --no-scripts \
    --no-interaction \
    --no-dev

# Copy package files and build frontend (layer cache)
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

# Copy application source
COPY . .

# Discover packages now that source is present
RUN php artisan package:discover --ansi

# Build frontend assets
RUN npm run build

# Create required storage directories
RUN mkdir -p \
    storage/app/public/cms/images \
    storage/app/public/cms/downloads \
    storage/app/public/cms/news \
    storage/app/public/cms/leadership \
    storage/app/private/receipts \
    storage/app/private/roo \
    storage/app/private/uploads \
    storage/app/private/strategic-plans \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]

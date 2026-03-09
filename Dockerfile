FROM php:8.3-cli

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    zip \
    xml \
    ctype \
    iconv \
    gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Create directories and set permissions BEFORE composer install
RUN mkdir -p /var/www/bootstrap/cache \
    && mkdir -p /var/www/storage/logs \
    && mkdir -p /var/www/storage/framework/cache \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/database \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache \
    && chmod -R 755 /var/www/database

# Create startup script
RUN echo '#!/bin/bash' > /start.sh && \
    echo 'set -e' >> /start.sh && \
    echo 'echo "Installing Composer dependencies..."' >> /start.sh && \
    echo 'composer install --no-dev --optimize-autoloader --no-interaction' >> /start.sh && \
    echo 'echo "Setting permissions..."' >> /start.sh && \
    echo 'chmod -R 755 /var/www/storage' >> /start.sh && \
    echo 'chmod -R 755 /var/www/bootstrap/cache' >> /start.sh && \
    echo 'echo "Starting Laravel server..."' >> /start.sh && \
    echo 'php artisan serve --host=0.0.0.0 --port=${PORT:-10000}' >> /start.sh && \
    chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]

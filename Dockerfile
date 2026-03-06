# Use PHP 8.3 FPM with Debian for better extension support
FROM php:8.3-fpm

# Set working directory
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
    nodejs \
    npm \
    nginx \
    supervisor \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    bcmath \
    zip \
    xml \
    ctype \
    iconv \
    intl \
    opcache \
    gd

# Install and configure opcache
RUN docker-php-ext-enable opcache && \
    { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install and build Node.js dependencies
RUN npm install && npm run production

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copy PHP-FPM configuration
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-custom.conf

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 10000

# Start supervisor to run both nginx and PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
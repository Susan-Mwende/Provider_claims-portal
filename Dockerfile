FROM php:8.3-fpm

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    nginx \
    supervisor \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    zip \
    xml \
    ctype \
    iconv

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copy PHP-FPM configuration
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-custom.conf

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create startup script
RUN echo '#!/bin/bash' > /start.sh && \
    echo 'if [ -n "$PORT" ]; then' >> /start.sh && \
    echo '  sed -i "s/listen 10000;/listen $PORT;/g" /etc/nginx/nginx.conf' >> /start.sh && \
    echo 'fi' >> /start.sh && \
    echo 'composer install --no-dev --optimize-autoloader --no-interaction' >> /start.sh && \
    echo 'chown -R www-data:www-data /var/www' >> /start.sh && \
    echo 'chmod -R 755 /var/www/storage' >> /start.sh && \
    echo 'chmod -R 755 /var/www/bootstrap/cache' >> /start.sh && \
    echo 'exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' >> /start.sh && \
    chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]

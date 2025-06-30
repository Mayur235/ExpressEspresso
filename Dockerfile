# Use the official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel application files into the container
COPY . /var/www/html

# Set Apache to serve from Laravel's public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install Laravel dependencies (for production)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# Set correct permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose Apache port
EXPOSE 80

# Run Laravel setup tasks and start Apache
CMD php artisan config:clear \
 && php artisan config:cache \
 && php artisan migrate --force \
 && php artisan db:seed --force || echo "Seeding skipped (maybe already done)" \
 && [ -L public/storage ] || php artisan storage:link \
 && apache2-foreground

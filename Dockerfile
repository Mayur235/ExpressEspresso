# Use the official PHP image with Apache
FROM php:8.2-apache

# Set working directory inside the container
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

# Enable Apache rewrite module for Laravel routing
RUN a2enmod rewrite

# Install Composer globally from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel application files into the container
COPY . /var/www/html

# Update Apache config to serve Laravel's public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install PHP dependencies without dev packages and optimize autoloader
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port 80 (Apache)
EXPOSE 80

# Run Laravel config cache, DB migrations, seeders, and start Apache
CMD php artisan config:cache \
 && php artisan migrate --force \
 && php artisan db:seed --force \
 && apache2-foreground

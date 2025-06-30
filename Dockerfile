# Use the official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install dependencies and PHP extensions
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

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app source
COPY . /var/www/html

# Set Laravel public folder as Apache's DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
 && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install PHP dependencies including dev for seeding
RUN composer install --optimize-autoloader --no-interaction --no-progress --no-scripts

# Ensure correct permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html/storage \
 && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Entry point: clear cache, run migrations & seeders, then start Apache
CMD php artisan config:clear \
 && php artisan config:cache \
 && php artisan migrate --force \
 && php artisan db:seed --force || true \
 && apache2-foreground

FROM php:8.4-apache

# Install dependencies and extensions needed for Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Enable Apache Mod Rewrite for Laravel public index parsing
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set application permissions for storage directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Bind to dynamic Port Environment variable assigned by Render/Railway
CMD sh -c "sed -i 's/Listen 80/Listen '${PORT:-80}'/g' /etc/apache2/ports.conf && apache2-foreground"
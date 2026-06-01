FROM php:8.4-cli

# Install system deps required for Composer and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    sqlite3 \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions needed for Laravel and sqlite tests
RUN docker-php-ext-install pdo pdo_sqlite mbstring zip xml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy the test runner script into the image and ensure it's executable
COPY docker/run-tests.sh /usr/local/bin/run-tests.sh
RUN chmod +x /usr/local/bin/run-tests.sh

CMD ["/usr/local/bin/run-tests.sh"]

FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx supervisor \
    freetype-dev libjpeg-turbo-dev libpng-dev \
    zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && rm -rf /var/cache/apk/*

RUN mkdir -p /run/nginx && cat > /etc/nginx/http.d/default.conf << 'EOF'
server {
    listen APP_PORT;
    root /var/www/html/public;
    index index.php;
    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

RUN cat > /etc/supervisord.conf << 'EOF'
[supervisord]
nodaemon=true
logfile=/dev/null

[program:php-fpm]
command=php-fpm -F
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0

[program:nginx]
command=nginx -g "daemon off;"
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
EOF

WORKDIR /var/www/html
COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage

CMD sh -c "sed -i 's/APP_PORT/$PORT/' /etc/nginx/http.d/default.conf \
    && php artisan config:cache \
    && php artisan route:cache \
    && supervisord -c /etc/supervisord.conf"
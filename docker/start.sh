#!/bin/sh
set -e

php artisan optimize:clear

cat > /etc/nginx/http.d/default.conf <<EOF
server {
    listen ${PORT:-8080};
    server_name _;

    root /var/www/html/public;
    index index.php index.html;

    client_max_body_size 50M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

echo "Nginx will listen on port ${PORT:-8080}."

if [ "${DB_CONNECTION}" = "mysql" ]; then
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."

    until mysqladmin ping \
        --host="${DB_HOST}" \
        --port="${DB_PORT:-3306}" \
        --user="${DB_USERNAME}" \
        --password="${DB_PASSWORD}" \
        --silent; do
        sleep 2
    done
fi

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec supervisord -c /etc/supervisord.conf

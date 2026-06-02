#!/bin/sh
set -e

echo "Starting Railway container with docker/start.sh."

export DB_CONNECTION="${DB_CONNECTION:-mysql}"
export DB_HOST="${DB_HOST:-${MYSQLHOST:-}}"
export DB_PORT="${DB_PORT:-${MYSQLPORT:-3306}}"
export DB_DATABASE="${DB_DATABASE:-${MYSQLDATABASE:-}}"
export DB_USERNAME="${DB_USERNAME:-${MYSQLUSER:-}}"
export DB_PASSWORD="${DB_PASSWORD:-${MYSQLPASSWORD:-}}"

echo "Laravel database connection: ${DB_CONNECTION} at ${DB_HOST}:${DB_PORT}."

CACHE_STORE=array php artisan optimize:clear

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

(
    echo "Running database migrations..."

    attempt=1
    until php artisan migrate --force; do
        if [ "$attempt" -ge 30 ]; then
            echo "Database migrations failed after ${attempt} attempts."
            exit 1
        fi

        echo "Database migration attempt ${attempt} failed; retrying in 2 seconds..."
        attempt=$((attempt + 1))
        sleep 2
    done

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
) &

exec supervisord -c /etc/supervisord.conf

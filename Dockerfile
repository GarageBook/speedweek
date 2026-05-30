FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libsqlite3-dev \
    libicu-dev \
    libzip-dev \
    nodejs \
    npm \
    zip \
    && docker-php-ext-install pdo_sqlite intl zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN mkdir -p database \
    /var/data \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 775 database storage bootstrap/cache \
    && chmod 664 database/database.sqlite

RUN php artisan config:clear && php artisan route:clear && php artisan view:clear

EXPOSE 10000

CMD sh -lc '
export APP_ENV="${APP_ENV:-production}"
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/var/data/database.sqlite}"
export SESSION_DRIVER="${SESSION_DRIVER:-database}"
export SESSION_LIFETIME="${SESSION_LIFETIME:-43200}"
export SESSION_EXPIRE_ON_CLOSE="${SESSION_EXPIRE_ON_CLOSE:-false}"

if [ "$APP_ENV" = "production" ] && [ "$DB_CONNECTION" = "sqlite" ] && [ "$DB_DATABASE" = "/app/database/database.sqlite" ]; then
  echo "Overriding non-persistent sqlite path to /var/data/database.sqlite"
  export DB_DATABASE=/var/data/database.sqlite
fi

if [ "$APP_ENV" = "production" ] && [ "$SESSION_DRIVER" = "file" ]; then
  echo "Overriding non-persistent session driver from file to database"
  export SESSION_DRIVER=database
fi

if [ "$DB_CONNECTION" = "sqlite" ] && [ -z "$DB_DATABASE" ]; then
  export DB_DATABASE=/var/data/database.sqlite
fi

mkdir -p /var/data "$(dirname "$DB_DATABASE")"
touch "$DB_DATABASE"

php artisan config:clear
php artisan migrate --force
php artisan db:seed --force --class=AdminUserSeeder
php artisan ops:debug-state
php artisan optimize:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan serve --host=0.0.0.0 --port=10000
'

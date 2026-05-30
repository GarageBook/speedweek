#!/bin/sh
set -e

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

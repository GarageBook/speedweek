#!/bin/sh
set -e

export APP_ENV="${APP_ENV:-production}"
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/var/data/database.sqlite}"
export SESSION_DRIVER="${SESSION_DRIVER:-database}"
export SESSION_LIFETIME="${SESSION_LIFETIME:-43200}"
export SESSION_EXPIRE_ON_CLOSE="${SESSION_EXPIRE_ON_CLOSE:-false}"
export SESSION_SECURE_COOKIE="${SESSION_SECURE_COOKIE:-true}"
export SESSION_SAME_SITE="${SESSION_SAME_SITE:-lax}"

if [ "$APP_ENV" = "production" ]; then
  if [ "$DB_CONNECTION" != "sqlite" ]; then
    echo "FATAL: production requires DB_CONNECTION=sqlite for paid Render persistent disk setup."
    exit 1
  fi

  if [ "$DB_DATABASE" != "/var/data/database.sqlite" ]; then
    echo "FATAL: production requires DB_DATABASE=/var/data/database.sqlite."
    exit 1
  fi

  if [ "$SESSION_DRIVER" != "database" ]; then
    echo "FATAL: production requires SESSION_DRIVER=database."
    exit 1
  fi
fi

mkdir -p /var/data
mkdir -p "$(dirname "$DB_DATABASE")"
touch "$DB_DATABASE"

php artisan config:clear
php artisan ops:assert-production-persistence
php artisan ops:debug-state
php artisan migrate --force
php artisan db:seed --force --class=AdminUserSeeder
php artisan optimize:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan filament:clear-cached-components
php artisan serve --host=0.0.0.0 --port=10000

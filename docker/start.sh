#!/bin/sh
set -e

export APP_ENV="${APP_ENV:-production}"
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/var/data/database.sqlite}"
export SESSION_DRIVER="${SESSION_DRIVER:-database}"
export SESSION_LIFETIME="${SESSION_LIFETIME:-43200}"
export SESSION_EXPIRE_ON_CLOSE="${SESSION_EXPIRE_ON_CLOSE:-false}"

# Never allow non-persistent sqlite path in production.
if [ "$APP_ENV" = "production" ] && [ "$DB_CONNECTION" = "sqlite" ] && [ "$DB_DATABASE" = "/app/database/database.sqlite" ]; then
  echo "FATAL: non-persistent sqlite path '/app/database/database.sqlite' is not allowed in production."
  exit 1
fi

# Ensure sqlite file path exists before Laravel boots migrations.
if [ "$DB_CONNECTION" = "sqlite" ]; then
  mkdir -p /var/data "$(dirname "$DB_DATABASE")"
  touch "$DB_DATABASE"
fi

# Make sure no stale config is used.
php artisan config:clear

# Fail-fast production guard before any migration/seed runs.
php artisan ops:assert-production-persistence

# Temporary diagnostics for live verification.
php artisan ops:debug-state

php artisan migrate --force
php artisan db:seed --force --class=AdminUserSeeder
php artisan optimize:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan serve --host=0.0.0.0 --port=10000

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

RUN mkdir -p database && touch database/database.sqlite

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000

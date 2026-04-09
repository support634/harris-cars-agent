FROM php:8.2-fpm AS php-base

RUN apt-get update && apt-get install -y \
    curl \
    git \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpng-dev \
    libpq-dev \
    libxml2-dev \
    libzip-dev \
    nginx \
    supervisor \
    unzip \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pcntl \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    zip

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

FROM php-base AS vendor

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

FROM node:20-bullseye-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js tailwind.config.js ./

# Allow app name to be passed at build time so Vite embeds it in the bundle
ARG APP_NAME="Harris Cars Service Center"
ENV VITE_APP_NAME=$APP_NAME

RUN npm run build

FROM php-base AS runtime

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /var/www/html/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

RUN composer dump-autoload \
    --no-dev \
    --optimize \
    --classmap-authoritative \
    --no-interaction \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/render/nginx.conf /etc/nginx/sites-available/default
COPY docker/render/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN chmod +x docker/render/start.sh

EXPOSE 80

CMD ["/var/www/html/docker/render/start.sh"]

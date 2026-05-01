## Build frontend assets (Vite)
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js tailwind.config.js ./
RUN npm run build

## Production image (Nginx + PHP-FPM)
FROM php:8.4-fpm-alpine

# EasyPanel passes app settings as --build-arg; persist them to runtime env.
ARG APP_NAME=DevTask
ARG APP_ENV=production
ARG APP_KEY=
ARG APP_DEBUG=false
ARG APP_URL=http://localhost
ARG APP_LOCALE=pt_BR
ARG APP_FALLBACK_LOCALE=pt_BR
ARG APP_FAKER_LOCALE=pt_BR
ARG LOG_CHANNEL=stack
ARG LOG_STACK=single
ARG LOG_LEVEL=info
ARG DB_CONNECTION=pgsql
ARG DB_HOST=127.0.0.1
ARG DB_PORT=5432
ARG DB_DATABASE=devtask
ARG DB_USERNAME=postgres
ARG DB_PASSWORD=
ARG SESSION_DRIVER=database
ARG SESSION_LIFETIME=120
ARG SESSION_ENCRYPT=false
ARG SESSION_PATH=/
ARG SESSION_DOMAIN=null
ARG QUEUE_CONNECTION=database
ARG CACHE_STORE=database
ARG REDIS_CLIENT=phpredis
ARG REDIS_HOST=127.0.0.1
ARG REDIS_PASSWORD=null
ARG REDIS_PORT=6379
ARG MAIL_MAILER=log
ARG MAIL_SCHEME=null
ARG MAIL_HOST=127.0.0.1
ARG MAIL_PORT=2525
ARG MAIL_USERNAME=null
ARG MAIL_PASSWORD=null
ARG MAIL_FROM_ADDRESS=hello@example.com
ARG MAIL_FROM_NAME='${APP_NAME}'
ARG VITE_APP_NAME='${APP_NAME}'

ENV APP_NAME=$APP_NAME \
    APP_ENV=$APP_ENV \
    APP_KEY=$APP_KEY \
    APP_DEBUG=$APP_DEBUG \
    APP_URL=$APP_URL \
    APP_LOCALE=$APP_LOCALE \
    APP_FALLBACK_LOCALE=$APP_FALLBACK_LOCALE \
    APP_FAKER_LOCALE=$APP_FAKER_LOCALE \
    LOG_CHANNEL=$LOG_CHANNEL \
    LOG_STACK=$LOG_STACK \
    LOG_LEVEL=$LOG_LEVEL \
    DB_CONNECTION=$DB_CONNECTION \
    DB_HOST=$DB_HOST \
    DB_PORT=$DB_PORT \
    DB_DATABASE=$DB_DATABASE \
    DB_USERNAME=$DB_USERNAME \
    DB_PASSWORD=$DB_PASSWORD \
    SESSION_DRIVER=$SESSION_DRIVER \
    SESSION_LIFETIME=$SESSION_LIFETIME \
    SESSION_ENCRYPT=$SESSION_ENCRYPT \
    SESSION_PATH=$SESSION_PATH \
    SESSION_DOMAIN=$SESSION_DOMAIN \
    QUEUE_CONNECTION=$QUEUE_CONNECTION \
    CACHE_STORE=$CACHE_STORE \
    REDIS_CLIENT=$REDIS_CLIENT \
    REDIS_HOST=$REDIS_HOST \
    REDIS_PASSWORD=$REDIS_PASSWORD \
    REDIS_PORT=$REDIS_PORT \
    MAIL_MAILER=$MAIL_MAILER \
    MAIL_SCHEME=$MAIL_SCHEME \
    MAIL_HOST=$MAIL_HOST \
    MAIL_PORT=$MAIL_PORT \
    MAIL_USERNAME=$MAIL_USERNAME \
    MAIL_PASSWORD=$MAIL_PASSWORD \
    MAIL_FROM_ADDRESS=$MAIL_FROM_ADDRESS \
    MAIL_FROM_NAME=$MAIL_FROM_NAME \
    VITE_APP_NAME=$VITE_APP_NAME

# System deps + PHP extensions
RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    curl \
    icu-dev \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    postgresql-dev \
    openssl-dev \
    zip \
    unzip \
  && docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache \
    intl \
  && rm -rf /var/cache/apk/*

# Redis (phpredis)
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apk del .build-deps

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Production php.ini
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# PHP custom INI
RUN echo "memory_limit = 256M" > "$PHP_INI_DIR/conf.d/memory.ini" \
  && echo "upload_max_filesize = 20M" > "$PHP_INI_DIR/conf.d/uploads.ini" \
  && echo "post_max_size = 20M" >> "$PHP_INI_DIR/conf.d/uploads.ini" \
  && echo "max_execution_time = 60" > "$PHP_INI_DIR/conf.d/timeouts.ini" \
  && echo "opcache.enable=1" > "$PHP_INI_DIR/conf.d/opcache.ini" \
  && echo "opcache.memory_consumption=128" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
  && echo "opcache.interned_strings_buffer=8" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
  && echo "opcache.max_accelerated_files=10000" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
  && echo "opcache.revalidate_freq=2" >> "$PHP_INI_DIR/conf.d/opcache.ini"

# Create www user
RUN addgroup -g 1000 www && adduser -D -u 1000 -G www www

# Nginx temp dirs (avoid 500 on POST)
RUN mkdir -p \
    /var/lib/nginx/tmp/client_body \
    /var/lib/nginx/tmp/proxy \
    /var/lib/nginx/tmp/fastcgi \
    /var/lib/nginx/tmp/uwsgi \
  && chown -R www:www /var/lib/nginx

# Copy configs
COPY docker/nginx-production.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php-fpm-www.conf /usr/local/etc/php-fpm.d/www.conf

# Entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/html

# Copy app source
COPY --chown=www:www . .

# Copy built assets from Vite stage
COPY --from=assets --chown=www:www /app/public/build /var/www/html/public/build

# Base perms + tmp
RUN chmod 1777 /tmp \
  && mkdir -p /var/www/html/storage/tmp \
  && chown -R www:www /var/www/html \
  && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

ENV TMPDIR=/var/www/html/storage/tmp

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Final perms (after composer)
RUN chown -R www:www /var/www/html \
  && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Logs
RUN mkdir -p /var/log/supervisor /var/log/nginx \
  && touch /var/log/nginx/error.log /var/log/nginx/access.log \
  && chown -R www:www /var/log/nginx

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --start-period=10s \
  CMD curl -f http://127.0.0.1/ || exit 1

CMD ["/entrypoint.sh"]

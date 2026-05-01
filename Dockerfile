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
FROM php:8.2-fpm-alpine

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
    sqlite-dev \
    postgresql-dev \
    mysql-client \
    imap-dev \
    krb5-dev \
    openssl-dev \
    zip \
    unzip \
  && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
  && docker-php-ext-install \
    pdo \
    pdo_sqlite \
    sqlite3 \
    pdo_mysql \
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
    imap \
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

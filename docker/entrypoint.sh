#!/usr/bin/env sh
set -eu

cd /var/www/html

# Garantir permissões mínimas (EasyPanel às vezes monta volumes)
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs storage/tmp bootstrap/cache
chown -R www:www storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Evita cache congelado vindo do build/deploy anterior
php artisan optimize:clear || true

# Rodar migrations automaticamente por padrao para evitar 500
# quando SESSION/CACHE/QUEUE usam driver "database".
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force --no-interaction || true
fi

if [ "${APP_ENV:-production}" = "production" ]; then
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

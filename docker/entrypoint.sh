#!/usr/bin/env sh
set -eu

cd /var/www/html

# Garantir permissões mínimas (EasyPanel às vezes monta volumes)
mkdir -p storage bootstrap/cache storage/tmp
chown -R www:www storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

if [ "${APP_ENV:-production}" = "production" ]; then
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
fi

# Opcional: rodar migrations automaticamente (habilite no EasyPanel)
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  php artisan migrate --force || true
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

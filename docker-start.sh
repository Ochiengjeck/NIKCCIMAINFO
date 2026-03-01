#!/bin/sh
set -e

echo "==> Checking APP_KEY..."
if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY is not set. Set it in Railway Variables."
  exit 1
fi

echo "==> Caching config..."
php artisan config:cache

echo "==> Caching routes..."
php artisan route:cache

echo "==> Caching views..."
php artisan view:cache

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Linking storage..."
php artisan storage:link || true

echo "==> Starting server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"

#!/usr/bin/env bash
# Spaceship Web Hosting Pro — re-deploy script.
# Run from ~/laravel-app/ on the server after `git pull`-ing new code.
# First-time setup (key:generate, migrate, db:seed, storage:link, admin:create)
# is documented in docs/SPACESHIP_DEPLOYMENT.md and is NOT performed here.

set -euo pipefail

cd "$(dirname "$0")"

git pull --ff-only

php composer.phar install --no-dev --optimize-autoloader
npm ci
npm run build

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Harmless on QUEUE_CONNECTION=sync; useful if the queue driver is later
# switched to database with a cron-driven worker.
php artisan queue:restart

echo "deploy: done"

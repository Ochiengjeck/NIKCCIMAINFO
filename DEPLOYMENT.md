# Railway Deployment Guide

This document explains how NiKCCIMA Backoffice was deployed to Railway (free tier).

---

## Overview

Railway is a cloud platform that builds and runs Docker containers from your GitHub repo. It provides:
- Free `$5/month` credit (sufficient for low-traffic apps)
- Managed PostgreSQL plugin
- Automatic HTTPS with a public domain
- Auto-deploy on every `git push`

---

## Problem: Default Railway Environment Uses PHP 8.2

Railway's auto-detected (Nixpacks) build environment ships PHP 8.2. This project requires PHP 8.4 because:

| Package | Minimum PHP |
|---|---|
| `spatie/laravel-permission` 7.x | 8.4 |
| `symfony/*` 8.x | 8.4 |
| `maennchen/zipstream-php` 3.x | 8.3 (64-bit) |

**Error seen:**
```
spatie/laravel-permission 7.2.3 requires php ^8.4 -> your php version (8.2.30) does not satisfy
```

**Solution:** Add a `Dockerfile` — Railway will use it instead of auto-detection.

---

## Files Added

### 1. `Dockerfile`

```dockerfile
FROM php:8.4-cli-alpine

# mlocati/php-extension-installer downloads pre-compiled PHP extension
# binaries instead of compiling from C source (10-20x faster builds)
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install system tools + all required PHP extensions in one layer
RUN apk add --no-cache bash git nodejs npm \
    && install-php-extensions \
        pdo_pgsql \    # PostgreSQL database driver
        pdo_sqlite \   # SQLite driver (used in tests)
        gd \           # Image processing (required by phpspreadsheet)
        mbstring \     # Multi-byte string functions
        zip \          # ZIP archive support
        opcache \      # PHP bytecode cache (performance)
        bcmath \       # Arbitrary precision math
        pcntl \        # Process control (needed by queue workers)
        xml \          # XML parsing
        ctype \        # Character type functions
        fileinfo \     # MIME type detection
        tokenizer      # PHP tokenizer (required by Laravel)

# Install Composer 2 (copied from official Composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files FIRST — Docker caches this layer.
# If composer.json/lock don't change, this step is skipped on rebuild.
COPY composer.json composer.lock ./
RUN composer install \
    --optimize-autoloader \
    --no-scripts \      # Skip post-install scripts (artisan not ready yet)
    --no-interaction \
    --no-dev            # Exclude dev dependencies in production

# Copy package files and install Node dependencies
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

# Copy the full application source
COPY . .

# Now that source exists, run Laravel's package discovery
# (writes bootstrap/cache/packages.php — maps service providers)
RUN php artisan package:discover --ansi

# Build Vite assets (CSS + JS) for production
RUN npm run build

# Create all required storage directories
# (Railway filesystem starts empty — these must exist for the app to run)
RUN mkdir -p \
    storage/app/public/cms/images \
    storage/app/public/cms/downloads \
    storage/app/public/cms/news \
    storage/app/public/cms/leadership \
    storage/app/private/receipts \
    storage/app/private/roo \
    storage/app/private/uploads \
    storage/app/private/strategic-plans \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker-start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

EXPOSE 8000
CMD ["start"]
```

**Why `php:8.4-cli-alpine`?**
- `8.4` — matches the PHP version required by all packages
- `cli` — `php artisan serve` is the web server (no Nginx/FPM needed for low traffic)
- `alpine` — minimal Linux image (~5MB base vs ~100MB for Debian)

---

### 2. `docker-start.sh`

This runs every time the container starts. Using a script (instead of an inline CMD) gives readable logs and a clear error if env vars are missing.

```sh
#!/bin/sh
set -e   # exit immediately if any command fails

echo "==> Checking APP_KEY..."
if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY is not set. Set it in Railway Variables."
  exit 1
fi

echo "==> Caching config..."
php artisan config:cache     # Merges all config files into one cached file

echo "==> Caching routes..."
php artisan route:cache      # Pre-compiles route list for faster routing

echo "==> Caching views..."
php artisan view:cache       # Pre-compiles Blade templates

echo "==> Running migrations..."
php artisan migrate --force  # --force skips the production confirmation prompt

echo "==> Linking storage..."
php artisan storage:link || true   # Creates public/storage symlink (|| true = don't fail if exists)

echo "==> Starting server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
# exec replaces the shell process with PHP (clean signal handling)
# 0.0.0.0 = listen on all network interfaces (required in containers)
# PORT is injected by Railway; defaults to 8000 if not set
```

---

### 3. `railway.toml`

Tells Railway to use the Dockerfile and configures health check behaviour.

```toml
[build]
builder = "DOCKERFILE"       # Use our Dockerfile instead of Nixpacks auto-detection
dockerfilePath = "Dockerfile"

[deploy]
healthcheckPath = "/"                    # Railway pings this URL to confirm the app is up
healthcheckTimeout = 100                 # Seconds to wait for first healthy response
restartPolicyType = "ON_FAILURE"         # Auto-restart if the container crashes
restartPolicyMaxRetries = 10
```

---

### 4. `bootstrap/app.php` — Trust Proxies

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*');   // <-- added
    ...
})
```

**Why this is needed:**

Railway terminates HTTPS at its load balancer, then forwards requests to your container over plain HTTP. Laravel sees an HTTP request and generates `http://` URLs for all assets (Vite, Livewire, Flux).

Without this, the browser blocks every script and stylesheet with:
```
Mixed Content: The page was loaded over HTTPS but requested an insecure script 'http://...'
```

`trustProxies(at: '*')` tells Laravel to trust the `X-Forwarded-Proto: https` header that Railway injects, so Laravel knows the original request was HTTPS and generates `https://` URLs.

---

## Railway Environment Variables

Set these in Railway → your service → **Variables** tab:

| Variable | Value | Notes |
|---|---|---|
| `APP_KEY` | `base64:...` | Generate with `php artisan key:generate --show` |
| `APP_ENV` | `production` | Disables debug mode behaviour |
| `APP_DEBUG` | `false` | Never expose stack traces publicly |
| `APP_URL` | `https://your-app.up.railway.app` | Must be `https://` — used by Vite asset URLs |
| `DB_CONNECTION` | `pgsql` | Switch from SQLite to PostgreSQL |
| `DB_HOST` | from Railway PostgreSQL plugin `PGHOST` | |
| `DB_PORT` | `5432` | |
| `DB_DATABASE` | from `PGDATABASE` | |
| `DB_USERNAME` | from `PGUSER` | |
| `DB_PASSWORD` | from `PGPASSWORD` | |
| `SESSION_DRIVER` | `database` | |
| `QUEUE_CONNECTION` | `database` | |
| `CACHE_STORE` | `database` | |

---

## First-Run Setup (after deploy)

Open the Railway dashboard terminal (`>_` icon on your service) and run:

```bash
# Seed chapters (Nigeria, Kenya, Global)
php artisan db:seed --class=ChapterSeeder

# Seed all 16 roles and permissions
php artisan db:seed --class=RolesAndPermissionsSeeder

# Create the first super-admin account
php artisan admin:create
```

---

## Deployment Workflow (ongoing)

Every `git push` to `main` triggers an automatic rebuild and redeploy:

```bash
git add .
git commit -m "your changes"
git push
```

Railway rebuilds the Docker image (using layer cache — unchanged layers are skipped) and replaces the running container with zero-downtime deployment.

---

## Known Limitation: Ephemeral Filesystem

Railway containers have a **temporary filesystem** — any files written at runtime (uploads, logs) are lost when the container restarts or redeploys.

**Impact on this app:** All media uploads (`storage/app/public/`, `storage/app/private/`) are lost on every deploy.

**Fix:** Configure an S3-compatible storage backend (e.g. Cloudflare R2 — free tier: 10GB, 10M requests/month). Laravel's filesystem abstraction makes this a config-only change — no code changes needed.

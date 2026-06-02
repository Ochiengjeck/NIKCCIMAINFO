# Spaceship Deployment Guide

How NiKCCIMA Backoffice is deployed to **Spaceship Web Hosting Pro** (shared cPanel).
For the historical Railway / Docker setup see `DEPLOYMENT.md`.

---

## Prerequisites

| | |
|---|---|
| Hosting | Spaceship Web Hosting Pro (cPanel) |
| Domain | `nikccima.org` |
| Database | MySQL (cPanel-provided; Postgres is not available) |
| PHP | **8.4** — `spatie/laravel-permission ^7.2` requires it |
| Node | 20+ on server, OR build assets locally and upload `public/build/` |
| TLS | cPanel **AutoSSL** using the existing PositiveSSL on the account |

Minimum required PHP extensions (verify in cPanel → Select PHP Version → Extensions):
`pdo_mysql`, `gd`, `mbstring`, `zip`, `bcmath`, `xml`, `fileinfo`, `tokenizer`,
`ctype`, `opcache`, `intl`, `curl`, `openssl`.

If `pdo_mysql` is missing, open a support ticket — that is a hard blocker.

---

## Phase 1 — Spaceship dashboard prep

1. **Activate SSH.**
   Spaceship dashboard → Hosting → Web Hosting Pro → Manage → cPanel.
   In cPanel search **SSH Access** → **Manage SSH Keys** → generate or import a key
   → click **Authorize**.
   The connection details (host, port — usually 21098, username) are on the same page.
   Test: `ssh -p 21098 <user>@nikccima.org`.

2. **Connect domain DNS.**
   Spaceship dashboard → Domains → `nikccima.org` → set the **Spaceship hosting
   nameservers** shown on the Web Hosting Pro Manage panel.
   Propagation: 15 min – 4 hr. Status flips from "Connecting" → "Connected".

3. **Create the MySQL database.**
   cPanel → **MySQL Databases**:
   - Create database `nikccima_main`
   - Create user `nikccima_app` with a strong password
   - Add user to database with **ALL PRIVILEGES**

   Both names will be prefixed with the cPanel account name
   (e.g. `cpaneluser_nikccima_main`). Note the prefix — it goes in `.env`.

4. **Set PHP 8.4.**
   cPanel → **MultiPHP Manager** (or "Select PHP Version") → set `nikccima.org`
   to **PHP 8.4**. Confirm extensions per the list above.

5. **Issue SSL.**
   cPanel → **SSL/TLS Status** → run **AutoSSL** for `nikccima.org` and `www.nikccima.org`.

---

## Phase 2 — First deploy

6. **Clone the repo outside `public_html`.**

   ```bash
   ssh -p 21098 <user>@nikccima.org
   cd ~
   git clone https://github.com/<owner>/NIKCCIMAINFO.git laravel-app
   cd laravel-app
   ```

   Private repo: add a deploy key in GitHub and an SSH key on the server, or use a
   fine-scoped PAT via HTTPS clone.

7. **Override the document root.**
   cPanel → **Domains** → click `nikccima.org` → change **Document Root** from
   `/home/<user>/public_html` to `/home/<user>/laravel-app/public`. Save.

   This is the critical step that lets Apache serve Laravel without moving files
   into `public_html`.

8. **Create `.env`.**

   ```bash
   cp .env.production.example .env
   php artisan key:generate
   ```

   Then edit `.env` and fill in:
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (with cPanel prefix from step 3)
   - `MAIL_USERNAME`, `MAIL_PASSWORD` (a Spacemail mailbox on the account)
   - `FLW_PUBLIC_KEY`, `FLW_SECRET_KEY`, `FLW_SECRET_HASH` (from Flutterwave dashboard)

9. **Install dependencies and build assets.**

   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   ```

   Fallback if Node is unavailable on the server: build locally, then
   `rsync -av public/build/ <user>@nikccima.org:laravel-app/public/build/`.

10. **Migrate, seed, link storage, create the first admin.**

    ```bash
    php artisan migrate --force
    php artisan db:seed --force
    php artisan storage:link
    php artisan admin:create
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

    `db:seed --force` runs `DatabaseSeeder` which chains `ChapterSeeder`,
    `RolesAndPermissionsSeeder`, and `CmsPagesSeeder` (the six default public
    CMS pages).

11. **Permissions.**

    ```bash
    chmod -R 755 storage bootstrap/cache
    chmod +x deploy.sh
    ```

---

## Phase 3 — Cron, webhook, smoke test

12. **Daily KPI cron.**
    cPanel → **Cron Jobs** → add:

    - Frequency: every minute (`* * * * *`) — Laravel scheduler decides what runs when
    - Command: `cd /home/<user>/laravel-app && php artisan schedule:run >> /dev/null 2>&1`

    Drives `kpi:snapshot` (the only currently scheduled command — see
    `routes/console.php`).

13. **Flutterwave webhook.**
    Flutterwave dashboard → Webhooks → set URL to
    `https://nikccima.org/webhook/flutterwave` and the secret hash to match
    `FLW_SECRET_HASH`. Route is already CSRF-exempt in `bootstrap/app.php:21`.

14. **Smoke test.**

    - `curl -I https://nikccima.org` returns 200 over TLS, no cert warnings
    - `https://nikccima.org/` renders the public homepage
    - `/login` accepts the super-admin from `admin:create`; the session persists
    - Upload a test image via `/admin/cms/media` — file lands in
      `storage/app/public/cms/images/` and resolves through `/storage/...`
    - `php artisan kpi:snapshot` from SSH writes a row to `kpi_snapshots`
    - Trigger a Flutterwave test webhook → `storage/logs/laravel.log` records it

---

## Future deploys

From your local machine, push to `main`. Then SSH in and run:

```bash
cd ~/laravel-app
./deploy.sh
```

`deploy.sh` runs `git pull`, `composer install --no-dev`, `npm ci && npm run build`,
`migrate --force`, the cache commands, and `queue:restart`.

---

## Known constraints on this hosting tier

- **No persistent processes.** Queue runs as `sync` — notifications send during the
  HTTP request. If load grows, switch to `database` queue + a per-minute cron
  running `php artisan queue:work --stop-when-empty --max-time=55`.
- **No Redis / Memcached.** Cache and session stay on the database driver.
- **No Docker.** `Dockerfile`, `railway.toml`, `docker-start.sh` remain in the repo
  for any future Railway / VPS deploy but are not used here.
- **No `php artisan serve`.** Apache serves the app via the document-root override
  in step 7.

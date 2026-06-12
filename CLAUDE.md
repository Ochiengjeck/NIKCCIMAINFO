# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**NiKCCIMA Backoffice Panel** — governed AfCFTA corridor execution system for the Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture. Built on Laravel 12 + Livewire 4 + Flux UI + Tailwind CSS 4 + Spatie Laravel Permission.

## Admin Commands
```bash
php artisan admin:create                               # Interactive: create/promote super-admin
php artisan admin:create --email=a@b.com --name=X --password=Y  # Non-interactive
php artisan db:seed --class=ChapterSeeder              # Seed Nigeria/Kenya/Global chapters
php artisan db:seed --class=RolesAndPermissionsSeeder  # Seed all 16 roles + permissions
php artisan permission:show                            # Show role/permission matrix
```

## Commands

### Setup (first-time)
```bash
composer run-script setup
# Runs: composer install, .env setup, app key generation, migrations, npm install, npm build
```

### Development
```bash
composer run-script dev
# Concurrently runs: php artisan serve + php artisan queue:listen + npm run dev
```

### Testing
PHPUnit (not Pest), config in `phpunit.xml`; tests run against an in-memory SQLite DB.
```bash
php artisan test                        # Run all tests (preferred)
php artisan test --filter=TestName      # Run a single test
./vendor/bin/phpunit                    # Direct runner
```

### Code Quality
```bash
composer run-script lint          # Auto-fix code style with Laravel Pint
composer run-script test:lint     # Check code style without fixing
composer run-script test          # Full suite: lint check + phpunit
```

### Frontend
```bash
npm run build   # Production asset build
npm run dev     # Vite dev server with hot reload
```

## Architecture

### Authentication
Handled by **Laravel Fortify** (`FortifyServiceProvider`). Auth views are registered from the `pages::` package namespace — not from `resources/views/auth/`. Custom actions live in `app/Actions/Fortify/` (user creation, password updates, 2FA). Rate limiting: 5 attempts/min for login, 2/min for 2FA.

### Reactive UI
**Livewire 4** components live in `app/Livewire/`. The **Flux** UI library (`livewire/flux`) provides pre-built components used throughout views.

### Routing
- `routes/web.php` — the public-facing website (home, about, pillars, trade, membership, events, news, leadership, downloads, contact), the authed `dashboard`, and the CSRF-exempt Flutterwave webhook. Requires `settings.php` and `admin.php`.
- `routes/settings.php` — user settings sub-routes (profile, password, 2FA)
- `routes/admin.php` — all backoffice routes, prefix `admin/`, middleware `[auth, verified, admin]`
- Each module's routes are added to `routes/admin.php` grouped by prefix

The app has two distinct surfaces: the **public website** (guest, no auth) served from `web.php`, and the **backoffice/admin panel** served from `admin.php`. Public Livewire components (`app/Livewire/Public/`) never call `$this->authorize()`.

### Frontend Stack
Vite bundles `resources/css/app.css` (Tailwind 4) and `resources/js/app.js`. Layouts live in `resources/views/layouts/` — `app.blade.php` with sidebar/header partials for authenticated pages, `auth/` layouts for guest pages.

### Database
SQLite by default (`database/database.sqlite`). Tests use SQLite in-memory. PostgreSQL for production (set `DB_CONNECTION=pgsql` in `.env`).

### RBAC (Spatie Laravel Permission)
16 roles from `super-admin` down to `anchor-investor-coordinator`. Permissions follow `{module}.{action}` naming (e.g. `members.approve`, `finance.export`). `super-admin` bypasses all permission checks via `gate-before`. Never check roles directly in code — always use `$this->authorize('permission.name')` in Livewire or `@can('permission.name')` in Blade.

### Chapter Isolation
Every module model uses the `ChapterScoped` trait (`app/Concerns/ChapterScoped.php`). Call `->forChapter()` on queries to auto-filter by `auth()->user()->chapter_id`. Global roles (`super-admin`, `global-secretariat`, `global-governing-council`) see all chapters.

### Backoffice Layout
Admin panel uses `layouts/admin.blade.php` → `layouts/admin/sidebar.blade.php`. Livewire components use `->layout('layouts.admin')` in their `render()` method. Module components live in `app/Livewire/{Module}/`.

### Validation Traits
Shared validation rules in `app/Concerns/` — `PasswordValidationRules`, `ProfileValidationRules`, `ChapterScoped` — used by Fortify actions and Livewire components.

### Public Website & CMS
Public pages are rendered through `PublicController` + the `layouts.website` layout (Blade `<x-layouts::website>`); a full-page Livewire component uses `->layout('layouts.website', [...])`. Content is editable via the admin CMS module (`app/Livewire/Cms/` — pages, blog, leadership, media, contact). CMS/public models (`CmsPage`, `BlogPost`, `BlogCategory`, `BlogTag`, `BlogComment`, `LeadershipProfile`, `MediaItem`, `ContactInquiry`) deliberately do **not** use `ChapterScoped`.

### Blog
The blog (`app/Livewire/Cms/BlogManager.php`, `BlogCategoryManager`, `BlogCommentModerator`) manages `BlogPost`s with `BlogCategory`/`BlogTag` taxonomy and moderated `BlogComment`s. Public surface: `/blog` (listing + sidebar), `/blog/{slug}` (post + related + author bio + comments), `/blog/category/{slug}`, `/blog/tag/{slug}`, and the RSS feed at `/blog/feed`. Legacy `/news*` URLs 301-redirect to `/blog`. Comments default to `pending` and only show once approved via the moderator (gated by `cms.publish`). Seed default categories with `db:seed --class=BlogCategorySeeder`.

### Media Uploads
All uploads flow through `MediaUploadController` (`POST /admin/media/upload`) via **direct XHR**, not Livewire `WithFileUploads`. Every upload becomes a `MediaItem` record (single source of truth); private files use `disk=local`, public files `disk=public`. Reuse the `<livewire:components.media-picker>` component (Upload + Library tabs) for any new file field — bind it with `wire:model` to an integer `*MediaItemId` property. The XHR layer requires the `<meta name="csrf-token">` tag in `partials/head.blade.php`.

### Payments
Flutterwave integration lives in `app/Services/FlutterwaveService.php`, with `FlutterwaveWebhookController` handling the CSRF-exempt callback (`POST /webhook/flutterwave`; exemption registered in `bootstrap/app.php`). Used for membership/invoice payments.

### Documents, Exports & Audit
- PDFs (e.g. membership certificates via `MembershipCertificateController`) are generated with **barryvdh/laravel-dompdf**.
- Excel exports (reports) use **maatwebsite/excel**.
- Model audit trails use **spatie/laravel-activitylog** via the `LogsActivity` trait on key models (`Member`, `MembershipApplication`, `Invoice`, `Ntb`, `Document`, `Deal`); surfaced through `app/Livewire/Audit/ActivityLog.php`.
- KPI rollups are computed by `KpiCalculationService` and snapshotted by the `kpi:snapshot` command (`KpiSnapshotCommand`).

## Code Style
Laravel Pint with the `laravel` preset (`pint.json`). CI enforces this on push/PR.

## Deployment
Production target is **Spaceship Web Hosting Pro** (shared cPanel, MySQL, no persistent processes — queue runs as `sync`). Domain: `nikccima.org`. PHP 8.4 is required by `spatie/laravel-permission ^7.2`. The full first-time setup is in `docs/SPACESHIP_DEPLOYMENT.md`; subsequent deploys run `./deploy.sh` over SSH from `~/laravel-app/`. Use `.env.production.example` as the server `.env` template — `.env.example` stays as the local-dev (SQLite) template. The `Dockerfile`, `railway.toml`, and `DEPLOYMENT.md` remain for future Railway / VPS use and are not consulted on Spaceship.

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
```bash
./vendor/bin/phpunit              # Run all tests
./vendor/bin/phpunit --filter=TestName  # Run a single test
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
- `routes/web.php` — main routes (home, dashboard) — includes `settings.php` and `admin.php`
- `routes/settings.php` — user settings sub-routes (profile, password, 2FA)
- `routes/admin.php` — all backoffice routes, prefix `admin/`, middleware `[auth, verified, admin]`
- Each module's routes are added to `routes/admin.php` grouped by prefix

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

## Code Style
Laravel Pint with the `laravel` preset (`pint.json`). CI enforces this on push/PR.

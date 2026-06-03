<?php

namespace App\Providers;

use App\Models\User;
use App\Services\SettingsService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureAuthorization();
        $this->configureMail();
    }

    /**
     * Apply admin-managed SMTP settings (from system_settings) over the .env defaults.
     * Only overrides when an SMTP host has been configured in the admin panel; otherwise
     * the .env mail configuration is left untouched. Guarded so console/migration/fresh
     * installs (no table yet) never fail.
     */
    protected function configureMail(): void
    {
        try {
            if (! Schema::hasTable('system_settings')) {
                return;
            }

            $settings = $this->app->make(SettingsService::class);

            $host = $settings->get('smtp_host');
            if (! $host) {
                return; // fall back to .env
            }

            $encryption = $settings->get('smtp_encryption', 'tls');

            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => (int) $settings->get('smtp_port', 587),
                'mail.mailers.smtp.username' => $settings->get('smtp_username') ?: null,
                'mail.mailers.smtp.password' => $settings->get('smtp_password') ?: null,
                // Symfony: 'smtps' implies SSL (465); null lets STARTTLS auto-negotiate (587).
                'mail.mailers.smtp.scheme' => $encryption === 'ssl' ? 'smtps' : null,
            ]);

            if ($from = $settings->get('mail_from_address')) {
                config(['mail.from.address' => $from]);
            }
            if ($fromName = $settings->get('mail_from_name')) {
                config(['mail.from.name' => $fromName]);
            }
        } catch (\Throwable) {
            // Never let mail configuration break the boot process.
        }
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    /**
     * Register authorization defaults.
     */
    protected function configureAuthorization(): void
    {
        Gate::before(function (User $user): ?bool {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}

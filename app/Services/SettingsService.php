<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting:{$key}", 3600, fn () => SystemSetting::get($key, $default));
    }

    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        SystemSetting::set($key, $value, $group);
        Cache::forget("setting:{$key}");
    }

    /**
     * The address that should receive admin/secretariat notifications.
     */
    public function adminNotificationEmail(): string
    {
        return $this->get('notification_email')
            ?: $this->get('nigeria_email')
            ?: 'info@nikccima.org';
    }

    /**
     * Whether membership categories are grouped into Corporate / Individual
     * (each with its own pricing). When false, categories use a single flat price.
     */
    public function membershipGroupByType(): bool
    {
        return (bool) $this->get('membership_group_by_type', false);
    }
}

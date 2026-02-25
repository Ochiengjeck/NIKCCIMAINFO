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
}

<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Clear the cache for a specific key or all settings.
     *
     * @param string|null $key
     * @return void
     */
    public function clearCache(string $key = null): void
    {
        if ($key) {
            Cache::forget('setting_' . $key);
        } else {
            // Get all keys from DB and clear them
            $keys = Setting::pluck('key')->toArray();
            foreach ($keys as $k) {
                Cache::forget('setting_' . $k);
            }
        }
    }
}

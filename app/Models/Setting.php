<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * Cache key prefix
     */
    protected static string $cachePrefix = 'settings_';
    protected static int $cacheTtl = 3600; // 1 hour

    /**
     * Get setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = static::$cachePrefix . $key;

        return Cache::remember($cacheKey, static::$cacheTtl, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return static::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set setting value
     */
    public static function set(string $key, mixed $value): bool
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );

        // Clear cache
        Cache::forget(static::$cachePrefix . $key);
        Cache::forget('settings_group_' . $setting->group);

        return true;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): array
    {
        $cacheKey = 'settings_group_' . $group;

        return Cache::remember($cacheKey, static::$cacheTtl, function () use ($group) {
            $settings = static::where('group', $group)->get();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = static::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Get all settings as array
     */
    public static function getAllSettings(): array
    {
        return Cache::remember('all_settings', static::$cacheTtl, function () {
            $settings = static::all();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = static::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Cast value to appropriate type
     */
    protected static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        $settings = static::all();

        foreach ($settings as $setting) {
            Cache::forget(static::$cachePrefix . $setting->key);
        }

        $groups = static::distinct()->pluck('group');
        foreach ($groups as $group) {
            Cache::forget('settings_group_' . $group);
        }

        Cache::forget('all_settings');
    }

    /**
     * Update multiple settings at once
     */
    public static function updateMultiple(array $settings, string $group = null): void
    {
        foreach ($settings as $key => $value) {
            $setting = static::updateOrCreate(
                ['key' => $key],
                [
                    'value' => (string) $value,
                    'group' => $group ?? 'general'
                ]
            );

            // Clear individual cache
            Cache::forget(static::$cachePrefix . $key);
        }

        // Clear group cache
        if ($group) {
            Cache::forget('settings_group_' . $group);
        }

        Cache::forget('all_settings');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HardwareSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::remember("hardware_setting_{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, ?string $type = null, ?string $group = null): void
    {
        $setting = self::firstOrNew(['key' => $key]);
        $setting->value = is_array($value) ? json_encode($value) : (string) $value;

        if ($type) {
            $setting->type = $type;
        }
        if ($group) {
            $setting->group = $group;
        }

        $setting->save();

        Cache::forget("hardware_setting_{$key}");
        Cache::forget('hardware_settings_all');
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): array
    {
        $settings = self::where('group', $group)->get();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = self::castValue($setting->value, $setting->type);
        }

        return $result;
    }

    /**
     * Get all settings
     */
    public static function getAll(): array
    {
        return Cache::remember('hardware_settings_all', 3600, function () {
            $settings = self::all();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Cast value to proper type
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json', 'array' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Clear all hardware settings cache
     */
    public static function clearCache(): void
    {
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget("hardware_setting_{$setting->key}");
        }
        Cache::forget('hardware_settings_all');
    }
}

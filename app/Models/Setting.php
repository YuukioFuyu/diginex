<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    protected $guarded = [];

    /**
     * Retrieve all settings from the cache, or from the database if not cached.
     */
    public static function getAllSettings(): mixed
    {
        return Cache::rememberForever('settings.all', function () {
            return self::all();
        });
    }

    /**
     * A description of the entire PHP function.
     *
     * @param  mixed  $key  description
     */
    public static function has(mixed $key): bool
    {
        return (bool) self::getAllSettings()->whereStrict('key', $key)->count();
    }

    /**
     * A description of the entire PHP function.
     *
     * @param  mixed  $section  description
     */
    private static function getDefinedSettingFields(mixed $section): Collection
    {
        return collect(config('settings')[$section]['elements'] ?? []);
    }

    /**
     * A description of the entire PHP function.
     *
     * @param  mixed  $section  description
     */
    public static function getValidationRules(mixed $section): array
    {
        return self::getDefinedSettingFields($section)->pluck('rules', 'key')
            ->reject(function ($val) {
                return is_null($val);
            })->toArray();
    }

    /**
     * Get the data type for the given field and section.
     *
     * @param  mixed  $field  description
     * @param  mixed  $section  description
     */
    public static function getDataType(mixed $field, mixed $section): mixed
    {
        $type = self::getDefinedSettingFields($section)
            ->pluck('data', 'key')
            ->get($field);

        return is_null($type) ? 'string' : $type;
    }

    /**
     * A description of the entire PHP function.
     *
     * @param  mixed  $key  description
     * @param  mixed  $val  description
     * @param  string  $type  description
     */
    public static function set(mixed $key, mixed $val, string $type = 'string'): mixed
    {
        if ($setting = self::getAllSettings()->where('key', $key)->first()) {
            return $setting->update([
                'key' => $key,
                'val' => $val,
                'type' => $type]) ? $val : false;
        }

        return self::add($key, $val, $type);
    }

    /**
     * Add a new key-value pair to the storage if the key doesn't already exist.
     * If the key exists, update its value and type.
     *
     * @param  mixed  $key  The key to be added or updated.
     * @param  mixed  $val  The value to be associated with the key.
     * @param  string  $type  The type of the value (default is 'string').
     * @return mixed The updated value if successful, false otherwise.
     */
    public static function add(mixed $key, mixed $val, string $type = 'string'): mixed
    {
        if (self::has($key)) {
            return self::set($key, $val, $type);
        }

        return self::create(['key' => $key, 'val' => $val, 'type' => $type]) ? $val : false;
    }

    /**
     * A description of the entire PHP function.
     *
     * @param  mixed  $key  description
     */
    public static function remove(mixed $key): bool
    {
        if (self::has($key)) {
            return self::whereKey($key)->delete();
        }

        return false;
    }

    /**
     * Casts a value to a specified data type.
     *
     * @param  mixed  $val  value to be cast
     * @param  string  $castTo  data type to cast the value to
     * @return mixed the casted value
     */
    private static function castValue(mixed $val, string $castTo): mixed
    {
        return match ($castTo) {
            'int', 'integer' => intval($val),
            'bool', 'boolean' => boolval($val),
            default => $val,
        };
    }

    /**
     * Get the default value for a specific field in a given section.
     *
     * @param  mixed  $field  the field for which to get the default value
     * @param  mixed  $section  the section in which to look for the default value
     * @return mixed the default value for the specified field in the given section
     */
    public static function getDefaultValueForField(mixed $field, mixed $section): mixed
    {
        return self::getDefinedSettingFields($section)->pluck('value', 'key')->get($field);
    }

    /**
     * Get the default value for a given key and section, with an optional default value.
     *
     * @param  mixed  $key  description
     * @param  mixed  $section  description
     * @param  mixed  $default  description
     */
    private static function getDefaultValue(mixed $key, mixed $section, mixed $default): mixed
    {
        return is_null($default) ? self::getDefaultValueForField($key, $section) : $default;
    }

    /**
     * A description of the entire PHP function.
     *
     * @param  mixed  $key  description
     * @param  mixed  $section  description
     * @param  mixed  $default  description
     */
    public static function get(mixed $key, mixed $section = null, mixed $default = null): mixed
    {
        if (self::has($key)) {
            $setting = self::getAllSettings()->where('key', $key)->first();

            return self::castValue($setting->val, $setting->type);
        }

        return self::getDefaultValue($key, $section, $default);
    }

    /**
     * Flush the cache for settings all
     */
    public static function flushCache(): void
    {
        Cache::forget('settings.all');
    }

    /**
     * The boot method is called when the model is booting.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::updated(function () {
            self::flushCache();
        });

        static::created(function () {
            self::flushCache();
        });
    }
}

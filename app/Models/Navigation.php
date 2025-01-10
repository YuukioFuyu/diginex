<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Navigation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'page_id',
        'header_order',
        'footer_order',
        'display',
        'target',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'page_id' => 'integer',
    ];

    public static function exists($field, $value, $excludedId = null)
    {
        $query = self::where($field, $value);


        if ($excludedId !== null) {
            $query->where('id', '!=', $excludedId);
        }
        return $query->exists();
    }

    public function getTransDataAttribute(): array
    {
        $name = json_decode($this->name, true);
        $defaultLanguage = config('app.static_default_language');

        return Language::languageGet()->keys()->mapWithKeys(function ($language) use ($defaultLanguage, $name) {
            return [
                $language => (object) [
                    'name' => $name[$language] ?? $name[$defaultLanguage],
                ],
            ];
        })->all();
    }

    public function scopeOrderedBy($query, $type)
    {
        return $query->whereIn('display', ['both', $type])->orderBy($type.'_order');
    }

    public function scopeForType($query, $type)
    {
        return $query->whereIn('display', ['both', $type]);
    }

    private static function flushCache(): void
    {
        Cache::forget('header_navigations');
        Cache::forget('footer_navigations');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::updated(function () {
            self::flushCache();
        });

        static::created(function () {
            self::flushCache();
        });
        static::deleted(function () {
            self::flushCache();
        });
    }
}

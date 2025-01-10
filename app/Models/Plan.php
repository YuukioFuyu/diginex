<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'background_shape',
        'monthly_price',
        'yearly_price',
        'badge',
        'features',
        'status',
    ];

    protected $casts = [
        'name' => 'array',
        'features' => 'array',
    ];

    public function translate($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale(); // Use the current app locale if none is passed

        if (isset($this->{$field}[$locale])) {
            return $this->{$field}[$locale]; // Return the translation for the current locale
        }

        // Fallback logic if needed (optional)
        return $this->{$field}['en'] ?? null; // Fallback to 'en' if no translation exists
    }

    public static function getAllWithCache(): Collection
    {
        $cacheKey = 'plan_all';

        return Cache::rememberForever($cacheKey, function () {
            return self::where('status', Status::ACTIVE)
                ->get(['id', 'name', 'icon', 'background_shape', 'monthly_price', 'yearly_price', 'features']);
        });
    }

    /**
     * Clear cache for all products
     */
    public static function clearCache(): void
    {
        Cache::forget('plan_all');
    }


    /**
     * Automatically clear cache on save, update, and delete
     */
    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}

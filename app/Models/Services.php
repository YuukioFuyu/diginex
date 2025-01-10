<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Services extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'price', 'light_icon', 'dark_icon', 'cover', 'video_cover', 'video_link', 'faq_content', 'side_content', 'tags', 'description', 'status'];

    protected $with = ['orders'];

    protected $casts = [
        'name' => 'array',
        'price' => 'double',
        'tags' => 'array',
        'side_content' => 'array',
        'faq_content' => 'array',
        'description' => 'array',
        'status' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'service_id');
    }

    // Get the translation for a specific field based on locale
    public static function getBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    public function translate($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale(); // Use the current app locale if none is passed

        if (isset($this->{$field}[$locale])) {
            return $this->{$field}[$locale]; // Return the translation for the current locale
        }

        // Fallback logic if needed (optional)
        return $this->{$field}['en'] ?? null; // Fallback to 'en' if no translation exists
    }

    public function setTranslation($field, $locale, $value): void
    {
        // Decode existing translations if they exist
        $translations = $this->{$field} ?? [];
        // Set the translation for the specified locale
        $translations[$locale] = $value;

        // Update the field with new translations
        $this->{$field} = $translations;
    }

    /**
     * Get all products with caching.
     *
     * The cache key is 'products_all'. If the cache exists, it will return the cached data.
     * Otherwise, it will fetch the data from the database and store it in the cache.
     *
     * @param  int|null  $take  The number of records to take. If null, it will fetch all.
     */
    public static function getAllWithCache(?int $take = null): Collection
    {
        $cacheKey = 'service_with_take'.$take == null ? 'all' : 'service_all_with_take'.$take;

        return Cache::rememberForever($cacheKey, function () use ($take) {
            return self::where('status', Status::ACTIVE)
                ->get(['light_icon', 'dark_icon', 'cover', 'name', 'slug', 'price', 'description'])
                ->take($take);
        });
    }

    public static function getAll(): Collection
    {
        $cacheKey = 'service_all';

        return Cache::rememberForever($cacheKey, function () {
            return self::where('status', Status::ACTIVE)
                ->get(['light_icon', 'dark_icon', 'cover', 'name', 'slug', 'price', 'description']);
        });
    }

    /**
     * Get a product by slug with caching
     */
    public static function getBySlugWithCache($slug)
    {
        $cacheKey = "service_slug_{$slug}";

        return Cache::rememberForever($cacheKey, function () use ($slug) {
            return self::where('slug', $slug)->first();
        });
    }

    /**
     * Clear cache for all products
     */
    public static function clearCache(): void
    {
        Cache::forget('service_all');
    }

    /**
     * Clear cache for a specific product by slug
     */
    public static function clearCacheBySlug($slug): void
    {
        Cache::forget("service_slug_{$slug}");
    }

    /**
     * Automatically clear cache on save, update, and delete
     */
    protected static function booted()
    {
        static::saved(function ($service) {
            self::clearCache();
            self::clearCacheBySlug($service->slug);
        });

        static::deleted(function ($service) {
            self::clearCache();
            self::clearCacheBySlug($service->slug);
        });
    }
}

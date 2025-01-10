<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Page extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function getValidSlugs()
    {
        return Cache::rememberForever('valid_slugs', function () {
            return static::where('slug', '!=', '/')->pluck('slug')->implode('|');
        });
    }

    public static function getSlugs()
    {
        return Cache::rememberForever('slugs_list', function () {
            return static::pluck('slug')->toArray();
        });
    }

    private static function flushCache(): void
    {
        Cache::forget('home_page_components');
        Cache::forget('valid_slugs');
        Cache::forget('slugs_list');
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

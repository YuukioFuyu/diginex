<?php

namespace App\Models;

use App\Constants\Status;
use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'is_default', 'is_rtl', 'status'];

    public static function default()
    {
        return Cache::rememberForever('default_language', function () {
            return self::where('is_default', true)->first(['code', 'is_rtl']);
        });
    }

    public static function languageGet()
    {
        return Cache::rememberForever('languagesPluck', function () {
            return self::where('status', Status::ACTIVE)->pluck('name', 'code');
        });
    }

    public static function flushCache(): void
    {
        Cache::forget('default_language');
        Cache::forget('languages');
        Cache::forget('languagesPluck');
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
    }
}

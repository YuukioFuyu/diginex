<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteCustom extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'value', 'is_active'];

    public static function value($type)
    {
        return Cache::rememberForever($type, function () use ($type) {
            return self::where('type', $type)->first();
        });
    }

    private static function flushCache($type): void
    {
        Cache::forget($type);
    }

    protected static function boot(): void
    {
        parent::boot();
        static::updated(function ($model) {
            self::flushCache($model->type);
        });
    }
}

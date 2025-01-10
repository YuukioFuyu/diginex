<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function blog()
    {
        return $this->hasMany(Blog::class, 'category_id');
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

    private static function flushCache(): void
    {
        Cache::forget('blogs');
        Cache::forget('blogCategories');
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

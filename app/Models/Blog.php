<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Blog extends Model
{
    use HasFactory;

    protected $with = ['category', 'author'];

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'summary',
        'content',
        'is_active',
        'author_id',
        'tag',
        'cover',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    public function getTransDataAttribute(): array
    {
        $title = json_decode($this->title, true);
        $summary = json_decode($this->summary, true);
        $content = json_decode($this->content, true);

        $defaultLanguage = config('app.static_default_language');

        return Language::languageGet()->keys()->mapWithKeys(function ($language) use ($defaultLanguage, $title, $summary, $content) {
            return [
                $language => (object) [
                    'title' => $title[$language] ?? $title[$defaultLanguage],
                    'summary' => $summary[$language] ?? $summary[$defaultLanguage],
                    'content' => $content[$language] ?? $content[$defaultLanguage],
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

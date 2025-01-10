<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ComponentContent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function component(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PageComponent::class, 'component_id');
    }

    private static function flushCache($content): void
    {

        if ($content->component->section == 'service') {
            Cache::forget('services');
        }
        Cache::forget('home_page_components');
        Cache::forget('page_component_'.$content->component->section);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::updated(function ($content) {
            self::flushCache($content);
        });

        static::created(function ($content) {
            self::flushCache($content);
        });

        static::deleted(function ($content) {
            self::flushCache($content);
        });
    }
}

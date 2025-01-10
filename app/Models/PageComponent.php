<?php

namespace App\Models;

use App\Constants\Component;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageComponent extends Model
{
    use HasFactory;

    protected $fillable = ['icon', 'name', 'section', 'category', 'content', 'type', 'content_fields', 'with_modal', 'status', 'content_id', 'sort', 'preview'];

    public function items()
    {
        return $this->hasMany(ComponentContent::class, 'component_id', 'id');
    }

    public function getContentAttribute($value)
    {

        if ($this->content_id !== null && $value === '{}') {
            return self::find($this->content_id)->content;
        }
        return $value;
    }

    private static function flushCache($component): void
    {
        Cache::forget('home_page_components');
        if ($component->type == Component::STATIC) {
            Cache::forget('page_component_'.$component->section);
        }
    }

    protected static function boot(): void
    {
        parent::boot();

        static::updated(function ($component) {
            self::flushCache($component);
        });

        static::created(function ($component) {
            self::flushCache($component);
        });
    }
}

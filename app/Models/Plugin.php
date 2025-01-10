<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Plugin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'credentials' => 'array',
        'copy_url' => 'array',
        'status' => 'boolean',
    ];

    public static function credentials($code): mixed
    {
        return Cache::rememberForever($code, function () use ($code) {
            $plugin = self::where('code', $code)->first();
            $credentials = $plugin->credentials;
            $credentials['status'] = $plugin->status;
            return $credentials;
        });
    }

    private static function flushCache($code): void
    {
        Cache::forget($code);
        Cache::forget('plugins_data');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::updated(function ($plugin) {
            self::flushCache($plugin->code);
        });
    }
}

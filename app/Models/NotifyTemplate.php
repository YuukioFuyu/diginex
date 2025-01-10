<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class NotifyTemplate extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'type',
        'subject',
        'push_message',
        'mail_message',
        'mail_status',
        'push_status',
        'variables', // Ensure this is fillable if you intend to use it
    ];

    /**
     * Get cached template data by code.
     */
    public static function getTemplateData(string $code): array
    {
        return Cache::rememberForever(self::notifyCacheKey($code), function () use ($code) {
            // Fetch the template by code
            $template = self::where('code', $code)->first();

            return [
                'name' => $template->name,
                'subject' => $template->subject,
                'push_message' => $template->push_message,
                'mail_message' => $template->mail_message,
                'via' => $template->getDeliveryMethods(),
            ];
        });
    }

    /**
     * Generate cache key based on template code.
     */
    private static function notifyCacheKey(string $code): string
    {
        return "notify_template_{$code}";
    }

    /**
     * Get delivery methods for the template.
     */
    protected function getDeliveryMethods(): array
    {
        $methods = [];

        if ($this->push_status) {
            $methods[] = 'database';
        }
        if ($this->mail_status) {
            $methods[] = 'mail';
        }

        return $methods;
    }

    /**
     * Boot method to handle model events and clear cache.
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when template is created, updated, or deleted
        static::saved(function ($template) {
            Cache::forget(self::notifyCacheKey($template->code));
        });

    }

    /**
     * Get template variables.
     */
    public function getVariablesAttribute(): array
    {
        return array_filter(explode(',', $this->attributes['variables'] ?? ''));
    }
}

<?php

namespace App\Models;

use App\Notifications\Admin\ResetPasswordNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected string $guard_name = 'admin';

    protected array $defaults = [
        'avatar' => 'general/static/default/avatar.png',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'avatar',
        'first_name',
        'last_name',
        'email',
        'password',
        'google2fa_enabled',
        'google2fa_secret',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Override to send the custom notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotify($token));
    }

    public function getRecentNotifications($limit = 5)
    {
        return $this->unreadNotifications()->take($limit)->get();
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getAttribute($key): mixed
    {
        $value = parent::getAttribute($key);

        return $value === null ? Arr::get($this->defaults, $key) : $value;
    }

    protected function google2faSecret(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                try {
                    return $value !== null ? decrypt($value) : $value;
                } catch (\Exception $e) {
                    // Handle the error gracefully, maybe log it or set to null
                    return null;  // Return null or another default value on failure
                }
            },
            set: fn ($value) => encrypt($value),
        );
    }

}

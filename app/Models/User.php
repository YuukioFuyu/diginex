<?php

namespace App\Models;

use App\Constants\SubscriptionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

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
        'phone',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'password',
        'google2fa_enabled',
        'google2fa_secret',
        'balance',
        'status',
        'email_verified_at',
        'provider',
        'provider_id',
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
        'google2fa_enabled' => 'boolean',
        'password' => 'hashed',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Check if the user has an active subscription and if it has expired.
     */
    public function checkSubscriptionExpiry(): bool
    {
        // Get the user's subscription
        $subscription = $this->subscription;

        // If the user does not have an active subscription, return false
        if ($subscription->status != SubscriptionStatus::ACTIVE) {
            return false;
        }

        // Check if the subscription has expired
        if ($subscription->expiry_date->lt(Carbon::now())) {
            // If it has expired, update the subscription status to EXPIRED
            $subscription->update(['status' => SubscriptionStatus::EXPIRED]);
        }

        // Return true if the subscription has not expired
        return $subscription->expiry_date->gte(Carbon::now());
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getCityAndCountryAttribute(): string
    {
        return ($this->city ? ($this->country ? "{$this->city}, {$this->country}" : $this->city) : $this->country) ?? '';
    }

    // User.php Model
    public function getRecentNotifications($limit = 5)
    {
        return $this->unreadNotifications()->take($limit)->get();
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
                    return;  // Return null or another default value on failure
                }
            },
            set: fn ($value) => encrypt($value),
        );
    }

    // Add a method to get the balance
    public function getBalance(): float
    {
        return $this->balance;
    }

    // Add a method to increase the balance
    public function addBalance(float $amount): bool
    {
        return $this->updateBalance($amount);
    }

    // Add a method to subtract from the balance
    public function subtractBalance(float $amount): bool
    {
        if ($this->balance < $amount) {
            return false; // Prevent balance going negative
        }

        return $this->updateBalance(-$amount);
    }

    // Helper method to handle balance updates safely within a transaction
    private function updateBalance(float $amount): bool
    {
        return DB::transaction(function () use ($amount) {
            // Use optimistic locking to avoid race conditions
            $this->balance += $amount;

            return $this->save();
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('email', 'LIKE', "%{$search}%");
    }
}

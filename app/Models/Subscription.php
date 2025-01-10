<?php

namespace App\Models;

use App\Constants\SubscriptionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'plan_id', 'transaction_id', 'duration_type', 'start_date', 'expiry_date', 'status'];

    protected $casts = [
        'start_date' => 'datetime',
        'expiry_date' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function scopeFindByTransactionId($query, int $transactionId)
    {
        return $query->where('transaction_id', $transactionId)->first();
    }

    public function isExpired(): bool
    {
        return $this->status === SubscriptionStatus::EXPIRED || $this->expiry_date->isPast();
    }

    public function isAvailableForRenew(): bool
    {
        return $this->status === SubscriptionStatus::EXPIRED
            || $this->status === SubscriptionStatus::ACTIVE
            && $this->expiry_date->isPast();
    }

    public function getCreatedAtTimeAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d Y h:i A');
    }

    public function getExpiryAtTimeAttribute(): string
    {
        return Carbon::parse($this->attributes['expiry_date'])->format('M d Y h:i A');
    }
}

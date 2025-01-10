<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $with = ['user'];

    protected $fillable = [
        'user_id',
        'txid',
        'description',
        'method',
        'method_type',
        'type',
        'amount',
        'fee',
        'currency',
        'pay_amount',
        'pay_currency',
        'array_data',
        'action_cause',
        'status',
    ];

    protected $casts = [
        'array_data' => 'array',
        'fee' => 'float',
        'amount' => 'float',
        'pay_amount' => 'float',
        'conversion_rate' => 'float',
    ];



    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function scopeGetByTxid($query, $txid)
    {
        return $query->where('txid', $txid)->first();
    }

    public function getCreatedAtTimeAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d Y h:i A');
    }

    // In Transaction.php (Model)

    public function scopeFilterByUser($query, ?int $userId)
    {
        return $userId ? $query->where('user_id', $userId) : $query;
    }

    public function scopeFilterByType($query, array $type)
    {
        return ! empty($type) ? $query->whereIn('type', $type) : $query;
    }

    public function scopeFilterByMethodType($query, array $methodType)
    {
        return ! empty($methodType) ? $query->whereIn('method_type', $methodType) : $query;
    }

    public function scopeFilterByStatus($query, ?string $status)
    {
        return $status && $status !== 'all' ? $query->where('status', $status) : $query;
    }

    public function scopeFilterByDateRange($query, ?array $dateRange)
    {
        if (! empty($dateRange)) {
            $startDate = Carbon::parse($dateRange[0])->startOfDay();
            $endDate = Carbon::parse($dateRange[1])->endOfDay();

            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }

    public function scopeSearch(Builder $query, ?string $searchTerm)
    {
        if (! empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                // Search in various transaction fields
                $q->where('type', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('txid', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($q) use ($searchTerm) {
                        // Combine first name and last name for search
                        $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$searchTerm}%"]);
                    });
            });
        }

        return $query;
    }

    public function scopeCountAmount(Builder $query, ?string $type, ?string $status)
    {
        return $query->where('type', $type)->where('status', $status)->sum('amount');
    }
}

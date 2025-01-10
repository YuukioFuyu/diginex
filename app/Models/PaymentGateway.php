<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'currencies',
        'credentials',
        'is_withdraw',
        'status',
    ];

    protected $casts = [
        'currencies' => 'array',
        'credentials' => 'array',
        'status' => 'boolean',
    ];

    public static function flushCache($paymentGateway): void
    {
        Cache::forget('payment_gateways');
        Cache::forget('payment_gateway_'.$paymentGateway->code);
        Cache::forget('payment_gateway_'.$paymentGateway->id);
    }

    public static function getAll($perPage = null)
    {
        // Retrieve payment gateways from the database and store them in the cache forever
        return Cache::rememberForever('payment_gateways', function () use ($perPage) {
            // Start the query to fetch payment gateways from the database
            $query = self::query();

            // If pagination is needed, paginate the payment gateways
            if ($perPage !== null) {
                return $query->paginate($perPage);
            }

            // If pagination is not needed, return all payment gateways as a collection
            return $query->get();
        });
    }

    public static function getByCode($code)
    {
        return Cache::rememberForever('payment_gateway_'.$code, function () use ($code) {
            return self::where('code', $code)->first();
        });
    }

    public static function getById($id)
    {
        return Cache::rememberForever('payment_gateway_'.$id, function () use ($id) {
            return self::where('id', $id)->first();
        });
    }

    public static function getCredentials($code)
    {

        $gateway = self::getByCode($code);
        if ($gateway) {
            return $gateway->credentials;
        }

        return [];
    }

    public static function getCurrencies($code)
    {

        $gateway = self::getByCode($code);
        if ($gateway) {
            return $gateway->currencies;
        }

        return [];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::updated(function ($paymentGateway) {
            self::flushCache($paymentGateway);
        });
    }
}

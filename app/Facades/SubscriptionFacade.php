<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\Subscription find(int $id)
 * @method static \App\Models\Subscription findByUserId(int $userId)
 * @method static \App\Models\Subscription findByTransactionId(string $transactionId)
 * @method static \App\Models\Subscription findByPlanId(int $planId)
 * @method static \App\Models\Subscription create(array $data)
 * @method static \App\Models\Subscription update(int $id, array $data)
 * @method static bool delete(int $id)
 *
 * @see \App\Services\SubscriptionService
 */ class SubscriptionFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'subscription';
    }

}

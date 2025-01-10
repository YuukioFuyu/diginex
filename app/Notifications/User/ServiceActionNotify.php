<?php

namespace App\Notifications\User;

use App\Constants\NotificationType;
use App\Models\Order;
use App\Models\Services;
use App\Notifications\NotificationBase;
use Exception;
use Illuminate\Bus\Queueable;

class ServiceActionNotify extends NotificationBase
{
    use Queueable;

    private mixed $serviceName;

    private object $transaction;

    /**
     * Create a new notification instance.
     *
     * @throws Exception
     */
    public function __construct(object $transaction)
    {
        $this->transaction = $transaction;

        // Eager load order and service
        $order = Order::with('service')->where('transaction_id', $transaction->id)->first();
        $this->serviceName = $order->service->name['en'] ?? 'service';

        parent::__construct(NotificationType::SERVICE_ACTION_USER);
    }

    protected function arrayData(): array
    {
        return [
            'amount' => $this->transaction->amount,
            'type' => $this->transaction->type,
            'status' => $this->transaction->status,
            'service_name' => $this->serviceName,
        ];
    }
}

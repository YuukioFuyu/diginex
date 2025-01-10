<?php

namespace App\Notifications\Admin;

use App\Constants\NotificationType;
use App\Models\Order;
use App\Notifications\NotificationBase;
use Exception;
use Illuminate\Bus\Queueable;

class ServiceActionNotify extends NotificationBase
{
    use Queueable;

    private ?string $serviceName;

    private object $transaction;

    /**
     * Constructor
     *
     * @throws Exception
     */
    public function __construct(object $transaction)
    {
        $this->transaction = $transaction;

        // Initialize parent constructor and pass the notification key
        parent::__construct(NotificationType::SERVICE_ACTION_ADMIN);

        // Eager load order and service
        $order = Order::with('service')->where('transaction_id', $transaction->id)->first();
        $this->serviceName = $order->service->name['en'] ?? 'service';
    }

    /**
     * Prepare the array data for the notification.
     */
    protected function arrayData(): array
    {
        return [
            'user_name' => $this->transaction->user->full_name,
            'user_avatar' => $this->transaction->user->avatar,
            'service_name' => $this->serviceName,
            'amount' => $this->formatCurrency($this->transaction->amount),
            'type' => $this->transaction->type,
            'method' => $this->transaction->method,
            'action_link' => route('admin.order.manual-payment'),
        ];
    }
}

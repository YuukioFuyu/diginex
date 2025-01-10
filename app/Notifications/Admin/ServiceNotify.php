<?php

namespace App\Notifications\Admin;

use App\Constants\NotificationType;
use App\Models\Order;
use App\Notifications\NotificationBase;
use Exception;
use Illuminate\Bus\Queueable;

class ServiceNotify extends NotificationBase
{
    use Queueable;

    private string $serviceName; // Stores the service name
    private object $transaction;  // The transaction object

    /**
     * Constructor
     *
     * @throws Exception
     */
    public function __construct(object $transaction)
    {
        $this->transaction = $transaction;

        // Fetch order and service details using eager loading
        $order = Order::with('service')->where('transaction_id', $transaction->id)->first();
        // Set the service name or a fallback value
        $this->serviceName = $order->service->name['en'] ?? 'service';


        // Initialize parent constructor and pass the notification key
        parent::__construct(NotificationType::SERVICE_ADMIN);
    }

    /**
     * Prepare the array data for the notification.
     * This method is required by the abstract base class.
     */
    protected function arrayData(): array
    {
        return [
            'user_name' => $this->transaction->user->full_name,
            'user_avatar' => $this->transaction->user->avatar,
            'service_name' => $this->serviceName ?? 'service',
            'amount' => $this->formatCurrency($this->transaction->amount),
            'type' => $this->transaction->type,
            'method' => $this->transaction->method,
            'action_link' => route('admin.order.history'),
        ];
    }
}

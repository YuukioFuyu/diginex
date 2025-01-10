<?php

namespace App\Notifications\Admin;

use App\Constants\NotificationType;
use App\Notifications\NotificationBase;
use Exception;
use Illuminate\Bus\Queueable;

class SubscriptionNotify extends NotificationBase
{
    use Queueable;

    private object $transaction;

    /**
     * Create a new notification instance.
     *
     * @throws Exception
     */
    public function __construct(object $transaction)
    {
        $this->transaction = $transaction;

        // Call parent constructor with the notification key
        parent::__construct(NotificationType::SUBSCRIPTION_ADMIN);
    }

    protected function arrayData(): array
    {
        return [
            'user_name' => $this->transaction->user->full_name,
            'user_avatar' => $this->transaction->user->avatar,
            'amount' => $this->formatCurrency($this->transaction->amount),
            'type' => $this->transaction->type,
            'method' => $this->transaction->method,
            'action_link' => route('admin.subscription.history'),
        ];
    }
}

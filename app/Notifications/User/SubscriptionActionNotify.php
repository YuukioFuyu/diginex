<?php

namespace App\Notifications\User;

use App\Constants\NotificationType;
use App\Notifications\NotificationBase;
use Exception;
use Illuminate\Bus\Queueable;

class SubscriptionActionNotify extends NotificationBase
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

        parent::__construct(NotificationType::SUBSCRIPTION_ACTION_USER);
    }

    protected function arrayData(): array
    {
        return [
            'amount' => $this->transaction->amount,
            'type' => $this->transaction->type,
            'status' => $this->transaction->status,
            'action_link' => route('user.subscription.my'),
        ];
    }
}

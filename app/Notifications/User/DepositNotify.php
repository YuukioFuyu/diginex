<?php

namespace App\Notifications\User;

use App\Constants\NotificationType;
use App\Notifications\NotificationBase;
use Illuminate\Bus\Queueable;

class DepositNotify extends NotificationBase
{
    use Queueable;

    private object $transaction;

    public function __construct(object $transaction)
    {
        $this->transaction = $transaction;
        parent::__construct(NotificationType::DEPOSIT_USER);

    }

    protected function arrayData(): array
    {
        return [
            'amount' => $this->formatCurrency($this->transaction->amount),
            'type' => $this->transaction->type,
            'action_link' => route('user.deposit.history'),
        ];
    }

}

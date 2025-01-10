<?php

namespace App\Notifications\User;

use App\Constants\NotificationType;
use App\Notifications\NotificationBase;
use Illuminate\Bus\Queueable;

class DepositActionNotify extends NotificationBase
{
    use Queueable;

    private object $transaction;

    public function __construct(object $transaction)
    {
        $this->transaction = $transaction;
        parent::__construct(NotificationType::DEPOSIT_ACTION_USER);
    }

    protected function arrayData(): array
    {
        return [
            'amount' => $this->formatCurrency($this->transaction->amount),
            'type' => $this->transaction->type,
            'status' => $this->transaction->status,
            'action_link' => route('user.deposit.history'),
        ];
    }
}

<?php

namespace App\Notifications\User;

use App\Constants\NotificationType;
use App\Notifications\NotificationBase;
use Exception;
use Illuminate\Bus\Queueable;

class BalanceUpdatedNotify extends NotificationBase
{
    use Queueable;

    public float $amount;

    public string $type;

    /**
     * Create a new notification instance.
     * @throws Exception
     */
    public function __construct(float $amount, string $type)
    {
        $this->amount = $amount;
        $this->type = $type;

        parent::__construct(NotificationType::BALANCE_UPDATED_USER);
    }

    protected function arrayData(): array
    {
        return [
            'amount' => $this->formatCurrency($this->amount),
            'type' => $this->type,
        ];
    }
}

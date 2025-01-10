<?php

namespace App\Notifications\User;

use App\Constants\NotificationType;
use App\Constants\type;
use App\Notifications\NotificationBase;
use Illuminate\Bus\Queueable;

class SupportNotification extends NotificationBase
{
    use Queueable;

    private object $ticket;

    public function __construct(object $ticket)
    {
        $this->ticket = $ticket;

        parent::__construct(NotificationType::SUPPORT_USER);
    }

    protected function arrayData(): array
    {
        $category = $this->ticket->category->name ?? __('Uncategorized');
        $ticketNumber = $this->ticket->uuid;

        return [
            'type' => type::SUPPORT_TICKET,
            'status' => $this->ticket->status,
            'ticket_number' => $ticketNumber,
            'category' => $category,
        ];
    }
}

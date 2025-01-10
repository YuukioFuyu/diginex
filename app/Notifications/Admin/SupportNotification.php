<?php

namespace App\Notifications\Admin;

use App\Constants\NotificationType;
use App\Constants\type;
use App\Notifications\NotificationBase;
use Illuminate\Bus\Queueable;

class SupportNotification extends NotificationBase
{
    use Queueable;

    private object $ticket;

    private string $type;

    public function __construct(object $ticket, string $type = 'create')
    {
        $this->ticket = $ticket;
        $this->type = $type;

        parent::__construct(NotificationType::SUPPORT_ADMIN);
    }

    protected function arrayData(): array
    {
        $category = $this->ticket->category->name ?? __('Uncategorized');
        $ticketNumber = $this->ticket->uuid;

        return [
            'user_name' => $this->ticket->user->full_name,
            'user_avatar' => $this->ticket->user->avatar,
            'type' => type::SUPPORT_TICKET,
            'ticket_type' => $this->type,
            'category' => $category,
            'ticket_number' => $ticketNumber,
            'action_link' => route('admin.support-ticket.show', $this->ticket->id),
        ];
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Constants\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Notifications\User\SupportNotification as UserSupportNotification;
use App\Traits\FileManageTrait;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use FileManageTrait;

    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'pendingTicket' => 'support-list',
            'inprogress' => 'support-list',
            'closeTicket' => 'support-list',
            'history' => 'support-list',
            'ticketShow' => 'support-manage',
            'ticketReplyStore' => 'support-manage',
            'statusUpdate' => 'support-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function pendingTicket()
    {
        $title = __('New Support Ticket Request');
        $tickets = Ticket::where('status', TicketStatus::PENDING)->orderBy('created_at', 'desc')->paginate(10);

        return view('backend.support_ticket.list', compact('tickets', 'title'));
    }

    public function inprogress()
    {
        $title = __('Open Support Ticket Request');
        $tickets = Ticket::where('status', TicketStatus::IN_PROGRESS)->orderBy('created_at', 'desc')->paginate(10);

        return view('backend.support_ticket.list', compact('tickets', 'title'));
    }

    public function closeTicket()
    {
        $title = __('Close Support Ticket Request');
        $tickets = Ticket::where('status', TicketStatus::CLOSED)->orderBy('created_at', 'desc')->paginate(10);

        return view('backend.support_ticket.list', compact('tickets', 'title'));
    }

    public function history()
    {
        $title = __('Support Ticket History');
        $tickets = Ticket::orderBy('created_at', 'desc')->paginate(10);

        return view('backend.support_ticket.list', compact('tickets', 'title'));
    }

    public function ticketShow(Ticket $ticket)
    {

        $title = __('Support Ticket Details');

        return view('backend.support_ticket.show', compact('ticket', 'title'));
    }

    public function ticketReplyStore(Ticket $ticket, Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);
        $ticket->messages()->create([
            'admin_id' => auth()->id(),
            'message' => $request->message,
            'attachment' => $request->hasFile('attachment') ? $this->uploadFile($request->file('attachment')) : null,
        ]);

        if ($ticket->status == TicketStatus::PENDING) {
            $ticket->status = TicketStatus::IN_PROGRESS;
            $ticket->save();
        }

        $ticket->user->notify(new UserSupportNotification($ticket));

        notifyEvs('success', 'Reply created successfully');

        return redirect()->back();
    }

    public function statusUpdate(Request $request, $ticketId)
    {
        $request->validate([
            'ticket_status' => 'required',
        ]);
        $ticket = Ticket::find($ticketId);
        $ticket->update([
            'status' => $request->ticket_status,
        ]);
        notifyEvs('success', 'Ticket status updated successfully');

        return redirect()->back();
    }
}

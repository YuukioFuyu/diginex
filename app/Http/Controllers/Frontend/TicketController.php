<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\Status;
use App\Constants\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SupportCategory;
use App\Models\Ticket;
use App\Notifications\Admin\SupportNotification as AdminSupportNotification;
use App\Traits\FileManageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    use FileManageTrait;

    public function index()
    {
        $tickets = Ticket::with('category')->where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(5);

        return view('frontend.user.ticket.index', compact('tickets'));
    }

    public function create()
    {
        $categories = SupportCategory::where('status', Status::ACTIVE)->get();

        return view('frontend.user.ticket.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
        ]);

        // Prepare ticket data
        $ticketData = [
            'uuid' => strtoupper('#'.Str::random(7)),
            'title' => $request->title,
            'message' => $request->message,
            'attachment' => $request->hasFile('attachment') ? $this->uploadFile($request->file('attachment')) : null,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'status' => TicketStatus::PENDING,
        ];

        // Create ticket
        $ticket = Ticket::create($ticketData);

        $admins = Admin::permission('support-notification')->get();
        Notification::send($admins, new AdminSupportNotification($ticket));

        notifyEvs('success', 'Ticket created successfully');

        return redirect()->route('user.support-ticket.index');
    }

    public function show($id)
    {

        $title = __('Support Ticket Details');

        $ticket = Ticket::findOrFail($id);

        return view('frontend.user.ticket.show', compact('title', 'ticket'));
    }

    public function reply(Ticket $ticket, Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $ticket->messages()->create([
            'message' => $request->message,
            'attachment' => $request->hasFile('attachment') ? $this->uploadFile($request->file('attachment')) : null,
        ]);
        $admins = Admin::permission('support-notification')->get();
        Notification::send($admins, new AdminSupportNotification($ticket, 'reply'));

        notifyEvs('success', 'Reply created successfully');

        return redirect()->back();
    }
}

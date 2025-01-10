<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\NotifyTemplate;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'list-notification',
            'recent' => 'list-notification',
            'markAllAsRead' => 'list-notification',
            'markAsRead' => 'list-notification',
            'template' => 'notification-template-list',
            'pushTemplate' => 'notification-template-manage',
            'mailTemplate' => 'notification-template-manage',
            'pushTemplateUpdate' => 'notification-template-manage',
            'mailTemplateUpdate' => 'notification-template-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(10);

        return view('backend.notification.index', compact('notifications'));
    }

    public function recent()
    {
        $notifications = auth()->user()->getRecentNotifications();

        return view('backend.layouts.partials._notification_dropdown', compact('notifications'))->render();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function template()
    {
        $notifyTemplates = NotifyTemplate::all();

        return view('backend.notification.template', compact('notifyTemplates'));
    }

    public function pushTemplate($id)
    {

        $pushTemplate = NotifyTemplate::find($id);

        return view('backend.notification.push_template', compact('pushTemplate'));
    }

    public function mailTemplate($id)
    {
        $mailTemplate = NotifyTemplate::find($id);

        return view('backend.notification.mail_template', compact('mailTemplate'));
    }

    public function pushTemplateUpdate(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'template_id' => 'required|exists:notify_templates,id',
            'message' => 'required|string',
            'status' => 'boolean',
        ]);

        // Find the template and update its fields
        $pushTemplate = NotifyTemplate::findOrFail($validated['template_id']);
        $pushTemplate->push_message = $validated['message'];
        $pushTemplate->push_status = $validated['status'];
        $pushTemplate->save();

        notifyEvs('success', __('Push template updated successfully'));

        return redirect()->back();
        //return redirect()->route('admin.notifications.template');
    }

    public function mailTemplateUpdate(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'template_id' => 'required|exists:notify_templates,id',
            'message' => 'required|string',
            'subject' => 'required|string',
            'status' => 'boolean',
        ]);

        // Find the template and update its fields
        $mailTemplate = NotifyTemplate::findOrFail($validated['template_id']);
        $mailTemplate->subject = $validated['subject'];
        $mailTemplate->mail_message = $validated['message'];
        $mailTemplate->mail_status = $validated['status'];
        $mailTemplate->save();

        notifyEvs('success', __('Mail template updated successfully'));

        return redirect()->back();
        //return redirect()->route('admin.notifications.template');
    }
}

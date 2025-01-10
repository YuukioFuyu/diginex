<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Subscriber;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'subscription' => 'email-subscriber',
            'mailSend' => 'email-subscriber',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function subscription()
    {
        $subscribers = Subscriber::all();

        return view('backend.subscriber.index', compact('subscribers'));
    }

    public function mailSend(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
        ]);

        if ($validatedData->fails()) {
            notifyEvs('error', $validatedData->errors()->first());

            return redirect()->back();
        }

        $validatedData = $validatedData->validated();

        try {
            $id = $request->id;
            $title = $validatedData['title'];
            $message = $validatedData['message'];
            $sendMail = Subscription::findOrFail($id)->email;

            Mail::to($sendMail)->send(new SendMail($title, $message));

            notifyEvs('success', __('Mail Send Successfully'));

            return redirect()->back();

        } catch (Exception $e) {
            notifyEvs('error', __('Failed to authenticate on SMTP server'));

            return redirect()->back();
        }

    }
}

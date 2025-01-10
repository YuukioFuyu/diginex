<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;

class ContactController extends Controller
{
    public function contactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            notifyEvs('error', $validator->errors()->first());
            return redirect()->back();
        }

        Mail::to(setting('support_email'))->queue(new SendMail($request->email, $request->message));

        Subscriber::updateOrCreate(
            ['email' => $request->email], // Update condition
            ['name' => $request->name, 'email' => $request->email] // Data to insert or update
        );

        notifyEvs('success', __('Message  Sent Successfully'));

        return redirect()->back();

    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Facades\TransactionFacade as Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class StatusController extends Controller
{
    // Handles successful payment
    public function success(Request $request)
    {
        return redirect()->route('user.transaction');
    }

    // Handles payment cancellation
    public function cancel(Request $request)
    {
        $tnx = $request->input('txid') ?? Session::get('cancel_tnx');
        Transaction::failTransaction($tnx);
        Session::forget('cancel_tnx');

        notifyEvs('warning', __('Payment Canceled'));

        return redirect()->route('user.transaction');
    }
}

<?php

namespace App\Payment;

class ManualPaymentSystem
{
    public function deposit(): \Illuminate\Http\RedirectResponse
    {
        notifyEvs('success', __('Successful Requested and will be processed shortly.'));
        return redirect()->route('user.transaction');
    }

}

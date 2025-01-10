<?php

namespace App\Payment\Paypal;

use App\Facades\TransactionFacade as Transaction;
use App\Payment\PaymentGateway as PaymentGatewayInterface;
use Illuminate\Http\Request;
use Session;

class PaypalPaymentGateway implements PaymentGatewayInterface
{
    private PayPalClient $client;

    public function __construct()
    {

        $this->client = new PayPalClient;
    }

    public function deposit($amount, $currency, $txid)
    {
        $paymentPayload = [
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'paypal',
            ],
            'transactions' => [
                [
                    'amount' => [
                        'total' => number_format($amount, 2),
                        'currency' => $currency,
                    ],
                    'description' => $txid,
                ],
            ],
            'redirect_urls' => [
                'return_url' => route('ipn.handle', ['gateway' => 'paypal']),
                'cancel_url' => route('status.cancel', ['txid' => $txid]),
            ],
        ];

        $payment = $this->client->createPayment($paymentPayload);
        Session::put('cancel_tnx', $txid);
        foreach ($payment->links as $link) {
            if ($link->rel === 'approval_url') {
                return redirect($link->href);
            }
        }

        return true;
    }

    public function handleIPN(Request $request): \Illuminate\Http\RedirectResponse
    {

        $response = $this->client->executePayment($request['paymentId'], $request['PayerID']);
        if (isset($response->state) && $response->state == 'approved') {
            $txn = $response->transactions[0]->description;
            Transaction::completeTransaction($txn);

            notifyEvs('success', __('Payment Successful'));

            return redirect()->route('user.dashboard');
        }

        notifyEvs('warning', __('Something went wrong. Please try again.'));

        return redirect()->back();
    }
}

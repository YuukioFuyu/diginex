<?php

namespace App\Payment\Mollie;

use App\Facades\TransactionFacade as Transaction;
use App\Models\PaymentGateway;
use App\Payment\PaymentGateway as PaymentGatewayInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MolliePaymentGateway implements PaymentGatewayInterface
{
    protected Client $client;

    private const MOLLIE_API_URL = 'https://api.mollie.com/v2/payments';

    public function __construct()
    {
        $credentials = PaymentGateway::getCredentials('mollie');
        if (empty($credentials['api_key'])) {
            throw new \Exception('Mollie API key is not set.');
        }

        $this->client = new Client([
            'headers' => [
                'Authorization' => 'Bearer '.$credentials['api_key'],
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function deposit($amount, $currency, $txid)
    {

        $paymentPayload = [
            'amount' => [
                'currency' => $currency,
                'value' => number_format($amount, 2, '.', ''),
            ],
            'description' => setting('site_title'),
            'redirectUrl' => route('status.success', ['txid' => $txid]),
            'webhookUrl' => route('ipn.handle', ['gateway' => 'mollie']),
            'metadata' => ['order_id' => $txid],
        ];

        try {
            $response = $this->client->post(self::MOLLIE_API_URL, ['json' => $paymentPayload]);
            $payment = json_decode($response->getBody(), true);

            if (! empty($payment['_links']['checkout']['href'])) {
                return redirect($payment['_links']['checkout']['href']);
            }

            Log::error('Mollie: Invalid payment response', $payment);

            return back()->withErrors(['error' => 'Failed to initiate payment, please try again.']);

        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $errorBody = $response ? $response->getBody()->getContents() : null;
            Log::error('Mollie Payment Error: '.$e->getMessage(), ['response' => $errorBody]);

            return back()->withErrors(['error' => 'Payment initiation failed. Please contact support.']);
        }
    }

    public function handleIPN(Request $request): \Illuminate\Http\JsonResponse
    {

        $paymentId = $request->input('id');
        try {
            $response = $this->client->get(self::MOLLIE_API_URL.'/'.$paymentId);
            $payment = json_decode($response->getBody(), true);

            if ($payment['status'] === 'paid') {
                Transaction::completeTransaction($payment['metadata']['order_id']);
                return response()->json(['status' => 'success']);
            } else {
                Transaction::failTransaction($payment['metadata']['order_id']);

                return response()->json(['status' => 'failed']);
            }
        } catch (GuzzleException $e) {
            Log::error('Mollie Webhook Error: '.$e->getMessage());

            return response()->json(['error' => 'Webhook processing failed'], 400);
        }
    }
}

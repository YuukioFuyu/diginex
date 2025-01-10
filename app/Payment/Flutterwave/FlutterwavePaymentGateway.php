<?php

namespace App\Payment\Flutterwave;

use App\Facades\TransactionFacade as Transaction;
use App\Models\PaymentGateway;
use App\Payment\PaymentGateway as PaymentGatewayInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlutterwavePaymentGateway implements PaymentGatewayInterface
{
    private const FLUTTERWAVE_API_URL = 'https://api.flutterwave.com/v3';

    protected Client $client;

    private string $publicKey;

    private string $secretKey;

    public function __construct()
    {
        $credentials = PaymentGateway::getCredentials('flutterwave');

        if (empty($credentials['secret_key']) || empty($credentials['public_key'])) {
            throw new \Exception('Flutterwave API credentials are not set.');
        }

        $this->publicKey = $credentials['public_key'];
        $this->secretKey = $credentials['secret_key'];

        $this->client = new Client([
            'headers' => [
                'Authorization' => 'Bearer '.$this->secretKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function deposit($amount, $currency, $txid)
    {
        $paymentPayload = [
            'tx_ref' => $txid,
            'amount' => $amount,
            'currency' => $currency,
            'redirect_url' => route('status.success'),
            'customer' => [
                'email' => auth()->user()->email ?? null, // Replace with actual customer email
            ],
            'customizations' => [
                'title' => setting('site_title'),
                'description' => 'Payment for order '.$txid,
            ],
        ];

        try {
            $response = $this->client->post(self::FLUTTERWAVE_API_URL.'/payments', [
                'json' => $paymentPayload,
            ]);

            $payment = json_decode($response->getBody(), true);

            if (! empty($payment['data']['link'])) {
                return redirect($payment['data']['link']);
            }

            Log::error('Flutterwave: Invalid payment response', $payment);

            return back()->withErrors(['error' => 'Failed to initiate payment, please try again.']);

        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $errorBody = $response ? $response->getBody()->getContents() : null;
            Log::error('Flutterwave Payment Error: '.$e->getMessage(), ['response' => $errorBody]);

            return back()->withErrors(['error' => 'Payment initiation failed. Please contact support.']);
        }
    }

    public function handleIPN(Request $request): \Illuminate\Http\JsonResponse
    {

        // Retrieve transaction references
        $txRef = $request->input('txRef');
        $transactionId = $request->input('id'); // Use ID if available

        if (! $transactionId) {
            // If ID is not found, return an error response
            return response()->json(['error' => 'Transaction ID not provided'], 400);
        }

        // Delay verification to account for potential synchronization issues
        sleep(2);

        try {
            // Verify transaction with Flutterwave using transaction ID
            $response = $this->client->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify", [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->secretKey, // Make sure this is set correctly
                    'Content-Type' => 'application/json',
                ],
            ]);
            $payment = json_decode($response->getBody(), true);

            if ($payment['status'] === 'success' && isset($payment['data'])) {
                if ($payment['data']['status'] === 'successful') {
                    Transaction::completeTransaction($txRef);

                    return response()->json(['status' => 'success']);
                } else {
                    Transaction::failTransaction($txRef);

                    return response()->json(['status' => 'failed']);
                }
            } else {
                Log::error('Unexpected Flutterwave response: '.json_encode($payment));

                return response()->json(['error' => 'Verification response unexpected'], 400);
            }

        } catch (GuzzleException $e) {
            Log::error('Flutterwave Webhook Verification Error: '.$e->getMessage());

            return response()->json(['error' => 'Verification failed'], 400);
        }
    }
}

<?php

namespace App\Payment\Stripe;

use App\Facades\TransactionFacade as Transaction;
use App\Models\PaymentGateway;
use App\Payment\PaymentGateway as PaymentGatewayInterface;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Stripe;
use Stripe\Webhook;

class StripePaymentGateway implements PaymentGatewayInterface
{
    protected $credentials;

    public function __construct()
    {
        $this->credentials = PaymentGateway::getCredentials('stripe');
        // Set the Stripe secret key once in the constructor
        Stripe::setApiKey($this->credentials['stripe_secret']);
    }

    public function deposit($amount, $currency, $txid)
    {
        // Create a new Checkout session for payment
        $session = CheckoutSession::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => setting('site_title'),
                    ],
                    'unit_amount' => (int) ($amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('status.success', ['txid' => $txid]),
            'cancel_url' => route('status.cancel', ['txid' => $txid]),
            'client_reference_id' => $txid, // Pass the transaction ID here
        ]);

        // Redirect to the Stripe checkout page
        return redirect($session->url);
    }

    public function handleIPN(Request $request): \Illuminate\Http\JsonResponse
    {
        // Retrieve the request's body and parse it
        $payload = $request->getContent();
        $sig_header = $request->headers->get('Stripe-Signature');
        $endpoint_secret = $this->credentials['webhook_secret'];

        try {
            // Verify the webhook signature
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event based on its type
        switch ($event['type']) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event['data']['object']);
                break;
                // You can add other case statements here for different events
            default:
                // Log or handle unexpected event types
                \Log::info('Unhandled event type: '.$event['type']);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleCheckoutSessionCompleted($session): void
    {
        $txn = $session->client_reference_id;
        Transaction::completeTransaction($txn);
    }
}

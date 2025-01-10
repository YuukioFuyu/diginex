<?php

namespace App\Services;

use App\Constants\DepositType;
use App\Constants\MethodType;
use App\Constants\TxnStatus;
use App\Constants\TxnType;
use App\Facades\BalanceFacade as UserBalance;
use App\Facades\TransactionFacade as Transaction;
use App\Models\DepositMethod;
use App\Payment\PaymentGatewayFactory;
use App\Traits\FileManageTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentService
{
    use FileManageTrait;

    protected PaymentGatewayFactory $paymentFactory;

    public function __construct(PaymentGatewayFactory $paymentFactory)
    {
        $this->paymentFactory = $paymentFactory;
    }

    /**
     * Process a payment for a transaction (Deposit, Subscription, Purchase).
     *
     * @throws Exception|Throwable
     */
    public function processPayment(array $requestData)
    {
        DB::beginTransaction();

        try {
            // Check if the payment is from the user's balance
            if (isset($requestData['payment_source']) && $requestData['payment_source'] === 'user_balance') {

                // Ensure user has enough balance
                $user = auth()->user();
                if ($user->balance < $requestData['amount']) {
                    throw new Exception(__('Insufficient balance.'));
                }

                $requestData['method_type'] = MethodType::SYSTEM;
                // Create transaction data
                $data = $this->prepareTransactionData($requestData, null, 0, $requestData['amount']); // No charge or deposit method needed

                // Create the transaction
                $transaction = Transaction::createTransaction($data);

                // Subtract from user's balance
                UserBalance::subtract($user, $transaction->amount);

                DB::commit();

                return $transaction;
            }

            // Fetch deposit method if payment is not from balance
            $depositMethod = DepositMethod::with('paymentGateway')->findOrFail($requestData['deposit_method']);

            // Validate amount range
            $this->validateAmount($depositMethod, $requestData['amount']);

            // Calculate charge and total payable amount
            [$charge, $total, $payAmount] = $this->calculateCharges($depositMethod, $requestData['amount']);

            // Prepare transaction data
            $data = $this->prepareTransactionData($requestData, $depositMethod, $charge, $payAmount);

            // Handle manual deposits
            if ($depositMethod->type == DepositType::MANUAL) {
                $data['method_type'] = MethodType::MANUAL;
                $data['array_data'] = $this->handleManualData($requestData['manual_data'] ?? []);
            }

            // Create the transaction
            $transaction = Transaction::createTransaction($data);

            // Get the payment gateway and process the payment
            $gateway = $this->paymentFactory->getGateway($depositMethod->paymentGateway->code ?? $depositMethod->type);
            $gatewayInjection = $gateway->deposit($transaction->pay_amount, $transaction->pay_currency, $transaction->txid);

            DB::commit();
            return [$transaction, $gatewayInjection];

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Payment processing failed: '.$e->getMessage());
        }
    }

    /**
     * Validate the amount against deposit method's min and max limits.
     *
     * @throws Exception
     */
    private function validateAmount(DepositMethod $depositMethod, float $amount): void
    {
        if ($depositMethod->min_deposit > $amount || $depositMethod->max_deposit < $amount) {
            throw new Exception(__('The amount must be between :min and :max.', [
                'min' => $depositMethod->min_deposit,
                'max' => $depositMethod->max_deposit,
            ]));
        }
    }

    /**
     * Calculate charge, total and payment amount.
     */
    private function calculateCharges(DepositMethod $depositMethod, float $amount): array
    {
        $charge = $depositMethod->charge_type == 'fixed' ? $depositMethod->charge : ($amount * $depositMethod->charge / 100);
        $total = $amount + $charge;
        $payAmount = $total * $depositMethod->conversion_rate;

        return [$charge, $total, $payAmount];
    }

    /**
     * Prepare the transaction data for saving.
     */
    private function prepareTransactionData(array $requestData, $depositMethod, float $charge, float $payAmount): array
    {
        $method = $depositMethod->name ?? $requestData['payment_source'];

        return [
            'user_id' => auth()->user()->id,
            'type' => $requestData['type'] ?? TxnType::DEPOSIT,
            'amount' => (float) $requestData['amount'],
            'fee' => $charge,
            'currency' => setting('site_currency'),
            'method' => $method,
            'method_Type' => $requestData['method_type'] ?? MethodType::AUTO,
            'pay_amount' => $payAmount,
            'pay_currency' => $depositMethod->currency ?? setting('site_currency'),
            'description' => __('Payment via :method', ['method' => $depositMethod->paymentGateway->name ?? $method]),
            'status' => TxnStatus::PENDING,
        ];
    }

    /**
     * Handle manual deposit data including file uploads.
     */
    private function handleManualData(array $manualData): array
    {
        foreach ($manualData as $key => $value) {
            if (is_file($value)) {
                $manualData[$key] = self::uploadImage($value);
            }
        }

        return $manualData;
    }
}

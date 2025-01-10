<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\MethodType;
use App\Constants\PlanType;
use App\Constants\SubscriptionStatus;
use App\Constants\TxnType;
use App\Facades\TransactionFacade as Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\RequestNowSubscriptionRequest;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Models\Admin;
use App\Models\DepositMethod;
use App\Models\Plan;
use App\Models\Subscription;
use App\Notifications\Admin\SubscriptionActionNotify as AdminSubscriptionActionNotify;
use App\Services\PaymentService;
use App\Services\SubscriptionService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Throwable;

class SubscriptionController extends Controller
{
    protected PaymentService $paymentService;

    protected SubscriptionService $subscriptionService;

    public function __construct(PaymentService $paymentService, SubscriptionService $subscriptionService)
    {
        $this->paymentService = $paymentService;
        $this->subscriptionService = $subscriptionService;
    }

    public function requestNow(RequestNowSubscriptionRequest $request)
    {
        $title = __('Subscription Request');
        $validated = $request->validated();

        // Fetch the plan and determine its price based on plan type
        $plan = Plan::findOrFail($validated['plan_id']);
        $planPrice = $this->getPlanPrice($plan, $validated['plan_type']);

        $subscriptionInfo = [
            'plan_name' => _tr($plan->name),
            'plan_id' => $plan->id,
            'plan_type' => $validated['plan_type'],
            'plan_price' => $planPrice,
        ];

        // Fetch active deposit methods
        $depositMethods = DepositMethod::active()->get();

        return view('frontend.user.subscription.request', compact('subscriptionInfo', 'depositMethods', 'title'));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $validated = $request->validated();

        try {
            $plan = Plan::findOrFail($validated['plan_id']);
            $planPrice = $this->getPlanPrice($plan, $validated['plan_type']);
            $validated['amount'] = $planPrice;
            $validated['type'] = $validated['txn_type'];

            if ($validated['deposit_method'] === 'user_balance') {
                $validated['payment_source'] = 'user_balance';
                $validated['method_type'] = MethodType::SYSTEM;
                return $this->processUserBalancePayment($validated, $plan);
            }

            return $this->processOtherDepositMethods($validated, $plan);

        } catch (Exception $e) {
            notifyEvs('error', $e->getMessage());

            return back();
        }
    }

    public function mySubscription()
    {
        $subscription = $this->getUserSubscription();


        return view('frontend.user.subscription.my_subscription', compact('subscription'));
    }

    public function upgrade()
    {
        $subscription = $this->getUserSubscription();

        // Fetch all plans except the current subscription's plan
        $plans = Plan::where('id', '!=', $subscription->plan_id)->get();
        $currentPlan = Plan::find($subscription->plan_id);

        return view('frontend.user.subscription.upgrade', compact('subscription', 'plans', 'currentPlan'));
    }

    public function renew()
    {
        $title = __('Renew Subscription');
        $subscription = $this->getUserSubscription();

        $subscriptionInfo = [
            'plan_name' => _tr($subscription->plan->name),
            'plan_id' => $subscription->plan->id,
            'plan_type' => $subscription->duration_type,
            'plan_price' => $this->getPlanPrice($subscription->plan, $subscription->duration_type),
        ];

        $depositMethods = DepositMethod::active()->get();
        $txnType = TxnType::RENEW_SUBSCRIPTION;

        return view('frontend.user.subscription.request', compact('subscriptionInfo', 'depositMethods', 'title', 'txnType'));
    }

    private function getPlanPrice(Plan $plan, string $planType)
    {
        return $planType == PlanType::MONTHLY ? $plan->monthly_price : $plan->yearly_price;
    }

    private function getUserSubscription()
    {
        return Subscription::where('user_id', auth()->id())->first();
    }

    private function processUserBalancePayment(array $validated, Plan $plan)
    {
        return DB::transaction(function () use ($plan, $validated) {

            $validated['method_type'] = MethodType::SYSTEM;

            $transaction = $this->paymentService->processPayment($validated);

            if ($validated['txn_type'] == TxnType::RENEW_SUBSCRIPTION) {
                $this->subscriptionService->renewSubscription($transaction->user_id, $transaction->id);
            } else {
                $this->subscriptionService->createSubscription($plan, $validated['plan_type'], $transaction->id, SubscriptionStatus::ACTIVE);
            }

            Transaction::completeTransaction($transaction->txid);
            notifyEvs('success', 'Successfully subscribed.');

            return redirect()->route('user.transaction');
        });
    }

    /**
     * @throws Throwable
     */
    private function processOtherDepositMethods(array $validated, Plan $plan)
    {
        [$transaction, $gatewayInjection] = $this->paymentService->processPayment($validated);

        if ($validated['txn_type'] == TxnType::RENEW_SUBSCRIPTION) {
            $this->subscriptionService->renewSubscription($transaction->user_id, $transaction->id);
        } else {
            $this->subscriptionService->createSubscription($plan, $validated['plan_type'], $transaction->id);
        }

        if ($transaction->method_type === MethodType::MANUAL) {
            $admins = Admin::permission('subscription-notification')->get();
            Notification::send($admins, new AdminSubscriptionActionNotify($transaction));
        }

        return $gatewayInjection;
    }
}

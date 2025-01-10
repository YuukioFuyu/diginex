<?php

namespace App\Http\Controllers\Backend;

use App\Constants\MethodType;
use App\Constants\SubscriptionStatus;
use App\Constants\TxnStatus;
use App\Http\Controllers\Controller;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'manualPayment' => 'subscription-manage',
            'running' => 'subscription-list',
            'expired' => 'subscription-list',
            'history' => 'subscription-list',
        ];
        $this->permissionAuthorization($permissions);
    }
    public function manualPayment()
    {
        $title = __('Subscription Request With Manual Payment');
        $subscriptions = Subscription::where('status', SubscriptionStatus::PENDING)
            ->whereHas('transaction', function ($query) {
                $query->where('method_type', MethodType::MANUAL);
                $query->where('status', TxnStatus::PENDING);
            })
            ->with(['user', 'plan', 'transaction'])
            ->paginate(10);

        $paymentAction = true;

        return view('backend.subscription.list', compact('subscriptions', 'title', 'paymentAction'));
    }

    public function running()
    {
        $title = __('Running Subscription');
        $subscriptions = Subscription::where('status', SubscriptionStatus::ACTIVE)
            ->with(['user', 'plan', 'transaction'])
            ->paginate(10);
        return view('backend.subscription.list', compact('subscriptions', 'title'));
    }

    public function expired()
    {
        $title = __('Expired Subscription');
        $subscriptions = Subscription::where('status', SubscriptionStatus::EXPIRED)
            ->with(['user', 'plan', 'transaction'])
            ->paginate(10);

        return view('backend.subscription.list', compact('subscriptions', 'title'));
    }

    public function history()
    {
        $title = __('Subscription History');
        $subscriptions = Subscription::with(['user', 'plan', 'transaction'])
            ->paginate(10);
        $paymentAction = true;

        return view('backend.subscription.list', compact('subscriptions', 'title', 'paymentAction'));
    }
}

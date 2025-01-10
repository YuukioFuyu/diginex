<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\MethodType;
use App\Constants\TxnType;
use App\Facades\TransactionFacade as Transaction;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\DepositMethod;
use App\Notifications\Admin\DepositActionNotify as AdminDepositActionNotify;
use App\Services\PaymentService;
use App\Traits\FileManageTrait;
use Exception;
use Illuminate\Http\Request;
use Notification;
use Throwable;

class DepositController extends Controller
{
    use FileManageTrait;

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;

    }

    public function create()
    {
        $depositMethods = DepositMethod::active()->get();

        return view('frontend.user.deposit.create', compact('depositMethods'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'deposit_method' => 'required|exists:deposit_methods,id',
            'amount' => 'required|numeric',
            'manual_data' => 'nullable|array',
        ]);



        try {
            [$transaction, $gatewayInjection] = $this->paymentService->processPayment($validated);


            if ($transaction->method_type === MethodType::MANUAL) {

                $admins = Admin::permission('deposit-notification')->get();
                Notification::send($admins, new AdminDepositActionNotify($transaction));
            }

            return $gatewayInjection;

        } catch (Exception $e) {
            notifyEvs('error', $e->getMessage());

            return redirect()->back();
        }
    }

    public function history()
    {
        $depositHistories = Transaction::getTransactions(auth()->user()->id, [TxnType::DEPOSIT]);

        return view('frontend.user.deposit.history', compact('depositHistories'));
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Constants\MethodType;
use App\Constants\TxnStatus;
use App\Constants\TxnType;
use App\Facades\TransactionFacade as Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class DepositController extends Controller
{
    use Notifiable;

    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'manualDepositRequest' => 'deposit-list',
            'history' => 'deposit-list',
            'depositRequestAction' => 'deposit-action',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function manualDepositRequest()
    {

        $depositRequests = Transaction::getTransactions(null, [TxnType::DEPOSIT], [MethodType::MANUAL], TxnStatus::PENDING);

        return view('backend.deposit.manual_deposit_request', compact('depositRequests'));
    }

    public function history()
    {
        $depositHistories = Transaction::getTransactions(null, [TxnType::DEPOSIT]);

        return view('backend.deposit.history', compact('depositHistories'));
    }

    public function depositRequestAction(Request $request, $txId)
    {
        // Fetch transaction details using a scope or method
        $depositRequest = Transaction::getTransaction($txId);

        // Handle GET request to display form data
        if ($request->isMethod('get')) {
            return view('backend.deposit.partials._deposit_action_form_data', compact('depositRequest'))->render();
        }

        // Handle POST request to process form actions
        if ($request->isMethod('post')) {
            // Use a helper method to process the form data
            $this->processDepositRequest($request, $txId);

            notifyEvs('success', __('The request has been updated.'));

            return redirect()->back();
        }

        // Abort if request method is not GET or POST
        abort(405, 'Method Not Allowed');
    }

    /**
     * Process the deposit request based on the form submission.
     *
     * @param  string  $txId
     * @return void
     */
    private function processDepositRequest(Request $request, $txId)
    {
        // Complete transaction and add balance if approved
        if ($request->filled('approve') && $request->input('approve') === 'yes') {
            // Fetch transaction and update status to completed
            $transaction = Transaction::getTransaction($txId);
            Transaction::completeTransaction($txId);
        }

        if ($request->has('reject') && $request->input('reject') === 'yes') {
            Transaction::failTransaction($txId);
        }

        // If there's a message, update the transaction data
        if ($request->filled('message')) {
            $data = ['action_cause' => $request->input('message')];
            Transaction::fieldsValueUpdate($txId, $data);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Txn;

class TransactionController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'transaction' => 'transaction-list',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function transaction(Request $request)
    {

        $transactions = Txn::getTransactions();

        return view('backend.transaction.index', compact('transactions'));
    }
}

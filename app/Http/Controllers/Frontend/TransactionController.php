<?php

namespace App\Http\Controllers\Frontend;

use App\Facades\TransactionFacade as Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function transaction()
    {

        // Paginate the results
        $transactions = Transaction::getTransactions(auth()->user()->id);

        // Return the view with the transactions
        return view('frontend.user.transaction.index', compact('transactions'));
    }
}

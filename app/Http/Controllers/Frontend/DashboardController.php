<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\OrderStatus;
use App\Constants\TicketStatus;
use App\Constants\TxnStatus;
use App\Constants\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $recentTransactions = Transaction::where('user_id', auth()->id())->orderBy('created_at', 'desc')->take(5)->get();
        $currencySymbol = setting('currency_symbol');
        $statistics = [
            [
                'title' => __('Total Deposits'),
                'value' => $currencySymbol.(\Txn::getTotalAmount(TxnType::DEPOSIT, TxnStatus::COMPLETED, auth()->id())),
                'icon' => 'wallet-1',
                'color' => 'blue',
            ],
            [
                'title' => __('Service Completed'),
                'value' => Order::where('user_id', auth()->id())->where('status', OrderStatus::COMPLETED)->count(),
                'icon' => 'service',
                'color' => 'red',
            ],
            [
                'title' => __('Running Service'),
                'value' => Order::where('user_id', auth()->id())->where('status', OrderStatus::WORKING)->count(),
                'icon' => 'order',
                'color' => 'indigo',
            ],
            [
                'title' => __('Pending Service'),
                'value' => Order::where('user_id', auth()->id())->where('status', OrderStatus::PENDING)->count(),
                'icon' => 'service-1',
                'color' => 'orange',
            ],
            [
                'title' => __('Total Transaction'),
                'value' => Transaction::where('user_id', auth()->id())->count(),
                'icon' => 'transaction',
                'color' => 'green',
            ],
            [
                'title' => __('Total Completed Transaction'),
                'value' => Transaction::where('user_id', auth()->id())->where('status', TxnStatus::COMPLETED)->count(),
                'icon' => 'transaction-1',
                'color' => 'yellow',
            ],
            [
                'title' => __('Total Support Ticket'),
                'value' => Ticket::where('user_id', auth()->id())->count(),
                'icon' => 'ticket',
                'color' => 'purple',
            ],
            [
                'title' => __('Pending Support Ticket'),
                'value' => Ticket::where('user_id', auth()->id())->where('status', TicketStatus::PENDING)->count(),
                'icon' => 'ticket-2',
                'color' => 'pink',
            ],
        ];

        // Get the date range
        $startDate = request()->start_date ? Carbon::createFromDate(request()->start_date) : Carbon::now()->subWeek()->startOfDay();
        $endDate = request()->end_date ? Carbon::createFromDate(request()->end_date) : Carbon::now()->endOfDay();

        $dateRange = $startDate->format('m-d-Y').' - '.$endDate->format('m-d-Y');

        // Generate all dates in the range

        $transactionsStatistics = Transaction::where('user_id', auth()->id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('date(created_at) as date, status, count(*) as total')
            ->groupBy('date', 'status')
            ->get()
            ->groupBy('date')
            ->map(function ($items) {
                $items = $items->keyBy('status')->map->total;
                $status = TxnStatus::STATUS;
                foreach ($status as $value) {
                    if (! $items->has($value)) {
                        $items[$value] = 0;
                    }
                }

                return $items;
            })
            ->toArray();

        $selectedDateData = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $selectedDateData[$date->format('Y-m-d')] = [
                TxnStatus::COMPLETED => 0,
                TxnStatus::PENDING => 0,
                TxnStatus::FAILED => 0,
            ];

        }

        $transactionsStatistics = array_merge($selectedDateData, $transactionsStatistics);

        if (request()->ajax()) {
            return response()->json($transactionsStatistics);
        }

        $serviceStatistics = Order::where('user_id', auth()->id());

        $serviceStatistics = $serviceStatistics->selectRaw('status, count(*) as total')->groupBy('status')->get()->keyBy('status')->map->total;

        $serviceStatistics = array_merge(array_fill_keys(OrderStatus::STATUS, 0), $serviceStatistics->toArray());

        return view('frontend.user.dashboard.index', compact('statistics', 'recentTransactions', 'transactionsStatistics', 'dateRange', 'serviceStatistics'));
    }
}

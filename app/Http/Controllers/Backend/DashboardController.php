<?php

namespace App\Http\Controllers\Backend;

use App\Constants\OrderStatus;
use App\Constants\TicketStatus;
use App\Constants\TxnStatus;
use App\Constants\TxnType;
use App\Constants\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Page;
use App\Models\PageComponent;
use App\Models\PaymentGateway;
use App\Models\Ticket;
use App\Models\Transaction as TransactionModel;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request, UserService $userService)
    {

        $cardStatistics = $this->getStatistics();

        // Retrieve transaction statistics and date range from the service
        $transactionData = $userService->getTransactionStatistics(null, $request->all());
        $transactionsStatistics = $transactionData['transactionsStatistics'];

        $dateRange = $transactionData['dateRange'];

        if ($request->ajax()) {
            return $this->ajaxRequestChart($request, $transactionsStatistics);
        }

        $orderStatistics = $this->getOrderStatistics($request->start_date, $request->end_date);
        $oderChartData = $orderStatistics['orderStatistics'];
        $orderDateRange = $orderStatistics['orderDateRange'];

        // Retrieve service statistics
        $serviceStatistics = Order::selectRaw('service_id, count(*) as total')
            ->with('service') // Assuming the relationship is 'service'
            ->groupBy('service_id')
            ->get()
            ->mapWithKeys(function ($order) {
                return [$order->service->name['en'] => $order->total];
            })
            ->toArray();

        $users = User::latest()->take(5)->get();

        return view('backend.dashboard.index', compact('cardStatistics', 'transactionsStatistics', 'dateRange', 'serviceStatistics', 'users', 'oderChartData', 'orderDateRange'));
    }

    private function getStatistics()
    {
        $currencySymbol = setting('currency_symbol');

        return [
            [
                'title' => __('Total Users'),
                'value' => User::count(),
                'icon' => 'list-1',
                'color' => 'purple',
                'link' => route('admin.users.all'),
            ],
            [
                'title' => __('Active Users'),
                'value' => User::where('status', UserStatus::ACTIVE)->count(),
                'icon' => 'active-users',
                'color' => 'success',
                'link' => route('admin.users.active'),
            ],
            [
                'title' => __('Unverified Users'),
                'value' => User::where('email_verified_at', null)->count(),
                'icon' => 'not-verified',
                'color' => 'secondary',
                'link' => route('admin.users.unverified'),
            ],
            [
                'title' => __('Blocked Users'),
                'value' => User::where('status', UserStatus::BANNED)->count(),
                'icon' => 'banned-users',
                'color' => 'danger',
                'link' => route('admin.users.banned'),
            ],
            [
                'title' => __('Total Deposits'),
                'value' => $currencySymbol.\Txn::getTotalAmount(TxnType::DEPOSIT, TxnStatus::COMPLETED),
                'icon' => 'wallet-2',
                'color' => 'success',
                'link' => route('admin.deposit.history'),
            ],
            [
                'title' => __('Service Completed'),
                'value' => Order::where('status', OrderStatus::COMPLETED)->count(),
                'icon' => 'service',
                'color' => 'indigo',
                'link' => route('admin.order.history'),
            ],
            [
                'title' => __('Running Service'),
                'value' => Order::where('status', OrderStatus::WORKING)->count(),
                'icon' => 'order',
                'color' => 'info',
                'link' => route('admin.order.working'),
            ],
            [
                'title' => __('Pending Service'),
                'value' => Order::where('status', OrderStatus::PENDING)->count(),
                'icon' => 'service-1',
                'color' => 'danger',
                'link' => route('admin.order.request'),
            ],
            [
                'title' => __('Total Transaction'),
                'value' => TransactionModel::count(),
                'icon' => 'transaction-2',
                'color' => 'purple',
                'link' => route('admin.transaction'),
            ],
            [
                'title' => __('Completed Transaction'),
                'value' => TransactionModel::where('status', TxnStatus::COMPLETED)->count(),
                'icon' => 'transaction-3',
                'color' => 'success',
                'link' => route('admin.transaction'),
            ],
            [
                'title' => __('Total Support Ticket'),
                'value' => Ticket::count(),
                'icon' => 'ticket-3',
                'color' => 'purple',
                'link' => route('admin.support-ticket.history'),
            ],
            [
                'title' => __('Pending Support Ticket'),
                'value' => Ticket::where('status', TicketStatus::PENDING)->count(),
                'icon' => 'ticket-4',
                'color' => 'secondary',
                'link' => route('admin.support-ticket.history'),
            ],
            [
                'title' => __('Solved Support Ticket'),
                'value' => Ticket::where('status', TicketStatus::COMPLETED)->count(),
                'icon' => 'ticket-check',
                'color' => 'success',
                'link' => route('admin.support-ticket.history'),
            ],
            [
                'title' => __('Automatic Gateway'),
                'value' => PaymentGateway::count(),
                'icon' => 'payment',
                'color' => 'danger',
                'link' => route('admin.payment.gateway.index'),
            ],
            [
                'title' => __('Total Components'),
                'value' => PageComponent::count(),
                'icon' => 'component-1',
                'color' => 'secondary',
                'link' => route('admin.page.component.index'),
            ],
            [
                'title' => __('Total Pages'),
                'value' => Page::count(),
                'icon' => 'page-1',
                'color' => 'purple',
                'link' => route('admin.page.site.index'),
            ],
        ];
    }

    public function ajaxRequestChart(Request $request, $transactionsStatistics)
    {
        // Validate the incoming request parameters
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'type' => 'nullable|string',
        ]);

        // Handle order statistics request
        if (isset($request->type) && $request->type == 'order') {

            $orderStatistics = $this->getOrderStatistics($request->start_date, $request->end_date);
            $oderChartData = $orderStatistics['orderStatistics'];

            return response()->json($oderChartData);
        }

        // Default response for transactions or other types
        return response()->json($transactionsStatistics);

    }

    private function getOrderStatistics($startDate = null, $endDate = null)
    {
        // Set default date range if not provided
        $startDate = Carbon::parse($startDate ?? now()->subDays(9))->startOfDay(); // Default to 15 days ago
        $endDate = Carbon::parse($endDate ?? now())->endOfDay(); // Default to today's date

        $orderDateRange = $startDate->format('m/d/Y').' - '.$endDate->format('m/d/Y');

        // Generate an array of dates in the range
        $dateRange = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateRange[] = $date->format('M d'); // Store formatted dates as "M d"
        }

        // Fetch statistics for orders grouped by date and status
        $orderStats = Order::selectRaw('DATE(created_at) as date, status, COUNT(id) as total_orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date', 'status')
            ->orderBy('date', 'asc')
            ->get();

        // Initialize the result array with formatted dates and default values
        $result = [];
        $statuses = ['pending', 'working', 'completed', 'cancelled']; // List your order statuses here

        foreach ($statuses as $status) {
            $result[$status] = array_fill_keys($dateRange, 0); // Fill each formatted date with 0
        }

        // Populate the result array with the fetched statistics
        foreach ($orderStats as $stat) {
            // Format the fetched date to "M d" to match the keys in $result
            $formattedDate = Carbon::parse($stat->date)->format('M d');

            // Update the count for the corresponding status and formatted date
            if (isset($result[$stat->status][$formattedDate])) {
                $result[$stat->status][$formattedDate] = $stat->total_orders; // Update the count for the corresponding status and formatted date
            }
        }

        return [
            'orderStatistics' => $result,
            'orderDateRange' => $orderDateRange, // Keep this as Y-m-d format
        ];
    }
}

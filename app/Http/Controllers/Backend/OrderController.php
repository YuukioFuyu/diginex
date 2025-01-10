<?php

namespace App\Http\Controllers\Backend;

use App\Constants\MethodType;
use App\Constants\OrderStatus;
use App\Constants\TxnStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'manualPayment' => 'order-manage',
            'orderRequest' => 'order-list',
            'orderManage' => 'order-manage',
            'working' => 'order-list',
            'history' => 'order-list',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function manualPayment()
    {
        $title = __('Order Request With Manual Payment');
        $orders = Order::where('status', OrderStatus::PENDING)
            ->whereHas('transaction', function ($query) {
                $query->where('method_type', MethodType::MANUAL);
                $query->where('status', TxnStatus::PENDING);
            })
            ->with(['user', 'service', 'transaction', 'tasks'])
            ->paginate(10);

        $paymentAction = true;

        return view('backend.order.list', compact('orders', 'title', 'paymentAction'));
    }

    public function orderRequest()
    {

        $title = __('Order Request');
        $orders = Order::where('status', OrderStatus::PENDING)
            ->whereHas('transaction', function ($query) {
                $query->where('status', TxnStatus::COMPLETED);
            })
            ->with(['user', 'service', 'transaction', 'tasks'])
            ->paginate(10);

        return view('backend.order.list', compact('orders', 'title'));
    }

    public function orderManage($id)
    {

        $order = Order::with(['user', 'service', 'transaction', 'tasks'])->find($id);

        $paymentStatus = collect([
            'pending' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
        ])->get($order->transaction->status, 'primary');

        // Task priority levels mapping
        $taskPriority = [
            'low' => 'primary',
            'medium' => 'warning',
            'high' => 'danger',
        ];

        // Task statuses and their respective properties
        $statuses = collect([
            'todo' => [
                'label' => __('Pending Tasks'),
                'tasks' => $order->tasks()->pending()->get(),
                'column_id' => 'todo-column',
                'border' => 'border-secondary',
            ],
            'in_progress' => [
                'label' => __('In Progress'),
                'tasks' => $order->tasks()->inProgress()->get(),
                'column_id' => 'in-progress-column',
                'border' => 'border-info',
            ],
            'done' => [
                'label' => __('Done'),
                'tasks' => $order->tasks()->completed()->get(),
                'column_id' => 'done-column',
                'border' => 'border-success',
            ],
        ]);

        return view('backend.order.manage', compact('order', 'paymentStatus', 'taskPriority', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:'.implode(',', OrderStatus::STATUS),
        ]);

        $order->status = $request->status;
        $order->save();

        notifyEvs('success', 'Order status updated successfully.');

        return redirect()->back();
    }

    public function working()
    {
        $title = __('Working Orders');
        $orders = Order::where('status', OrderStatus::WORKING)->with(['user', 'service', 'transaction', 'tasks'])->paginate(10);

        return view('backend.order.list', compact('orders', 'title'));
    }

    public function history()
    {
        $title = __('Order History');

        // Get date range from request
        $dateRange = request('daterange');

        // Define the query with search and date range filters
        $orders = Order::with(['user', 'service', 'transaction', 'tasks'])
            ->search(request('search'))
            ->dateRange($dateRange)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.order.list', compact('orders', 'title'));
    }
}

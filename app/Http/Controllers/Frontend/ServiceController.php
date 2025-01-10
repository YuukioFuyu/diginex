<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\MethodType;
use App\Constants\OrderStatus;
use App\Constants\TxnType;
use App\Facades\TransactionFacade as Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Services\ServicePurchaseRequest;
use App\Models\Admin;
use App\Models\DepositMethod;
use App\Models\Order;
use App\Models\Services;
use App\Models\Task;
use App\Notifications\Admin\ServiceNotify as AdminServiceActionNotify;
use App\Services\PaymentService;
use App\Traits\FileManageTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ServiceController extends Controller
{
    use FileManageTrait;

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;

    }

    public function details($slug)
    {
        $service = Services::getBySlugWithCache($slug);

        return view('frontend.service.details', compact('service'));
    }

    public function bookService($slug)
    {
        $service = Services::getBySlug($slug);
        if (! $service) {
            abort(404);
        }

        $depositMethods = DepositMethod::active()->get();

        return view('frontend.user.service.book_now', compact('service', 'depositMethods'));
    }

    public function store(ServicePurchaseRequest $request)
    {
        $validated = $request->validated();

        try {
            $service = Services::findOrFail($validated['service_id']);

            // Add service price and transaction type
            $validated['amount'] = $service->price;
            $validated['type'] = TxnType::SERVICE_PURCHASE;

            if ($validated['deposit_method'] === 'user_balance') {
                $validated['payment_source'] = 'user_balance';
                $validated['method_type'] = MethodType::SYSTEM;

                return $this->processUserBalancePayment($validated, $service);
            }

            return $this->processOtherDepositMethods($validated, $service);

        } catch (Exception $e) {
            return $this->handlePaymentError($e);
        }
    }

    public function myService()
    {
        // Task priority levels mapping

        $orders = Order::where('user_id', auth()->id())
            ->orderByRaw('status = ? DESC', [OrderStatus::WORKING])
            ->latest()
            ->paginate(10);

        return view('frontend.user.service.my_service', compact('orders'));
    }

    public function taskBoard($id)
    {
        $order = Order::with(['user', 'service', 'transaction', 'tasks'])->find($id);

        $paymentStatus = collect([
            'pending' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
        ])->get($order->transaction->status, 'primary');

        $orderStatus = collect([
            OrderStatus::PENDING => 'info',
            OrderStatus::WORKING => 'orange',
            OrderStatus::COMPLETED => 'success',
            OrderStatus::CANCELLED => 'danger',
        ])->get($order->status);

        // Task priority levels mapping
        $taskPriority = [
            'low' => 'primary',
            'medium' => 'warning',
            'high' => 'danger',
        ];

        // Task statuses and their respective properties
        $taskBoard = collect([
            [
                'label' => __('Pending Tasks'),
                'tasks' => $order->tasks()->pending()->get(),
                'border' => 'indigo',
            ],
            [
                'label' => __('In Progress'),
                'tasks' => $order->tasks()->inProgress()->get(),
                'border' => 'orange',
            ],
            [
                'label' => __('Done'),
                'tasks' => $order->tasks()->completed()->get(),
                'border' => 'green',
            ],
        ]);

        return view('frontend.user.service.task_board', compact('order', 'paymentStatus', 'taskPriority', 'taskBoard', 'orderStatus'));
    }

    public function taskShow($id)
    {
        $taskPriority = [
            'low' => 'primary',
            'medium' => 'warning',
            'high' => 'danger',
        ];

        $taskStatus = [
            'pending' => 'secondary',
            'in_progress' => 'info',
            'completed' => 'success',
        ];
        $task = Task::findOrFail($id);

        return view('frontend.user.service.task_show', compact('task', 'taskPriority', 'taskStatus'))->render();
    }

    public function fileDownload()
    {
        $filename = request('filename');
        $path = 'assets/'.$filename;

        if (! file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return response()->download($path);
    }

    public function taskRequestInfoUpdate(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $data = $request->except('_token');

        // Update the `info_fields` by transforming the collection directly
        $updatedFields = collect($task->info_fields)->map(function ($field) use ($data, $request) {
            $key = $field['name'];

            if ($field['type'] == 'file' && $request->hasFile($key)) {
                // Check if a file exists and upload it
                $field['value'] = self::uploadFile(
                    $request->file($key),
                    $field['value'] ?? null
                );
            } elseif (array_key_exists($key, $data)) {
                // Set value from data if provided
                $field['value'] = $data[$key];
            }

            return $field;
        })->all();
        // Save the updated fields
        $task->fill(['info_fields' => $updatedFields])->save();

        notifyEvs('success', 'Task info updated successfully.');

        return redirect()->back();
    }

    private function processUserBalancePayment(array $validated, $service)
    {

        return DB::transaction(function () use ($service, $validated) {
            $transaction = $this->paymentService->processPayment($validated);
            $this->createOrder(auth()->id(), $service->id, $transaction->id);
            Transaction::completeTransaction($transaction->txid);

            notifyEvs('success', __('Service purchased successfully.'));

            return redirect()->route('user.transaction');
        });
    }

    private function processOtherDepositMethods(array $validated, $service)
    {
        [$transaction, $gatewayInjection] = $this->paymentService->processPayment($validated);

        $this->createOrder(auth()->id(), $service->id, $transaction->id);

        if ($transaction->method_type === MethodType::MANUAL) {
            $admins = Admin::permission('service-notification')->get();
            Notification::send($admins, new AdminServiceActionNotify($transaction));
        }

        return $gatewayInjection;
    }

    private function createOrder($userId, $serviceId, $transactionId)
    {

        Order::create([
            'user_id' => $userId,
            'service_id' => $serviceId,
            'transaction_id' => $transactionId,
            'order_number' => '#'.rand(100000, 999999),
            'status' => OrderStatus::PENDING,
        ]);
    }

    private function handlePaymentError(Exception $e)
    {
        notifyEvs('error', $e->getMessage());

        return redirect()->back();
    }
}

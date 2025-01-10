<?php

namespace App\Http\Controllers\Backend;

use App\Constants\BalanceUpdateType;
use App\Constants\TxnType;
use App\Constants\UserStatus;
use App\Facades\TransactionFacade as Transaction;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    /**
     * Inject the UserService.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        // Define permissions for each action
        $permissions = [
            'active' => 'user-list',
            'banned' => 'user-list',
            'unverified' => 'user-list',
            'edit' => 'user-manage',
            'manage' => 'user-manage',
            'all' => 'user-list',
            'transaction' => 'user-manage',
            'sendNotification' => 'user-send-notification',
            'notify' => 'user-send-notification',
            'login' => 'user-as-login',
            'balanceUpdate' => 'user-balance-manage',
            'statusUpdate' => 'user-manage',
            'infoUpdate' => 'user-manage',
            'passwordChange' => 'user-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function active()
    {
        $title = __('Active Users');
        $users = User::search(request('search'))->where('status', UserStatus::ACTIVE)->latest()->paginate(10);

        return view('backend.user.list', compact('title', 'users'));
    }

    public function banned()
    {
        $title = __('Banned Users');
        $users = User::search(request('search'))->where('status', UserStatus::BANNED)->latest()->paginate(10);

        return view('backend.user.list', compact('title', 'users'));
    }

    public function unverified()
    {
        $title = __('Unverified Users');
        $users = User::search(request('search'))->where('email_verified_at', null)->latest()->paginate(10);

        return view('backend.user.list', compact('title', 'users'));
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);

        return view('backend.user.edit', compact('user'));
    }

    public function manage($id, Request $request)
    {
        $user = $this->userService->getUserById($id);

        // Retrieve card statistics from the service
        $cardStatistics = $this->userService->getCardStatistics($user);

        // Retrieve transaction statistics and date range from the service
        $transactionData = $this->userService->getTransactionStatistics($id, $request->all());
        $transactionsStatistics = $transactionData['transactionsStatistics'];
        $dateRange = $transactionData['dateRange'];

        if ($request->ajax()) {
            return response()->json($transactionsStatistics);
        }

        return view('backend.user.info', compact('user', 'cardStatistics', 'transactionsStatistics', 'dateRange'));
    }

    public function all()
    {
        $title = __('All Users');
        $users = User::search(request('search'))->latest()->paginate(10);

        return view('backend.user.list', compact('title', 'users'));
    }

    public function transaction($id)
    {
        $user = $this->userService->getUserById($id);

        // Retrieve transaction statistics from the service
        $transactions = Transaction::getTransactions($user->id);

        return view('backend.user.transaction', compact('user', 'transactions'));
    }

    public function sendNotification()
    {
        return view('backend.user.send_notification');
    }

    public function notify(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable',
            'send_email' => 'required_without:send_push|boolean',
            'send_push' => 'required_without:send_email|boolean',
            'message' => 'required|string',
        ]);

        $channels = [];

        if (isset($validated['send_email']) && $validated['send_email']) {
            $channels[] = 'mail';
        }

        if (isset($validated['send_push']) && $validated['send_push']) {
            $channels = array_merge($channels, ['broadcast', 'database']);
        }

        $this->userService->sendCustomNotification($validated['message'], $channels, $validated['user_id'] ?? null);

        notifyEvs('success', __('Notification sent successfully'));

        return redirect()->back();
    }

    public function login($id)
    {
        $user = $this->userService->getUserById($id);
        auth('web')->login($user, true);

        return redirect()->route('user.dashboard');

    }

    public function balanceUpdate(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:'.implode(',', BalanceUpdateType::TYPES),
            'amount' => 'required|numeric',
        ]);

        $user = $this->userService->getUserById($validated['user_id']);

        $notificationType = $validated['type'] === BalanceUpdateType::ADD ? TxnType::ADD_BALANCE : TxnType::SUBTRACT_BALANCE;

        $isUpdated = $this->userService->updateBalance($user, $validated['type'], $validated['amount']);

        if ($isUpdated) {
            $this->userService->notifyBalanceUpdate($user, $validated['amount'], $notificationType);
            notifyEvs('success', __('Balance updated successfully'));
        } else {
            notifyEvs('error', __('Failed to update balance'));
        }

        return redirect()->back();
    }

    public function statusUpdate(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|boolean',
            'email_verified' => 'nullable|boolean',
            'two_factor' => 'nullable|boolean',
        ]);

        $user = $this->userService->getUserById($validated['user_id']);
        $this->userService->updateUserStatus(
            $user,
            $validated['status'],
            $validated['email_verified'] ?? false,
            $validated['two_factor'] ?? false
        );

        notifyEvs('success', __('Status updated successfully'));

        return redirect()->back();
    }

    public function infoUpdate($id, Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:255|unique:users,phone,'.$id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
        ]);

        $user = $this->userService->getUserById($id);
        $this->userService->updateUserInfo($user, $validated);

        notifyEvs('success', __('User information updated successfully'));

        return redirect()->back();
    }

    public function passwordChange($id, Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user = $this->userService->getUserById($id);
        $this->userService->updateUserPassword($user, $validated['password']);

        notifyEvs('success', __('Password updated successfully'));

        return redirect()->back();
    }
}

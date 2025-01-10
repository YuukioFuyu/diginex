<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Traits\FileManageTrait;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    use FileManageTrait;

    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'payment-gateway-list',
            'edit' => 'payment-gateway-manage',
            'update' => 'payment-gateway-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function index()
    {
        $paymentGateways = PaymentGateway::getAll(10);

        return view('backend.payment_gateway.index', compact('paymentGateways'));
    }

    public function edit($id)
    {
        $paymentGateway = PaymentGateway::getById($id);

        return view('backend.payment_gateway.edit', compact('paymentGateway'))->render();
    }

    public function update($id, Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'credentials' => 'required',
            'status' => 'boolean',
        ]);

        $paymentGateway = PaymentGateway::getById($id);
        $validated['status'] = $request->boolean('status');

        $paymentGateway->update($validated);

        notifyEvs('success', __('Payment Gateway Updated Successfully'));

        return redirect()->route('admin.payment.gateway.index');
    }

    public function gatewayCurrency($gateway_id)
    {
        $paymentGateway = PaymentGateway::getById($gateway_id);
        $supportedCurrencies = $paymentGateway->currencies;

        return [
            'payCurrency' => $paymentGateway->currency,
            'view' => view('backend.payment_gateway.partial._currencies_list', compact('supportedCurrencies'))->render(),
        ];
    }
}

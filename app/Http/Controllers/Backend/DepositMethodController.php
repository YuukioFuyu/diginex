<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositMethod\StoreUpdateRequest;
use App\Models\DepositMethod;
use App\Models\PaymentGateway;
use App\Traits\FileManageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;
use Throwable;

class DepositMethodController extends Controller
{
    use FileManageTrait;

    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'method' => 'deposit-method-list',
            'store' => 'deposit-method-manage',
            'edit' => 'deposit-method-manage',
            'update' => 'deposit-method-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of deposit methods.
     *
     * @return View
     */
    public function method($type)
    {
        // Retrieve all payment gateways
        $paymentGateways = PaymentGateway::getAll();

        // Retrieve deposit methods of the specified type, paginated
        $paymentMethods = DepositMethod::with('paymentGateway')->where('type', $type)->paginate(10);

        // Return the view with the required data
        return view('backend.deposit.method.index', compact('type', 'paymentGateways', 'paymentMethods'));
    }

    /**
     * Stores a new deposit method.
     *
     * @param  StoreUpdateRequest  $request  The request containing the deposit method data.
     * @return RedirectResponse A redirect response to the deposit method index page.
     */
    public function store(StoreUpdateRequest $request)
    {
        // Prepare the data for creating a new deposit method.
        $data = $this->prepareData($request);

        // Create a new deposit method using the prepared data.
        DepositMethod::create($data);

        // Notify the user of a successful deposit method creation.
        notifyEvs('success', __('Deposit Method Added Successfully'));

        // Redirect the user back to the deposit method index page.
        return redirect()->route('admin.deposit.method.type', ['type' => $request->input('type')]);
    }

    /**
     * Prepares the data for creating or updating a DepositMethod.
     *
     * @return array
     */
    private function prepareData(StoreUpdateRequest $request, ?DepositMethod $paymentMethod = null)
    {

        // Get the validated request data
        $validated = $request->validated();

        // Generate the method code if payment gateway ID and currency are present
        $methodCode = $request->input('payment_gateway_id')
            ? PaymentGateway::find($request->input('payment_gateway_id'))->code.'-'.strtolower($request->input('currency'))
            : $validated['method_code'];

        // Merge the validated data with additional fields
        return array_merge($validated, [
            // Upload a new logo if present, otherwise use the existing logo
            'logo' => $request->hasFile('logo') ? self::uploadImage($request->file('logo'), $paymentMethod?->logo) : ($paymentMethod?->logo),
            'method_code' => $methodCode,
            // Get the fields input, default to null if not present
            'fields' => $request->filled('fields') ? json_encode($request->input('fields')) : null,
            // Clean and decode the receive payment details if present
            'receive_payment_details' => $request->filled('receive_payment_details') ? Purifier::clean(htmlspecialchars_decode($request->input('receive_payment_details'))) : null,
            // Get the status as a boolean
            'status' => $request->boolean('status'),
        ]);
    }

    public function create()
    {
        $type = request('type');
        // Get all available payment gateways.
        $paymentGateways = PaymentGateway::getAll();
        $fieldCount = 0;
        return view('backend.deposit.method.create', compact('type', 'paymentGateways', 'fieldCount'));

    }

    /**
     * Displays the edit form for a deposit method.
     *
     * @param  int  $id  The ID of the deposit method to edit.
     * @return string The rendered edit form view.
     *
     * @throws Throwable
     */
    public function edit(int $id)
    {
        // Retrieve the deposit method with its associated payment gateway.
        $paymentMethod = DepositMethod::with('paymentGateway')->find($id);

        // Get all available payment gateways.
        $paymentGateways = PaymentGateway::getAll();

        $fieldCount = $paymentMethod->fields ? count(json_decode($paymentMethod->fields), true) : 0;


        // Render the edit form view with the retrieved data.
        return view('backend.deposit.method.edit', compact('paymentMethod', 'paymentGateways', 'fieldCount'));
    }

    /**
     * Updates a deposit method.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(StoreUpdateRequest $request, $id)
    {

        // Retrieve the deposit method by its ID
        $paymentMethod = DepositMethod::find($id);

        // Prepare the data for updating the deposit method
        $data = $this->prepareData($request, $paymentMethod);

        // Update the deposit method with the prepared data
        $paymentMethod->update($data);

        // Notify the user of a successful update
        notifyEvs('success', __('Deposit Method Updated Successfully'));

        // Redirect the user back to the deposit method index page
        return redirect()->route('admin.deposit.method.type', ['type' => $request->input('type')]);
    }
}

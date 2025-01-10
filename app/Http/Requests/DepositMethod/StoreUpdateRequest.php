<?php

namespace App\Http\Requests\DepositMethod;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'logo' => 'sometimes|required_if:type,manual|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required',
            'name' => 'required',
            'payment_gateway_id' => 'required_if:type,auto',
            'method_code' => 'required_if:type,manual',
            'currency' => 'required',
            'currency_symbol' => 'required',
            'charge' => 'required',
            'charge_type' => 'required',
            'conversion_rate_live' => 'boolean',
            'conversion_rate' => 'required_if:conversion_rate_live,0',
            'min_deposit' => 'required',
            'max_deposit' => 'required',
            'fields' => 'required_if:type,manual',
            'status' => 'boolean',
        ];
    }
}

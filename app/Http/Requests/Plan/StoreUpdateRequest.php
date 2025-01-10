<?php

namespace App\Http\Requests\Plan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules()
    {
        $required = $this->isMethod('POST') ? 'required' : 'nullable';
        $requiredIf = ($this->input('lang') === config('app.fallback_locale') || $this->input('lang') == null) ? 'required' : 'nullable';

        return [
            'lang' => 'nullable|string|max:10',
            'name' => 'required', // Name is now an array
            'icon' => [$required, 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // Adjust as per icon upload requirements
            'background_shape' => $required.'|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Background shape validation
            'monthly_price' => [$requiredIf, 'numeric', 'min:1'], // Monthly price validation
            'yearly_price' => [$requiredIf, 'numeric', 'min:1'], // Yearly price validation
            'features' => 'nullable|array', // Features should be an array
            'features.*' => 'required|string|max:255', // Each feature in the array must be a string
            'status' => 'boolean', // Status validation
        ];
    }

    /**
     * Get the custom error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => __('The product name is required.'),
            'name.*.required' => __('Each product name is required and must be a string.'),
            'icon.required' => __('The icon is required and must be an image file.'),
            'background_shape.required' => __('The background shape is required and must be an image.'),
            'monthly_price.required' => __('The monthly price is required and must be numeric.'),
            'yearly_price.required' => __('The yearly price is required and must be numeric.'),
            'features.*.required' => __('Each feature is required and must be a string.'),
            'status.boolean' => __('The status must be true or false.'),
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class NavigationRequest extends FormRequest
{
    public $validator = null;

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
    public function rules(): array
    {
        $defaultLang = config('app.static_default_language');

        $rules = [
            'navigation_name' => 'required',
            'navigation_display' => 'required_if:lang,'.$defaultLang,
            'target_link' => 'required_if:lang,'.$defaultLang,
            'page_id' => 'required_unless:is_custom_url,active',
            'custom_url' => 'required_if:is_custom_url,active,lang,'.$defaultLang,
            'status' => 'boolean',
        ];

        if ($this->input('lang') !== null && $this->input('lang') !== $defaultLang) {
            unset($rules['page_id']);
        }

        return $rules;

    }

    public function messages(): array
    {
        return [
            'page_id.required_unless' => 'The page field is required.',
            'custom_url.required_if' => 'The custom url field is required.',
            'navigation_name.required' => 'The navigation name field is required.',
            'navigation_display.required' => 'The navigation display field is required.',
            'target_link.required' => 'The target link field is required.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $this->validator = $validator;
    }
}

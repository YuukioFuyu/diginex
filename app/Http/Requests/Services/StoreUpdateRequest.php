<?php

namespace App\Http\Requests\Services;

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
            'lang' => 'nullable',
            'name' => 'required|string|max:255',
            'slug' => [$requiredIf, 'string', 'max:255', Rule::unique('services', 'slug')->ignore($this->service ?? null)],
            'price' => $requiredIf, '|numeric|min:1',
            'light_icon' => $required.'|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust max size if necessary
            'dark_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover' => $required.'|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video_cover' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video_link' => 'nullable|url',
            'description' => $requiredIf,
            'side_content' => 'nullable|array',
            'faq_content' => 'nullable|array',
            'tags' => 'nullable',
            'status' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('Product name is required.'),
            'slug.required' => __('Slug is required.'),
            'price.required' => __('Price is required and should be numeric.'),
            'light_icon.required' => __('Light icon is required and should be an image.'),
            'cover.required' => __('Cover image is required and should be an image.'),
        ];
    }
}

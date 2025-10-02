<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('manage-system');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        // For create, key is required and unique
        if ($this->isMethod('post')) {
            $rules['key'] = 'required|string|max:255|unique:email_templates,key';
        }

        // For update, key is not allowed to change
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['key'] = 'prohibited';
        }

        return $rules;
    }
}

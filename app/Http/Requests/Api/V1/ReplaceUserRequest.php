<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceUserRequest  extends BaseUserRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.email' => 'email|max:250|unique:users,email',
            'data.attributes.name' => 'string|max:250',
            'data.attributes.password' => 'string|max:250',
            'data.attributes.is_admin' => 'boolean',
            'data.attributes.is_manager' => 'boolean',
        ];
    }
}

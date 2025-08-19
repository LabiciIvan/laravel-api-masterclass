<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function mappedAttributes(): array
    {
        $attributes = [
            'email' => 'data.attributes.email',
            'name' => 'data.attributes.name',
            'password' => 'data.attributes.password',
            'is_admin' => 'data.attributes.is_admin',
            'is_manager' => 'data.attributes.is_manager',
        ];

        $model = [];

        foreach ($attributes as $columnName => $requestAttribute) {
            if ($this->has($requestAttribute)) {
                $model[$columnName] = $this->input($requestAttribute);
            }
        }

        return $model;
    }

}

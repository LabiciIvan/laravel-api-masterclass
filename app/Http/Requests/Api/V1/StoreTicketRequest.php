<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Permissions\Abilities;

class StoreTicketRequest extends BaseTicketRequest
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
        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            'data.relationships.author.data.id' => 'required|int'
        ];

        $user = $this->user();

        if ($user->tokenCan(Abilities::CreateOwnTicket)) {
            // Allow author only of current user making request if his role is not manager or admin.
            $rules['data.relationships.author.data.id'] .= '|size:' . $user->id;
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        // Direct merge the route parameter into the request and bring to validation.
        if ($this->routeIs('authors.tickets.store')) {
            $this->merge(['data.relationships.author.data.id' => $this->route('author')]);
        }
    }
}

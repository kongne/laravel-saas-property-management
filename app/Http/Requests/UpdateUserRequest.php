<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'role' => 'sometimes|string|in:admin,landlord,tenant',
            'is_active' => 'sometimes|boolean',
            'phone' => 'nullable|string|max:30',
        ];
    }
}

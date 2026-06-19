<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'unit_id' => 'required|exists:units,id',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'lease_start' => 'nullable|date',
            'lease_end' => 'nullable|date|after:lease_start',
            'status' => 'nullable|string|in:active,past,pending',
            'notes' => 'nullable|string',
        ];
    }
}

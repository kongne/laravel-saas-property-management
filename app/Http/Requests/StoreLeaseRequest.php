<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|string|in:monthly,quarterly,yearly',
            'due_day' => 'nullable|integer|between:1,31',
            'status' => 'nullable|string|in:active,expired,terminated,pending',
            'terms' => 'nullable|string',
        ];
    }
}

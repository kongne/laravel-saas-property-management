<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:100',
            'priority' => 'nullable|string|in:low,medium,high,emergency',
            'status' => 'nullable|string|in:open,in_progress,resolved,closed',
            'requested_date' => 'nullable|date',
            'assigned_to' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
        ];
    }
}

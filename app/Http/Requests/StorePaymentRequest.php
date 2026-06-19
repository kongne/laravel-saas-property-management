<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lease_id' => 'required|exists:leases,id',
            'tenant_id' => 'required|exists:tenants,id',
            'unit_id' => 'required|exists:units,id',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'late_fee' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date',
            'status' => 'nullable|string|in:pending,paid,overdue,partial,cancelled',
            'payment_method' => 'nullable|string|in:cash,check,bank_transfer,credit_card,mobile_money,orange_money,mtn_money,other',
            'mobile_money_number' => 'nullable|string|max:30|required_if:payment_method,orange_money|required_if:payment_method,mtn_money',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }
}

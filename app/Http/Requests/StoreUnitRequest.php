<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:50',
            'type' => 'required|string|in:studio,one_bedroom,two_bedroom,three_bedroom,penthouse,commercial,other',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'area_sqft' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array',
            'status' => 'nullable|string|in:available,occupied,maintenance,reserved',
        ];
    }
}

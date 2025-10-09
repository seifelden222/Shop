<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditOrderRquest extends FormRequest
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
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'items' => 'nullable|array|min:1', // Make items optional
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.product_name' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
        ];
    }
}

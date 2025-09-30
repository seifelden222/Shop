<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRquest extends FormRequest
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
            'shipping_address' => 'required|string|max:500',
            'address' => 'required|string|max:500', // Changed to required
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'payment_method' => 'required|string',
            'shipping_cost' => 'nullable|numeric|min:0',
        ];
    }
}

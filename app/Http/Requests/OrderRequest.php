<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'address' => 'required|string|max:500',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'shipping_cost' => 'nullable|numeric|min:0',
            // total_price is calculated server-side; no need for client input
            'total_price' => 'nullable|numeric|min:0',
            'order_number' => 'nullable|uuid|unique:orders,order_number',
            'currency' => 'nullable|string|max:10',
            'provider_order_id' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:50',
            'payment_status' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            // optional items array (each item validated server-side)
            'items' => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'customer_name' => 'nullable|string|max:200',
            'customer_email' => 'nullable|email|max:200',
            
        ];
    }
}

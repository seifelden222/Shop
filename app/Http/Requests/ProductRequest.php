<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        // Create
        if ($this->isMethod('post')) {
            return $user->can('create', Product::class);
        }

        // Update
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $routeModel = $this->route('product') ?? $this->route('products') ?? $this->route('id');
            if ($routeModel instanceof Product) {
                return $user->can('update', $routeModel);
            }
            if (is_numeric($routeModel) || is_string($routeModel)) {
                $model = Product::find($routeModel);
                return $model ? $user->can('update', $model) : false;
            }
            return false;
        }

        // Default deny
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:published,draft,archived',
            'brand' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'name_snapshot' => 'nullable|string|max:255',
        ];
    }
}

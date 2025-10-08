<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Brand;

class BrandRequest extends FormRequest
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

        if ($this->isMethod('post')) {
            return $user->can('create', Brand::class);
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $routeModel = $this->route('brands') ?? $this->route('brand') ?? $this->route('id');
            if ($routeModel instanceof Brand) {
                return $user->can('update', $routeModel);
            }
            if (is_numeric($routeModel) || is_string($routeModel)) {
                $model = Brand::find($routeModel);
                return $model ? $user->can('update', $model) : false;
            }
            return false;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $brandId = $this->route('brands') ? $this->route('brands')->id : null;
        
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $brandId,
        ];
    }
}
 
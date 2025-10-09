<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class CategorieRequest extends FormRequest
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
            return $user->can('create', Category::class);
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $routeModel = $this->route('category') ?? $this->route('categories') ?? $this->route('id');
            if ($routeModel instanceof Category) {
                return $user->can('update', $routeModel);
            }
            if (is_numeric($routeModel) || is_string($routeModel)) {
                $model = Category::find($routeModel);
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
        return [
            'name' => 'required|string|max:255|unique:categories,name,' ,
            'description' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug,',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'sometimes|boolean',
        ];
    }
}

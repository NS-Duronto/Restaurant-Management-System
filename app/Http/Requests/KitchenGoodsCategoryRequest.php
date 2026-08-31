<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KitchenGoodsCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:190',
                Rule::unique('kitchen_goods_categories', 'name')->ignore($this->route('kitchenGoodsCategory.id') ?? $this->route('kitchenGoodsCategory')),
            ],
            'description' => ['nullable', 'string', 'max:900'],
            'status' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}

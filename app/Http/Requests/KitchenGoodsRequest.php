<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KitchenGoodsRequest extends FormRequest
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
                Rule::unique('kitchen_goods', 'name')->ignore($this->route('kitchenGood.id') ?? $this->route('kitchenGood') ?? $this->route('kitchen_good')),
            ],
            'kitchen_goods_category_id' => ['required', 'numeric', 'exists:kitchen_goods_categories,id'],
            'unit_id' => ['required', 'numeric', 'exists:units,id'],
            'current_stock' => ['nullable', 'numeric', 'min:0'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'numeric'],
        ];
    }
}

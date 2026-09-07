<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemIngredientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $itemId = $this->route('item')?->id ?? $this->route('item') ?? $this->route('item.id');
        $ingredientId = $this->route('itemIngredient')?->id ?? $this->route('itemIngredient') ?? $this->route('itemIngredient.id');

        return [
            'kitchen_goods_id' => [
                'required',
                'numeric',
                'exists:kitchen_goods,id',
                Rule::unique('item_ingredients', 'kitchen_goods_id')
                    ->where('item_id', $itemId)
                    ->ignore($ingredientId),
            ],
            'quantity' => ['required', 'numeric', 'min:0.001'],
            'unit_id' => ['nullable', 'numeric', 'exists:units,id'],
        ];
    }
}

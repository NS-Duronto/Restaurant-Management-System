<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseCategoryRequest extends FormRequest
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
                Rule::unique('expense_categories', 'name')->ignore($this->route('expenseCategory.id') ?? $this->route('expenseCategory') ?? $this->route('expense_category')),
            ],
            'description' => ['nullable', 'string', 'max:900'],
            'status' => ['required', 'numeric'],
        ];
    }
}

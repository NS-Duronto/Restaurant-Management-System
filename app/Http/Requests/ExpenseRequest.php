<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_category_id' => ['required', 'numeric', 'exists:expense_categories,id'],
            'title' => ['required', 'string', 'max:190'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'payment_method' => ['required', 'numeric'],
            'payee_name' => ['nullable', 'string', 'max:190'],
            'note' => ['nullable', 'string', 'max:500'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}

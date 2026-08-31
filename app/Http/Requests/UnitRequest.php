<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
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
                Rule::unique('units', 'name')->ignore($this->route('unit.id') ?? $this->route('unit')),
            ],
            'code' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'numeric'],
        ];
    }
}

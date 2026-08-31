<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendToKitchenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
            'items' => ['required'],
        ];
    }
}

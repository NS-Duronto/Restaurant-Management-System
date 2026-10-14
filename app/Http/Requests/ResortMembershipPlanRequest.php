<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResortMembershipPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'badge'       => ['nullable', 'string', 'max:100'],
            'duration'    => ['nullable', 'string', 'max:100'],
            'features'    => ['nullable'],
            'btn_text'    => ['nullable', 'string', 'max:100'],
            'btn_link'    => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'numeric'],
            'sort_order'  => ['nullable', 'numeric'],
            'status'      => ['nullable', 'numeric'],
        ];
    }
}

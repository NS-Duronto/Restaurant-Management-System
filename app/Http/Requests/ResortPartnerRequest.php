<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResortPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'image_url'   => ['nullable', 'string', 'max:500'],
            'website_url' => ['nullable', 'string', 'max:500'],
            'sort_order'  => ['nullable', 'numeric'],
            'status'      => ['nullable', 'numeric'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
        ];
    }
}

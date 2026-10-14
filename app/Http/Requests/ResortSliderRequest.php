<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResortSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:255'],
            'subtitle'   => ['nullable', 'string', 'max:1000'],
            'image_url'  => ['nullable', 'string', 'max:500'],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'btn_text1'  => ['nullable', 'string', 'max:100'],
            'btn_link1'  => ['nullable', 'string', 'max:255'],
            'btn_text2'  => ['nullable', 'string', 'max:100'],
            'btn_link2'  => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'numeric'],
            'status'     => ['nullable', 'numeric'],
        ];
    }
}

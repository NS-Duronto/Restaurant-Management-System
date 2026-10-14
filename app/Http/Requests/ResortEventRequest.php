<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResortEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'tag'             => ['nullable', 'string', 'max:100'],
            'event_date'      => ['nullable', 'string', 'max:100'],
            'image_url'       => ['nullable', 'string', 'max:500'],
            'description'     => ['nullable', 'string', 'max:2000'],
            'social_link'     => ['nullable', 'string', 'max:500'],
            'is_about_slider' => ['nullable', 'numeric'],
            'sort_order'      => ['nullable', 'numeric'],
            'status'          => ['nullable', 'numeric'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}

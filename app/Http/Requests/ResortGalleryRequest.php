<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResortGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => ['nullable', 'string', 'max:255'],
            'type'       => ['required', 'string', 'in:photo,video'],
            'category'   => ['nullable', 'string', 'max:100'],
            'image_url'  => ['nullable', 'string', 'max:500'],
            'video_url'  => ['nullable', 'string', 'max:500'],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sort_order' => ['nullable', 'numeric'],
            'status'     => ['nullable', 'numeric'],
        ];
    }
}

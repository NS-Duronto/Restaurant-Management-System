<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResortTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'bio'         => ['nullable', 'string', 'max:1000'],
            'image_url'   => ['nullable', 'string', 'max:500'],
            'facebook'    => ['nullable', 'string', 'max:500'],
            'linkedin'    => ['nullable', 'string', 'max:500'],
            'twitter'     => ['nullable', 'string', 'max:500'],
            'instagram'   => ['nullable', 'string', 'max:500'],
            'email'       => ['nullable', 'email', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'sort_order'  => ['nullable', 'numeric'],
            'status'      => ['nullable', 'numeric'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}

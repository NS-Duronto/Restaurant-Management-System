<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResortBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'         => ['required', 'string', 'in:resort,party'],
            'name'         => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:50'],
            'email'        => ['nullable', 'email', 'max:255'],
            'booking_date' => ['nullable', 'string', 'max:100'],
            'check_in'     => ['nullable', 'string', 'max:100'],
            'check_out'    => ['nullable', 'string', 'max:100'],
            'room_type'    => ['nullable', 'string', 'max:100'],
            'event_type'   => ['nullable', 'string', 'max:100'],
            'guests_count' => ['nullable', 'numeric', 'min:1'],
            'notes'        => ['nullable', 'string', 'max:2000'],
            'status'       => ['nullable', 'numeric'],
        ];
    }
}

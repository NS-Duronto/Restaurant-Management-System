<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PosPaymentSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order_setup_pos_card_types' => ['required'],
            'order_setup_pos_mfs_types'  => ['required'],
        ];
    }
}

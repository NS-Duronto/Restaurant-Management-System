<?php

namespace App\Http\Requests;

use App\Enums\Activity;
use App\Enums\OrderType;
use App\Rules\ValidJsonOrder;
use Dipokhalder\Settings\Facades\Settings;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PosPaymentMethod;
use App\Enums\PaymentStatus;

class PosOrderRequest extends FormRequest
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
        $isUnpaid = (int) $this->input('payment_status') === PaymentStatus::UNPAID;

        return [
            'token'                  => ['required', 'numeric'],
            'customer_id'            => ['required', 'numeric'],
            'branch_id'              => ['required', 'numeric'],
            'subtotal'               => ['required', 'numeric'],
            'discount'               => ['nullable', 'numeric'],
            'dining_table_id'        => $this->input('order_type') == OrderType::DINING_TABLE ? [
                'required',
                'numeric'
            ] : ['nullable'],
            'total'                  => ['required', 'numeric'],
            'order_type'             => ['required', 'numeric'],
            'is_advance_order'       => ['required', 'numeric'],
            'delivery_time'          => ['nullable'],
            'source'                 => ['required', 'numeric'],
            'items'                  => ['required', 'json', new ValidJsonOrder],
            'payment_status'         => ['nullable', 'numeric'],
            'pos_payment_method'     => ['required', 'numeric'],
            'pos_payment_sub_method' => ['nullable', 'string', 'max:50'],
            'pos_payment_note'       => $isUnpaid ? ['nullable', 'string'] : (in_array($this->input('pos_payment_method'), [PosPaymentMethod::CARD, PosPaymentMethod::MOBILE_BANKING, PosPaymentMethod::OTHER]) ? ['required', 'string'] : ['nullable', 'string']),
            'pos_received_amount'    => (!$isUnpaid && $this->input('pos_payment_method') == PosPaymentMethod::CASH) ? ['required', 'numeric'] : ['nullable', 'numeric'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $isUnpaid = (int) $this->input('payment_status') === PaymentStatus::UNPAID;
            $orderType = $this->input('order_type');
            if ($orderType == OrderType::DELIVERY && Settings::group('order_setup')->get("order_setup_delivery") == Activity::DISABLE) {
                $validator->errors()->add('order_type', 'This order type is disabled now you can try another order type right now or call the management.');
            } else if ($orderType == OrderType::TAKEAWAY && Settings::group('order_setup')->get("order_setup_takeaway") == Activity::DISABLE) {
                $validator->errors()->add('order_type', 'This order type is disabled now you can try another order type right now or call the management.');
            } else if (blank($orderType)) {
                $validator->errors()->add('order_type', 'This order type is disabled now you can try another order type right now or call the management.');
            }
            if (!$isUnpaid && $this->input('pos_payment_method') == PosPaymentMethod::CASH && !blank($this->input('pos_received_amount')) && ((float)$this->input('total') > (float)$this->input('pos_received_amount'))) {
                $validator->errors()->add('pos_received_amount', 'The received amount can not be less than the total amount.');
            }
        });
    }

    public function messages()
    {
        return [
            'pos_payment_note.required'    => $this->input('pos_payment_method') == PosPaymentMethod::CARD ? 'Last 4 digits of card is required' : ($this->input('pos_payment_method') == PosPaymentMethod::MOBILE_BANKING ? 'Transaction ID field is required' : 'Payment note field is required'),
            'pos_received_amount.required' => 'The received amount field is required',
            'dining_table_id.required'     => 'The dining table field is required'
        ];
    }
}

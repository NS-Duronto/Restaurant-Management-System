<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier?->name ?? '',
            'purchase_no' => $this->purchase_no,
            'date' => AppLibrary::date($this->date),
            'raw_date' => $this->date ? date('Y-m-d', strtotime($this->date)) : '',
            'total_amount' => (float) $this->total_amount,
            'currency_total_amount' => AppLibrary::currencyAmountFormat($this->total_amount),
            'paid_amount' => (float) $this->paid_amount,
            'currency_paid_amount' => AppLibrary::currencyAmountFormat($this->paid_amount),
            'payment_method' => $this->payment_method,
            'note' => $this->note ?? '',
            'user_id' => $this->user_id,
            'user_name' => $this->user?->name ?? '',
            'total_items' => $this->items_count ?? $this->items?->count() ?? 0,
        ];
    }
}

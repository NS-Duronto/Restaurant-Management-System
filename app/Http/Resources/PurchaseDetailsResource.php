<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'supplier_id' => $this->supplier_id,
            'supplier' => new SupplierResource($this->supplier),
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
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kitchen_goods_id' => $item->kitchen_goods_id,
                    'kitchen_goods_name' => $item->kitchenGoods?->name ?? '',
                    'quantity' => (float) $item->quantity,
                    'unit_id' => $item->unit_id,
                    'unit_name' => $item->unit?->name ?? '',
                    'unit_code' => $item->unit?->code ?? '',
                    'unit_cost' => (float) $item->unit_cost,
                    'currency_unit_cost' => AppLibrary::currencyAmountFormat($item->unit_cost),
                    'total_cost' => (float) $item->total_cost,
                    'currency_total_cost' => AppLibrary::currencyAmountFormat($item->total_cost),
                ];
            }),
        ];
    }
}

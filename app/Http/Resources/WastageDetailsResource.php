<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class WastageDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'wastage_no' => $this->wastage_no,
            'date' => AppLibrary::date($this->date),
            'raw_date' => $this->date ? date('Y-m-d', strtotime($this->date)) : '',
            'user_id' => $this->user_id,
            'user_name' => $this->user?->name ?? '',
            'note' => $this->note ?? '',
            'total_loss_amount' => (float) $this->total_loss_amount,
            'flat_total_loss_amount' => AppLibrary::flatAmountFormat($this->total_loss_amount),
            'currency_total_loss_amount' => AppLibrary::currencyAmountFormat($this->total_loss_amount),
            'total_items' => $this->items ? $this->items->count() : 0,
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kitchen_goods_id' => $item->kitchen_goods_id,
                    'kitchen_goods_name' => $item->kitchenGoods?->name ?? '',
                    'quantity' => (float) $item->quantity,
                    'unit_id' => $item->unit_id,
                    'unit_name' => $item->unit?->name ?? '',
                    'unit_code' => $item->unit?->code ?? '',
                    'cost_per_unit' => (float) $item->cost_per_unit,
                    'flat_cost_per_unit' => AppLibrary::flatAmountFormat($item->cost_per_unit),
                    'total_cost' => (float) $item->total_cost,
                    'flat_total_cost' => AppLibrary::flatAmountFormat($item->total_cost),
                    'reason' => $item->reason ?? '',
                ];
            }),
        ];
    }
}

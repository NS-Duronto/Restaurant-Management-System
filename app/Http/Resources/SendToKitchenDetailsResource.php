<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class SendToKitchenDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'send_no' => $this->send_no,
            'date' => AppLibrary::date($this->date),
            'raw_date' => $this->date ? date('Y-m-d', strtotime($this->date)) : '',
            'user_id' => $this->user_id,
            'user_name' => $this->user?->name ?? '',
            'note' => $this->note ?? '',
            'total_items' => (int) $this->total_items,
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kitchen_goods_id' => $item->kitchen_goods_id,
                    'kitchen_goods_name' => $item->kitchenGoods?->name ?? '',
                    'quantity' => (float) $item->quantity,
                    'unit_id' => $item->unit_id,
                    'unit_name' => $item->unit?->name ?? '',
                    'unit_code' => $item->unit?->code ?? '',
                ];
            }),
        ];
    }
}

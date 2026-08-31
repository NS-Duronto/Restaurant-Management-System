<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class KitchenGoodsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'kitchen_goods_category_id' => $this->kitchen_goods_category_id,
            'category_name' => $this->category?->name ?? '',
            'unit_id' => $this->unit_id,
            'unit_name' => $this->unit?->name ?? '',
            'unit_code' => $this->unit?->code ?? '',
            'current_stock' => (float) $this->current_stock,
            'cost_per_unit' => (float) $this->cost_per_unit,
            'currency_cost_per_unit' => AppLibrary::currencyAmountFormat($this->cost_per_unit),
            'status' => $this->status,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemIngredientResource extends JsonResource
{
    public function toArray($request): array
    {
        $costPerUnit = (float) ($this->kitchenGoods?->cost_per_unit ?? 0);
        $qty = (float) $this->quantity;
        $totalCost = $qty * $costPerUnit;

        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'kitchen_goods_id' => $this->kitchen_goods_id,
            'kitchen_goods_name' => $this->kitchenGoods?->name,
            'unit_id' => $this->unit_id ?? $this->kitchenGoods?->unit_id,
            'unit_name' => $this->unit?->name ?? $this->kitchenGoods?->unit?->name,
            'unit_code' => $this->unit?->code ?? $this->kitchenGoods?->unit?->code,
            'quantity' => (float) $this->quantity,
            'cost_per_unit' => $costPerUnit,
            'flat_cost_per_unit' => AppLibrary::flatAmountFormat($costPerUnit),
            'currency_cost_per_unit' => AppLibrary::currencyAmountFormat($costPerUnit),
            'total_cost' => $totalCost,
            'flat_total_cost' => AppLibrary::flatAmountFormat($totalCost),
            'currency_total_cost' => AppLibrary::currencyAmountFormat($totalCost),
            'current_stock' => (float) ($this->kitchenGoods?->current_stock ?? 0),
        ];
    }
}

<?php

namespace App\Services;

use App\Http\Requests\ItemIngredientRequest;
use App\Http\Requests\PaginateRequest;
use App\Libraries\AppLibrary;
use App\Libraries\QueryExceptionLibrary;
use App\Models\Item;
use App\Models\ItemIngredient;
use Exception;
use Illuminate\Support\Facades\Log;

class ItemIngredientService
{
    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request, Item $item)
    {
        try {
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'asc';

            return ItemIngredient::with(['kitchenGoods.unit', 'unit'])
                ->where('item_id', $item->id)
                ->orderBy($orderColumn, $orderType)
                ->$method($methodValue);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(ItemIngredientRequest $request, Item $item): ItemIngredient
    {
        try {
            return ItemIngredient::create($request->validated() + ['item_id' => $item->id]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(ItemIngredientRequest $request, Item $item, ItemIngredient $itemIngredient): ItemIngredient
    {
        try {
            if ($item->id == $itemIngredient->item_id) {
                return tap($itemIngredient)->update($request->validated());
            } else {
                throw new Exception(trans('all.item_match'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Item $item, ItemIngredient $itemIngredient): void
    {
        try {
            if ($item->id == $itemIngredient->item_id) {
                $itemIngredient->delete();
            } else {
                throw new Exception(trans('all.item_match'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Item $item, ItemIngredient $itemIngredient): ItemIngredient
    {
        try {
            if ($item->id == $itemIngredient->item_id) {
                return $itemIngredient->load(['kitchenGoods.unit', 'unit']);
            } else {
                throw new Exception(trans('all.item_match'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * Summary of recipe cost vs item selling price
     * @throws Exception
     */
    public function summary(Item $item): array
    {
        try {
            $ingredients = ItemIngredient::with('kitchenGoods')->where('item_id', $item->id)->get();
            $totalCost = 0;
            foreach ($ingredients as $ingredient) {
                $costPerUnit = (float) ($ingredient->kitchenGoods?->cost_per_unit ?? 0);
                $totalCost += (float) $ingredient->quantity * $costPerUnit;
            }

            $price = (float) $item->price;
            $grossProfit = round($price - $totalCost, 2);
            $grossProfitMargin = $price > 0 ? round(($grossProfit / $price) * 100, 2) : 0;

            return [
                'item_id' => $item->id,
                'item_name' => $item->name,
                'item_price' => $price,
                'flat_item_price' => AppLibrary::flatAmountFormat($price),
                'currency_item_price' => AppLibrary::currencyAmountFormat($price),
                'total_recipe_cost' => $totalCost,
                'flat_total_recipe_cost' => AppLibrary::flatAmountFormat($totalCost),
                'currency_total_recipe_cost' => AppLibrary::currencyAmountFormat($totalCost),
                'gross_profit' => $grossProfit,
                'flat_gross_profit' => AppLibrary::flatAmountFormat($grossProfit),
                'currency_gross_profit' => AppLibrary::currencyAmountFormat($grossProfit),
                'gross_profit_margin_percent' => $grossProfitMargin,
                'total_ingredients' => $ingredients->count(),
            ];
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}

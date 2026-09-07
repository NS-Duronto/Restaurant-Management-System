<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\WastageRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\KitchenGoods;
use App\Models\Wastage;
use App\Models\WastageItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WastageService
{
    protected array $wastageFilter = [
        'wastage_no',
        'user_id',
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';

            return Wastage::with(['user', 'items.kitchenGoods', 'items.unit'])->where(function ($query) use ($requests, $request) {
                foreach ($requests as $key => $val) {
                    if (in_array($key, $this->wastageFilter)) {
                        if ($key == 'user_id') {
                            $query->where($key, $val);
                        } else {
                            $query->where($key, 'like', '%'.$val.'%');
                        }
                    }
                }

                if ($request->first_date && $request->last_date) {
                    $query->whereDate('date', '>=', date('Y-m-d', strtotime($request->first_date)))
                        ->whereDate('date', '<=', date('Y-m-d', strtotime($request->last_date)));
                }
            })->orderBy($orderColumn, $orderType)->$method($methodValue);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(WastageRequest $request): Wastage
    {
        try {
            return DB::transaction(function () use ($request) {
                $wastageNo = 'WST-'.date('ymd').'-'.rand(1000, 9999);

                $wastage = Wastage::create([
                    'wastage_no' => $wastageNo,
                    'date' => $request->date,
                    'user_id' => Auth::id(),
                    'total_loss_amount' => 0,
                    'note' => $request->note,
                ]);

                $items = is_string($request->items) ? json_decode($request->items) : $request->items;
                $totalLoss = 0;

                if (! blank($items)) {
                    foreach ($items as $item) {
                        $itemObj = (object) $item;
                        $goodsId = $itemObj->kitchen_goods_id;
                        $qty = (float) $itemObj->quantity;
                        $unitId = $itemObj->unit_id ?? null;
                        $reason = $itemObj->reason ?? 'Damaged';

                        $goods = KitchenGoods::find($goodsId);
                        $costPerUnit = (float) ($itemObj->cost_per_unit ?? ($goods?->cost_per_unit ?? 0));
                        $lineTotal = $qty * $costPerUnit;
                        $totalLoss += $lineTotal;

                        WastageItem::create([
                            'wastage_id' => $wastage->id,
                            'kitchen_goods_id' => $goodsId,
                            'unit_id' => $unitId ?? $goods?->unit_id,
                            'quantity' => $qty,
                            'cost_per_unit' => $costPerUnit,
                            'total_cost' => $lineTotal,
                            'reason' => $reason,
                        ]);

                        // Stock OUT: Decrement from current stock in store
                        if ($goods) {
                            $goods->decrement('current_stock', $qty);
                        }
                    }
                }

                $wastage->update(['total_loss_amount' => $totalLoss]);

                return $wastage->load(['user', 'items.kitchenGoods', 'items.unit']);
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Wastage $wastage): Wastage
    {
        try {
            return $wastage->load(['user', 'items.kitchenGoods', 'items.unit']);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Wastage $wastage): void
    {
        try {
            DB::transaction(function () use ($wastage) {
                // Reverse Stock OUT
                foreach ($wastage->items as $item) {
                    $goods = KitchenGoods::find($item->kitchen_goods_id);
                    if ($goods) {
                        $goods->increment('current_stock', $item->quantity);
                    }
                }
                $wastage->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}

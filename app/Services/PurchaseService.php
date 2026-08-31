<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PurchaseRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\KitchenGoods;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseService
{
    protected array $purchaseFilter = [
        'supplier_id',
        'purchase_no',
        'payment_method',
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

            return Purchase::with(['supplier', 'user', 'items.kitchenGoods', 'items.unit'])->where(function ($query) use ($requests, $request) {
                foreach ($requests as $key => $val) {
                    if (in_array($key, $this->purchaseFilter)) {
                        if ($key == 'supplier_id' || $key == 'payment_method') {
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
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(PurchaseRequest $request): Purchase
    {
        try {
            return DB::transaction(function () use ($request) {
                $purchaseNo = 'PUR-'.date('ymd').'-'.rand(1000, 9999);

                $purchase = Purchase::create([
                    'supplier_id' => $request->supplier_id,
                    'purchase_no' => $purchaseNo,
                    'date' => $request->date,
                    'total_amount' => 0,
                    'paid_amount' => $request->paid_amount ?? 0,
                    'payment_method' => $request->payment_method,
                    'note' => $request->note,
                    'user_id' => Auth::id(),
                ]);

                $items = is_string($request->items) ? json_decode($request->items) : $request->items;
                $totalAmount = 0;

                if (! blank($items)) {
                    foreach ($items as $item) {
                        $itemObj = (object) $item;
                        $goodsId = $itemObj->kitchen_goods_id;
                        $qty = (float) $itemObj->quantity;
                        $cost = (float) $itemObj->unit_cost;
                        $unitId = $itemObj->unit_id ?? null;
                        $itemTotal = $qty * $cost;
                        $totalAmount += $itemTotal;

                        PurchaseItem::create([
                            'purchase_id' => $purchase->id,
                            'kitchen_goods_id' => $goodsId,
                            'quantity' => $qty,
                            'unit_id' => $unitId,
                            'unit_cost' => $cost,
                            'total_cost' => $itemTotal,
                        ]);

                        // Stock IN: Increment current stock of kitchen goods and update cost per unit
                        $goods = KitchenGoods::find($goodsId);
                        if ($goods) {
                            $goods->increment('current_stock', $qty);
                            if ($cost > 0) {
                                $goods->update(['cost_per_unit' => $cost]);
                            }
                        }
                    }
                }

                $purchase->update([
                    'total_amount' => $totalAmount,
                    'paid_amount' => $request->paid_amount ?? $totalAmount,
                ]);

                return $purchase->load(['supplier', 'items.kitchenGoods', 'items.unit', 'user']);
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Purchase $purchase): Purchase
    {
        try {
            return $purchase->load(['supplier', 'items.kitchenGoods', 'items.unit', 'user']);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Purchase $purchase): void
    {
        try {
            DB::transaction(function () use ($purchase) {
                // Reverse Stock IN
                foreach ($purchase->items as $item) {
                    $goods = KitchenGoods::find($item->kitchen_goods_id);
                    if ($goods) {
                        $goods->decrement('current_stock', $item->quantity);
                    }
                }
                $purchase->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}

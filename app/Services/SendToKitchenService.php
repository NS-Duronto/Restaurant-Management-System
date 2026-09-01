<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SendToKitchenRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\KitchenGoods;
use App\Models\SendToKitchen;
use App\Models\SendToKitchenItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendToKitchenService
{
    protected array $sendFilter = [
        'send_no',
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

            return SendToKitchen::with(['user', 'items.kitchenGoods', 'items.unit'])->where(function ($query) use ($requests, $request) {
                foreach ($requests as $key => $val) {
                    if (in_array($key, $this->sendFilter)) {
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
    public function store(SendToKitchenRequest $request): SendToKitchen
    {
        try {
            return DB::transaction(function () use ($request) {
                $sendNo = 'STK-'.date('ymd').'-'.rand(1000, 9999);

                $sendToKitchen = SendToKitchen::create([
                    'send_no' => $sendNo,
                    'date' => $request->date,
                    'user_id' => Auth::id(),
                    'note' => $request->note,
                    'total_items' => 0,
                ]);

                $items = is_string($request->items) ? json_decode($request->items) : $request->items;
                $count = 0;

                if (! blank($items)) {
                    foreach ($items as $item) {
                        $itemObj = (object) $item;
                        $goodsId = $itemObj->kitchen_goods_id;
                        $qty = (float) $itemObj->quantity;
                        $unitId = $itemObj->unit_id ?? null;

                        $goods = KitchenGoods::find($goodsId);
                        if ($goods && (float) $goods->current_stock < $qty) {
                            throw new Exception("Insufficient stock for {$goods->name}. Available: {$goods->current_stock}, Requested: {$qty}", 422);
                        }

                        SendToKitchenItem::create([
                            'send_to_kitchen_id' => $sendToKitchen->id,
                            'kitchen_goods_id' => $goodsId,
                            'quantity' => $qty,
                            'unit_id' => $unitId,
                        ]);

                        // Stock OUT: Decrement current stock in store
                        if ($goods) {
                            $goods->decrement('current_stock', $qty);
                        }
                        $count++;
                    }
                }

                $sendToKitchen->update(['total_items' => $count]);

                return $sendToKitchen->load(['user', 'items.kitchenGoods', 'items.unit']);
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(SendToKitchen $sendToKitchen): SendToKitchen
    {
        try {
            return $sendToKitchen->load(['user', 'items.kitchenGoods', 'items.unit']);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(SendToKitchen $sendToKitchen): void
    {
        try {
            DB::transaction(function () use ($sendToKitchen) {
                // Reverse Stock OUT
                foreach ($sendToKitchen->items as $item) {
                    $goods = KitchenGoods::find($item->kitchen_goods_id);
                    if ($goods) {
                        $goods->increment('current_stock', $item->quantity);
                    }
                }
                $sendToKitchen->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}

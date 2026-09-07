<?php

namespace App\Services;

use App\Http\Requests\KitchenGoodsRequest;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\KitchenGoods;
use Exception;
use Illuminate\Support\Facades\Log;

class KitchenGoodsService
{
    protected array $goodsFilter = [
        'name',
        'kitchen_goods_category_id',
        'unit_id',
        'status',
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

            return KitchenGoods::with(['category', 'unit'])->where(function ($query) use ($requests, $request) {
                foreach ($requests as $key => $val) {
                    if (in_array($key, $this->goodsFilter)) {
                        if ($key == 'status' || $key == 'kitchen_goods_category_id' || $key == 'unit_id') {
                            $query->where($key, $val);
                        } else {
                            $query->where($key, 'like', '%'.$val.'%');
                        }
                    }
                }

                if ($request->has('low_stock') && $request->get('low_stock') == 1) {
                    $query->where('alert_quantity', '>', 0)
                        ->whereColumn('current_stock', '<=', 'alert_quantity');
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
    public function store(KitchenGoodsRequest $request): KitchenGoods
    {
        try {
            return KitchenGoods::create($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(KitchenGoodsRequest $request, KitchenGoods $kitchenGood): KitchenGoods
    {
        try {
            return tap($kitchenGood)->update($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(KitchenGoods $kitchenGood): void
    {
        try {
            $kitchenGood->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(KitchenGoods $kitchenGood): KitchenGoods
    {
        try {
            return $kitchenGood->load(['category', 'unit']);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}

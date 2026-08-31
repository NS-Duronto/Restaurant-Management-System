<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\KitchenGoodsRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\KitchenGoodsResource;
use App\Models\KitchenGoods;
use App\Services\KitchenGoodsService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KitchenGoodsController extends AdminController implements HasMiddleware
{
    private KitchenGoodsService $goodsService;

    public function __construct(KitchenGoodsService $goodsService)
    {
        parent::__construct();
        $this->goodsService = $goodsService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:kitchen-goods', only: ['store', 'update', 'destroy', 'show']),
        ];
    }

    public function index(PaginateRequest $request)
    {
        try {
            return KitchenGoodsResource::collection($this->goodsService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(KitchenGoodsRequest $request)
    {
        try {
            return new KitchenGoodsResource($this->goodsService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(KitchenGoods $kitchenGood)
    {
        try {
            return new KitchenGoodsResource($this->goodsService->show($kitchenGood));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(KitchenGoodsRequest $request, KitchenGoods $kitchenGood)
    {
        try {
            return new KitchenGoodsResource($this->goodsService->update($request, $kitchenGood));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(KitchenGoods $kitchenGood)
    {
        try {
            $this->goodsService->destroy($kitchenGood);

            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

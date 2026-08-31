<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\KitchenGoodsCategoryRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\KitchenGoodsCategoryResource;
use App\Models\KitchenGoodsCategory;
use App\Services\KitchenGoodsCategoryService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KitchenGoodsCategoryController extends AdminController implements HasMiddleware
{
    private KitchenGoodsCategoryService $categoryService;

    public function __construct(KitchenGoodsCategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
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
            return KitchenGoodsCategoryResource::collection($this->categoryService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(KitchenGoodsCategoryRequest $request)
    {
        try {
            return new KitchenGoodsCategoryResource($this->categoryService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(KitchenGoodsCategory $kitchenGoodsCategory)
    {
        try {
            return new KitchenGoodsCategoryResource($this->categoryService->show($kitchenGoodsCategory));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(KitchenGoodsCategoryRequest $request, KitchenGoodsCategory $kitchenGoodsCategory)
    {
        try {
            return new KitchenGoodsCategoryResource($this->categoryService->update($request, $kitchenGoodsCategory));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(KitchenGoodsCategory $kitchenGoodsCategory)
    {
        try {
            $this->categoryService->destroy($kitchenGoodsCategory);

            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

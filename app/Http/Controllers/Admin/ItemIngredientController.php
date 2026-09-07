<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ItemIngredientRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ItemIngredientResource;
use App\Models\Item;
use App\Models\ItemIngredient;
use App\Services\ItemIngredientService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ItemIngredientController extends AdminController implements HasMiddleware
{
    public ItemIngredientService $itemIngredientService;

    public function __construct(ItemIngredientService $itemIngredientService)
    {
        parent::__construct();
        $this->itemIngredientService = $itemIngredientService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:items_show', only: ['index', 'show', 'summary', 'store', 'update', 'destroy']),
        ];
    }

    public function index(PaginateRequest $request, Item $item)
    {
        try {
            return ItemIngredientResource::collection($this->itemIngredientService->list($request, $item));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(ItemIngredientRequest $request, Item $item)
    {
        try {
            return new ItemIngredientResource($this->itemIngredientService->store($request, $item));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(ItemIngredientRequest $request, Item $item, ItemIngredient $itemIngredient)
    {
        try {
            return new ItemIngredientResource($this->itemIngredientService->update($request, $item, $itemIngredient));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Item $item, ItemIngredient $itemIngredient)
    {
        try {
            $this->itemIngredientService->destroy($item, $itemIngredient);
            return response(['status' => true, 'message' => trans('all.message.delete_success')], 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Item $item, ItemIngredient $itemIngredient)
    {
        try {
            return new ItemIngredientResource($this->itemIngredientService->show($item, $itemIngredient));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function summary(Item $item)
    {
        try {
            return response(['status' => true, 'data' => $this->itemIngredientService->summary($item)]);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

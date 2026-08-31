<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExpenseCategoryRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ExpenseCategoryResource;
use App\Models\ExpenseCategory;
use App\Services\ExpenseCategoryService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ExpenseCategoryController extends AdminController implements HasMiddleware
{
    private ExpenseCategoryService $categoryService;

    public function __construct(ExpenseCategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:expenses|settings', only: ['store', 'update', 'destroy', 'show']),
        ];
    }

    public function index(PaginateRequest $request)
    {
        try {
            return ExpenseCategoryResource::collection($this->categoryService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(ExpenseCategoryRequest $request)
    {
        try {
            return new ExpenseCategoryResource($this->categoryService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        try {
            return new ExpenseCategoryResource($this->categoryService->show($expenseCategory));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(ExpenseCategoryRequest $request, ExpenseCategory $expenseCategory)
    {
        try {
            return new ExpenseCategoryResource($this->categoryService->update($request, $expenseCategory));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        try {
            $this->categoryService->destroy($expenseCategory);

            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

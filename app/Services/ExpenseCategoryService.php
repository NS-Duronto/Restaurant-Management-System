<?php

namespace App\Services;

use App\Http\Requests\ExpenseCategoryRequest;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\ExpenseCategory;
use Exception;
use Illuminate\Support\Facades\Log;

class ExpenseCategoryService
{
    protected array $expenseCategoryFilter = [
        'name',
        'description',
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

            return ExpenseCategory::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->expenseCategoryFilter)) {
                        if ($key == 'status') {
                            $query->where($key, $request);
                        } else {
                            $query->where($key, 'like', '%'.$request.'%');
                        }
                    }
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
    public function store(ExpenseCategoryRequest $request): ExpenseCategory
    {
        try {
            return ExpenseCategory::create($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(ExpenseCategoryRequest $request, ExpenseCategory $expenseCategory): ExpenseCategory
    {
        try {
            return tap($expenseCategory)->update($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(ExpenseCategory $expenseCategory): void
    {
        try {
            if ($expenseCategory->expenses()->exists()) {
                throw new Exception("Cannot delete category because expenses are associated with it.", 422);
            }
            $expenseCategory->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(ExpenseCategory $expenseCategory): ExpenseCategory
    {
        try {
            return $expenseCategory;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}

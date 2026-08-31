<?php

namespace App\Services;

use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\Expense;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpenseService
{
    protected array $expenseFilter = [
        'expense_category_id',
        'title',
        'payment_method',
        'payee_name',
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

            return Expense::with(['category', 'user', 'media'])->where(function ($query) use ($requests, $request) {
                foreach ($requests as $key => $val) {
                    if (in_array($key, $this->expenseFilter)) {
                        if ($key == 'expense_category_id' || $key == 'payment_method') {
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
    public function store(ExpenseRequest $request): Expense
    {
        try {
            $expense = Expense::create($request->validated() + ['user_id' => Auth::id()]);
            if ($request->file('file')) {
                $expense->addMediaFromRequest('file')->toMediaCollection('expense_file');
            }

            return $expense->load(['category', 'user', 'media']);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(ExpenseRequest $request, Expense $expense): Expense
    {
        try {
            $expense->update($request->validated());
            if ($request->file('file')) {
                $expense->clearMediaCollection('expense_file');
                $expense->addMediaFromRequest('file')->toMediaCollection('expense_file');
            }

            return $expense->load(['category', 'user', 'media']);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Expense $expense): void
    {
        try {
            $expense->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Expense $expense): Expense
    {
        try {
            return $expense->load(['category', 'user', 'media']);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}

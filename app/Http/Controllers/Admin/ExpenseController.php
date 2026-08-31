<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Services\ExpenseService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ExpenseController extends AdminController implements HasMiddleware
{
    private ExpenseService $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        parent::__construct();
        $this->expenseService = $expenseService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:expenses', only: ['store', 'update', 'destroy', 'show']),
        ];
    }

    public function index(PaginateRequest $request)
    {
        try {
            return ExpenseResource::collection($this->expenseService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(ExpenseRequest $request)
    {
        try {
            return new ExpenseResource($this->expenseService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Expense $expense)
    {
        try {
            return new ExpenseResource($this->expenseService->show($expense));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        try {
            return new ExpenseResource($this->expenseService->update($request, $expense));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Expense $expense)
    {
        try {
            $this->expenseService->destroy($expense);

            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

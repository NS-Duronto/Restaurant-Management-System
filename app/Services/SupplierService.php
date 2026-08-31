<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SupplierRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Models\Supplier;
use Exception;
use Illuminate\Support\Facades\Log;

class SupplierService
{
    protected array $supplierFilter = [
        'name',
        'company_name',
        'email',
        'phone',
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

            return Supplier::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->supplierFilter)) {
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
    public function store(SupplierRequest $request): Supplier
    {
        try {
            return Supplier::create($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(SupplierRequest $request, Supplier $supplier): Supplier
    {
        try {
            return tap($supplier)->update($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Supplier $supplier): void
    {
        try {
            $supplier->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Supplier $supplier): Supplier
    {
        try {
            return $supplier->load('purchases');
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}

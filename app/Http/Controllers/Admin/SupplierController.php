<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Services\SupplierService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SupplierController extends AdminController implements HasMiddleware
{
    private SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        parent::__construct();
        $this->supplierService = $supplierService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:suppliers', only: ['store', 'update', 'destroy', 'show']),
        ];
    }

    public function index(PaginateRequest $request)
    {
        try {
            return SupplierResource::collection($this->supplierService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(SupplierRequest $request)
    {
        try {
            return new SupplierResource($this->supplierService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Supplier $supplier)
    {
        try {
            return new SupplierResource($this->supplierService->show($supplier));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        try {
            return new SupplierResource($this->supplierService->update($request, $supplier));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $this->supplierService->destroy($supplier);

            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

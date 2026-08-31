<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseDetailsResource;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Services\PurchaseService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PurchaseController extends AdminController implements HasMiddleware
{
    private PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        parent::__construct();
        $this->purchaseService = $purchaseService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:purchases', only: ['store', 'destroy', 'show']),
        ];
    }

    public function index(PaginateRequest $request)
    {
        try {
            return PurchaseResource::collection($this->purchaseService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(PurchaseRequest $request)
    {
        try {
            return new PurchaseDetailsResource($this->purchaseService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Purchase $purchase)
    {
        try {
            return new PurchaseDetailsResource($this->purchaseService->show($purchase));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Purchase $purchase)
    {
        try {
            $this->purchaseService->destroy($purchase);

            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

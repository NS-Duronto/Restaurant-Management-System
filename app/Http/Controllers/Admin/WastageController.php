<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\WastageRequest;
use App\Http\Resources\WastageDetailsResource;
use App\Http\Resources\WastageResource;
use App\Models\Wastage;
use App\Services\WastageService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class WastageController extends AdminController implements HasMiddleware
{
    private WastageService $wastageService;

    public function __construct(WastageService $wastageService)
    {
        parent::__construct();
        $this->wastageService = $wastageService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:kitchen-goods', only: ['index', 'store', 'destroy', 'show']),
        ];
    }

    public function index(PaginateRequest $request)
    {
        try {
            return WastageResource::collection($this->wastageService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(WastageRequest $request)
    {
        try {
            return new WastageDetailsResource($this->wastageService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Wastage $wastage)
    {
        try {
            return new WastageDetailsResource($this->wastageService->show($wastage));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Wastage $wastage)
    {
        try {
            $this->wastageService->destroy($wastage);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

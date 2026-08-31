<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Services\UnitService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UnitController extends AdminController implements HasMiddleware
{
    private UnitService $unitService;

    public function __construct(UnitService $unitService)
    {
        parent::__construct();
        $this->unitService = $unitService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings|units', only: ['store', 'update', 'destroy', 'show']),
        ];
    }

    public function index(PaginateRequest $request)
    {
        try {
            return UnitResource::collection($this->unitService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(UnitRequest $request)
    {
        try {
            return new UnitResource($this->unitService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Unit $unit)
    {
        try {
            return new UnitResource($this->unitService->show($unit));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(UnitRequest $request, Unit $unit)
    {
        try {
            return new UnitResource($this->unitService->update($request, $unit));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Unit $unit)
    {
        try {
            $this->unitService->destroy($unit);

            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

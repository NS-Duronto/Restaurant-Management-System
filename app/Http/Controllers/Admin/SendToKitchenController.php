<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SendToKitchenRequest;
use App\Http\Resources\SendToKitchenDetailsResource;
use App\Http\Resources\SendToKitchenResource;
use App\Models\SendToKitchen;
use App\Services\SendToKitchenService;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SendToKitchenController extends AdminController implements HasMiddleware
{
    private SendToKitchenService $sendService;

    public function __construct(SendToKitchenService $sendService)
    {
        parent::__construct();
        $this->sendService = $sendService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:send-to-kitchen', only: ['store', 'destroy', 'show']),
        ];
    }

    public function index(PaginateRequest $request)
    {
        try {
            return SendToKitchenResource::collection($this->sendService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(SendToKitchenRequest $request)
    {
        try {
            return new SendToKitchenDetailsResource($this->sendService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(SendToKitchen $sendToKitchen)
    {
        try {
            return new SendToKitchenDetailsResource($this->sendService->show($sendToKitchen));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(SendToKitchen $sendToKitchen)
    {
        try {
            $this->sendService->destroy($sendToKitchen);

            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\AdminSimpleItemResource;
use App\Http\Resources\CustomerStatesResource;
use App\Http\Resources\SalesSummaryResource;
use App\Libraries\AppLibrary;
use App\Services\DashboardService;
use App\Services\ItemService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends AdminController
{
    private DashboardService $dashboardService;

    private ItemService $itemService;

    public function __construct(DashboardService $dashboardService, ItemService $itemService)
    {
        parent::__construct();
        $this->dashboardService = $dashboardService;
        $this->itemService = $itemService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:dashboard', only: ['orderStatistics', 'orderSummary', 'featuredItems', 'mostPopularItems', 'topCustomers', 'totalSales', 'salesSummary', 'customerStates', 'totalOrders', 'totalCustomers', 'totalMenuItems']),
            new Middleware('permission:dashboard|profit-loss-report', only: ['totalExpenses', 'totalNetProfit', 'profitSummary']),
        ];
    }

    public function totalSales(Request $request): Response|array|Application|ResponseFactory
    {
        try {
            return ['data' => ['total_sales' => AppLibrary::currencyAmountFormat($this->dashboardService->totalSales($request))]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function totalOrders(Request $request): Response|array|Application|ResponseFactory
    {
        try {
            return ['data' => ['total_orders' => $this->dashboardService->totalOrders($request)]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function totalCustomers(Request $request): Response|array|Application|ResponseFactory
    {
        try {
            return ['data' => ['total_customers' => $this->dashboardService->totalCustomers($request)]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function totalMenuItems(Request $request): Response|array|Application|ResponseFactory
    {
        try {
            return ['data' => ['total_menu_items' => $this->dashboardService->totalMenuItems($request)]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function salesSummary(
        Request $request
    ): Response|SalesSummaryResource|Application|ResponseFactory {
        try {
            return new SalesSummaryResource($this->dashboardService->salesSummary($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function customerStates(
        Request $request
    ): Response|CustomerStatesResource|Application|ResponseFactory {
        try {
            return new CustomerStatesResource($this->dashboardService->customerStates($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function featuredItems(): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        try {
            return AdminSimpleItemResource::collection($this->itemService->featuredItems());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function mostPopularItems(): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        try {
            return AdminSimpleItemResource::collection($this->itemService->mostPopularItems());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function totalExpenses(Request $request): Response|array|Application|ResponseFactory
    {
        try {
            $total = $this->dashboardService->totalExpenses($request);

            return ['data' => ['total_expenses' => AppLibrary::currencyAmountFormat($total), 'raw_total' => $total]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function totalNetProfit(Request $request): Response|array|Application|ResponseFactory
    {
        try {
            $profit = $this->dashboardService->totalNetProfit($request);

            return ['data' => ['net_profit' => AppLibrary::currencyAmountFormat($profit), 'raw_profit' => $profit]];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function profitSummary(Request $request): Response|array|Application|ResponseFactory
    {
        try {
            return ['data' => $this->dashboardService->profitSummary($request)];
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

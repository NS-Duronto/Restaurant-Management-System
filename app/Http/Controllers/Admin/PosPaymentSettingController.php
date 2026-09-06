<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Dipokhalder\Settings\Facades\Settings;
use App\Http\Requests\PosPaymentSettingRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\JsonResponse;

class PosPaymentSettingController extends AdminController
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings', only: ['update']),
        ];
    }

    public function index(): JsonResponse
    {
        try {
            $cardTypes = Settings::group('order_setup')->get('order_setup_pos_card_types');
            if (empty($cardTypes)) {
                $cardTypes = ['Visa', 'Mastercard', 'Takapay', 'Nexuspay'];
            } elseif (is_string($cardTypes)) {
                $decoded = json_decode($cardTypes, true);
                $cardTypes = is_array($decoded) ? $decoded : ['Visa', 'Mastercard', 'Takapay', 'Nexuspay'];
            }

            $mfsTypes = Settings::group('order_setup')->get('order_setup_pos_mfs_types');
            if (empty($mfsTypes)) {
                $mfsTypes = ['bKash', 'Rocket', 'Nagad', 'Ucash'];
            } elseif (is_string($mfsTypes)) {
                $decoded = json_decode($mfsTypes, true);
                $mfsTypes = is_array($decoded) ? $decoded : ['bKash', 'Rocket', 'Nagad', 'Ucash'];
            }

            return response()->json([
                'data' => [
                    'order_setup_pos_card_types' => $cardTypes,
                    'order_setup_pos_mfs_types'  => $mfsTypes,
                ]
            ]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(PosPaymentSettingRequest $request): JsonResponse
    {
        try {
            $cardTypes = $request->order_setup_pos_card_types;
            if (is_array($cardTypes)) {
                $cardTypes = json_encode(array_values(array_filter($cardTypes)));
            }

            $mfsTypes = $request->order_setup_pos_mfs_types;
            if (is_array($mfsTypes)) {
                $mfsTypes = json_encode(array_values(array_filter($mfsTypes)));
            }

            Settings::group('order_setup')->set([
                'order_setup_pos_card_types' => $cardTypes,
                'order_setup_pos_mfs_types'  => $mfsTypes,
            ]);

            return response()->json([
                'status'  => true,
                'message' => trans('message.pos_payment_updated_successfully'),
                'data'    => [
                    'order_setup_pos_card_types' => json_decode($cardTypes, true),
                    'order_setup_pos_mfs_types'  => json_decode($mfsTypes, true),
                ]
            ]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResortBookingRequest;
use App\Services\ResortService;
use Illuminate\Http\Request;

class ResortController extends Controller
{
    private ResortService $resortService;

    public function __construct(ResortService $resortService)
    {
        $this->resortService = $resortService;
    }

    public function content()
    {
        try {
            return response([
                'status' => true,
                'data'   => $this->resortService->getLandingContent(),
            ], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function booking(ResortBookingRequest $request)
    {
        try {
            $booking = $this->resortService->storeBooking($request);
            $message = $booking->type === 'party'
                ? 'আপনার পার্টি / ইভেন্ট বুকিং রিকোয়েস্ট সফলভাবে গ্রহণ করা হয়েছে! আমাদের ইভেন্ট কো-অর্ডিনেটর খুব শীঘ্রই আপনার সাথে যোগাযোগ করে সকল আয়োজন চূড়ান্ত করবেন।'
                : 'আপনার রিসোর্ট রুম বুকিং রিকোয়েস্ট সফলভাবে গ্রহণ করা হয়েছে! আমাদের প্রতিনিধি খুব শীঘ্রই আপনার সাথে যোগাযোগ করে বুকিং নিশ্চিত করবেন।';

            return response([
                'status'  => true,
                'message' => $message,
                'data'    => $booking,
            ], 201);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

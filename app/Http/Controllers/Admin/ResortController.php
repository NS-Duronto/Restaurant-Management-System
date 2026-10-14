<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\ResortBooking;
use App\Models\ResortEvent;
use App\Models\ResortGallery;
use App\Models\ResortMembershipPlan;
use App\Models\ResortPartner;
use App\Models\ResortSlider;
use App\Models\ResortTeam;
use App\Services\ResortService;
use App\Http\Requests\ResortSliderRequest;
use App\Http\Requests\ResortGalleryRequest;
use App\Http\Requests\ResortEventRequest;
use App\Http\Requests\ResortTeamRequest;
use App\Http\Requests\ResortMembershipPlanRequest;
use App\Http\Requests\ResortPartnerRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class ResortController extends AdminController
{
    private ResortService $resortService;

    public function __construct(ResortService $resortService)
    {
        parent::__construct();
        $this->resortService = $resortService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:resort_settings'),
        ];
    }

    // ----------------- SETTINGS -----------------
    public function getSettings()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->getSettings()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function updateSettings(Request $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->updateSettings($request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    // ----------------- SECTIONS REORDER -----------------
    public function sections()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->sections()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function updateSectionsOrder(Request $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->updateSectionsOrder($request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    // ----------------- SLIDERS -----------------
    public function sliders()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->sliders()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function storeSlider(ResortSliderRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->storeSlider($request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function updateSlider(ResortSlider $slider, ResortSliderRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->updateSlider($slider, $request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroySlider(ResortSlider $slider)
    {
        try {
            $this->resortService->deleteSlider($slider);
            return response(['status' => true, 'message' => 'Slider deleted successfully'], 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    // ----------------- GALLERIES -----------------
    public function galleries()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->galleries()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function storeGallery(ResortGalleryRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->storeGallery($request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function updateGallery(ResortGallery $gallery, ResortGalleryRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->updateGallery($gallery, $request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroyGallery(ResortGallery $gallery)
    {
        try {
            $this->resortService->deleteGallery($gallery);
            return response(['status' => true, 'message' => 'Gallery item deleted successfully'], 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    // ----------------- EVENTS -----------------
    public function events()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->events()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function storeEvent(ResortEventRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->storeEvent($request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function updateEvent(ResortEvent $event, ResortEventRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->updateEvent($event, $request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroyEvent(ResortEvent $event)
    {
        try {
            $this->resortService->deleteEvent($event);
            return response(['status' => true, 'message' => 'Event deleted successfully'], 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    // ----------------- TEAMS -----------------
    public function teams()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->teams()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function storeTeam(ResortTeamRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->storeTeam($request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function updateTeam(ResortTeam $team, ResortTeamRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->updateTeam($team, $request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroyTeam(ResortTeam $team)
    {
        try {
            $this->resortService->deleteTeam($team);
            return response(['status' => true, 'message' => 'Team member deleted successfully'], 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    // ----------------- MEMBERSHIP PLANS -----------------
    public function plans()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->plans()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function storePlan(ResortMembershipPlanRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->storePlan($request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function updatePlan(ResortMembershipPlan $plan, ResortMembershipPlanRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->updatePlan($plan, $request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroyPlan(ResortMembershipPlan $plan)
    {
        try {
            $this->resortService->deletePlan($plan);
            return response(['status' => true, 'message' => 'Plan deleted successfully'], 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    // ----------------- PARTNERS -----------------
    public function partners()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->partners()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function storePartner(ResortPartnerRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->storePartner($request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function updatePartner(ResortPartner $partner, ResortPartnerRequest $request)
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->updatePartner($partner, $request)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroyPartner(ResortPartner $partner)
    {
        try {
            $this->resortService->deletePartner($partner);
            return response(['status' => true, 'message' => 'Partner deleted successfully'], 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    // ----------------- BOOKING REQUESTS -----------------
    public function bookings()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->bookings()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function updateBookingStatus(ResortBooking $booking, Request $request)
    {
        try {
            $status = $request->input('status', 10);
            return response(['status' => true, 'data' => $this->resortService->updateBookingStatus($booking, $status)], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroyBooking(ResortBooking $booking)
    {
        try {
            $this->resortService->deleteBooking($booking);
            return response(['status' => true, 'message' => 'Booking request deleted successfully'], 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    // ----------------- WEBSITE ORDERS -----------------
    public function websiteOrders()
    {
        try {
            return response(['status' => true, 'data' => $this->resortService->websiteOrders()], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}

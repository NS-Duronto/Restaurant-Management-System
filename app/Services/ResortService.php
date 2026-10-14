<?php

namespace App\Services;

use Exception;
use App\Models\DiningTable;
use App\Models\Item;
use App\Models\Order;
use App\Models\ResortBooking;
use App\Models\ResortEvent;
use App\Models\ResortGallery;
use App\Models\ResortMembershipPlan;
use App\Models\ResortPartner;
use App\Models\ResortSection;
use App\Models\ResortSlider;
use App\Models\ResortTeam;
use App\Enums\Status;
use App\Libraries\AppLibrary;
use App\Libraries\QueryExceptionLibrary;
use Dipokhalder\Settings\Facades\Settings;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ResortService
{
    /**
     * Get all content required for the public Resort Landing Page.
     *
     * @throws Exception
     */
    public function getLandingContent(): array
    {
        try {
            $settings = Settings::group('resort')->all();

            $sections = ResortSection::orderBy('sort_order', 'asc')
                ->get();

            $sliders = ResortSlider::where('status', Status::ACTIVE)
                ->orderBy('sort_order', 'asc')
                ->get();

            $photos = ResortGallery::where('status', Status::ACTIVE)
                ->where('type', 'photo')
                ->orderBy('sort_order', 'asc')
                ->get();

            $videos = ResortGallery::where('status', Status::ACTIVE)
                ->where('type', 'video')
                ->orderBy('sort_order', 'asc')
                ->get();

            $aboutSliders = ResortEvent::where('status', Status::ACTIVE)
                ->where('is_about_slider', 1)
                ->orderBy('sort_order', 'asc')
                ->get();

            $latestEvents = ResortEvent::where('status', Status::ACTIVE)
                ->where('is_about_slider', 0)
                ->orderBy('sort_order', 'asc')
                ->get();

            $teams = ResortTeam::whereIn('status', [Status::ACTIVE, 1])
                ->orderBy('sort_order', 'asc')
                ->get();

            $plans = ResortMembershipPlan::whereIn('status', [Status::ACTIVE, 1])
                ->orderBy('sort_order', 'asc')
                ->get();

            $partners = ResortPartner::whereIn('status', [Status::ACTIVE, 1])
                ->orderBy('sort_order', 'asc')
                ->get();

            // Popular dishes from Sohoj RMS restaurant catalog
            $foodItems = Item::where('status', Status::ACTIVE)
                ->with(['media', 'category'])
                ->orderBy('is_featured', 'desc')
                ->orderBy('order', 'asc')
                ->take(12)
                ->get()
                ->map(function ($item) {
                    return [
                        'id'             => $item->id,
                        'name'           => $item->name,
                        'slug'           => $item->slug,
                        'price'          => (float) $item->price,
                        'currency_price' => AppLibrary::currencyAmountFormat($item->price),
                        'flat_price'     => AppLibrary::flatAmountFormat($item->price),
                        'thumb'          => $item->thumb,
                        'cover'          => $item->cover,
                        'preview'        => $item->preview,
                        'category_id'    => $item->item_category_id,
                        'category_name'  => optional($item->category)->name,
                        'description'    => $item->description,
                        'caution'        => $item->caution,
                        'is_featured'    => (int) $item->is_featured,
                    ];
                });

            // Dining tables with live generated QR codes for restaurant ordering
            $defaultBranchId = (int) (Settings::group('resort')->get('resort_branch_id')
                ?: Settings::group('site')->get('site_default_branch', 1)) ?: 1;

            $tablesQuery = DiningTable::withoutGlobalScopes()
                ->where('status', Status::ACTIVE);

            // Filter to resort default branch so tables from multiple city branches don't collide
            if ($defaultBranchId) {
                $branchTables = (clone $tablesQuery)->where('branch_id', $defaultBranchId)->get();
                if ($branchTables->isNotEmpty()) {
                    $tablesQuery->where('branch_id', $defaultBranchId);
                }
            }

            $diningTables = $tablesQuery
                ->with('branch')
                ->orderBy('id', 'asc')
                ->get()
                ->map(function ($table) {
                    $slug = $table->slug ?: ('table-' . $table->id);
                    $menuUrl = url('/menu/' . $slug);
                    $qrSvg = base64_encode(QrCode::format('svg')->size(220)->margin(1)->generate($menuUrl));
                    return [
                        'id'          => $table->id,
                        'name'        => $table->name,
                        'slug'        => $slug,
                        'size'        => (int) $table->size,
                        'branch_id'   => $table->branch_id,
                        'branch_name' => optional($table->branch)->name,
                        'menu_url'    => $menuUrl,
                        'qr_code'     => 'data:image/svg+xml;base64,' . $qrSvg,
                    ];
                });

            return [
                'settings'      => $settings,
                'sections'      => $sections,
                'sliders'       => $sliders,
                'photos'        => $photos,
                'videos'        => $videos,
                'about_sliders' => $aboutSliders,
                'latest_events' => $latestEvents,
                'teams'         => $teams,
                'plans'         => $plans,
                'partners'         => $partners,
                'food_items'       => $foodItems,
                'total_food_count' => Item::where('status', Status::ACTIVE)->count(),
                'dining_tables'    => $diningTables,
            ];
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * Get resort settings.
     */
    public function getSettings(): array
    {
        try {
            return Settings::group('resort')->all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * Update resort settings.
     */
    public function updateSettings($request): array
    {
        try {
            $data = $request->except(['_token', 'logo']);
            Settings::group('resort')->set($data);
            return Settings::group('resort')->all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * Sections list
     */
    public function sections()
    {
        try {
            return ResortSection::orderBy('sort_order', 'asc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * Reorder sections and toggle active status.
     */
    public function updateSectionsOrder($request): array
    {
        try {
            $sections = $request->input('sections', []);
            foreach ($sections as $index => $item) {
                ResortSection::where('id', $item['id'])->update([
                    'sort_order' => $index + 1,
                    'status'     => (int) ($item['status'] ?? Status::ACTIVE),
                ]);
            }
            return ResortSection::orderBy('sort_order', 'asc')->get()->toArray();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    // ------------------- SLIDERS -------------------
    public function sliders()
    {
        try {
            return ResortSlider::orderBy('sort_order', 'asc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function storeSlider($request): ResortSlider
    {
        try {
            $slider = ResortSlider::create($request->validated());
            if ($request->hasFile('image')) {
                $slider->addMediaFromRequest('image')->toMediaCollection('slider');
            }
            return $slider;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function updateSlider(ResortSlider $slider, $request): ResortSlider
    {
        try {
            $slider->update($request->validated());
            if ($request->hasFile('image')) {
                $slider->clearMediaCollection('slider');
                $slider->addMediaFromRequest('image')->toMediaCollection('slider');
            }
            return $slider;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deleteSlider(ResortSlider $slider): bool
    {
        try {
            $slider->clearMediaCollection('slider');
            return $slider->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    // ------------------- GALLERIES -------------------
    public function galleries()
    {
        try {
            return ResortGallery::orderBy('sort_order', 'asc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function storeGallery($request): ResortGallery
    {
        try {
            $gallery = ResortGallery::create($request->validated());
            if ($request->hasFile('image')) {
                $gallery->addMediaFromRequest('image')->toMediaCollection('gallery');
            }
            return $gallery;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function updateGallery(ResortGallery $gallery, $request): ResortGallery
    {
        try {
            $gallery->update($request->validated());
            if ($request->hasFile('image')) {
                $gallery->clearMediaCollection('gallery');
                $gallery->addMediaFromRequest('image')->toMediaCollection('gallery');
            }
            return $gallery;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deleteGallery(ResortGallery $gallery): bool
    {
        try {
            $gallery->clearMediaCollection('gallery');
            return $gallery->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    // ------------------- EVENTS -------------------
    public function events()
    {
        try {
            return ResortEvent::orderBy('sort_order', 'asc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function storeEvent($request): ResortEvent
    {
        try {
            $event = ResortEvent::create($request->validated());
            if ($request->hasFile('image')) {
                $event->addMediaFromRequest('image')->toMediaCollection('event');
            }
            return $event;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function updateEvent(ResortEvent $event, $request): ResortEvent
    {
        try {
            $event->update($request->validated());
            if ($request->hasFile('image')) {
                $event->clearMediaCollection('event');
                $event->addMediaFromRequest('image')->toMediaCollection('event');
            }
            return $event;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deleteEvent(ResortEvent $event): bool
    {
        try {
            $event->clearMediaCollection('event');
            return $event->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    // ------------------- TEAM MEMBERS -------------------
    public function teams()
    {
        try {
            return ResortTeam::orderBy('sort_order', 'asc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function storeTeam($request): ResortTeam
    {
        try {
            $team = ResortTeam::create($request->validated());
            if ($request->hasFile('image')) {
                $team->addMediaFromRequest('image')->toMediaCollection('team');
            }
            return $team;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function updateTeam(ResortTeam $team, $request): ResortTeam
    {
        try {
            $team->update($request->validated());
            if ($request->hasFile('image')) {
                $team->clearMediaCollection('team');
                $team->addMediaFromRequest('image')->toMediaCollection('team');
            }
            return $team;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deleteTeam(ResortTeam $team): bool
    {
        try {
            $team->clearMediaCollection('team');
            return $team->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    // ------------------- MEMBERSHIP PLANS -------------------
    public function plans()
    {
        try {
            return ResortMembershipPlan::orderBy('sort_order', 'asc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function storePlan($request): ResortMembershipPlan
    {
        try {
            $data = $request->validated();
            if (isset($data['features']) && is_array($data['features'])) {
                $data['features'] = json_encode($data['features']);
            }
            return ResortMembershipPlan::create($data);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function updatePlan(ResortMembershipPlan $plan, $request): ResortMembershipPlan
    {
        try {
            $data = $request->validated();
            if (isset($data['features']) && is_array($data['features'])) {
                $data['features'] = json_encode($data['features']);
            }
            $plan->update($data);
            return $plan;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deletePlan(ResortMembershipPlan $plan): bool
    {
        try {
            return $plan->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    // ------------------- PARTNERS -------------------
    public function partners()
    {
        try {
            return ResortPartner::orderBy('sort_order', 'asc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function storePartner($request): ResortPartner
    {
        try {
            $partner = ResortPartner::create($request->validated());
            if ($request->hasFile('image')) {
                $partner->addMediaFromRequest('image')->toMediaCollection('partner');
            }
            return $partner;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function updatePartner(ResortPartner $partner, $request): ResortPartner
    {
        try {
            $partner->update($request->validated());
            if ($request->hasFile('image')) {
                $partner->clearMediaCollection('partner');
                $partner->addMediaFromRequest('image')->toMediaCollection('partner');
            }
            return $partner;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deletePartner(ResortPartner $partner): bool
    {
        try {
            $partner->clearMediaCollection('partner');
            return $partner->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    // ------------------- BOOKINGS -------------------
    public function bookings()
    {
        try {
            return ResortBooking::orderBy('id', 'desc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function storeBooking($request): ResortBooking
    {
        try {
            $data = $request->validated();
            if (($data['type'] ?? '') === 'party') {
                $data['room_type'] = null;
            } else {
                $data['event_type'] = null;
            }
            if (empty($data['status'])) {
                $data['status'] = Status::ACTIVE;
            }
            return ResortBooking::create($data);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function updateBookingStatus(ResortBooking $booking, int $status): ResortBooking
    {
        try {
            $booking->update(['status' => $status]);
            return $booking;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deleteBooking(ResortBooking $booking): bool
    {
        try {
            return $booking->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    // ------------------- WEBSITE ORDERS -------------------
    public function websiteOrders()
    {
        try {
            return Order::with(['orderItems', 'user', 'diningTable'])
                ->where(function ($query) {
                    $query->where(function ($sub) {
                        $sub->whereNotNull('customer_phone')
                            ->where('customer_phone', '!=', '');
                    })->orWhere(function ($sub) {
                        $sub->whereNotNull('customer_name')
                            ->where('customer_name', '!=', '');
                    })->orWhere(function ($sub) {
                        $sub->whereNotNull('room_number')
                            ->where('room_number', '!=', '');
                    });
                })
                ->orderBy('id', 'desc')
                ->take(100)
                ->get()
                ->map(function ($order) {
                    $order->status = (int) $order->status;
                    $order->status_name = trans('orderStatus.' . $order->status);
                    return $order;
                });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}

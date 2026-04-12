<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;
use App\Services\Hotel\HotelOwnerAccessService;
use App\Services\Hotel\HotelOwnerQueryService;
use App\Services\Hotel\UpsertHotelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function __construct(
        private readonly HotelOwnerQueryService $hotelOwnerQueryService,
        private readonly HotelOwnerAccessService $hotelOwnerAccessService,
        private readonly UpsertHotelService $upsertHotelService,
    ) {
    }

    public function index(Request $request): View
    {
        return view('hotels.owner.hotel-list', [
            'hotels' => $this->hotelOwnerQueryService->listForOwner($request->user()),
        ]);
    }

    public function create(): View
    {
        return view('hotels.owner.create-hotel', [
            'hotel' => $this->hotelOwnerQueryService->makeDraft(),
        ]);
    }

    public function store(StoreHotelRequest $request): RedirectResponse
    {
        $hotel = $this->upsertHotelService->create($request, $request->user());

        return redirect()
            ->route('hotel_owner.hotels.edit', $hotel)
            ->with('success', 'Hotel saved successfully. It is now ready for review.');
    }

    public function edit(Request $request, Hotel $hotel): View
    {
        $hotel = $this->hotelOwnerAccessService->authorize($request->user(), $hotel);

        return view('hotels.owner.edit-hotel', [
            'hotel' => $this->hotelOwnerQueryService->loadForEdit($hotel),
        ]);
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel): RedirectResponse
    {
        $hotel = $this->hotelOwnerAccessService->authorize($request->user(), $hotel);
        $hotel = $this->upsertHotelService->update($request, $hotel);

        return redirect()
            ->route('hotel_owner.hotels.edit', $hotel)
            ->with('success', 'Hotel updated successfully.');
    }
}

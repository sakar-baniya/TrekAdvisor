<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserDashboardQueryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Hotel Owner Dashboard Controller: Hotel owner ko main landing page.
 *
 * Function:
 * Kati bookings aayo, ra earning kati bhyo bhanne stats QueryService bata dekhaur.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly UserDashboardQueryService $userDashboardQueryService,
    ) {
    }

    /**
     * Dashboard View: Owner ko summary stats dekhaune.
     */
    public function index(Request $request): View
    {
        return view('hotels.owner.dashboard', $this->userDashboardQueryService->hotelOwnerData($request->user()));
    }
}




<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserDashboardQueryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly UserDashboardQueryService $userDashboardQueryService,
    ) {
    }

    public function index(Request $request): View
    {
        return view('hotel.hotel-owner-dashboard', $this->userDashboardQueryService->hotelOwnerData($request->user()));
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\UserDashboardQueryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly UserDashboardQueryService $userDashboardQueryService,
    ) {
    }

    public function customer(Request $request): View
    {
        return view('customer.dashboard', $this->userDashboardQueryService->customerData($request->user()));
    }

    public function staff(): View
    {
        return view('staff.dashboard', $this->userDashboardQueryService->staffData());
    }

    public function hotelOwner(Request $request): View
    {
        return view('hotel.dashboard', $this->userDashboardQueryService->hotelOwnerData($request->user()));
    }
}

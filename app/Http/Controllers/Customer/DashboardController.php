<?php

namespace App\Http\Controllers\Customer;

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

    public function customer(Request $request): View
    {
        return view('customer.customer-dashboard', $this->userDashboardQueryService->customerData($request->user()));
    }

    public function staff(): View
    {
        return view('staff.staff-dashboard', $this->userDashboardQueryService->staffData());
    }

    public function hotelOwner(Request $request): View
    {
        return view('hotel.hotel-owner-dashboard', $this->userDashboardQueryService->hotelOwnerData($request->user()));
    }
}

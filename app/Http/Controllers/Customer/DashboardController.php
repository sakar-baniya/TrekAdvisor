<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserDashboardQueryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Handles dashboard screens for customer-facing role pages.
 *
 * Why this exists:
 * Keeps dashboard data loading in one place and sends it to the right view.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly UserDashboardQueryService $userDashboardQueryService,
    ) {
    }

    /**
     * Show the customer dashboard.
     *
     * Why:
     * Customer users need booking summaries and quick stats in one page.
     */
    public function customer(Request $request): View
    {
        return view('customer.customer-dashboard', $this->userDashboardQueryService->customerData($request->user()));
    }

    /**
     * Show the staff dashboard view from this controller.
     *
     * Why:
     * Kept for compatibility with older calls that may still hit this method.
     */
    public function staff(): View
    {
        return view('staff.staff-dashboard', $this->userDashboardQueryService->staffData());
    }

    /**
     * Show the hotel-owner dashboard.
     *
     * Why:
     * Hotel owners need their own booking and revenue overview.
     */
    public function hotelOwner(Request $request): View
    {
        return view('hotel.hotel-owner-dashboard', $this->userDashboardQueryService->hotelOwnerData($request->user()));
    }
}

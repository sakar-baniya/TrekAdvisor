<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserDashboardQueryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Role Dashboard Controller: Role anusar ko dashboard page redirect garne thau.
 *
 * Function:
 * Customer lai customer dashboard, Staff lai staff dashboard ma data pathaucha.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly UserDashboardQueryService $userDashboardQueryService,
    ) {
    }

    /**
     * Customer Dashboard: Customer ko aafno summary dekhaune.
     */
    public function customer(Request $request): View
    {
        return view('account.dashboard', $this->userDashboardQueryService->customerData($request->user()));
    }

    /**
     * Staff Dashboard: Staff ko summary dekhaune.
     */
    public function staff(): View
    {
        return view('staff.staff-dashboard', $this->userDashboardQueryService->staffData());
    }

    /**
     * Hotel Owner Dashboard: Hotel owner ko hotel bookings ra earnings dekhaune.
     */
    public function hotelOwner(Request $request): View
    {
        return view('hotels.owner.dashboard', $this->userDashboardQueryService->hotelOwnerData($request->user()));
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserDashboardQueryService;
use Illuminate\View\View;

/**
 * Staff Dashboard Controller: Staff ko main landing page.
 *
 * Function:
 * Staff ko daily tasks, summary data, ra pending works dekhauna QueryService call garcha.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly UserDashboardQueryService $userDashboardQueryService,
    ) {
    }

    /**
     * Dashboard View: Staff ko summary stats dekhaune.
     */
    public function index(): View
    {
        return view('staff.staff-dashboard', $this->userDashboardQueryService->staffData());
    }
}




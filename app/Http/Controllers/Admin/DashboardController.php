<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AdminDashboardQueryService;
use Illuminate\Contracts\View\View;

/**
 * Admin Dashboard Controller: Admin ko mukhya landing page.
 *
 * Function:
 * Total bookings, revenue, ra platform ko summary data tanna ko lagi query service call garchha.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardQueryService $adminDashboardQueryService,
    ) {
    }

    /**
     * Dashboard View (Index): Admin dashboard render garne thau.
     */
    public function index(): View
    {
        return view('admin.admin-dashboard', $this->adminDashboardQueryService->data());
    }
}




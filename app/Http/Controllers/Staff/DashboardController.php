<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserDashboardQueryService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly UserDashboardQueryService $userDashboardQueryService,
    ) {
    }

    public function index(): View
    {
        return view('dashboard.staff-dashboard', $this->userDashboardQueryService->staffData());
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AdminDashboardQueryService;
use Illuminate\Contracts\View\View;

/**
 * Yo DashboardController controller le dashboard controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardQueryService $adminDashboardQueryService,
    ) {
    }

    /**
     * Yo function le index ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function index(): View
    {
        return view('admin.admin-dashboard', $this->adminDashboardQueryService->data());
    }
}




<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AdminDashboardQueryService;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardQueryService $adminDashboardQueryService,
    ) {
    }

    public function index(): View
    {
        return view('admin.dashboard', $this->adminDashboardQueryService->data());
    }
}

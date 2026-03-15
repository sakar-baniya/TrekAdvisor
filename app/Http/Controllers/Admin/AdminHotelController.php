<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminHotelController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $hotelsQuery = Hotel::query()
            ->with('owner')
            ->orderByDesc('created_at');

        if ($status !== '') {
            $hotelsQuery->where('status', $status);
        }

        $hotels = $hotelsQuery->paginate(10)->withQueryString();
        $pendingCount = Hotel::query()->where('status', 'Pending')->count();

        return view('admin.hotels.index', [
            'hotels' => $hotels,
            'pendingCount' => $pendingCount,
            'status' => $status,
        ]);
    }

    public function updateStatus(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Active,Inactive,Pending'],
        ]);

        $hotel->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Hotel status updated.');
    }
}

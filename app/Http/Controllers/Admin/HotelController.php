<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $hotelsQuery = Hotel::query()
            ->with(['owner', 'rooms'])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $hotelsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('owner', fn ($ownerQuery) => $ownerQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($status !== '') {
            $hotelsQuery->where('status', $status);
        }

        $hotels = $hotelsQuery->paginate(10)->withQueryString();
        $pendingCount = Hotel::query()->where('status', 'pending')->count();
        $activeCount = Hotel::query()->where('status', 'active')->count();
        $inactiveCount = Hotel::query()->where('status', 'inactive')->count();

        return view('admin.hotels.index', [
            'hotels' => $hotels,
            'pendingCount' => $pendingCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function updateStatus(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,inactive,pending'],
        ]);

        $hotel->update([
            'status' => $validated['status'],
        ]);

        if ($validated['status'] === 'active' && $hotel->owner && ! $hotel->owner->isApproved()) {
            $hotel->owner->update([
                'approval_status' => 'approved',
            ]);
        }

        return back()->with('success', 'Hotel status updated.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GearRental;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminGearRentalController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $rentals = GearRental::query()
            ->with(['user', 'gearItem'])
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.gear-rentals.index', [
            'rentals' => $rentals,
            'status' => $status,
        ]);
    }

    public function markReturned(GearRental $gearRental): RedirectResponse
    {
        if ($gearRental->status === 'returned') {
            return back()->with('success', 'Rental was already marked as returned.');
        }

        $gearRental->update([
            'status' => 'returned',
        ]);

        return back()->with('success', 'Rental marked as returned.');
    }

    public function confirm(GearRental $gearRental): RedirectResponse
    {
        if ($gearRental->status !== 'pending') {
            return back()->with('error', 'Only pending rentals can be confirmed.');
        }

        $gearRental->update([
            'status' => 'active',
        ]);

        return back()->with('success', 'Rental confirmed and activated.');
    }
}


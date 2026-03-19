<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GearRental;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if ($gearRental->status === 'Returned') {
            return back()->with('success', 'Rental was already marked as returned.');
        }

        DB::transaction(function () use ($gearRental) {
            $gearRental->update([
                'status' => 'Returned',
            ]);

            $gearItem = $gearRental->gearItem;

            if ($gearItem) {
                $gearItem->update([
                    'available_stock' => min($gearItem->available_stock + $gearRental->quantity, $gearItem->total_stock),
                ]);
            }
        });

        return back()->with('success', 'Rental marked as returned.');
    }
}

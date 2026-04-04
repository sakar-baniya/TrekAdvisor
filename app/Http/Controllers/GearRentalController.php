<?php

namespace App\Http\Controllers;

use App\Models\GearItem;
use App\Models\GearRental;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GearRentalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display available gear items for rental
     */
    public function shop(Request $request): View
    {
        $search = $request->string('search')->toString();
        $type = $request->string('type')->toString();

        $gearItems = GearItem::query()
            ->where('status', 'Active')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($type !== '', fn ($query) => $query->where('type', $type))
            ->paginate(12)
            ->withQueryString();

        return view('customer.gear.shop', [
            'gearItems' => $gearItems,
            'search' => $search,
            'type' => $type,
            'types' => GearItem::query()
                ->where('status', 'Active')
                ->select('type')
                ->distinct()
                ->orderBy('type')
                ->pluck('type'),
        ]);
    }

    /**
     * Show gear item details
     */
    public function show(GearItem $gear): View
    {
        if ($gear->status === 'Inactive') {
            abort(404);
        }

        return view('customer.gear.show', [
            'gear' => $gear,
        ]);
    }

    /**
     * Store a new gear rental request
     */
    public function rent(Request $request, GearItem $gear): RedirectResponse
    {
        if ($gear->status === 'Inactive') {
            return back()->with('error', 'This gear item is not available.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
            'expected_return_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $rental = GearRental::create([
            'user_id' => auth()->id(),
            'gear_item_id' => $gear->id,
            'rental_reference' => $this->generateRentalReference(),
            'quantity' => $validated['quantity'],
            'status' => 'Pending',
            'notes' => $validated['notes'] ?? null,
            'expected_return_date' => $validated['expected_return_date'] ?? null,
        ]);

        return redirect()
            ->route('customer.dashboard')
            ->with('success', "Gear '{$gear->name}' rental request submitted. Reference: {$rental->rental_reference}");
    }

    /**
     * Display customer's gear rentals in dashboard
     */
    public function myRentals(): View
    {
        $rentals = GearRental::query()
            ->where('user_id', auth()->id())
            ->with('gearItem')
            ->latest()
            ->paginate(10);

        return view('customer.gear.my-rentals', [
            'rentals' => $rentals,
        ]);
    }

    /**
     * Cancel a rental request
     */
    public function cancel(GearRental $gearRental): RedirectResponse
    {
        try {
            $this->authorize('delete', $gearRental);
        } catch (AuthorizationException $e) {
            return back()->with('error', 'You are not authorized to cancel this rental.');
        }

        if (!in_array($gearRental->status, ['Pending', 'Active'])) {
            return back()->with('error', "Cannot cancel a {$gearRental->status} rental.");
        }

        $gearRental->update([
            'status' => 'Cancelled',
        ]);

        return back()->with('success', 'Rental request cancelled successfully.');
    }

    /**
     * Generate unique rental reference
     */
    protected function generateRentalReference(): string
    {
        do {
            $reference = 'GR-' . strtoupper(Str::random(8));
        } while (GearRental::where('rental_reference', $reference)->exists());

        return $reference;
    }
}

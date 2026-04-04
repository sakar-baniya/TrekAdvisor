<?php

namespace App\Services\Gear;

use App\Models\GearItem;
use App\Models\GearRental;
use App\Models\User;
use Illuminate\Support\Str;

class GearRentalService
{
    /**
     * Create a new gear rental request
     */
    public function createRental(User $user, GearItem $gear, array $data): GearRental
    {
        // Validate gear is available
        if ($gear->status === 'Inactive') {
            throw new \Exception('This gear item is not available for rental.');
        }

        // Validate quantity
        if ($data['quantity'] < 1) {
            throw new \Exception('Quantity must be at least 1.');
        }

        return GearRental::create([
            'user_id' => $user->id,
            'gear_item_id' => $gear->id,
            'rental_reference' => $this->generateRentalReference(),
            'quantity' => $data['quantity'],
            'status' => 'Pending',
            'notes' => $data['notes'] ?? null,
            'expected_return_date' => $data['expected_return_date'] ?? null,
        ]);
    }

    /**
     * Confirm a rental (admin action)
     */
    public function confirmRental(GearRental $rental): void
    {
        if ($rental->status !== 'Pending') {
            throw new \Exception('Only pending rentals can be confirmed.');
        }

        $rental->update(['status' => 'Active']);
    }

    /**
     * Mark rental as returned (admin action)
     */
    public function markReturned(GearRental $rental): void
    {
        if ($rental->status !== 'Active') {
            throw new \Exception('Only active rentals can be marked as returned.');
        }

        $rental->update(['status' => 'Returned']);
    }

    /**
     * Cancel a rental request
     */
    public function cancelRental(GearRental $rental): void
    {
        if (!in_array($rental->status, ['Pending', 'Active'])) {
            throw new \Exception("Cannot cancel a {$rental->status} rental.");
        }

        $rental->update(['status' => 'Cancelled']);
    }

    /**
     * Get customer's active and pending rentals
     */
    public function getUserRentals(User $user, ?string $status = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = $user->gearRentals()->with('gearItem');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    /**
     * Get rental statistics for admin dashboard
     */
    public function getRentalStats(): array
    {
        return [
            'total_rentals' => GearRental::count(),
            'pending_rentals' => GearRental::where('status', 'Pending')->count(),
            'active_rentals' => GearRental::where('status', 'Active')->count(),
            'returned_rentals' => GearRental::where('status', 'Returned')->count(),
            'cancelled_rentals' => GearRental::where('status', 'Cancelled')->count(),
        ];
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

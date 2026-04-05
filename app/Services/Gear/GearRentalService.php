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
        if ($gear->status === 'inactive') {
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
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
            'expected_return_date' => $data['expected_return_date'] ?? null,
        ]);
    }

    /**
     * Confirm a rental (admin action)
     */
    public function confirmRental(GearRental $rental): void
    {
        if ($rental->status !== 'pending') {
            throw new \Exception('Only pending rentals can be confirmed.');
        }

        $rental->update(['status' => 'active']);
    }

    /**
     * Mark rental as returned (admin action)
     */
    public function markReturned(GearRental $rental): void
    {
        if ($rental->status !== 'active') {
            throw new \Exception('Only active rentals can be marked as returned.');
        }

        $rental->update(['status' => 'returned']);
    }

    /**
     * Cancel a rental request
     */
    public function cancelRental(GearRental $rental): void
    {
        if (!in_array($rental->status, ['pending', 'active'])) {
            throw new \Exception("Cannot cancel a {$rental->status} rental.");
        }

        $rental->update(['status' => 'cancelled']);
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
            'pending_rentals' => GearRental::where('status', 'pending')->count(),
            'active_rentals' => GearRental::where('status', 'active')->count(),
            'returned_rentals' => GearRental::where('status', 'returned')->count(),
            'cancelled_rentals' => GearRental::where('status', 'cancelled')->count(),
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


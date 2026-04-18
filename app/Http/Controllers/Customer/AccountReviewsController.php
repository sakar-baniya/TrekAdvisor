<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\Review;
use App\Models\Trek;
use App\Models\TrekBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Customer Account Reviews Controller: Customer le aafno reviews halne ra edit garne thau.
 *
 * Function:
 * Completed trek ra hotel ma review post garne, aafno purano review update ya delete garne.
 */
class AccountReviewsController extends Controller
{
    /**
     * Submit Trek Review (Store): Customer le ghumera aayepachi trek lai rating dine.
     */
    public function storeTrek(Request $request, Trek $trek): RedirectResponse
    {
        $user = $request->user();

        $eligible = TrekBooking::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('departure', fn ($query) => $query->where('trek_id', $trek->id))
            ->exists();

        abort_unless($eligible, 403);

        $review = Review::query()
            ->where('user_id', $user->id)
            ->where('reviewable_type', Trek::class)
            ->where('reviewable_id', $trek->id)
            ->first();

        if ($review) {
            return back()->with('error', 'You already reviewed this trek.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        Review::create([
            'user_id' => $user->id,
            'reviewable_type' => Trek::class,
            'reviewable_id' => $trek->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Review submitted.');
    }

    /**
     * Submit Hotel Review (Store): Basera aayepachi hotel lai rating dine.
     */
    public function storeHotel(Request $request, Hotel $hotel): RedirectResponse
    {
        $user = $request->user();

        $eligible = HotelBooking::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('hotelRoom', fn ($query) => $query->where('hotel_id', $hotel->id))
            ->exists();

        abort_unless($eligible, 403);

        $review = Review::query()
            ->where('user_id', $user->id)
            ->where('reviewable_type', Hotel::class)
            ->where('reviewable_id', $hotel->id)
            ->first();

        if ($review) {
            return back()->with('error', 'You already reviewed this hotel.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        Review::create([
            'user_id' => $user->id,
            'reviewable_type' => Hotel::class,
            'reviewable_id' => $hotel->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Review submitted.');
    }

    /**
     * Update Review (Update): Purano review edit garne.
     */
    public function update(Request $request, Review $review): RedirectResponse
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $review->update($validated);

        return back()->with('success', 'Review updated.');
    }

    /**
     * Delete Review (Destroy): Aafno review delete garne.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $this->authorize('delete', $review);

        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}




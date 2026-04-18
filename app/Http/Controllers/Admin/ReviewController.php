<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin Review Controller: Customer le deko reviews herne ra filter garne thau.
 *
 * Function:
 * Reviews display garne, flagged (naramro/spam) reviews flag garne ya delete garne.
 */
class ReviewController extends Controller
{
    /**
     * Review List (Index): Sabai reviews dekhaune.
     */
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();
        $rating = $request->string('rating')->toString();

        $reviews = Review::query()
            ->with(['user', 'reviewable'])
            ->when($type !== '', fn ($query) => $query->where('reviewable_type', $this->resolveReviewType($type)))
            ->when($rating !== '', fn ($query) => $query->where('rating', $rating))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.reviews.review-list', [
            'reviews' => $reviews,
            'type' => $type,
            'rating' => $rating,
        ]);
    }

    /**
     * Review Details (Show): Euta review ko pura detail herne.
     */
    public function show(Review $review): View
    {
        $review->load(['user', 'reviewable']);

        return view('admin.reviews.review-details', compact('review'));
    }

    /**
     * Reply to Review: Admin le customer ko review ma response dine.
     */
    public function reply(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'admin_reply' => ['required', 'string', 'max:1000'],
        ]);

        $review->update([
            'admin_reply' => $validated['admin_reply'],
            'admin_replied_at' => now(),
        ]);

        return back()->with('success', 'Reply saved successfully.');
    }

    /**
     * Delete Review (Destroy): Database bata review parmanently delete garne.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }

    /**
     * Resolve Type (Helper): Review trek ko ho ki hotel ko chhutyaune.
     */
    protected function resolveReviewType(string $type): ?string
    {
        return match ($type) {
            'trek' => 'App\\Models\\Trek',
            'hotel' => 'App\\Models\\Hotel',
            default => null,
        };
    }
}





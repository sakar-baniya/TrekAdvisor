<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Yo ReviewController controller le review controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class ReviewController extends Controller
{
    /**
     * Yo function le index ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function index(Request $request): View
    {
        return $this->reviewListView($request, false);
    }

    /**
     * Yo function le flagged ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function flagged(Request $request): View
    {
        return $this->reviewListView($request, true);
    }

    /**
     * Yo function le show ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function show(Review $review): View
    {
        $review->load(['user', 'reviewable']);

        return view('admin.reviews.review-details', compact('review'));
    }

    /**
     * Yo function le toggle flag ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function toggleFlag(Review $review): RedirectResponse
    {
        $review->update([
            'is_flagged' => ! $review->is_flagged,
            'flagged_at' => ! $review->is_flagged ? now() : null,
        ]);

        return back()->with('success', $review->is_flagged ? 'Review flagged.' : 'Review unflagged.');
    }

    /**
     * Yo function le destroy ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }

    /**
     * Yo function le review list view ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    protected function reviewListView(Request $request, bool $flaggedOnly): View
    {
        $type = $request->string('type')->toString();
        $rating = $request->string('rating')->toString();

        $reviews = Review::query()
            ->with(['user', 'reviewable'])
            ->when($flaggedOnly, fn ($query) => $query->where('is_flagged', true))
            ->when($type !== '', fn ($query) => $query->where('reviewable_type', $this->resolveReviewType($type)))
            ->when($rating !== '', fn ($query) => $query->where('rating', $rating))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.reviews.review-list', [
            'reviews' => $reviews,
            'type' => $type,
            'rating' => $rating,
            'flaggedOnly' => $flaggedOnly,
        ]);
    }

    /**
     * Yo function le resolve review type ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
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





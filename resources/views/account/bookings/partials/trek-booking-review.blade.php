<div class="bg-white rounded-2xl border border-slate-200 p-6 animate-fadeIn">
    @if ($booking->status === 'completed')
        <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
            <i class="fas fa-star w-5 h-5 text-slate-400 text-center"></i>
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Experience Review</h2>
                <p class="text-sm text-slate-500">How was your trek? Help other explorers by sharing your story.</p>
            </div>
        </div>

        @if ($review)
            <div class="space-y-6">
                <x-reviews.form :action="route('account.reviews.update', $review)" method="PATCH" :rating="$review->rating" :comment="$review->comment" submit-label="Update My Review" />
                
                <form method="POST" action="{{ route('account.reviews.destroy', $review) }}" class="pt-4 border-t border-slate-100 text-right">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-medium text-slate-400 hover:text-red-600 transition-colors" onclick="return confirm('Permanentely delete your review?')">
                        Delete Review
                    </button>
                </form>
            </div>
        @else
            @if ($booking->departure?->trek)
                <x-reviews.form :action="route('account.reviews.treks.store', $booking->departure->trek)" submit-label="Submit Trek Review" />
            @endif
        @endif
    @else
        <div class="flex items-center gap-3 text-slate-400">
            <i class="fas fa-lock w-5 h-5 text-center opacity-50"></i>
            <p class="text-sm text-slate-500 italic">Review will be available once the trek is completed.</p>
        </div>
    @endif
</div>

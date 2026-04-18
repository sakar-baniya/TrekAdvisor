<div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm animate-fadeIn">
    @if ($booking->status === 'completed')
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-lg">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <h2 class="text-base font-semibold text-slate-900">Experience Review</h2>
                <p class="text-sm text-slate-500">How was your trek? Help other explorers by sharing your story.</p>
            </div>
        </div>

        @if ($review)
            <div class="space-y-6">
                <x-reviews.form :action="route('account.reviews.update', $review)" method="PATCH" :rating="$review->rating" :comment="$review->comment" submit-label="Update My Review" />
                
                <form method="POST" action="{{ route('account.reviews.destroy', $review) }}" class="pt-4 border-t border-slate-50 text-right">
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
        <div class="flex items-center gap-4 text-slate-400">
            <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center">
                <i class="fas fa-lock text-xs opacity-50"></i>
            </div>
            <p class="text-xs text-slate-500 italic">Review will be available once the trek is completed.</p>
        </div>
    @endif
</div>

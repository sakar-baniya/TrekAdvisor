@section('page-title', 'Review Moderation')
@section('page-subtitle', 'Review ID: ' . $review->id)
@section('page-back', route('admin.reviews.index'))

<x-layouts.dashboard>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Review Card -->
        <article class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-8 md:p-10 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">Public Feedback</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Comment and rating as shown on platform</p>
                </div>
                <div class="flex text-amber-400 text-sm">
                    @foreach(range(1, 5) as $i)
                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                    @endforeach
                </div>
            </div>

            <div class="p-8 md:p-10 space-y-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Verified Reviewer</span>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-[10px]">
                                {{ strtoupper(substr($review->user?->name ?? 'G', 0, 1)) }}
                            </div>
                            <p class="text-sm font-semibold text-slate-900">{{ $review->user?->name ?? 'Guest' }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Platform Target</span>
                        <p class="text-sm font-semibold text-slate-900">{{ $review->reviewable?->title ?? $review->reviewable?->name ?? 'Deleted Item' }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Content Type</span>
                        <p class="text-xs font-bold text-slate-600 uppercase tracking-wider bg-slate-50 px-2 py-0.5 rounded border border-slate-100 inline-block">
                            {{ class_basename($review->reviewable_type) }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Timestamp</span>
                        <p class="text-sm font-semibold text-slate-900 italic opacity-60">{{ $review->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100 relative">
                    <i class="fas fa-quote-left absolute -top-4 left-6 text-2xl text-slate-200"></i>
                    <p class="text-sm text-slate-700 leading-relaxed font-medium italic">"{{ $review->comment ?: 'No written comment left with this rating.' }}"</p>
                </div>
            </div>
        </article>

        <!-- Sidebar Stack -->
        <aside class="space-y-8">
            <section class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Moderation</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Management Controls</p>
                </div>

                <div class="space-y-4">
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Permanently delete this review from the platform?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-3 px-6 py-3.5 bg-red-50 text-red-600 border-red-100 text-[10px] font-bold uppercase tracking-[0.2em] rounded-2xl hover:bg-red-600 hover:text-white transition-all border shadow-sm">
                            <i class="fas fa-trash"></i>
                            <span>Delete Review</span>
                        </button>
                    </form>
                </div>
            </section>

            <div class="p-8 rounded-[2rem] bg-slate-900 text-white shadow-xl shadow-slate-900/10">
                <i class="fas fa-info-circle text-white/50 mb-4 text-xl"></i>
                <p class="text-[10px] font-bold uppercase tracking-widest text-white/70 mb-2">Policy Note</p>
                <p class="text-xs font-semibold leading-relaxed">Deletion is permanent across all user-facing views. Only delete reviews that violate platform community standards or are confirmed spam.</p>
            </div>
        </aside>
    </div>
</x-layouts.dashboard>


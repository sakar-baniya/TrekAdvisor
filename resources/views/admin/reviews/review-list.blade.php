@section('page-title', 'All Reviews')
@section('page-subtitle', 'Monitor and moderate platform reviews.')

<x-layouts.dashboard>
    <div class="space-y-6">




    <!-- Filters -->
    <section class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mb-10">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <select name="type" class="bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all appearance-none cursor-pointer">
                <option value="">All Categories</option>
                <option value="trek" @selected($type === 'trek')>Treks</option>
                <option value="hotel" @selected($type === 'hotel')>Hotels</option>
            </select>

            <select name="rating" class="bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all appearance-none cursor-pointer">
                <option value="">All Ratings</option>
                @foreach (range(5, 1) as $star)
                    <option value="{{ $star }}" @selected((string) $rating === (string) $star)>{{ $star }} Stars</option>
                @endforeach
            </select>

            <div></div> <!-- Empty column to maintain grid if needed, or just leave it col-span-2 -->

            <button type="submit" class="bg-slate-900 text-white rounded-lg px-4 py-3 text-sm font-semibold hover:bg-slate-800 transition-all shadow-sm">Apply Filters</button>
        </form>
    </section>

    <!-- Review Cards -->
    <div class="space-y-4 mb-10">
        @forelse ($reviews as $review)
            <div x-data="{ replying: false }" class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-slate-300 transition-all group">
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="flex text-amber-400 text-xs">
                                    @foreach(range(1, 5) as $i)
                                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endforeach
                                </div>
                                <span class="text-xs font-medium text-slate-500">
                                    {{ $review->user?->name ?? 'Guest' }} on {{ $review->reviewable?->title ?? $review->reviewable?->name ?? 'Deleted Item' }}
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 italic mb-4">"{{ $review->comment }}"</p>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <span class="text-xs text-slate-400">
                                <i class="far fa-clock mr-1"></i> {{ $review->created_at->diffForHumans() }}
                            </span>
                            <span class="text-xs text-slate-400 bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100">
                                {{ class_basename($review->reviewable_type) }}
                            </span>
                        </div>

                        <!-- Admin Reply Section -->
                        <div class="pt-4 border-t border-slate-50">
                            @if($review->admin_reply)
                                <div x-show="!replying" class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                            <i class="fas fa-reply text-[8px]"></i> Platform Reply
                                        </span>
                                        <span class="text-[9px] text-slate-400 font-semibold italic">{{ $review->admin_replied_at?->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 font-medium leading-relaxed mb-3">"{{ $review->admin_reply }}"</p>
                                    <button @click="replying = true" class="text-[9px] font-bold text-slate-400 hover:text-slate-900 uppercase tracking-widest transition-colors">
                                        Edit Response
                                    </button>
                                </div>
                            @else
                                <button x-show="!replying" @click="replying = true" class="text-xs font-semibold text-slate-400 hover:text-slate-900 flex items-center gap-2 transition-colors">
                                    <i class="fas fa-reply text-[10px]"></i> Add official response
                                </button>
                            @endif

                            <form x-show="replying" x-cloak method="POST" action="{{ route('admin.reviews.reply', $review) }}" class="space-y-3">
                                @csrf
                                @method('PATCH')
                                <textarea name="admin_reply" rows="2" required class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-slate-900/10 focus:bg-white placeholder:text-slate-400 transition-all" placeholder="Write your professional response here...">{{ $review->admin_reply }}</textarea>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-[9px] font-bold uppercase tracking-widest rounded-lg hover:bg-slate-800 transition-all shadow-sm">Save Reply</button>
                                    <button type="button" @click="replying = false" class="px-4 py-2 bg-white text-slate-400 text-[9px] font-bold uppercase tracking-widest rounded-lg border border-slate-100 hover:bg-slate-50 transition-all">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="flex items-start gap-2 lg:border-l lg:pl-6 border-slate-50 pt-4 lg:pt-0">
                        <a href="{{ route('admin.reviews.show', $review) }}" class="p-2.5 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition-all border border-slate-100 shadow-sm" title="View Details">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 rounded-xl bg-red-50 text-red-400 hover:bg-red-600 hover:text-white transition-all border border-red-100 shadow-sm" title="Delete Review">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-xl border border-slate-200 shadow-sm text-center">
                <i class="fas fa-star text-4xl text-slate-100 mb-4 block"></i>
                <p class="text-xs font-medium text-slate-400">No reviews found matching criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($reviews->hasPages())
        <div class="admin-pagination">{{ $reviews->links() }}</div>
    @endif
    </div>
</x-layouts.dashboard>


<x-layouts.app>
    @php
        $galleryImages = $trek->gallery->pluck('path')->prepend($trek->image)->filter()->unique()->values();
    @endphp

    <!-- Detail Hero -->
    <section class="relative h-[60vh] md:h-[70vh] min-h-[450px] overflow-hidden group">
        <img src="{{ $trek->image }}" alt="{{ $trek->title }}" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

        <div class="absolute inset-0 flex flex-col justify-end pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="max-w-3xl">
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <span class="px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-widest bg-slate-900 text-white shadow-lg">
                            {{ $trek->difficulty }}
                        </span>
                        <div class="flex items-center gap-2 text-amber-400 font-bold">
                            <i class="fas fa-star"></i>
                            <span class="text-white">{{ $avgRating ? number_format($avgRating, 1) : 'New' }}</span>
                            <span class="text-slate-400 font-medium text-sm">({{ $reviewCount }} reviews)</span>
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold text-white tracking-tight mb-8">{{ $trek->title }}</h1>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 py-6 border-t border-white/10">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-clock text-slate-400 text-xl"></i>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Duration</span>
                                <strong class="text-white text-lg">{{ $trek->duration_days ?? $trek->itineraries->count() }} Days</strong>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-mountain text-slate-400 text-xl"></i>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Max Alt.</span>
                                <strong class="text-white text-lg">{{ $trek->max_altitude ? number_format($trek->max_altitude) . 'm' : 'High Pacing' }}</strong>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-hiking text-slate-400 text-xl"></i>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type</span>
                                <strong class="text-white text-lg">Guided Trek</strong>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-users text-slate-400 text-xl"></i>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Group size</span>
                                <strong class="text-white text-lg">2 - 12</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ tab: 'overview' }">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Navigation Tabs -->
                <div class="flex items-center gap-8 border-b border-slate-200 mb-10 overflow-x-auto whitespace-nowrap scrollbar-hide">
                    @foreach(['overview' => 'Overview', 'itinerary' => 'Itinerary', 'reviews' => 'Reviews'] as $id => $label)
                        <button 
                            @click="tab = '{{ $id }}'"
                            class="pb-4 text-sm font-bold tracking-wider uppercase transition-all relative"
                            :class="tab === '{{ $id }}' ? 'text-slate-900 border-b-4 border-slate-900' : 'text-slate-400 hover:text-slate-600'"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <!-- Tab: Overview -->
                <div x-cloak x-show="tab === 'overview'" class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <article>
                        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-6">Trip Overview</h2>
                        
                        @if ($galleryImages->count() > 1)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                                @foreach ($galleryImages->take(6) as $image)
                                    <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 shadow-sm border border-slate-100">
                                        <img src="{{ $image }}" alt="{{ $trek->title }} gallery" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="text-lg text-slate-600 leading-relaxed space-y-4">
                            {!! nl2br(e($trek->description)) !!}
                        </div>
                    </article>

                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100">
                        <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center gap-2">
                             <i class="fas fa-sparkles text-slate-900"></i> What makes this trek stand out
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-start gap-4 p-4 bg-white rounded-2xl shadow-sm border border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-700 shrink-0">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <strong class="block text-slate-900 mb-1">Expert-led route</strong>
                                    <span class="text-sm text-slate-500">Well-structured pacing for a smoother trekking experience.</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-white rounded-2xl shadow-sm border border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-700 shrink-0">
                                    <i class="fas fa-compass"></i>
                                </div>
                                <div>
                                    <strong class="block text-slate-900 mb-1">Adventure planning</strong>
                                    <span class="text-sm text-slate-500">Departure selection, group pricing, and booking flow in one place.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Itinerary -->
                <div x-cloak x-show="tab === 'itinerary'" class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-8">Day by Day Itinerary</h2>
                    <div class="relative space-y-8 before:absolute before:inset-y-0 before:left-8 before:border-l-2 before:border-slate-100">
                        @foreach($trek->itineraries as $itinerary)
                            <div class="relative pl-20 group">
                                <div class="absolute left-0 top-0 w-16 h-16 bg-white border-4 border-slate-50 rounded-full flex items-center justify-center text-slate-900 font-semibold shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                                    D{{ $itinerary->day_number }}
                                </div>
                                <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm group-hover:shadow-md transition-shadow">
                                    <h3 class="text-xl font-extrabold text-slate-900 mb-3 tracking-tight">{{ $itinerary->title }}</h3>
                                    <p class="text-slate-600 leading-relaxed font-medium">{{ $itinerary->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tab: Reviews -->
                <div x-cloak x-show="tab === 'reviews'" class="space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Customer Reviews</h2>
                        <div class="flex items-center gap-4 py-2 px-6 bg-slate-900 rounded-full text-white shadow-xl">
                            <span class="text-2xl font-semibold">{{ $avgRating ?? '5.0' }}</span>
                            <div class="h-8 w-px bg-white/20"></div>
                            <span class="text-sm font-bold text-slate-300 uppercase tracking-widest">{{ $reviewCount }} Total</span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-700 text-sm font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($canReview && !$userReview)
                        <div class="p-8 bg-white/80 backdrop-blur-sm rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl shadow-lg shadow-indigo-200">
                                    <i class="fas fa-pen-nib"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 leading-tight">Share Your Journey</h3>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Tell others about your experience</p>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                                    <ul class="list-disc list-inside text-sm text-red-600">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('account.reviews.treks.store', $trek) }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 px-1">How would you rate it?</label>
                                    <div class="flex items-center gap-2" x-data="{ rating: 5, hover: 0 }">
                                        <input type="hidden" name="rating" :value="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <button type="button" 
                                                @click="rating = {{ $i }}" 
                                                @mouseenter="hover = {{ $i }}" 
                                                @mouseleave="hover = 0"
                                                class="text-3xl focus:outline-none transition-transform active:scale-90"
                                                :class="(hover || rating) >= {{ $i }} ? 'text-amber-400' : 'text-slate-200'">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        @endfor
                                        <span class="ml-4 text-sm font-bold text-slate-600" x-text="rating + '/5 Stars'"></span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 px-1">Describe your experience</label>
                                    <textarea name="comment" rows="4" 
                                        class="w-full bg-slate-50 border-none rounded-[1.5rem] px-6 py-4 text-sm font-medium text-slate-800 placeholder:text-slate-400 focus:ring-4 focus:ring-indigo-500/10 transition-all"
                                        placeholder="What was the highlight of your trek? Any tips for future travelers?"></textarea>
                                </div>

                                <button type="submit" 
                                    class="w-full bg-slate-900 text-white font-bold py-5 rounded-2xl shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all flex items-center justify-center gap-3 group">
                                    Post My Review
                                    <i class="fas fa-paper-plane text-xs text-slate-400 group-hover:text-white group-hover:translate-x-1 transition-all"></i>
                                </button>
                            </form>
                        </div>
                    @elseif($userReview)
                        <div class="p-6 bg-indigo-50/50 border border-indigo-100 rounded-[1.5rem] flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Your review is live!</h4>
                                <p class="text-xs text-slate-500 font-medium">Thank you for sharing your journey with the community.</p>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6">
                        @forelse($reviews as $review)
                            <article class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                                <!-- Background Quote -->
                                <i class="fas fa-quote-right absolute -right-4 top-4 text-9xl text-slate-50 -z-0"></i>
                                
                                <div class="relative z-10">
                                    <div class="flex items-center gap-2 text-amber-500 mb-4">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="{{ $i < $review->rating ? 'fas' : 'far' }} fa-star text-sm"></i>
                                        @endfor
                                    </div>
                                    <p class="text-lg font-bold text-slate-800 mb-6 italic leading-relaxed">"{{ $review->comment }}"</p>
                                    <div class="flex items-center gap-3 pt-6 border-t border-slate-50">
                                        <div class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center text-white font-semibold text-sm uppercase">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong class="block text-slate-900 text-sm">{{ $review->user->name }}</strong>
                                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    @if($review->admin_reply)
                                        <div class="mt-6 ml-4 md:ml-10 p-6 bg-slate-50 rounded-2xl border-l-4 border-slate-200 relative group/reply hover:border-slate-900 transition-colors">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-slate-900 flex items-center justify-center text-[8px] text-white">
                                                        <i class="fas fa-shield-alt"></i>
                                                    </div>
                                                    <span class="text-[10px] font-extrabold text-slate-900 uppercase tracking-widest italic">Official Response</span>
                                                </div>
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $review->admin_replied_at?->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-slate-600 leading-relaxed font-medium italic">"{{ $review->admin_reply }}"</p>
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="py-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-bold italic">No reviews yet for this adventure.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-8">
                <div class="bg-white rounded-xl shadow-lg shadow-slate-900/5 border border-slate-100 overflow-hidden sticky top-24">
                    <div class="bg-slate-900 p-10 text-white text-center">
                        <span class="block text-slate-400 text-[10px] font-semibold uppercase tracking-widest mb-2">Starting from</span>
                        <div class="flex items-baseline justify-center gap-1">
                             <span class="text-sm font-bold opacity-60">NPR</span>
                             <strong class="text-4xl font-semibold tracking-tight">{{ number_format($trek->base_price, 0) }}</strong>
                        </div>
                        <span class="block text-slate-400 text-xs font-semibold mt-1">per person</span>
                    </div>

                    <div class="p-8 space-y-8">
                        <div>
                            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4">Group Discounts</h4>
                            <div class="space-y-2">
                                @foreach(['3-5 people' => '5% off', '6-9 people' => '10% off', '10+ people' => '15% off'] as $group => $off)
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 text-sm font-bold text-slate-700">
                                        <span>{{ $group }}</span>
                                        <span class="text-slate-900">{{ $off }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4">Upcoming Departures</h4>
                            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                                @forelse($trek->departures as $departure)
                                    <div class="p-5 rounded-2xl border border-slate-100 bg-white hover:border-slate-300 transition-colors shadow-sm">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <strong class="block text-slate-900">{{ $departure->start_date->format('M d') }} - {{ $departure->end_date->format('M d') }}</strong>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                                    {{ $departure->capacity - $departure->booked_seats }} Seats Left
                                                </span>
                                            </div>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold uppercase tracking-tighter {{ ($departure->capacity - $departure->booked_seats) > 4 ? 'bg-blue-50 text-blue-700' : 'bg-red-100 text-red-800' }}">
                                                {{ $departure->status }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                            <span class="font-semibold text-slate-900">NPR {{ number_format($departure->price, 0) }}</span>
                                            <a href="{{ route('bookings.create', $departure->id) }}" class="inline-flex justify-center items-center px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-lg hover:bg-slate-800 transition-colors">
                                                Book
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-slate-400 italic text-sm text-center py-4">No departures scheduled.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.app>


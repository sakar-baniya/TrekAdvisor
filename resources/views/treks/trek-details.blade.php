<x-app-layout>
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
                        <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest bg-emerald-500 text-white shadow-xl">
                            {{ $trek->difficulty }}
                        </span>
                        <div class="flex items-center gap-2 text-amber-400 font-bold">
                            <i class="fas fa-star"></i>
                            <span class="text-white">{{ $avgRating ? number_format($avgRating, 1) : 'New' }}</span>
                            <span class="text-slate-400 font-medium text-sm">({{ $reviewCount }} reviews)</span>
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight mb-8">{{ $trek->title }}</h1>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 py-6 border-t border-white/10">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-clock text-emerald-400 text-xl"></i>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Duration</span>
                                <strong class="text-white text-lg">{{ $trek->duration_days ?? $trek->itineraries->count() }} Days</strong>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-mountain text-emerald-400 text-xl"></i>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Max Alt.</span>
                                <strong class="text-white text-lg">{{ $trek->max_altitude ? number_format($trek->max_altitude) . 'm' : 'High Pacing' }}</strong>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-hiking text-emerald-400 text-xl"></i>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type</span>
                                <strong class="text-white text-lg">Guided Trek</strong>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-users text-emerald-400 text-xl"></i>
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
                             <i class="fas fa-sparkles text-emerald-500"></i> What makes this trek stand out
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-start gap-4 p-4 bg-white rounded-2xl shadow-sm border border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <strong class="block text-slate-900 mb-1">Expert-led route</strong>
                                    <span class="text-sm text-slate-500">Well-structured pacing for a smoother trekking experience.</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-white rounded-2xl shadow-sm border border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
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
                                <div class="absolute left-0 top-0 w-16 h-16 bg-white border-4 border-slate-50 rounded-full flex items-center justify-center text-slate-900 font-black shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
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
                            <span class="text-2xl font-black">{{ $avgRating ?? '5.0' }}</span>
                            <div class="h-8 w-px bg-white/20"></div>
                            <span class="text-sm font-bold text-slate-300 uppercase tracking-widest">{{ $reviewCount }} Total</span>
                        </div>
                    </div>

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
                                        <div class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center text-white font-black text-sm uppercase">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong class="block text-slate-900 text-sm">{{ $review->user->name }}</strong>
                                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
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
                <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-900/10 border border-slate-100 overflow-hidden sticky top-24">
                    <div class="bg-slate-900 p-10 text-white text-center">
                        <span class="block text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Starting from</span>
                        <div class="flex items-baseline justify-center gap-1">
                             <span class="text-sm font-bold opacity-60">NPR</span>
                             <strong class="text-4xl font-black tracking-tight">{{ number_format($trek->base_price, 0) }}</strong>
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
                                        <span class="text-emerald-600">{{ $off }}</span>
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
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-tighter {{ ($departure->capacity - $departure->booked_seats) > 4 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $departure->status }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                            <span class="font-black text-slate-900">NPR {{ number_format($departure->price, 0) }}</span>
                                            <a href="{{ route('bookings.create', $departure->id) }}" class="inline-flex justify-center items-center px-4 py-2 bg-slate-900 text-white text-xs font-black rounded-lg hover:bg-slate-800 transition-colors">
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
</x-app-layout>

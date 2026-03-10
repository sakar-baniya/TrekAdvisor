<x-app-layout>
    <div class="font-sans bg-slate-50 min-h-screen">
        <!-- Hero Section: The High Altitude Welcome -->
        <div class="relative h-[65vh] flex items-end pb-24 text-white overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="{{ $trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b' }}" alt="{{ $trek->title }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 w-full">
                <div class="flex flex-col items-start">
                    @php
                        $difficultyColors = [
                            'easy' => 'bg-emerald-500/90',
                            'moderate' => 'bg-blue-500/90',
                            'difficult' => 'bg-amber-500/90',
                            'extreme' => 'bg-rose-500/90'
                        ][strtolower($trek->difficulty)] ?? 'bg-slate-500/90';
                    @endphp
                    <div class="{{ $difficultyColors }} px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em] backdrop-blur-md shadow-2xl mb-6">
                        {{ $trek->difficulty }} EXPEDITION
                    </div>
                    
                    <h1 class="text-6xl md:text-8xl font-black mb-8 tracking-tighter leading-none drop-shadow-2xl">
                        {{ $trek->title }}
                    </h1>
                    
                    <div class="flex flex-wrap gap-6 text-sm font-black uppercase tracking-widest bg-white/10 backdrop-blur-xl px-8 py-5 rounded-[2rem] border border-white/20">
                        <span class="flex items-center"><i class="fas fa-calendar-alt mr-3 text-blue-400"></i> {{ $trek->itineraries->count() }} Days</span>
                        <div class="w-px h-4 bg-white/20"></div>
                        <span class="flex items-center"><i class="fas fa-mountain mr-3 text-blue-400"></i> Easy Access</span>
                        <div class="w-px h-4 bg-white/20"></div>
                        <span class="flex items-center"><i class="fas fa-tag mr-3 text-blue-400"></i> ${{ number_format($trek->base_price) }} Starting</span>
                        <div class="w-px h-4 bg-white/20"></div>
                        <span class="flex items-center"><i class="fas fa-star mr-3 text-amber-300"></i> {{ $avgRating ?? 'New' }} ({{ $reviewCount }})</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-20 pb-24 relative z-20 grid grid-cols-1 lg:grid-cols-[1fr_420px] gap-12">
            
            <div class="space-y-12">
                <!-- Description -->
                <section class="bg-white p-12 lg:p-20 rounded-[3.5rem] shadow-2xl shadow-slate-200/50">
                    <h2 class="text-4xl font-black text-slate-900 mb-10 flex items-center before:content-[''] before:w-3 before:h-10 before:bg-blue-600 before:mr-6 before:rounded-full tracking-tight text-[1.4em]">
                        About This Trek
                    </h2>
                    <div class="text-slate-500 line-height-relaxed text-lg font-medium leading-loose space-y-6">
                        {!! nl2br(e($trek->description)) !!}
                    </div>
                </section>

                <!-- Itinerary -->
                <section class="bg-white p-12 lg:p-20 rounded-[3.5rem] shadow-2xl shadow-slate-200/50">
                    <h2 class="text-4xl font-black text-slate-900 mb-12 flex items-center before:content-[''] before:w-3 before:h-10 before:bg-blue-600 before:mr-6 before:rounded-full tracking-tight">
                        Itinerary
                    </h2>
                    <div class="relative border-l-2 border-slate-100 ml-6 py-4 space-y-12">
                        @foreach($trek->itineraries as $itinerary)
                            <div class="relative pl-14">
                                <div class="absolute -left-[1.45rem] top-0 bg-blue-600 text-white px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-200 border-4 border-white">
                                    DAY {{ $itinerary->day_number }}
                                </div>
                                <div class="group">
                                    <h3 class="text-xl font-black text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">
                                        {{ $itinerary->title }}
                                    </h3>
                                    <p class="text-slate-500 font-medium leading-relaxed">
                                        {{ $itinerary->description }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Reviews -->
                <section class="bg-white p-12 lg:p-20 rounded-[3.5rem] shadow-2xl shadow-slate-200/50">
                    <h2 class="text-4xl font-black text-slate-900 mb-12 flex items-center before:content-[''] before:w-3 before:h-10 before:bg-blue-600 before:mr-6 before:rounded-full tracking-tight">
                        Customer Reviews
                    </h2>
                    <div class="mb-10 flex flex-wrap items-center gap-4 rounded-2xl bg-slate-50 px-6 py-4">
                        <div class="text-3xl font-black text-slate-900">{{ $avgRating ?? 'New' }}</div>
                        <div class="text-sm text-slate-600 font-semibold">{{ $reviewCount }} reviews</div>
                    </div>
                    @if($reviews->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($reviews as $review)
                                <div class="bg-slate-50/50 p-10 rounded-[2.5rem] border border-slate-100 flex flex-col">
                                    <div class="flex text-amber-400 mb-6 space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star text-xs"></i>
                                        @endfor
                                    </div>
                                    <p class="text-slate-600 font-medium italic text-lg leading-relaxed mb-8 flex-1">
                                        "{{ $review->comment }}"
                                    </p>
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-black text-sm shadow-lg">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $review->user->name }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                            <i class="fas fa-comment-slash text-4xl text-slate-300 mb-4 block"></i>
                            <p class="text-slate-400 font-black uppercase tracking-widest text-[10px]">No reviews yet.</p>
                        </div>
                    @endif
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-8">
                <div class="sticky top-8 space-y-8">
                    <!-- Booking Dates -->
                    <div class="bg-white p-10 rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-50">
                        <h3 class="text-2xl font-black text-slate-900 mb-2 uppercase tracking-tight">Available Dates</h3>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-10">Select a date to start booking</p>
                        
                        <div class="space-y-6">
                            @forelse($trek->departures as $departure)
                                <div class="group p-6 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-xl hover:shadow-slate-100 transition-all border border-transparent hover:border-slate-100">
                                    <div class="flex justify-between items-start mb-6">
                                        <div>
                                            <p class="text-sm font-black text-slate-900">{{ $departure->start_date->format('M d, Y') }}</p>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mt-1">
                                                {{ $departure->capacity - $departure->booked_seats }} SLOTS REMAINING
                                            </p>
                                        </div>
                                        <p class="text-xl font-black text-blue-600">${{ number_format($departure->price) }}</p>
                                    </div>
                                    <div class="mb-4 flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-slate-500">
                                        <span>{{ $departure->start_date->format('M d') }} - {{ $departure->end_date->format('M d, Y') }}</span>
                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                        <span>{{ $departure->status }}</span>
                                    </div>
                                    <a href="{{ route('bookings.create', $departure->id) }}" class="w-full block py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.3em] text-center hover:bg-blue-600 hover:shadow-xl hover:shadow-blue-200 transition-all active:scale-95">
                                        BOOK THIS DATE
                                    </a>
                                </div>
                            @empty
                                <div class="p-8 text-center bg-rose-50 rounded-3xl">
                                    <p class="text-[10px] font-black text-rose-600 uppercase tracking-[0.2em]">No dates available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Trek Information -->
                    <div class="bg-slate-900 text-white p-10 rounded-[3rem] shadow-2xl shadow-slate-900/40 relative overflow-hidden">
                        <div class="absolute -right-8 -top-8 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>
                        <h3 class="text-xl font-black mb-8 uppercase tracking-tight relative z-10">Why Trek With Us?</h3>
                        <ul class="space-y-6 relative z-10">
                            <li class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center border border-emerald-500/20">
                                    <i class="fas fa-shield-alt text-xs"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest">Expert Local Guides</span>
                            </li>
                            <li class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center border border-emerald-500/20">
                                    <i class="fas fa-file-contract text-xs"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest">Permits Included</span>
                            </li>
                            <li class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center border border-emerald-500/20">
                                    <i class="fas fa-hotel text-xs"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest">Comfortable Guesthouses</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>

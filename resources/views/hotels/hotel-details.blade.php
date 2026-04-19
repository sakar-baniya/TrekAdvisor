<x-layouts.app>
    @php
        $galleryImages = $hotel->gallery->pluck('path')->prepend($hotel->image)->filter()->unique()->values();
        
        $backUrl = url()->previous();
        $returnTo = request()->query('return_to');
        if (is_string($returnTo) && $returnTo !== '' && (\Illuminate\Support\Str::startsWith($returnTo, ['/']) || \Illuminate\Support\Str::startsWith($returnTo, url('/')))) {
            $backUrl = $returnTo;
        }
    @endphp

    <!-- Content Header / Back Button -->
    <div class="bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <a href="{{ $backUrl }}" class="inline-flex items-center text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Back to Results
            </a>
        </div>
    </div>

    <!-- Hotel Hero -->
    <section class="relative h-[50vh] min-h-[400px] overflow-hidden group">
        <img src="{{ $hotel->image }}" alt="{{ $hotel->name }}" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent"></div>

        <div class="absolute inset-0 flex flex-col justify-end pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-semibold uppercase tracking-widest bg-white/90 backdrop-blur-sm text-slate-900 shadow-sm">
                        <i class="fas fa-map-marker-alt text-slate-900 mr-1.5"></i> {{ $hotel->location }}
                    </span>
                    <div class="flex items-center gap-2 text-amber-400 font-bold">
                        <i class="fas fa-star text-sm"></i>
                        <span class="text-white text-sm">{{ $hotel->reviews_avg_rating ? number_format($hotel->reviews_avg_rating, 1) : 'New' }}</span>
                        <span class="text-slate-400 font-medium text-sm">({{ $hotel->reviews_count }} reviews)</span>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-semibold text-white tracking-tight">{{ $hotel->name }}</h1>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-12">
                <!-- Gallery Grid -->
                @if ($galleryImages->isNotEmpty())
                    <section>
                        <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-6 px-1">Property Gallery</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($galleryImages->take(6) as $image)
                                <div class="aspect-[4/3] rounded-3xl overflow-hidden bg-slate-100 shadow-sm border border-slate-100 group">
                                    <img src="{{ $image }}" alt="{{ $hotel->name }} photo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Overview -->
                <section>
                    <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mb-6">Hotel Overview</h2>
                    <div class="text-slate-600 leading-relaxed space-y-4 text-lg">
                        {!! nl2br(e($hotel->description)) !!}
                    </div>
                </section>

                <!-- Room Types -->
                <section>
                    <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mb-8">Available Room Categories</h2>
                    <div class="space-y-6">
                        @forelse ($hotel->rooms as $room)
                            <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row md:items-center gap-6 group">
                                <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                                    <i class="fas fa-bed text-xl"></i>
                                </div>
                                <div class="flex-grow">
                                    <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $room->room_type }}</h3>
                                    <div class="flex items-center gap-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                        <span><i class="fas fa-users mr-1"></i> Standard Capacity</span>
                                        <span><i class="fas fa-check text-slate-900 mr-1"></i> {{ $room->total_rooms }} Available</span>
                                    </div>
                                </div>
                                <div class="text-left md:text-right border-t md:border-t-0 md:border-l border-slate-100 pt-6 md:pt-0 md:pl-8">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 md:justify-end">Price per Night</span>
                                    <div class="flex items-baseline gap-1 md:justify-end">
                                        <span class="text-xs font-bold text-slate-500">NPR</span>
                                        <strong class="text-2xl font-semibold text-slate-900 tracking-tight">{{ number_format($room->price_per_night, 0) }}</strong>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-bold italic">No room categories listed yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Property Reviews -->
                <section id="reviews" class="pt-12 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="text-2xl font-semibold text-slate-900 tracking-tight">Guest Experiences</h2>
                        <div class="flex items-center gap-3 px-4 py-1.5 bg-slate-900 rounded-full text-white shadow-lg">
                            <span class="text-lg font-bold">{{ $hotel->reviews_avg_rating ? number_format($hotel->reviews_avg_rating, 1) : '5.0' }}</span>
                            <div class="h-4 w-px bg-white/20"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $hotel->reviews_count }} stays reviewed</span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="p-4 mb-6 bg-rose-50 border border-rose-100 rounded-2xl text-rose-700 text-sm font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($canReview && !$userReview)
                        <div class="p-8 mb-10 bg-white/80 backdrop-blur-sm rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white text-xl shadow-lg shadow-slate-200">
                                    <i class="fas fa-pen-fancy"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 leading-tight">Rate Your Stay</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Share your feedback with future guests</p>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-[1.5rem]">
                                    <ul class="list-disc list-inside text-sm text-red-600 font-medium">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('account.reviews.hotels.store', $hotel) }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 px-1">Quality Score</label>
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
                                        <span class="ml-4 text-sm font-bold text-slate-600" x-text="rating + ' Stars'"></span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 px-1">Review Details</label>
                                    <textarea name="comment" rows="4" 
                                        class="w-full bg-slate-50 border-none rounded-[1.5rem] px-6 py-4 text-sm font-medium text-slate-800 placeholder:text-slate-400 focus:ring-4 focus:ring-slate-900/5 transition-all"
                                        placeholder="How was the hospitality? Was the room as described?"></textarea>
                                </div>

                                <button type="submit" 
                                    class="w-full bg-slate-900 text-white font-bold py-5 rounded-2xl shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all flex items-center justify-center gap-3 group">
                                    Submit Review
                                    <i class="fas fa-arrow-right text-[10px] text-slate-400 group-hover:text-white group-hover:translate-x-1 transition-all"></i>
                                </button>
                            </form>
                        </div>
                    @elseif($userReview)
                        <div class="p-6 mb-10 bg-slate-50 border border-slate-200 rounded-[1.5rem] flex items-center gap-4">
                            <div class="w-10 h-10 bg-white shadow-sm border border-slate-100 rounded-full flex items-center justify-center text-emerald-500">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Your review is live!</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Thank you for your feedback.</p>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6">
                        @forelse ($hotel->reviews as $review)
                            <article class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
                                <i class="fas fa-quote-right absolute -right-4 top-4 text-9xl text-slate-50 opacity-10 transition-opacity group-hover:opacity-20"></i>
                                
                                <div class="relative z-10">
                                    <div class="flex items-center gap-1.5 text-amber-500 mb-4">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-[10px]"></i>
                                        @endfor
                                    </div>
                                    
                                    <p class="text-lg font-bold text-slate-800 mb-6 italic leading-relaxed">"{{ $review->comment }}"</p>
                                    
                                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs text-uppercase">
                                                {{ substr($review->user?->name ?? 'G', 0, 1) }}
                                            </div>
                                            <div>
                                                <strong class="block text-slate-900 text-sm italic">{{ $review->user?->name ?? 'Global Traveler' }}</strong>
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($review->admin_reply)
                                        <div class="mt-8 ml-4 md:ml-10 p-6 bg-slate-50 rounded-2xl border-l-4 border-slate-200 relative group/reply hover:border-slate-900 transition-colors">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-slate-900 flex items-center justify-center text-[8px] text-white">
                                                        <i class="fas fa-shield-alt"></i>
                                                    </div>
                                                    <span class="text-[10px] font-extrabold text-slate-900 uppercase tracking-widest italic">Host Response</span>
                                                </div>
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $review->admin_replied_at?->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"{{ $review->admin_reply }}"</p>
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="py-16 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-bold italic">This property hasn't been reviewed yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Booking Sidebar -->
            <aside class="space-y-8">
                <div class="bg-white rounded-xl shadow-lg shadow-slate-900/5 border border-slate-100 overflow-hidden sticky top-24">
                    <div class="bg-slate-900 p-10 text-white text-center">
                        <span class="block text-slate-400 text-[10px] font-semibold uppercase tracking-widest mb-2">Starting from</span>
                        <div class="flex items-baseline justify-center gap-1">
                             <span class="text-sm font-bold opacity-60">NPR</span>
                             <strong class="text-4xl font-semibold tracking-tight">{{ number_format($hotel->rooms->min('price_per_night') ?? 0, 0) }}</strong>
                        </div>
                        <span class="block text-slate-400 text-xs font-semibold mt-1">per night</span>
                    </div>

                    <div class="p-8 space-y-8">
                        @if (filled($hotel->booking_policy))
                            <div>
                                <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-3">Booking Policy</h4>
                                <div class="text-sm text-slate-600 bg-slate-50 p-4 rounded-2xl leading-relaxed">
                                    {!! nl2br(e($hotel->booking_policy)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Booking Form -->
                        @if (auth()->check() && auth()->user()->role === 'customer')
                            <form method="POST" action="{{ route('customer.hotel-bookings.store', $hotel) }}" class="space-y-5">
                                @csrf

                                @if (session('error'))
                                    <div class="p-4 bg-red-50 text-red-600 text-xs font-bold rounded-xl border border-red-100">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div>
                                    <x-input-label for="hotel_room_id" :value="__('Select Room Type')" class="mb-2" />
                                    <select name="hotel_room_id" id="hotel_room_id" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 px-4 py-3 bg-slate-50 focus:bg-white transition-colors text-sm font-bold text-slate-700">
                                        <option value="">Choose a room...</option>
                                        @foreach ($hotel->rooms as $room)
                                            <option value="{{ $room->id }}" @selected((string) old('hotel_room_id') === (string) $room->id)>
                                                {{ $room->room_type }} ({{ number_format($room->price_per_night, 0) }}/night)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hotel_room_id')
                                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="check_in" :value="__('Check-in')" class="mb-2" />
                                        <x-text-input type="date" name="check_in" id="check_in" required value="{{ old('check_in') }}" min="{{ now()->toDateString() }}" class="text-sm" />
                                        @error('check_in')
                                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <x-input-label for="check_out" :value="__('Check-out')" class="mb-2" />
                                        <x-text-input type="date" name="check_out" id="check_out" required value="{{ old('check_out') }}" min="{{ now()->addDay()->toDateString() }}" class="text-sm" />
                                        @error('check_out')
                                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="num_rooms" :value="__('Number of Rooms')" class="mb-2" />
                                    <x-text-input type="number" name="num_rooms" id="num_rooms" min="1" max="10" required value="{{ old('num_rooms', 1) }}" />
                                    @error('num_rooms')
                                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full inline-flex justify-center items-center py-4 px-6 bg-slate-900 border border-transparent rounded-2xl shadow-xl text-sm font-semibold text-white hover:bg-slate-800 focus:outline-none transition-all duration-200">
                                        Request Booking
                                    </button>
                                    <p class="text-[10px] text-slate-400 font-bold text-center mt-4 px-2 uppercase tracking-tighter">No payment required now. Property will confirm shortly.</p>
                                </div>
                            </form>
                        @elseif (auth()->check())
                            <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl text-sm text-slate-500 font-bold text-center italic">
                                Submit requests from a customer account.
                            </div>
                        @else
                            <div class="p-8 text-center bg-slate-50 rounded-3xl border border-slate-100">
                                <p class="text-sm text-slate-500 font-bold mb-6">Sign in to request a stay</p>
                                <a href="{{ route('login') }}" class="inline-flex py-3 px-8 bg-white border border-slate-300 rounded-xl font-semibold text-slate-900 hover:bg-slate-50 transition-colors shadow-sm text-xs uppercase tracking-widest">
                                    Sign In
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.app>


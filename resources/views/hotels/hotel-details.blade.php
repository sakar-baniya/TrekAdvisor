<x-app-layout>
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
                                    <x-input-error :messages="$errors->get('hotel_room_id')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="check_in" :value="__('Check-in')" class="mb-2" />
                                        <x-text-input type="date" name="check_in" id="check_in" required value="{{ old('check_in') }}" min="{{ now()->toDateString() }}" class="text-sm" />
                                        <x-input-error :messages="$errors->get('check_in')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="check_out" :value="__('Check-out')" class="mb-2" />
                                        <x-text-input type="date" name="check_out" id="check_out" required value="{{ old('check_out') }}" min="{{ now()->addDay()->toDateString() }}" class="text-sm" />
                                        <x-input-error :messages="$errors->get('check_out')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="num_rooms" :value="__('Number of Rooms')" class="mb-2" />
                                    <x-text-input type="number" name="num_rooms" id="num_rooms" min="1" max="10" required value="{{ old('num_rooms', 1) }}" />
                                    <x-input-error :messages="$errors->get('num_rooms')" class="mt-2" />
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
</x-app-layout>

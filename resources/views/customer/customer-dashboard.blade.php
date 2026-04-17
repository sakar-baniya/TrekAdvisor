<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <!-- Dashboard Header -->
        <div class="bg-white border-b border-slate-200 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-emerald-600 font-bold uppercase tracking-widest text-xs mb-2">Customer Portal</p>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-slate-500 mt-2 font-medium">Manage your Himalayan adventures and hotel stays in one place.</p>
                </div>
                <!-- Mini Profile Quick Info -->
                <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <div class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white font-black">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-900 line-clamp-1">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">{{ auth()->user()->role }} Account</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                @foreach([
                    ['label' => 'Trek Bookings', 'count' => $stats['trek_bookings'], 'icon' => 'fa-mountain', 'color' => 'bg-emerald-50 text-emerald-600'],
                    ['label' => 'Hotel Bookings', 'count' => $stats['hotel_bookings'], 'icon' => 'fa-hotel', 'color' => 'bg-blue-50 text-blue-600'],
                    ['label' => 'Upcoming Trips', 'count' => $stats['upcoming_trips'], 'icon' => 'fa-calendar-check', 'color' => 'bg-amber-50 text-amber-600']
                ] as $stat)
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl {{ $stat['color'] }} flex items-center justify-center text-xl">
                            <i class="fas {{ $stat['icon'] }}"></i>
                        </div>
                        <div>
                            <strong class="block text-3xl font-black text-slate-900 tracking-tight">{{ $stat['count'] }}</strong>
                            <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $stat['label'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Main Sections -->
            <div class="space-y-12">
                <!-- Trek Bookings Section -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Active Trek Bookings</h2>
                        <a href="{{ route('treks.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 underline underline-offset-4 uppercase tracking-widest">New Adventure</a>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @forelse ($trekBookings as $booking)
                            @php
                                $statusColors = match (strtolower($booking->status)) {
                                    'confirmed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-slate-100 text-slate-800 border-slate-200',
                                };
                            @endphp
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row md:items-center justify-between gap-6 group">
                                <div class="flex items-center gap-6">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors">
                                        <i class="fas fa-mountain"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $booking->departure?->trek?->title ?? 'Trek Booking' }}</h3>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">
                                            <span class="flex items-center gap-1.5"><i class="far fa-calendar text-emerald-500"></i> {{ optional($booking->departure?->start_date)->format('M d, Y') }}</span>
                                            <span class="flex items-center gap-1.5"><i class="fas fa-users text-blue-500"></i> {{ $booking->total_passengers }} Pax</span>
                                            <span class="flex items-center gap-1.5"><i class="fas fa-hashtag text-slate-300"></i> {{ $booking->booking_reference }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 border-t md:border-t-0 pt-4 md:pt-0">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border {{ $statusColors }}">
                                        {{ $booking->status }}
                                    </span>
                                    <div class="h-4 w-px bg-slate-100 hidden md:block"></div>
                                    <a href="{{ route('account.bookings.treks.show', $booking) }}" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-slate-800 transition-colors shadow-sm uppercase tracking-widest">
                                        Summary
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-bold italic italic">No trek bookings found.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Hotel Bookings Section -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Recent Hotel Stays</h2>
                        <a href="{{ route('hotels.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 underline underline-offset-4 uppercase tracking-widest">Book Room</a>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @forelse ($hotelBookings as $booking)
                            @php
                                $statusColors = match (strtolower($booking->status)) {
                                    'confirmed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-slate-100 text-slate-800 border-slate-200',
                                };
                            @endphp
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row md:items-center justify-between gap-6 group">
                                <div class="flex items-center gap-6">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors">
                                        <i class="fas fa-hotel"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                                            {{ $booking->hotelRoom?->hotel?->name ?? 'Hotel Stay' }}
                                            <span class="text-slate-400 font-medium ml-1 text-sm">{{ $booking->hotelRoom?->room_type ? '- ' . $booking->hotelRoom->room_type : '' }}</span>
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">
                                            <span class="flex items-center gap-1.5"><i class="fas fa-calendar-check text-emerald-500"></i> {{ optional($booking->check_in)->format('M d') }}</span>
                                            <span class="flex items-center gap-1.5"><i class="fas fa-calendar-times text-red-400"></i> {{ optional($booking->check_out)->format('M d, Y') }}</span>
                                            <span class="flex items-center gap-1.5"><i class="fas fa-bed text-blue-400"></i> {{ $booking->num_rooms }} Room{{ $booking->num_rooms > 1 ? 's' : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 border-t md:border-t-0 pt-4 md:pt-0">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border {{ $statusColors }}">
                                        {{ $booking->status }}
                                    </span>
                                    <div class="h-4 w-px bg-slate-100 hidden md:block"></div>
                                    <a href="{{ route('account.bookings.hotels.show', $booking) }}" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-slate-800 transition-colors shadow-sm uppercase tracking-widest">
                                        Summary
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-bold italic">No hotel bookings yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>

<x-customer-layout 
    title="Welcome back, {{ auth()->user()->name }}!" 
    subtitle="Manage your Himalayan adventures and hotel stays in one place."
>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <x-customer.stat-card 
            label="Trek Bookings" 
            :value="$stats['trek_bookings']" 
            icon="fa-mountain" 
        />
        <x-customer.stat-card 
            label="Hotel Bookings" 
            :value="$stats['hotel_bookings']" 
            icon="fa-hotel" 
        />
        <x-customer.stat-card 
            label="Upcoming Trips" 
            :value="$stats['upcoming_trips']" 
            icon="fa-calendar-check" 
        />
    </div>

    <!-- Main Sections -->
    <div class="space-y-12">
        <!-- Trek Bookings Section -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-base font-semibold text-slate-900">Active Trek Bookings</h2>
                <a href="{{ route('treks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors shadow-sm">
                    <i class="fas fa-plus text-[10px] opacity-60"></i> New booking
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4">
                @forelse ($trekBookings as $booking)
                    <x-customer.booking-card 
                        :title="$booking->departure?->trek?->title ?? 'Trek Booking'" 
                        :status="$booking->status" 
                        icon="fa-mountain" 
                        :viewRoute="route('account.bookings.treks.show', $booking)"
                    >
                        <x-slot name="meta">
                            <span class="flex items-center gap-2 text-xs text-slate-500">
                                <i class="far fa-calendar text-slate-400"></i> {{ optional($booking->departure?->start_date)->format('M d, Y') }}
                            </span>
                            <span class="flex items-center gap-2 text-xs text-slate-500">
                                <i class="fas fa-users text-slate-400"></i> {{ $booking->total_passengers }} Pax
                            </span>
                            <span class="flex items-center gap-2 text-xs text-slate-500">
                                <i class="fas fa-hashtag text-slate-400"></i> {{ $booking->booking_reference }}
                            </span>
                        </x-slot>
                    </x-customer.booking-card>
                @empty
                    <x-customer.empty-state 
                        title="No treks planned yet" 
                        message="Ready to hit the trails? Browse our curated treks and start your journey."
                        icon="fa-mountain"
                        actionText="Browse Treks"
                        :actionRoute="route('treks.index')"
                    />
                @endforelse
            </div>
        </section>

        <!-- Hotel Bookings Section -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-base font-semibold text-slate-900">Recent Hotel Stays</h2>
                <a href="{{ route('hotels.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors shadow-sm">
                    <i class="fas fa-plus text-[10px] opacity-60"></i> Book room
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4">
                @forelse ($hotelBookings as $booking)
                    <x-customer.booking-card 
                        :title="$booking->hotelRoom?->hotel?->name ?? 'Hotel Stay'" 
                        :status="$booking->status" 
                        icon="fa-hotel" 
                        :viewRoute="route('account.bookings.hotels.show', $booking)"
                    >
                        <x-slot name="meta">
                            <span class="flex items-center gap-2 text-xs text-slate-500">
                                <i class="fas fa-calendar-check text-slate-400"></i> {{ optional($booking->check_in)->format('M d') }}
                            </span>
                            <span class="flex items-center gap-2 text-xs text-slate-500">
                                <i class="fas fa-calendar-times text-slate-400"></i> {{ optional($booking->check_out)->format('M d, Y') }}
                            </span>
                            <span class="flex items-center gap-2 text-xs text-slate-500">
                                <i class="fas fa-bed text-slate-400"></i> {{ $booking->num_rooms }} Room{{ $booking->num_rooms > 1 ? 's' : '' }}
                            </span>
                        </x-slot>
                    </x-customer.booking-card>
                @empty
                    <x-customer.empty-state 
                        title="No hotel stays yet" 
                        message="Find the perfect basecamp for your rest and recovery."
                        icon="fa-hotel"
                        actionText="Find Hotels"
                        :actionRoute="route('hotels.index')"
                    />
                @endforelse
            </div>
        </section>
    </div>
</x-customer-layout>

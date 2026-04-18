<x-layouts.app>
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Progress Indicator -->
            <div class="flex items-center justify-between mb-12 relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-200 -z-0"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold shadow-lg shadow-slate-900/20">1</div>
                    <span class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-900">Info</span>
                </div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center font-semibold">2</div>
                    <span class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Passengers</span>
                </div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center font-semibold">3</div>
                    <span class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Confirm</span>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-900/5 border border-slate-100 overflow-hidden" 
                 x-data="{
                    count: 1,
                    price: {{ $departure->price }},
                    max: {{ $departure->capacity - $departure->booked_seats }},
                    get discount() {
                        if (this.count >= 10) return 15;
                        if (this.count >= 6) return 10;
                        if (this.count >= 3) return 5;
                        return 0;
                    },
                    get subtotal() {
                        return this.count * this.price;
                    },
                    get total() {
                        return this.subtotal - ((this.subtotal * this.discount) / 100);
                    },
                    format(val) {
                        return new Number(val).toLocaleString();
                    }
                 }">
                
                <!-- Summary Hero -->
                <div class="relative h-64 md:h-80">
                    <img src="{{ $departure->trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b' }}" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-12">
                        <p class="text-slate-400 font-medium uppercase tracking-widest text-xs mb-2">Step 1: Configuration</p>
                        <h1 class="text-3xl md:text-4xl font-semibold text-white tracking-tight mb-2">{{ $departure->trek->title }}</h1>
                        <p class="text-slate-300 font-bold flex items-center gap-2">
                            <i class="fas fa-calendar text-slate-400"></i>
                            {{ $departure->start_date->format('M d, Y') }} - {{ $departure->end_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                <!-- Booking Controls -->
                <div class="p-8 md:p-12">
                    <form action="{{ route('bookings.store') }}" method="POST" class="max-w-xl mx-auto">
                        @csrf
                        <input type="hidden" name="departure_id" value="{{ $departure->id }}">
                        <input type="hidden" name="total_passengers" :value="count">

                        <div class="text-center mb-10">
                            <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mb-3">How many people are traveling?</h2>
                            <p class="text-slate-500 font-medium">Group discounts will apply automatically based on passenger count.</p>
                        </div>

                        <!-- Counter Component -->
                        <div class="flex items-center justify-center gap-8 mb-12">
                            <button type="button" @click="if(count > 1) count--" class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-600 text-2xl font-semibold hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                -
                            </button>
                            <div class="text-center">
                                <span class="text-6xl font-semibold text-slate-900 tracking-tighter" x-text="count"></span>
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Passengers</span>
                            </div>
                            <button type="button" @click="if(count < max) count++" class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-600 text-2xl font-semibold hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                +
                            </button>
                        </div>

                        <!-- Availability Kicker -->
                        <div class="flex items-center justify-center gap-2 mb-12">
                            <span class="px-4 py-1.5 rounded-full bg-slate-900 text-white text-[10px] font-semibold uppercase tracking-widest shadow-xl">
                                {{ $departure->capacity - $departure->booked_seats }} slots available
                            </span>
                        </div>

                        <!-- Live Price Table -->
                        <div class="bg-slate-50 rounded-3xl p-8 space-y-4 border border-slate-100 mb-10 shadow-inner">
                            <div class="flex justify-between items-center text-sm font-bold text-slate-400 uppercase tracking-widest">
                                <span>Price per person</span>
                                <span class="text-slate-900">NPR {{ number_format($departure->price, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm font-bold text-slate-400 uppercase tracking-widest">
                                <span>Group Discount</span>
                                <span class="text-slate-900" x-text="discount + '%'"></span>
                            </div>
                            <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Estimated Total</span>
                                    <span class="text-[10px] font-bold text-slate-400">(Auto-calculated)</span>
                                </div>
                                <strong class="text-3xl font-semibold text-slate-900 tracking-tight" x-text="'NPR ' + format(total)"></strong>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
                            <div class="p-4 bg-white rounded-2xl border border-slate-100 flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                <p class="text-xs font-semibold text-slate-500 leading-relaxed">Passenger details like Passport # will be required on the next step.</p>
                            </div>
                            <div class="p-4 bg-white rounded-2xl border border-slate-100 flex items-start gap-3">
                                <i class="fas fa-shield-alt text-slate-400 mt-1"></i>
                                <p class="text-xs font-semibold text-slate-500 leading-relaxed">Secure payment via Stripe or eSewa is initiated after this step.</p>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-5 bg-slate-900 text-white font-semibold rounded-2xl shadow-2xl shadow-slate-900/20 hover:bg-slate-800 transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-3 group">
                            Continue to Passengers
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>


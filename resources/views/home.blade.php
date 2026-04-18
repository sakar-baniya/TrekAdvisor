<x-app-layout>
    <!-- Hero Section -->
    <section class="relative min-h-[85vh] flex items-center justify-center bg-slate-900 overflow-hidden">
        <!-- Background Image overlay -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-1000" style="background-image: url('{{ asset('images/ui/hero.webp') }}');"></div>
        <div class="absolute inset-0 bg-slate-900/60 bg-gradient-to-t from-slate-900 pb-20"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center py-20 text-center">
            <div class="mb-12 max-w-4xl">
                <h1 class="text-white text-5xl md:text-6xl font-black tracking-tight mb-8 drop-shadow-2xl leading-tight">
                    Local Experts in Himalayan Trekking
                </h1>
                <p class="text-slate-200 text-lg md:text-2xl font-medium tracking-wide max-w-2xl mx-auto opacity-95">
                    Explore the Himalayas with a team that's guided with care for over 17 years.
                </p>
            </div>

            <!-- Search Bar -->
            <div class="w-full max-w-2xl" x-data="{
                category: 'treks',
                treksUrl: '{{ route('treks.index') }}',
                hotelsUrl: '{{ route('hotels.index') }}',
                placeholder() {
                    if (this.category === 'treks') return 'Search for treks, destinations...'
                    return 'Search for hotels, locations...'
                },
                exploreLabel() {
                    if (this.category === 'treks') return 'Explore Treks'
                    return 'Explore Hotels'
                },
                actionUrl() {
                    if (this.category === 'treks') return this.treksUrl
                    return this.hotelsUrl
                }
            }">
                <div class="flex justify-center gap-3 mb-6">
                    <button type="button" @click="category = 'treks'" :class="category === 'treks' ? 'bg-white text-slate-900 font-bold shadow-md scale-[1.03]' : 'bg-slate-900/40 text-white font-medium hover:bg-slate-800/80'" class="px-6 py-2.5 rounded-full transition-all duration-300 backdrop-blur-md border border-white/20 text-sm tracking-wide">
                        Treks
                    </button>
                    <button type="button" @click="category = 'hotels'" :class="category === 'hotels' ? 'bg-white text-slate-900 font-bold shadow-md scale-[1.03]' : 'bg-slate-900/40 text-white font-medium hover:bg-slate-800/80'" class="px-6 py-2.5 rounded-full transition-all duration-300 backdrop-blur-md border border-white/20 text-sm tracking-wide">
                        Hotels
                    </button>
                </div>

                <form :action="actionUrl()" method="GET" class="bg-white p-2.5 rounded-full shadow-lg flex items-center w-full focus-within:ring-4 focus-within:ring-white/10 transition-all">
                    <div class="flex-grow flex items-center pl-6 pr-2">
                        <i class="fas fa-search text-slate-300 mr-4 text-xl" aria-hidden="true"></i>
                        <input type="text" name="search" x-bind:placeholder="placeholder()" class="w-full text-slate-900 border-none bg-transparent py-4 focus:outline-none focus:ring-0 placeholder-slate-400 font-medium" autocomplete="off" />
                    </div>
                    <button type="submit" class="bg-slate-900 text-white font-black px-10 py-4 rounded-full hover:bg-slate-800 transform transition-all hover:scale-[1.01] shadow-md flex-shrink-0 uppercase text-xs tracking-widest" x-text="exploreLabel()"></button>
                </form>
            </div>
        </div>
    </section>

    <!-- Featured Treks -->
    <section class="py-24 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-16 border-b border-slate-200 pb-8">
                <div>
                    <p class="text-slate-400 font-black tracking-[0.2em] uppercase text-[10px] mb-2">Adventure Awaits</p>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Featured Treks</h2>
                    <p class="text-slate-600 mt-4 text-xl font-medium">Curated Himalayan routes with trusted local guides.</p>
                </div>
                <div class="mt-6 md:mt-0">
                    <a href="{{ route('treks.index') }}" class="inline-flex items-center text-slate-900 font-bold uppercase tracking-widest text-xs border-2 border-slate-200 rounded-xl px-8 py-3.5 hover:bg-slate-50 transition-all duration-200">
                        View all <i class="fas fa-arrow-right ml-3 text-[10px]"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse ($featuredTreks->take(3) as $trek)
                    <article class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col h-full">
                        <div class="h-64 overflow-hidden relative bg-slate-200">
                            @if($trek->image)
                                <img src="{{ $trek->image }}" alt="Image of {{ $trek->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                    <i class="fas fa-mountain text-5xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-sm text-slate-900 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl shadow-sm flex items-center gap-2">
                                <i class="fas fa-star text-amber-500"></i> {{ $trek->reviews_avg_rating ? number_format($trek->reviews_avg_rating, 1) : 'NEW' }}
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center gap-6 text-[10px] font-black text-slate-400 mb-4 uppercase tracking-[0.15em]">
                                <span class="flex items-center gap-2"><i class="far fa-calendar-alt text-slate-300"></i> {{ $trek->duration_days ? $trek->duration_days . ' days' : 'Multi-day' }}</span>
                                <span class="flex items-center gap-2"><i class="fas fa-mountain text-slate-300"></i> {{ ucfirst($trek->difficulty ?? 'moderate') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight line-clamp-2">{{ $trek->title }}</h3>
                            
                            <div class="mt-auto pt-8 border-t border-slate-50 flex items-center justify-between">
                                <div>
                                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">From</span>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-3xl font-black text-slate-900 tracking-tight">NPR {{ number_format($trek->base_price, 0) }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('treks.show', $trek->slug) }}"
                                    class="!text-white bg-slate-900 px-6 py-2.5 rounded-full font-semibold hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-900/25 transition-all duration-200 ease-out no-underline">
                                    Book
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-xl border-2 border-dashed border-slate-100 italic">
                        <p class="text-slate-400 font-bold">No featured treks available at this moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Hotels -->
    <section id="featured-hotels" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-16 border-b border-slate-50 pb-8">
                <div>
                    <p class="text-slate-400 font-black tracking-[0.2em] uppercase text-[10px] mb-2">Luxury Stays</p>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Featured Hotels</h2>
                    <p class="text-slate-600 mt-4 text-xl font-medium">Handpicked stays with comfort, charm, and mountain views.</p>
                </div>
                <div class="mt-6 md:mt-0">
                    <a href="{{ route('hotels.index') }}" class="inline-flex items-center text-slate-900 font-bold uppercase tracking-widest text-xs border-2 border-slate-200 rounded-xl px-8 py-3.5 hover:bg-slate-50 transition-all duration-200">
                        Explore all <i class="fas fa-arrow-right ml-3 text-[10px]"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse ($featuredHotels->take(3) as $hotel)
                    <article class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col h-full">
                        <div class="h-64 overflow-hidden relative">
                            @if($hotel->image)
                                <img src="{{ $hotel->image }}" alt="Image of {{ $hotel->name }}" class="w-full h-full object-cover ">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50">
                                    <i class="fas fa-hotel text-5xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-sm text-slate-900 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl shadow-sm flex items-center gap-2">
                                <i class="fas fa-star text-amber-500"></i> {{ $hotel->reviews_avg_rating ? number_format($hotel->reviews_avg_rating, 1) : 'NEW' }}
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-slate-900 mb-2 tracking-tight line-clamp-1">{{ $hotel->name }}</h3>
                            <div class="flex items-center text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">
                                <i class="fas fa-map-marker-alt text-slate-300 mr-2"></i> Himalayan Region
                            </div>
                            
                            <div class="mt-auto pt-8 border-t border-slate-50 flex items-center justify-between">
                                <div>
                                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nightly</span>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-3xl font-black text-slate-900 tracking-tight">NPR {{ number_format($hotel->rooms_min_price_per_night ?? 0, 0) }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('hotels.show', $hotel) }}"
                                    class="!text-white bg-slate-900 px-6 py-2.5 rounded-full font-semibold hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-900/25 transition-all duration-200 ease-out no-underline">
                                    Book
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-20 text-center bg-slate-50 rounded-xl border-2 border-dashed border-slate-100 italic">
                        <p class="text-slate-400 font-bold">No hotel properties listed yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Global CTA -->
    <section class="py-32 relative overflow-hidden bg-slate-900 text-white text-center rounded-t-3xl mt-16 group">
        <div class="absolute inset-0 bg-slate-800/40 mix-blend-multiply group-hover:scale-105 transition-transform duration-1000"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6">
            <h2 class="text-5xl md:text-7xl font-black tracking-tight mb-8 leading-tight text-white">Ready for Your Adventure?</h2>
            <p class="text-slate-300 text-xl md:text-2xl font-medium mb-12 max-w-2xl mx-auto leading-relaxed opacity-90">
                Join thousands of trekkers using TrekAdvisor to plan excursions and stays in one premium experience.
            </p>
            <a href="{{ route('treks.index') }}" class="inline-flex items-center justify-center px-12 py-5 bg-white text-slate-900 font-bold uppercase tracking-widest text-sm rounded-xl shadow-lg hover:bg-slate-50 hover:scale-[1.02] transition-all duration-300">
                Start Planning Today
            </a>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <p class="text-slate-400 font-black tracking-[0.2em] uppercase text-[10px] mb-3">Testimonials</p>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">What Trekkers Say</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($testimonials as $testimonial)
                    <article class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col h-full hover:shadow-md transition-all duration-200">
                        <div class="flex text-amber-500 text-xs mb-6 gap-1">
                            @for($i = 0; $i < 5; $i++)
                                <i class="{{ $i < $testimonial->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-slate-600 font-medium italic leading-relaxed mb-8 flex-grow italic">{{ Str::limit($testimonial->comment, 160) }}</p>
                        <div class="flex items-center gap-5 pt-8 border-t border-slate-50 mt-auto">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-900 font-black flex-shrink-0 text-lg">
                                {{ substr($testimonial->user?->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <strong class="block text-slate-900 font-black text-xs uppercase tracking-widest mb-1">{{ $testimonial->user?->name ?? 'Anonymous' }}</strong>
                                <span class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest">{{ $testimonial->reviewable?->title ?? $testimonial->reviewable?->name ?? 'Verified Trekker' }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>

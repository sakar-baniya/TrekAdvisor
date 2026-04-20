<x-layouts.app>
    <!-- Hero Section -->
    <section class="relative bg-slate-900 overflow-hidden py-16 md:py-24 px-4 sm:px-6 lg:px-8">
        <div
            class="absolute inset-0 bg-[url('{{ asset('images/ui/hotel-hero.webp') }}')] bg-cover bg-center opacity-30">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900"></div>

        <div class="relative z-10 max-w-7xl mx-auto text-center">
            <p class="text-slate-400 font-semibold tracking-widest uppercase text-sm mb-4">Stay Collection</p>
            <h1 class="text-white text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">Find Your Perfect
                Stay</h1>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto font-medium">Browse premium hotels and lodges
                across the Himalayan regions, curated for comfort and authenticity.</p>
        </div>
    </section>

    <!-- Main Catalog Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{
        hotels: {{ $hotels->getCollection()->toJson() }},
        loading: false,
        search: '{{ request('search') }}',
        location: '{{ request('location') }}',
        minPrice: '{{ request('min_price') }}',
        maxPrice: '{{ request('max_price') }}',
        sort: '{{ request('sort', 'popularity') }}',
        meta: {
            total: {{ $hotels->total() }},
            current_page: {{ $hotels->currentPage() }}
        },
        hasFilters() {
            return this.search || this.location || this.minPrice || this.maxPrice || this.sort !== 'popularity';
        },
        async applyFilters() {
            this.loading = true;
            const params = new URLSearchParams({
                search: this.search,
                location: this.location,
                min_price: this.minPrice,
                max_price: this.maxPrice,
                sort: this.sort,
                page: 1
            });
            
            try {
                const response = await fetch(`/api/v1/hotels?${params.toString()}`);
                const data = await response.json();
                this.hotels = data.data;
                this.meta = data.meta;
                window.history.pushState({}, '', `/hotels?${params.toString()}`);
            } catch (e) {
                console.error('Fetch failed', e);
            } finally {
                this.loading = false;
            }
        },
        clearFilters() {
            this.search = '';
            this.location = '';
            this.minPrice = '';
            this.maxPrice = '';
            this.sort = 'popularity';
            this.applyFilters();
        },
        formatPrice(price) {
            return new Number(price || 0).toLocaleString();
        }
    }">
        <!-- Filter Toolbar -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Search</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" @keyup.enter="applyFilters()"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-slate-900 focus:border-slate-900 transition-all font-medium"
                            placeholder="Hotel name...">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Location</label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="location" @keyup.enter="applyFilters()"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-slate-900 focus:border-slate-900 transition-all font-medium"
                            placeholder="City or region...">
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Price Per
                        Night</label>
                    <div class="flex items-center gap-2">
                        <input type="number" x-model="minPrice" placeholder="Min"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-slate-900 focus:border-slate-900 transition-all font-medium">
                        <span class="text-slate-300">-</span>
                        <input type="number" x-model="maxPrice" placeholder="Max"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-slate-900 focus:border-slate-900 transition-all font-medium">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button @click="applyFilters()" :disabled="loading"
                        class="flex-1 bg-slate-900 text-white font-bold py-2 rounded-xl hover:bg-slate-800 transition-colors disabled:opacity-50">
                        <span x-show="!loading">Apply</span>
                        <span x-show="loading"><i class="fas fa-spinner animate-spin"></i></span>
                    </button>
                    <button x-show="hasFilters()" @click="clearFilters()"
                        class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-colors"
                        title="Clear Filters">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Sort By</label>
                    <select x-model="sort" @change="applyFilters()"
                        class="w-full bg-white border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-slate-900 focus:border-slate-900 transition-all font-bold">
                        <option value="popularity">Most Popular</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="rating">Highest Rated</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Results Info -->
        <div class="mb-8">
            <p class="text-slate-500 font-medium text-lg">
                Showing <span class="text-slate-900 font-extrabold" x-text="meta.total"></span> luxury hotels
            </p>
        </div>

        <!-- Hotel Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" :style="loading ? 'opacity: 0.5' : ''">
            <template x-for="hotel in hotels" :key="hotel.id">
                <article
                    class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col h-full group">
                    <div class="h-64 overflow-hidden relative bg-slate-100">
                        <img :src="hotel.image || '/images/hotels/hotel-2.png'" :alt="hotel.name"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            x-on:error="$el.src='/images/hotels/hotel-2.png'">
                        <div
                            class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest text-slate-900 shadow-sm border border-slate-100/50">
                            <i class="fas fa-map-marker-alt text-slate-400 mr-2"></i> <span
                                x-text="hotel.location"></span>
                        </div>
                    </div>
                    <div class="p-8 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-slate-900 mb-3 transition-colors line-clamp-2"
                            x-text="hotel.name"></h3>

                        <div class="flex items-center gap-4 text-xs font-semibold text-slate-400 mb-6">
                            <span class="flex items-center gap-1.5"><i class="fas fa-bed"></i> Comfort & Style</span>
                            <span class="flex items-center gap-1.5 text-amber-500">
                                <i class="fas fa-star text-[10px]"></i>
                                <span class="text-slate-900"
                                    x-text="hotel.reviews_avg_rating ? parseFloat(hotel.reviews_avg_rating).toFixed(1) : 'New'"></span>
                                <em class="text-slate-400 font-normal italic ml-1"
                                    x-text="'(' + (hotel.reviews_count || 0) + ')'"></em>
                            </span>
                        </div>

                        <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                            <div>
                                <span
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Starting
                                    from</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-bold text-slate-900 tracking-tight"
                                        x-text="'NPR ' + formatPrice(hotel.rooms_min_price_per_night)"></span>
                                    <span class="text-xs text-slate-500 font-medium">/night</span>
                                </div>
                            </div>
                            
                            <a :href="'/hotels/' + hotel.id"
                                class="!text-white bg-slate-900 px-8 py-3 rounded-2xl font-bold text-sm hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-900/25 transition-all duration-300 no-underline">
                                View Stay
                            </a>
                        </div>
                    </div>
                </article>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="hotels.length === 0 && !loading" style="display: none;"
            class="py-20 text-center bg-white rounded-[3rem] border-2 border-slate-100 border-dashed">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-hotel text-3xl text-slate-200"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">No hotels found</h3>
            <p class="text-slate-400 mb-8 font-medium max-w-sm mx-auto">We couldn't find any stays matching your criteria. Try adjusting your filters or location search.</p>
            <button @click="clearFilters()"
                class="inline-flex items-center justify-center px-8 py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20">
                Reset Selection
            </button>
        </div>

        <!-- Static Pagination -->
        <div class="mt-12" x-show="!hasFilters()">
            {{ $hotels->links('components.ui.pagination') }}
        </div>
    </div>
</x-layouts.app>

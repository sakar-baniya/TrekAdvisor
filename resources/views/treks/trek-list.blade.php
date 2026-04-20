<x-layouts.app>
    <!-- Hero Section -->
    <section class="relative bg-slate-900 overflow-hidden py-16 md:py-24 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 bg-[url('{{ asset('images/ui/hero.webp') }}')] bg-cover bg-center opacity-30">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900"></div>

        <div class="relative z-10 max-w-7xl mx-auto text-center">
            <p class="text-slate-400 font-semibold tracking-widest uppercase text-sm mb-4">Trek Collection</p>
            <h1 class="text-white text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">Discover Amazing
                Treks</h1>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto font-medium">Explore the Himalayas with
                curated routes, flexible departures, and customer reviews.</p>
        </div>
    </section>

    <!-- Main Catalog Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{
        treks: {{ $treks->getCollection()->toJson() }},
        loading: false,
        search: '{{ request('search') }}',
        difficulty: '{{ request('difficulty') }}',
        minPrice: '{{ request('min_price') }}',
        maxPrice: '{{ request('max_price') }}',
        sort: '{{ request('sort', 'popularity') }}',
        meta: {
            total: {{ $treks->total() }},
            current_page: {{ $treks->currentPage() }}
        },
        hasFilters() {
            return this.search || this.difficulty || this.minPrice || this.maxPrice || this.sort !== 'popularity';
        },
        async applyFilters() {
            this.loading = true;
            const params = new URLSearchParams({
                search: this.search,
                difficulty: this.difficulty,
                min_price: this.minPrice,
                max_price: this.maxPrice,
                sort: this.sort,
                page: 1
            });
            
            try {
                const response = await fetch(`/api/v1/treks?${params.toString()}`);
                const data = await response.json();
                this.treks = data.data;
                this.meta = data.meta;
                window.history.pushState({}, '', `/treks?${params.toString()}`);
            } catch (e) {
                console.error('Fetch failed', e);
            } finally {
                this.loading = false;
            }
        },
        clearFilters() {
            this.search = '';
            this.difficulty = '';
            this.minPrice = '';
            this.maxPrice = '';
            this.sort = 'popularity';
            this.applyFilters();
        },
        formatPrice(price) {
            return new Number(price).toLocaleString();
        },
        getBadgeClass(difficulty) {
            const d = (difficulty || 'moderate').toLowerCase();
            if (d === 'easy') return 'bg-blue-50 text-blue-700';
            if (d === 'difficult') return 'bg-orange-50 text-orange-800';
            if (d === 'extreme') return 'bg-red-50 text-red-800';
            return 'bg-slate-100 text-slate-800';
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
                            placeholder="Trek name...">
                    </div>
                </div>

                <div>
                    <label
                        class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Difficulty</label>
                    <select x-model="difficulty" @change="applyFilters()"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-slate-900 focus:border-slate-900 transition-all font-medium">
                        <option value="">All Difficulty</option>
                        <option value="easy">Easy</option>
                        <option value="moderate">Moderate</option>
                        <option value="difficult">Difficult</option>
                        <option value="extreme">Extreme</option>
                    </select>
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Price
                        Range</label>
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
                        <option value="duration">Duration</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Results Info -->
        <div class="flex items-center justify-between mb-8">
            <p class="text-slate-500 font-medium">
                Showing <span class="text-slate-900 font-bold" x-text="meta.total"></span> treks
            </p>
        </div>

        <!-- Trek Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" :style="loading ? 'opacity: 0.5' : ''">
            <template x-for="trek in treks" :key="trek.id">
                <article
                    class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-200 flex flex-col h-full group">
                    <div class="h-60 overflow-hidden relative bg-slate-100">
                        <img :src="trek.image || '/images/treks/trek-1.png'" :alt="trek.title"
                            class="w-full h-full object-cover transition-transform duration-500 "
                            x-on:error="$el.src='/images/treks/trek-1.png'">
                        <div class="absolute top-4 right-4 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-sm"
                            :class="getBadgeClass(trek.difficulty)" x-text="trek.difficulty || 'Moderate'"></div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-slate-900 mb-3 transition-colors line-clamp-2"
                            x-text="trek.title"></h3>

                        <div class="flex items-center gap-4 text-xs font-semibold text-slate-400 mb-4">
                            <span class="flex items-center gap-1.5"><i class="far fa-clock"></i> <span
                                    x-text="trek.duration_days || 'Flexible'"></span> Days</span>
                            <span class="flex items-center gap-1.5 text-amber-500">
                                <i class="fas fa-star"></i>
                                <span
                                    x-text="trek.reviews_avg_rating ? parseFloat(trek.reviews_avg_rating).toFixed(1) : 'New'"></span>
                                <em class="text-slate-400 font-normal italic ml-1"
                                    x-text="'(' + (trek.reviews_count || 0) + ' reviews)'"></em>
                            </span>
                        </div>

                        <div class="mt-auto pt-6 border-t border-slate-50 flex items-end justify-between">
                            <div>
                                <span
                                    class="block text-[10px] font-semibold text-slate-400 uppercase tracking-widest mb-1">Price</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-bold text-slate-900 tracking-tight"
                                        x-text="'NPR ' + formatPrice(trek.base_price)"></span>
                                    <span class="text-xs text-slate-500 font-medium">/pp</span>
                                </div>
                            </div>
                            <a :href="'/treks/' + (trek.slug || trek.id)"
                                class="!text-white bg-slate-900 px-6 py-2.5 rounded-full font-semibold hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-900/25 transition-all duration-200 ease-out no-underline">
                                Book
                            </a>
                        </div>
                    </div>
                </article>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="treks.length === 0 && !loading" style="display: none;"
            class="py-20 text-center bg-white rounded-3xl border border-slate-200 border-dashed">
            <div class="text-5xl text-slate-200 mb-4">
                <i class="fas fa-mountain-sun"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">No treks found</h3>
            <p class="text-slate-500 mb-6 font-medium">Adjust your filters or clear them to see more adventures.</p>
            <button @click="clearFilters()"
                class="inline-flex items-center justify-center px-6 py-3 bg-white border border-slate-300 rounded-xl font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                Clear All Filters
            </button>
        </div>

        <!-- Static Pagination (Fallback or standard) -->
        <div class="mt-12" x-show="!hasFilters()">
            {{ $treks->links('components.ui.pagination') }}
        </div>
    </div>
</x-layouts.app>


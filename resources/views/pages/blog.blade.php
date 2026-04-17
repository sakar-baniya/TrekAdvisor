<x-app-layout>
    <!-- Hero Section -->
    <section class="bg-slate-900 overflow-hidden relative py-24">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-64 h-64 bg-slate-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <p class="text-emerald-400 font-bold tracking-widest uppercase text-sm mb-4">Resources</p>
            <h1 class="text-white text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">Stories, advice, and trek planning notes</h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto font-medium">Read practical guidance and inspiration for building stronger adventures.</p>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($posts as $post)
                    <article class="bg-white rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group group h-full">
                        <div class="p-8 pb-4">
                            <div class="flex items-center gap-3 text-emerald-600 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                    <i class="fas fa-book-open text-xs"></i>
                                </div>
                                <span class="text-sm font-bold uppercase tracking-widest">{{ $post['category'] }}</span>
                            </div>
                            
                            <div class="flex items-center gap-4 text-xs font-semibold text-slate-400 mb-4">
                                <span class="flex items-center gap-1.5"><i class="far fa-clock"></i> {{ $post['reading_time'] }}</span>
                                <span class="flex items-center gap-1.5"><i class="far fa-calendar"></i> {{ $post['date'] }}</span>
                            </div>

                            <h3 class="text-2xl font-extrabold text-slate-900 mb-4 leading-tight group-hover:text-emerald-600 transition-colors">{{ $post['title'] }}</h3>
                            <p class="text-slate-500 leading-relaxed line-clamp-3 mb-6">{{ $post['excerpt'] }}</p>
                        </div>

                        <div class="mt-auto px-8 pb-8 flex items-center justify-between border-t border-slate-50 pt-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold">
                                    {{ substr($post['author'], 0, 1) }}
                                </div>
                                <strong class="text-slate-900 font-bold text-sm">{{ $post['author'] }}</strong>
                            </div>
                            <span class="inline-flex items-center text-slate-900 font-bold text-sm group/btn">
                                Read More <i class="fas fa-arrow-right ml-2 text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Explore More -->
            <div class="mt-20 text-center">
                <p class="text-slate-400 font-medium mb-6">Want more adventure inspiration?</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('treks.index') }}" class="px-8 py-4 bg-slate-900 text-white font-extrabold rounded-full hover:bg-slate-800 transition-all shadow-lg hover:scale-105">
                        Discover Treks
                    </a>
                    <a href="{{ route('about') }}" class="px-8 py-4 bg-white text-slate-900 border border-slate-200 font-extrabold rounded-full hover:bg-slate-50 transition-all shadow-sm">
                        About Us
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>

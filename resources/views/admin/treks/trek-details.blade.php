@section('page-title', $trek->title)
@section('page-subtitle', 'Resource Management')
@section('page-back', route('admin.treks.index'))

@section('page-actions')
    <a href="{{ route('admin.treks.edit', $trek) }}" class="inline-flex items-center px-6 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10">
        <i class="fas fa-pen mr-2"></i> Edit Adventure
    </a>
@endsection

<x-layouts.dashboard>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
        <!-- Main Content (Hero & Description) -->
        <article class="lg:col-span-2 space-y-8">
            <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                <div class="aspect-[16/9] w-full overflow-hidden bg-slate-100">
                    <img src="{{ $trek->image ?: 'https://via.placeholder.com/1200x700?text=Trek' }}" 
                         alt="{{ $trek->title }}" 
                         class="w-full h-full object-cover">
                </div>
                
                <div class="p-8 md:p-12">
                    <div class="flex flex-wrap items-center gap-6 mb-10 pb-10 border-b border-slate-50">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Duration</span>
                            <span class="text-sm font-bold text-slate-900">{{ $trek->duration_days ?? 'N/A' }} Days</span>
                        </div>
                        <div class="w-px h-8 bg-slate-100 hidden sm:block"></div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Difficulty</span>
                            <span class="text-sm font-bold text-slate-900">{{ $trek->difficulty }}</span>
                        </div>
                        <div class="w-px h-8 bg-slate-100 hidden sm:block"></div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Max Elevation</span>
                            <span class="text-sm font-bold text-slate-900">{{ $trek->max_altitude ? number_format($trek->max_altitude) . 'm' : 'Not set' }}</span>
                        </div>
                        <div class="w-px h-8 bg-slate-100 hidden sm:block"></div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Base Price</span>
                            <span class="text-sm font-black text-slate-900 tracking-tight">NPR {{ number_format($trek->base_price, 2) }}</span>
                        </div>
                    </div>

                    <div class="prose prose-slate max-w-none">
                        <p class="text-slate-600 leading-relaxed font-medium">{{ $trek->description }}</p>
                    </div>
                </div>
            </section>

            <section class="bg-white p-8 md:p-12 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="mb-10 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight">Daily Itinerary</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Expedition day-by-day roadmap</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-sm"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($trek->itineraries as $day)
                        <article class="p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-slate-200 transition-all group">
                            <div class="flex items-start gap-4">
                                <span class="bg-slate-900 text-white text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-widest">Day {{ $day->day_number }}</span>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-slate-900 mb-1 group-hover:text-blue-600 transition-colors">{{ $day->title }}</h4>
                                    <p class="text-[11px] font-medium text-slate-500 leading-relaxed">{{ $day->description }}</p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">No itinerary days recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </article>

        <!-- Sidebar Stats & Gallery -->
        <aside class="space-y-8">
            <section class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                        <i class="fas fa-chart-line text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Metrics</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Operational Health</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Visibility Status</span>
                        @php
                            $isLive = strtolower($trek->status) === 'active' || strtolower($trek->status) === 'published';
                        @endphp
                        <span class="block px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest border {{ $isLive ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-500 border-slate-100' }} text-center">
                            {{ $trek->status }}
                        </span>
                    </div>
                    <div class="h-px bg-slate-50"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Slots</span>
                            <span class="text-sm font-bold text-slate-900">{{ $trek->departures->count() }} Departures</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Onboarded</span>
                            <span class="text-sm font-bold text-slate-900">{{ $trek->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </section>

            @if ($trek->gallery->isNotEmpty())
                <section class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Visual Library</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Supporting trek photos</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($trek->gallery as $image)
                            <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-50 group cursor-pointer">
                                <img src="{{ $image->path }}" 
                                     alt="Gallery {{ $loop->iteration }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <div class="p-8 rounded-[2rem] bg-slate-900 text-white shadow-xl shadow-slate-900/10">
                <i class="fas fa-fingerprint text-white/50 mb-4 text-xl"></i>
                <p class="text-[10px] font-bold uppercase tracking-widest text-white/70 mb-2">Resource Locator</p>
                <code class="text-[9px] font-mono text-emerald-400 break-all leading-tight">{{ $trek->slug }}</code>
            </div>
        </aside>
    </div>
</x-layouts.dashboard>


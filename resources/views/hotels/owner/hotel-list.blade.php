<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Property Management</h1>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] italic">Your registered hotels and accommodations</p>
        </div>
        <a href="{{ route('hotel_owner.hotels.create') }}" class="inline-flex items-center px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20">
            <i class="fas fa-plus mr-2"></i> Register New Hotel
        </a>
    </div>

    @if (session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl">
            <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Success</p>
            <p class="text-xs font-semibold text-emerald-600 mt-1">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Hotel List Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($hotels as $hotel)
            <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col italic-hover">
                 <!-- Preview / Status -->
                 <div class="relative h-48 bg-slate-900 overflow-hidden">
                      <img src="{{ $hotel->image ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-60 group-hover:opacity-90">
                      <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20"></div>
                      <div class="absolute bottom-6 left-8">
                           @php
                                $statusColors = match(strtolower($hotel->status)) {
                                    'active', 'approved' => 'bg-emerald-500',
                                    'pending' => 'bg-amber-500',
                                    default => 'bg-slate-400'
                                };
                           @endphp
                           <div class="flex items-center gap-2 mb-2">
                                <span class="w-2 h-2 rounded-full {{ $statusColors }} animate-pulse"></span>
                                <span class="text-[9px] font-black text-white uppercase tracking-[0.2em]">{{ $hotel->status }}</span>
                           </div>
                           <h3 class="text-xl font-black text-white tracking-tight line-clamp-1 italic italic">{{ $hotel->name }}</h3>
                      </div>
                 </div>

                 <!-- Basic Info -->
                 <div class="p-8 flex-grow flex flex-col">
                      <div class="flex flex-wrap gap-4 mb-8">
                           <div class="flex flex-col">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Location</span>
                                <span class="text-[10px] font-black text-slate-900 uppercase italic">{{ $hotel->location }}</span>
                           </div>
                           <div class="w-px h-6 bg-slate-100 hidden sm:block"></div>
                           <div class="flex flex-col">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Inventory</span>
                                <span class="text-[10px] font-black text-slate-900 uppercase italic">{{ $hotel->rooms_count ?? 0 }} Room Types</span>
                           </div>
                      </div>

                      <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                           <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-[10px]">
                                     <i class="fas fa-images"></i>
                                </div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $hotel->gallery_count ?? 0 }} Photos</span>
                           </div>
                           <a href="{{ route('hotel_owner.hotels.edit', $hotel) }}" class="inline-flex items-center px-5 py-2.5 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">
                                Edit Property
                           </a>
                      </div>
                 </div>
            </div>
        @empty
            <div class="col-span-full py-20 px-6 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-hotel text-3xl text-slate-200"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">No hotels registered</h3>
                <p class="text-slate-400 font-medium mt-2 max-w-sm mx-auto">Start by adding your first property to show up in our marketplace search results.</p>
                <div class="mt-10">
                    <a href="{{ route('hotel_owner.hotels.create') }}" class="inline-flex items-center px-10 py-4 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-all shadow-2xl shadow-slate-900/20">
                        <i class="fas fa-plus mr-2"></i> Register Hotel
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</x-dashboard-layout>

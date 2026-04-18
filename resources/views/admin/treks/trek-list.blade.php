<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900 tracking-tight">Trek Inventory</h1>
            <p class="text-slate-500 font-medium mt-1">Monitor and manage all trekking expeditions across the marketplace.</p>
        </div>
        <a href="{{ route('admin.treks.create') }}" class="inline-flex items-center px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-semibold uppercase tracking-widest hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20">
            <i class="fas fa-plus mr-2"></i> New Trek Listing
        </a>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8 p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                <i class="fas fa-filter"></i>
            </div>
            <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-widest">Advanced Filters</h3>
        </div>
        <x-dashboard.trek-inventory-filters :search="$search" :difficulty="$difficulty" :status="$status" />
    </div>

    <!-- Inventory Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        @forelse ($treks as $trek)
            <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden flex flex-col italic-hover">
                 <!-- Image Placeholder/Preview -->
                 <div class="relative h-48 overflow-hidden">
                      <img src="{{ $trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                      <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-slate-900/80">
                           <span class="px-2 py-1 rounded-md bg-white/20 backdrop-blur-md text-[9px] font-semibold text-white uppercase tracking-widest">
                               {{ $trek->difficulty }}
                           </span>
                      </div>
                 </div>
                 
                 <div class="p-6 flex-grow flex flex-col justify-between">
                      <div>
                           <div class="flex items-center justify-between gap-4 mb-2">
                                <h3 class="text-lg font-semibold text-slate-900 leading-tight line-clamp-1 group-hover:text-emerald-600 transition-colors">{{ $trek->title }}</h3>
                                <span class="px-2 py-0.5 rounded-full border border-slate-100 text-[8px] font-semibold uppercase tracking-tighter text-slate-400">
                                    {{ $trek->status }}
                                </span>
                           </div>
                           <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">
                               <i class="fas fa-map-marker-alt text-emerald-500 mr-1 opacity-70"></i> {{ $trek->location ?? 'Nepal' }}
                           </p>
                      </div>

                      <div class="flex items-center justify-between pt-6 border-t border-slate-50 mt-4">
                           <div class="flex flex-col">
                                <span class="text-[8px] font-semibold text-slate-400 uppercase tracking-widest">Starting Price</span>
                                <strong class="text-sm font-semibold text-slate-900 italic tracking-tight italic">NPR {{ number_format($trek->base_price ?? 0, 0) }}</strong>
                           </div>
                           <div class="flex gap-2">
                                <a href="{{ route('admin.treks.edit', $trek) }}" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                     <i class="fas fa-edit text-xs"></i>
                                </a>
                           </div>
                      </div>
                 </div>
            </div>
        @empty
            <div class="col-span-full py-20 px-6 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-mountain-sun text-3xl text-slate-200"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 tracking-tight">No treks found</h3>
                <p class="text-slate-400 font-medium mt-2 max-w-sm mx-auto">Try adjusting your filters or create a new trek listing to populated your inventory.</p>
                <div class="mt-10">
                    <a href="{{ route('admin.treks.index') }}" class="text-xs font-semibold text-slate-900 uppercase tracking-widest underline underline-offset-8 decoration-slate-200 hover:decoration-slate-900 transition-all">
                        Clear All Filters
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($treks->hasPages())
        <div class="flex flex-col sm:flex-row items-center justify-between border-t border-slate-100 pt-8 pb-12">
            <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest mb-4 sm:mb-0 italic italic">
                Showing {{ $treks->firstItem() }} to {{ $treks->lastItem() }} of {{ $treks->total() }} Inventory items
            </div>
            
            <div class="flex items-center gap-1">
                {{ $treks->links() }}
            </div>
        </div>
    @endif
</x-dashboard-layout>

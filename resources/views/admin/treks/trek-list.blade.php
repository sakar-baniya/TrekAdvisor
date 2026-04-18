@section('page-title', 'Manage Treks')
@section('page-subtitle', 'Monitor and manage all trekking expeditions across the marketplace.')

@section('page-actions')
    <a href="{{ route('admin.treks.create') }}" class="inline-flex items-center px-4 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-800 transition-all shadow-sm">
        New Trek
    </a>
@endsection

<x-layouts.dashboard>

    <!-- Filter Toolbar -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8 p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                <i class="fas fa-filter"></i>
            </div>
            <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-widest">Filter Treks</h3>
        </div>
        <x-dashboard.trek-inventory-filters :search="$search" :difficulty="$difficulty" :status="$status" />
    </div>

    <!-- Inventory Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($treks as $trek)
            <div class="group bg-white rounded-[2rem] border border-slate-100 shadow-sm transition-all duration-500 overflow-hidden flex flex-col">
                 <!-- Image Placeholder/Preview -->
                 <div class="relative h-56 overflow-hidden">
                      <img src="{{ $trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b' }}" 
                           class="w-full h-full object-cover transition-transform duration-1000">
                      <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                      <div class="absolute top-4 left-4">
                           <span class="px-3 py-1 rounded-lg bg-white/20 backdrop-blur-md text-[9px] font-bold text-white uppercase tracking-widest border border-white/20">
                               {{ $trek->difficulty }}
                           </span>
                      </div>
                      <div class="absolute bottom-4 left-4">
                           <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $trek->status === 'Active' ? 'bg-emerald-400' : 'bg-slate-400' }}"></span>
                                <span class="text-[9px] font-bold text-white uppercase tracking-widest">{{ $trek->status }}</span>
                           </div>
                      </div>
                 </div>
                 
                 <div class="p-8 flex-grow flex flex-col">
                      <div class="mb-6">
                           <h3 class="text-xl font-bold text-slate-900 leading-tight mb-2 transition-colors font-display line-clamp-1">{{ $trek->title }}</h3>
                           <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                               <i class="fas fa-map-marker-alt text-emerald-500"></i> {{ $trek->location ?? 'Nepal' }}
                           </p>
                      </div>

                      <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                           <div>
                                <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Base Price</span>
                                <strong class="text-lg font-extrabold text-slate-900 tracking-tight font-display">NPR {{ number_format($trek->base_price ?? 0, 0) }}</strong>
                           </div>
                           <div class="flex gap-2">
                                <a href="{{ route('admin.treks.edit', $trek) }}" class="w-11 h-11 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all shadow-sm border border-slate-100" title="Edit Trek">
                                     <i class="fas fa-pencil-alt text-xs"></i>
                                </a>
                                <form action="{{ route('admin.treks.destroy', $trek) }}" method="POST" class="inline-block" onsubmit="return confirm('WARNING: Are you absolutely sure you want to delete this trek? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-11 h-11 rounded-xl bg-red-50 text-red-500 flex items-center justify-center border border-red-100" title="Delete Trek">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                           </div>
                      </div>
                 </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-100 shadow-sm flex flex-col items-center text-center px-6">
                <div class="w-24 h-24 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-8 border border-slate-50 shadow-inner">
                    <i class="fas fa-mountain-sun text-4xl"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight font-display mb-3">No Treks Found</h3>
                <p class="text-sm font-medium text-slate-500 max-w-sm leading-relaxed mb-10">
                    We couldn't find any treks matching your criteria. Try adjusting your filters or create a new masterpiece.
                </p>
                <a href="{{ route('admin.treks.index') }}" class="inline-flex items-center gap-3 px-6 py-3 bg-slate-50 text-slate-900 text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-slate-900 hover:text-white transition-all border border-slate-100">
                    <i class="fas fa-redo-alt text-[10px]"></i> Reset All Filters
                </a>
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
</x-layouts.dashboard>


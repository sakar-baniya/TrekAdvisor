<x-layouts.dashboard>
    <div class="max-w-7xl mx-auto">
        <form action="{{ route('hotel_owner.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div>
                    <a href="{{ route('hotel_owner.hotels.index') }}" class="inline-flex items-center text-[10px] font-semibold uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors mb-4">
                        <i class="fas fa-arrow-left mr-2"></i> My Properties
                    </a>
                    <h1 class="text-4xl font-semibold text-slate-900 tracking-tight">Edit Property</h1>
                    <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-xs italic tracking-tight">Updating: {{ $hotel->name }}</p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="pb-32">
                @include('hotels.owner.hotel-form-fields', ['hotel' => $hotel])
            </div>

            <!-- Sticky Action Bar -->
            <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-slate-100 p-6 z-50">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="hidden md:flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-lg">
                            <i class="fas fa-save text-xs"></i>
                        </div>
                        <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest leading-none">
                            Saved Changes:<br><span class="text-slate-900 line-clamp-1 italic italic">{{ $hotel->name }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <a href="{{ route('hotel_owner.hotels.index') }}" class="flex-1 md:flex-none text-center px-8 py-3 bg-slate-50 text-slate-600 text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 md:flex-none px-12 py-3 bg-slate-900 text-white text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20">
                            Update Hotel
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>


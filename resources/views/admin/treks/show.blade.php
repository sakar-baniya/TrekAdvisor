<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tight">
                {{ __('Trek Details') }}
            </h2>
            <a href="{{ route('admin.treks.edit', $trek->id) }}" class="inline-flex items-center px-8 py-4 bg-white border border-gray-200 rounded-2xl font-black text-xs text-gray-900 uppercase tracking-widest hover:bg-gray-50 active:scale-95 focus:outline-none transition-all shadow-lg shadow-gray-100">
                <i class="fas fa-edit mr-3"></i> Edit Trek
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
        
        <!-- Main Details Column -->
        <div class="lg:col-span-2 space-y-10">
            
            <!-- Hero Component -->
            <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-2xl shadow-gray-100/50 overflow-hidden">
                <div class="relative aspect-video">
                    <img src="{{ $trek->image ?? 'https://via.placeholder.com/1200x800?text=NO+IMAGE' }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-10 left-10 right-10">
                        <div class="flex items-center space-x-4 mb-4">
                            <span class="px-3 py-1 bg-white rounded-lg text-[10px] font-black uppercase tracking-widest text-gray-900 shadow-xl">{{ $trek->difficulty }}</span>
                            <span class="px-3 py-1 bg-gray-900/40 backdrop-blur-md rounded-lg text-[10px] font-black uppercase tracking-widest text-white border border-white/20">ID: {{ $trek->id }}</span>
                        </div>
                        <h1 class="text-4xl font-black text-white leading-tight tracking-tight">{{ $trek->title }}</h1>
                    </div>
                </div>
                <div class="p-10">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <span class="w-8 h-0.5 bg-gray-900 mr-4"></span>
                        About This Trek
                    </h3>
                    <div class="prose prose-lg max-w-none text-gray-600 font-medium leading-relaxed">
                        {!! nl2br(e($trek->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Gallery Archive -->
            @if($trek->gallery && $trek->gallery->count() > 0)
                <div class="bg-white border border-gray-100 rounded-[2.5rem] p-10 shadow-2xl shadow-gray-100/50">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em] mb-10 flex items-center">
                        <span class="w-8 h-0.5 bg-gray-900 mr-4"></span>
                        Gallery
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($trek->gallery as $image)
                            <a href="{{ $image->path }}" target="_blank" class="block group aspect-[4/3] rounded-2xl overflow-hidden border border-gray-50 shadow-sm">
                                <img src="{{ $image->path }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-10">
            
            <!-- Tariff Card -->
            <div class="bg-gray-900 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-gray-200">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Price</p>
                <div class="flex items-baseline mb-10">
                    <span class="text-5xl font-black tracking-tight">${{ number_format($trek->base_price) }}</span>
                    <span class="ml-2 text-gray-400 text-xs font-bold font-mono">USD</span>
                </div>
                
                <div class="space-y-6 pt-10 border-t border-white/10">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</span>
                        <div class="flex items-center">
                            <span class="h-2 w-2 rounded-full {{ $trek->status == 'Active' ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.8)]' : 'bg-gray-600' }} mr-3"></span>
                            <span class="text-xs font-black uppercase tracking-widest">{{ $trek->status }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Difficulty</span>
                        <span class="text-xs font-black uppercase tracking-widest text-white bg-white/10 px-3 py-1 rounded-lg border border-white/5">{{ $trek->difficulty }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Last Updated</span>
                        <span class="text-xs font-black uppercase tracking-widest font-mono">{{ $trek->updated_at->format('Y.m.d') }}</span>
                    </div>
                </div>
            </div>

            <!-- Meta Intelligence -->
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-10 shadow-xl shadow-gray-100/50">
                <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-6">System Info</h4>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-2xl flex items-center">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-gray-900 shadow-sm mr-4">
                            <i class="fas fa-link text-xs"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest leading-none mb-1">Url Snippet (Slug)</p>
                            <p class="text-xs font-bold text-gray-900 truncate tracking-tight">{{ $trek->slug }}</p>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl flex items-center">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-gray-900 shadow-sm mr-4">
                            <i class="fas fa-images text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest leading-none mb-1">Images Count</p>
                            <p class="text-xs font-bold text-gray-900 tracking-tight">{{ $trek->gallery->count() + 1 }} Photos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Action -->
            <form action="{{ route('admin.treks.destroy', $trek->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this trek?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-8 py-5 bg-white border border-red-100 rounded-[2rem] font-black text-[10px] text-red-500 uppercase tracking-[0.4em] hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-lg shadow-red-50">
                    Delete Trek
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>

<x-app-layout>
    <div class="max-w-[1200px] mx-auto py-16 px-6 lg:px-8 font-sans">
        <!-- Page Title -->
        <header class="text-center mb-16">
            <h1 class="text-5xl md:text-6xl font-black text-slate-900 mb-6 tracking-tighter">
                Find Your Next <span class="text-blue-600">Adventure</span>
            </h1>
            <p class="text-xl text-slate-500 max-w-2xl mx-auto font-medium leading-relaxed">
                Choose from our hand-picked list of amazing treks in the Himalayas.
            </p>
        </header>

        <!-- Filters -->
        <div class="flex justify-end mb-10">
            <form action="{{ route('treks.index') }}" method="GET" class="flex items-center space-x-4">
                <div class="relative group">
                    <select name="difficulty" onchange="this.form.submit()" class="appearance-none pl-8 pr-12 py-3 bg-white border border-slate-200 rounded-full text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer shadow-sm group-hover:border-blue-300">
                        <option value="">Difficulty Level</option>
                        <option value="Easy" {{ request('difficulty') == 'Easy' ? 'selected' : '' }}>Easy</option>
                        <option value="Moderate" {{ request('difficulty') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="Difficult" {{ request('difficulty') == 'Difficult' ? 'selected' : '' }}>Difficult</option>
                        <option value="Extreme" {{ request('difficulty') == 'Extreme' ? 'selected' : '' }}>Extreme</option>
                    </select>
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fas fa-filter text-[10px]"></i>
                    </div>
                </div>
                
                @if(request('difficulty'))
                    <a href="{{ route('treks.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-800 transition-colors">
                        Clear Filters
                    </a>
                @endif
            </form>
        </div>

        <!-- Trek List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($treks as $trek)
                <div class="group bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-2xl shadow-slate-100/50 hover:-translate-y-3 transition-all duration-500 flex flex-col">
                    <!-- Image -->
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=640&q=80' }}" alt="{{ $trek->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent"></div>
                        
                        @php
                            $difficultyColors = [
                                'easy' => 'bg-emerald-500/90 text-white',
                                'moderate' => 'bg-blue-500/90 text-white',
                                'difficult' => 'bg-amber-500/90 text-white',
                                'extreme' => 'bg-rose-500/90 text-white'
                            ][strtolower($trek->difficulty)] ?? 'bg-slate-500/90 text-white';
                        @endphp
                        
                        <div class="absolute top-6 left-6 {{ $difficultyColors }} px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest backdrop-blur-sm shadow-lg">
                            {{ $difficultyColors === 'bg-rose-500/90 text-white' ? 'EXTREME' : ($difficultyColors === 'bg-amber-500/90 text-white' ? 'DIFFICULT' : ($difficultyColors === 'bg-blue-500/90 text-white' ? 'MODERATE' : 'EASY')) }}
                        </div>
                    </div>

                    <!-- Trek Details -->
                    <div class="p-8 flex-1 flex flex-col">
                        <h3 class="text-2xl font-black text-slate-900 mb-3 group-hover:text-blue-600 transition-colors tracking-tight">
                            {{ $trek->title }}
                        </h3>
                        
                        <div class="flex items-center mb-4 pb-4 border-b border-slate-50">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mr-2">Starting From</span>
                            <span class="text-xl font-black text-blue-600">${{ number_format($trek->base_price) }}</span>
                        </div>
                        
                        <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8 flex-1">
                            {{ Str::limit($trek->description, 110) }}
                        </p>
                        
                        <a href="{{ route('treks.show', $trek->slug) }}" class="w-full block py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.3em] text-center hover:bg-blue-600 hover:shadow-xl hover:shadow-blue-100 transition-all active:scale-95">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- System Pagination -->
        <div class="mt-20 flex justify-center">
            {{ $treks->links() }}
        </div>
    </div>
</x-app-layout>

<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tight">
            {{ __('Edit Trek') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.treks.update', $trek->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
            @csrf
            @method('PUT')
            
            <!-- Section: Trek Details -->
            <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-2xl shadow-gray-100/50 overflow-hidden">
                <div class="p-10 border-b border-gray-50 bg-gray-50/20 flex justify-between items-center">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em] flex items-center">
                        <span class="w-8 h-0.5 bg-gray-900 mr-4"></span>
                        Trek Details
                    </h3>
                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">ID: {{ $trek->id }}</span>
                </div>
                <div class="p-10 space-y-8">
                    <div>
                        <label for="title" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Trek Name</label>
                        <input type="text" name="title" id="title" class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-bold placeholder-gray-300 focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" value="{{ old('title', $trek->title) }}" required>
                        @error('title') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Description</label>
                        <textarea name="description" id="description" rows="6" class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-medium placeholder-gray-300 focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" required>{{ old('description', $trek->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Price & Difficulty -->
            <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-2xl shadow-gray-100/50 overflow-hidden">
                <div class="p-10 border-b border-gray-50 bg-gray-50/20">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em] flex items-center">
                        <span class="w-8 h-0.5 bg-gray-900 mr-4"></span>
                        Price & Difficulty
                    </h3>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <label for="base_price" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Price (USD)</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 font-black">$</span>
                            <input type="number" name="base_price" id="base_price" step="0.01" class="block w-full pl-12 pr-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-black placeholder-gray-300 focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" value="{{ old('base_price', $trek->base_price) }}" required>
                        </div>
                        @error('base_price') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="difficulty" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Difficulty</label>
                        <select name="difficulty" id="difficulty" class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all appearance-none cursor-pointer" required>
                            <option value="Easy" {{ old('difficulty', $trek->difficulty) == 'Easy' ? 'selected' : '' }}>EASY</option>
                            <option value="Moderate" {{ old('difficulty', $trek->difficulty) == 'Moderate' ? 'selected' : '' }}>MODERATE</option>
                            <option value="Difficult" {{ old('difficulty', $trek->difficulty) == 'Difficult' ? 'selected' : '' }}>DIFFICULT</option>
                            <option value="Extreme" {{ old('difficulty', $trek->difficulty) == 'Extreme' ? 'selected' : '' }}>EXTREME</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Status</label>
                        <select name="status" id="status" class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all appearance-none cursor-pointer" required>
                            <option value="Active" {{ old('status', $trek->status) == 'Active' ? 'selected' : '' }}>ACTIVE (LIVE)</option>
                            <option value="Inactive" {{ old('status', $trek->status) == 'Inactive' ? 'selected' : '' }}>INACTIVE (HIDDEN)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section: Photos -->
            <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-2xl shadow-gray-100/50 overflow-hidden">
                <div class="p-10 border-b border-gray-50 bg-gray-50/20">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em] flex items-center">
                        <span class="w-8 h-0.5 bg-gray-900 mr-4"></span>
                        Photos
                    </h3>
                </div>
                <div class="p-10 space-y-12">
                    <!-- Primary Image -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 px-1">Main Image</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="relative group aspect-video overflow-hidden rounded-[1.5rem] border border-gray-100 shadow-lg">
                                <img src="{{ $trek->image ?? 'https://via.placeholder.com/600x400?text=NO+IMAGE' }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gray-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Current Image</span>
                                </div>
                            </div>
                            <label for="image" class="flex flex-col items-center justify-center w-full h-auto border-2 border-gray-100 border-dashed rounded-[1.5rem] cursor-pointer bg-gray-50 hover:bg-white hover:border-gray-900 transition-all group relative px-8 py-10">
                                <i class="fas fa-sync-alt text-xl text-gray-300 group-hover:text-gray-900 mb-3 transition-colors"></i>
                                <p class="text-[10px] font-black text-gray-400 group-hover:text-gray-900 transition-colors uppercase tracking-widest text-center">Upload New Image</p>
                                <input id="image" name="image" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" />
                            </label>
                        </div>
                        @error('image') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Gallery Management -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 px-1">Gallery Images</label>
                        
                        @if($trek->gallery && $trek->gallery->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                                @foreach($trek->gallery as $img)
                                    <div class="relative group aspect-square rounded-2xl overflow-hidden border border-gray-50 shadow-sm">
                                        <img src="{{ $img->path }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        
                                        <label class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm p-2 rounded-xl border border-gray-100 cursor-pointer hover:bg-red-500 hover:border-red-500 group/btn transition-all shadow-md">
                                            <input type="checkbox" name="remove_gallery[]" value="{{ $img->id }}" class="hidden peer">
                                            <i class="fas fa-trash-alt text-[10px] text-gray-400 peer-checked:text-white group-hover/btn:text-white transition-colors"></i>
                                            <div class="absolute inset-0 rounded-xl peer-checked:ring-2 peer-checked:ring-red-500 pointer-events-none"></div>
                                        </label>

                                        <div class="absolute inset-0 bg-red-600/20 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="p-6 bg-red-50 rounded-2xl border border-red-100/50 mb-10 flex items-center">
                                <i class="fas fa-info-circle text-red-300 mr-4"></i>
                                <span class="text-[10px] font-black text-red-400 uppercase tracking-widest leading-relaxed">System Info: Images with the trash icon will be deleted when you update the trek.</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-center w-full">
                            <label for="gallery" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-100 border-dashed rounded-[2rem] cursor-pointer bg-gray-50 hover:bg-white hover:border-gray-900 transition-all group relative">
                                <div class="flex flex-col items-center justify-center py-4">
                                    <i class="fas fa-plus-circle text-xl text-gray-300 group-hover:text-gray-900 mb-2 transition-colors"></i>
                                    <p class="text-[10px] font-black text-gray-400 group-hover:text-gray-900 transition-colors uppercase tracking-widest">Add Gallery Images</p>
                                </div>
                                <input id="gallery" name="gallery[]" type="file" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" />
                            </label>
                        </div>
                        @error('gallery.*') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Update Button -->
            <div class="pt-10 flex justify-end">
                <button type="submit" class="px-12 py-5 bg-gray-900 border border-transparent rounded-[2rem] font-black text-sm text-white uppercase tracking-[0.3em] hover:bg-black active:scale-95 transition-all shadow-2xl shadow-gray-200">
                    Update Trek
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>

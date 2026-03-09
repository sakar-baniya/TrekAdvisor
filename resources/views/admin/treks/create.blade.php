<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tight">
            {{ __('Add New Trek') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.treks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
            @csrf
            
            <!-- Section: Trek Details -->
            <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-2xl shadow-gray-100/50 overflow-hidden">
                <div class="p-10 border-b border-gray-50 bg-gray-50/20">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em] flex items-center">
                        <span class="w-8 h-0.5 bg-gray-900 mr-4"></span>
                        Trek Details
                    </h3>
                </div>
                <div class="p-10 space-y-8">
                    <div>
                        <label for="title" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Trek Name</label>
                        <input type="text" name="title" id="title" class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-bold placeholder-gray-300 focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" placeholder="e.g. Everest Base Camp" required value="{{ old('title') }}">
                        @error('title') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Description</label>
                        <textarea name="description" id="description" rows="6" class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-medium placeholder-gray-300 focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" placeholder="Enter trek details here..." required>{{ old('description') }}</textarea>
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
                            <input type="number" name="base_price" id="base_price" step="0.01" class="block w-full pl-12 pr-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-black placeholder-gray-300 focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" placeholder="0.00" required value="{{ old('base_price') }}">
                        </div>
                        @error('base_price') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="difficulty" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Difficulty</label>
                        <select name="difficulty" id="difficulty" class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all appearance-none cursor-pointer" required>
                            <option value="Easy" {{ old('difficulty') == 'Easy' ? 'selected' : '' }}>EASY</option>
                            <option value="Moderate" {{ old('difficulty') == 'Moderate' || !old('difficulty') ? 'selected' : '' }}>MODERATE</option>
                            <option value="Difficult" {{ old('difficulty') == 'Difficult' ? 'selected' : '' }}>DIFFICULT</option>
                            <option value="Extreme" {{ old('difficulty') == 'Extreme' ? 'selected' : '' }}>EXTREME</option>
                        </select>
                        @error('difficulty') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Status</label>
                        <select name="status" id="status" class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all appearance-none cursor-pointer" required>
                            <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>ACTIVE (LIVE)</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>INACTIVE (HIDDEN)</option>
                        </select>
                        @error('status') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
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
                <div class="p-10 space-y-10">
                    <!-- Primary Image -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 px-1">Main Image</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-100 border-dashed rounded-[2rem] cursor-pointer bg-gray-50 hover:bg-white hover:border-gray-900 transition-all group overflow-hidden relative">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-image text-2xl text-gray-300 group-hover:text-gray-900 mb-4 transition-colors"></i>
                                    <p class="text-xs font-black text-gray-400 group-hover:text-gray-900 transition-colors uppercase tracking-widest">Upload Main Image</p>
                                </div>
                                <input id="image" name="image" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" />
                            </label>
                        </div>
                        @error('image') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Gallery -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 px-1">Gallery Images</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="gallery" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-100 border-dashed rounded-[2rem] cursor-pointer bg-gray-50 hover:bg-white hover:border-gray-900 transition-all group relative">
                                <div class="flex flex-col items-center justify-center py-4">
                                    <i class="fas fa-images text-xl text-gray-300 group-hover:text-gray-900 mb-2 transition-colors"></i>
                                    <p class="text-[10px] font-black text-gray-400 group-hover:text-gray-900 transition-colors uppercase tracking-widest">Upload Multiple Images</p>
                                </div>
                                <input id="gallery" name="gallery[]" type="file" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" />
                            </label>
                        </div>
                        @error('gallery.*') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 px-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-10 flex justify-end">
                <button type="submit" class="px-12 py-5 bg-gray-900 border border-transparent rounded-[2rem] font-black text-sm text-white uppercase tracking-[0.3em] hover:bg-black active:scale-95 transition-all shadow-2xl shadow-gray-200">
                    Save Trek
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>

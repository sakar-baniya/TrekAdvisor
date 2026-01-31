<x-app-layout>
        <div class="flex items-center">
            <a href="{{ route('admin.treks.index') }}" class="mr-4 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Trek') }}: <span class="text-gray-500">{{ $trek->title }}</span>
            </h2>
        </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.treks.update', $trek->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="title" class="block font-medium text-sm text-gray-700">Trek Title</label>
                                <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('title', $trek->title) }}" required>
                                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="base_price" class="block font-medium text-sm text-gray-700">Base Price ($)</label>
                                <input type="number" name="base_price" id="base_price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('base_price', $trek->base_price) }}" required>
                                @error('base_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="difficulty" class="block font-medium text-sm text-gray-700">Difficulty</label>
                                <select name="difficulty" id="difficulty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="Easy" {{ old('difficulty', $trek->difficulty) == 'Easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="Moderate" {{ old('difficulty', $trek->difficulty) == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                    <option value="Difficult" {{ old('difficulty', $trek->difficulty) == 'Difficult' ? 'selected' : '' }}>Difficult</option>
                                    <option value="Extreme" {{ old('difficulty', $trek->difficulty) == 'Extreme' ? 'selected' : '' }}>Extreme</option>
                                </select>
                                @error('difficulty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="Active" {{ old('status', $trek->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status', $trek->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('description', $trek->description) }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="image" class="block font-medium text-sm text-gray-700">Trek Image</label>
                                @if($trek->image)
                                    <div class="mt-2 mb-4">
                                        <img src="{{ $trek->image }}" alt="{{ $trek->title }}" class="h-32 w-48 object-cover rounded shadow-sm">
                                        <p class="text-gray-500 text-xs mt-1">Current image preview</p>
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="text-gray-500 text-xs mt-1">Leave empty to keep the current image. Max size: 2MB.</p>
                                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t border-gray-100 pt-6">
                            <a href="{{ route('admin.treks.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 mr-6 transition-colors">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-black active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 transition ease-in-out duration-150 shadow-md">
                                <i class="fas fa-sync mr-2"></i> Update Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

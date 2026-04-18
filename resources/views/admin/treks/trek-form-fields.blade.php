@php
    $imagePreview = old('existing_image', $trek->image);
    $galleryImages = $trek->relationLoaded('gallery') ? $trek->gallery : collect();
@endphp

@if ($errors->any())
    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
        <p class="text-sm font-black text-red-800 uppercase tracking-widest">Action Required</p>
        <p class="text-xs font-semibold text-red-600 mt-1">Please fix the highlighted fields and try again.</p>
    </div>
@endif

<div class="space-y-12" x-data="{
    itinerary: {{ json_encode(old('itinerary', $trek->itineraries->map(fn($day) => ['title' => $day->title, 'description' => $day->description])->values()->all()) ?: [['title' => '', 'description' => '']]) }},
    addDay() {
        this.itinerary.push({ title: '', description: '' });
    },
    removeDay(index) {
        if (this.itinerary.length > 1) {
            this.itinerary.splice(index, 1);
        } else {
            this.itinerary[0] = { title: '', description: '' };
        }
    }
}">
    <!-- Basic Info Section -->
    <x-dashboard.basic-information-section :trek="$trek" :edit="isset($edit) ? $edit : false" :errors="$errors ?? null" />

    <!-- Media Section -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 md:p-10 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Trek Media</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Upload primary hero and gallery photos</p>
            </div>
            <label class="cursor-pointer">
                <input type="file" name="gallery_images[]" accept="image/*" multiple hidden>
                <span class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-900 hover:text-white transition-all">
                    Choose Photos
                </span>
            </label>
        </div>
        <div class="p-8 md:p-10">
            @if ($trek->images->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                    @foreach ($trek->images as $image)
                        <x-dashboard.media-card :image="$image" :isPrimary="$image->sort_order === 0" />
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center border-2 border-dashed border-slate-100 rounded-3xl mb-8">
                    <p class="text-xs font-bold text-slate-400 italic">No media uploaded yet.</p>
                </div>
            @endif
            
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">
                <i class="fas fa-info-circle mr-1 text-blue-500"></i> Max 4MB per file. High-resolution JPG or WEBP recommended.
            </p>
        </div>
    </section>

    <!-- Description Section -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black">
        <div class="p-8 md:p-10 border-b border-slate-50">
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Full Description *</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Main overview for customers and staff</p>
        </div>
        <div class="p-8 md:p-10">
            <textarea name="description" rows="10" class="w-full bg-slate-50 border-transparent rounded-3xl focus:ring-slate-900 focus:border-slate-900 text-sm font-medium p-6" required>{{ old('description', $trek->description) }}</textarea>
            @error('description') <p class="mt-2 text-[10px] font-black text-red-600 uppercase tracking-widest">{{ $message }}</p> @enderror
        </div>
    </section>

    <!-- Itinerary Section -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 md:p-10 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Day-by-Day Itinerary</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Add as many days as needed for the trek</p>
            </div>
            <button type="button" @click="addDay" class="inline-flex items-center px-6 py-3 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                <i class="fas fa-plus mr-2"></i> Add Day
            </button>
        </div>
        
        <div class="p-8 md:p-10 space-y-6">
            <template x-for="(day, index) in itinerary" :key="index">
                <div class="p-8 bg-slate-50/50 rounded-3xl border border-slate-100 group relative">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xs font-black text-slate-900 uppercase tracking-widest">
                            Day <span x-text="index + 1"></span>
                        </span>
                        <button type="button" @click="removeDay(index)" class="text-[10px] font-black text-red-400 hover:text-red-700 uppercase tracking-widest transition-colors">
                            Remove
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-ui.input type="text" ::name="'itinerary['+index+'][title]'" x-model="day.title" label="Title" placeholder="e.g. Flight to Lukla & Trek to Phakding" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 pl-1 mb-2">Description</label>
                            <textarea ::name="'itinerary['+index+'][description]'" x-model="day.description" rows="4" class="w-full bg-white border-slate-200 rounded-2xl focus:ring-slate-900 focus:border-slate-900 text-sm font-medium p-4" placeholder="Describe the activities, elevation gain, and sights for this day..."></textarea>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <!-- No form-actions here; they are in the parent files (create/edit) -->
</div>

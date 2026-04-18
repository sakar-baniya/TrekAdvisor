@php
    $imagePreview = old('existing_image', $trek->image);
    $galleryImages = $trek->relationLoaded('gallery') ? $trek->gallery : collect();

    // Resolve itinerary: use old() on validation fail, otherwise use DB data
    $existingItinerary = old('itinerary')
        ? collect(old('itinerary'))->map(fn($d) => ['title' => $d['title'] ?? '', 'description' => $d['description'] ?? ''])->values()->all()
        : $trek->itineraries->map(fn($d) => ['title' => $d->title, 'description' => $d->description ?? ''])->values()->all();

    if (empty($existingItinerary)) {
        $existingItinerary = [['title' => '', 'description' => '']];
    }
@endphp

@if ($errors->any())
    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
        <p class="text-sm font-display font-black text-red-800 uppercase tracking-widest">Action Required</p>
        <p class="text-xs font-semibold text-red-600 mt-1">Please fix the highlighted fields and try again.</p>
    </div>
@endif

<div class="space-y-12">
    <!-- Basic Info Section -->
    <x-dashboard.basic-information-section :trek="$trek" :edit="isset($edit) ? $edit : false" :errors="$errors ?? null" />

    <!-- Media Section -->
    <section class="bg-white border border-slate-200/70 rounded-2xl p-6 md:p-7 overflow-hidden text-black relative" x-data="{ isUploading: false }">
        
        <!-- Loading Overlay (shows during form submission) -->
        <div x-show="isUploading" style="display: none;" class="absolute inset-0 z-50 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center">
            <i class="fas fa-circle-notch fa-spin text-3xl text-slate-900 mb-3"></i>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-600">Uploading & Saving...</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-slate-900 text-base font-semibold tracking-tight">Trek Media</h3>
                <p class="text-slate-500 text-sm mt-1">Upload primary hero and gallery photos</p>
            </div>
            
            <div class="flex items-center gap-3">
                <input 
                    type="file" 
                    id="gallery_images_input" 
                    name="gallery_images[]" 
                    accept="image/*" 
                    multiple 
                    class="sr-only"
                    @change="isUploading = true; {{ isset($edit) && $edit ? '$event.target.form.submit()' : '' }}"
                >
                <label for="gallery_images_input" class="cursor-pointer inline-flex items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                    <i class="fas fa-upload mr-2"></i> Choose Photos
                </label>
            </div>
        </div>

        <div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-4" x-show="'{{ $trek->images->isNotEmpty() }}' == '1' || previews.length > 0">
                <!-- Existing Images -->
                @foreach ($trek->images as $image)
                    <x-dashboard.media-card :image="$image" :isPrimary="$image->sort_order === 0" />
                @endforeach
                
                <!-- New Local Previews -->
                <template x-for="(preview, index) in previews" :key="index">
                    <div class="relative rounded-2xl overflow-hidden aspect-video bg-slate-100 shadow-sm border border-slate-200/50">
                        <img :src="preview.url" class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-900/10 hover:bg-slate-900/0 transition-colors pointer-events-none">
                            <span class="px-3 py-1 bg-white/90 text-slate-800 text-[9px] font-black uppercase tracking-widest rounded-lg shadow-sm border border-slate-200">Will Save</span>
                        </div>
                    </div>
                </template>
            </div>

            @if ($trek->images->isEmpty())
                <div x-show="previews.length === 0" class="border-2 border-dashed border-slate-200 rounded-2xl py-10 flex flex-col items-center justify-center text-slate-500 text-sm mb-4">
                    <i class="fas fa-images text-2xl mb-2 opacity-20"></i>
                    <span>No media uploaded yet.</span>
                </div>
            @endif
            
            <p class="text-xs text-slate-500">
                <i class="fas fa-info-circle mr-1 text-slate-400"></i> Max 4MB per file. High-resolution JPG or WEBP recommended.
            </p>
        </div>
    </section>

    <!-- Description Section -->
    <section class="bg-white border border-slate-200/70 rounded-2xl p-6 md:p-7 overflow-hidden text-black">
        <div class="mb-6">
            <h3 class="text-slate-900 text-base font-semibold tracking-tight">Full Description *</h3>
            <p class="text-slate-500 text-sm mt-1">Main overview for customers and staff</p>
        </div>
        <div class="space-y-2">
            <textarea name="description" rows="10" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" required placeholder="Tell the story of this adventure...">{{ old('description', $trek->description) }}</textarea>
            @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
    </section>

    <!-- Itinerary Section -->
    {{--
        IMPORTANT: We use PHP-rendered static inputs for EXISTING days to guarantee reliable form submission.
        Alpine.js is used ONLY for adding new days dynamically (client-side only).
        This bypasses the x-for + textarea dynamic name binding bug in Alpine.js.
    --}}
    <section
        class="bg-white border border-slate-200/70 rounded-2xl p-6 md:p-7 overflow-hidden"
        x-data="{
            newDays: [],
            nextIndex: {{ count($existingItinerary) }},
            addDay() {
                this.newDays.push({ index: this.nextIndex, title: '', description: '' });
                this.nextIndex++;
            },
            removeNew(i) {
                this.newDays.splice(i, 1);
            }
        }"
    >
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-slate-900 text-base font-semibold tracking-tight">Day-by-Day Itinerary</h3>
                <p class="text-slate-500 text-sm mt-1">Add as many days as needed for the trek</p>
            </div>
            <button type="button" @click="addDay" class="inline-flex items-center px-4 py-2.5 bg-slate-900 text-white hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
                <i class="fas fa-plus mr-2 text-xs"></i> Add Day
            </button>
        </div>
        
        <div class="space-y-4" id="itinerary-days">
            
            {{-- ✅ STATIC: PHP-rendered inputs for existing/pre-filled days — always submitted correctly --}}
            @foreach ($existingItinerary as $dayIndex => $day)
                <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 relative" id="itinerary-day-{{ $dayIndex }}">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Day {{ $dayIndex + 1 }}</span>
                        <button
                            type="button"
                            class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors"
                            onclick="this.closest('[id^=itinerary-day-]').remove()"
                        >Remove</button>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-ui.input-label value="Title" class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1" />
                            <input
                                type="text"
                                name="itinerary[{{ $dayIndex }}][title]"
                                value="{{ $day['title'] }}"
                                placeholder="e.g. Flight to Lukla & Trek to Phakding"
                                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30"
                            />
                        </div>
                        <div>
                            <x-ui.input-label value="Description" class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1" />
                            <textarea
                                name="itinerary[{{ $dayIndex }}][description]"
                                rows="4"
                                placeholder="Describe the activities, elevation gain, and sights for this day..."
                                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30"
                            >{{ $day['description'] }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- ✅ DYNAMIC: Alpine-rendered inputs for NEW days added client-side --}}
            <template x-for="(day, i) in newDays" :key="day.index">
                <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 relative">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            New Day <span x-text="i + 1"></span>
                        </span>
                        <button type="button" @click="removeNew(i)" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors">Remove</button>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-ui.input-label value="Title" class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1" />
                            {{-- Hidden input carries title for reliable form submission --}}
                            <input type="hidden" :name="'itinerary[' + day.index + '][title]'" :value="day.title" />
                            <input
                                type="text"
                                x-model="day.title"
                                @input="day.title = $event.target.value"
                                placeholder="e.g. Rest day in Manang"
                                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30"
                            />
                        </div>
                        <div>
                            <x-ui.input-label value="Description" class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1" />
                            {{-- Hidden input carries description for reliable form submission --}}
                            <input type="hidden" :name="'itinerary[' + day.index + '][description]'" :value="day.description" />
                            <textarea
                                x-model="day.description"
                                @input="day.description = $event.target.value"
                                rows="4"
                                placeholder="Describe the activities, elevation gain, and sights for this day..."
                                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30"
                            ></textarea>
                        </div>
                    </div>
                </div>
            </template>

        </div>
    </section>

    <!-- No form-actions here; they are in the parent files (create/edit) -->
</div>


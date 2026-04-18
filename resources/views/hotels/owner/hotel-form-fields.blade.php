@php
    $imagePreview = old('existing_image', $hotel->image);
    $galleryImages = $hotel->relationLoaded('gallery') ? $hotel->gallery : collect();
@endphp

@if ($errors->any())
    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
        <p class="text-sm font-semibold text-red-800 uppercase tracking-widest">Error</p>
        <p class="text-xs font-semibold text-red-600 mt-1">Please fix the highlighted fields and try again.</p>
    </div>
@endif

<div class="space-y-12">
    <!-- Basic Information -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black">
        <div class="p-8 md:p-10 border-b border-slate-50">
            <h3 class="text-xl font-semibold text-slate-900 tracking-tight">Basic Information</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Core details guests see when browsing</p>
        </div>
        <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <x-ui.input-label value="Hotel Name *" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                <x-ui.text-input type="text" name="name" :value="old('name', $hotel->name)" required />
                @error('name') <p class="text-[10px] font-semibold text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <x-ui.input-label value="Location *" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                <x-ui.text-input type="text" name="location" :value="old('location', $hotel->location)" required />
                @error('location') <p class="text-[10px] font-semibold text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <!-- Main Image Section -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 md:p-10 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4 text-black">
            <div>
                <h3 class="text-xl font-semibold text-slate-900 tracking-tight">Main Hotel Image</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Appears on hotel cards and search results</p>
            </div>
            <label class="cursor-pointer">
                <input type="file" name="image" accept="image/*" hidden data-image-input>
                <span class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-600 text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-900 hover:text-white transition-all">
                    Choose Hero Image
                </span>
            </label>
        </div>
        <div class="p-8 md:p-10 flex flex-col items-center justify-center min-h-[200px] bg-slate-50/50" data-image-preview>
            @if ($imagePreview)
                <img src="{{ $imagePreview }}" class="max-h-64 rounded-3xl shadow-lg border-4 border-white" alt="Hotel preview">
            @else
                <div class="text-center">
                    <i class="fas fa-image text-4xl text-slate-200 mb-2"></i>
                    <p class="text-xs font-bold text-slate-400 italic">No image selected or uploaded yet.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black">
        <div class="p-8 md:p-10 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-slate-900 tracking-tight">Property Gallery</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Showcase rooms, views, and amenities</p>
            </div>
            <label class="cursor-pointer">
                <input type="file" name="gallery_images[]" accept="image/*" multiple hidden data-gallery-input>
                <span class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-600 text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-900 hover:text-white transition-all">
                    Upload Photos
                </span>
            </label>
        </div>
        <div class="p-8 md:p-10">
            @if ($galleryImages->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($galleryImages as $image)
                        <div class="relative group rounded-3xl overflow-hidden shadow-sm aspect-square border border-slate-100">
                             <img src="{{ $image->path }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                             <div class="absolute inset-x-0 bottom-0 bg-white/90 backdrop-blur-sm p-3 flex items-center gap-2">
                                 <input type="checkbox" name="remove_gallery_images[]" value="{{ $image->id }}" class="rounded text-slate-900 focus:ring-slate-900">
                                 <span class="text-[10px] font-semibold text-red-600 uppercase tracking-widest">Remove</span>
                             </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center border-2 border-dashed border-slate-100 rounded-3xl">
                    <p class="text-xs font-bold text-slate-400 italic">No gallery images found.</p>
                </div>
            @endif
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" data-gallery-preview></div>
        </div>
    </section>

    <!-- Description -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black">
        <div class="p-8 md:p-10 border-b border-slate-50">
            <h3 class="text-xl font-semibold text-slate-900 tracking-tight">Description *</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Highlight stays and unique amenities</p>
        </div>
        <div class="p-8 md:p-10">
            <textarea name="description" rows="8" class="w-full bg-slate-50 border-transparent rounded-3xl focus:ring-black focus:border-black text-sm font-medium p-6" required>{{ old('description', $hotel->description) }}</textarea>
            @error('description') <p class="mt-2 text-[10px] font-semibold text-red-600 uppercase tracking-widest">{{ $message }}</p> @enderror
        </div>
    </section>

    <!-- Policy -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black">
        <div class="p-8 md:p-10 border-b border-slate-50">
            <h3 class="text-xl font-semibold text-slate-900 tracking-tight">Booking Policy</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Cancellation, check-in, and guest rules</p>
        </div>
        <div class="p-8 md:p-10">
            <textarea name="booking_policy" rows="5" class="w-full bg-slate-50 border-transparent rounded-3xl focus:ring-black focus:border-black text-sm font-medium p-6" placeholder="Example: Free cancellation up to 48 hours before check-in. Check-in after 1 PM. Valid government ID required.">{{ old('booking_policy', $hotel->booking_policy) }}</textarea>
            @error('booking_policy') <p class="mt-2 text-[10px] font-semibold text-red-600 uppercase tracking-widest">{{ $message }}</p> @enderror
        </div>
    </section>

    <!-- Inline Script for Preview (Keeping logic but making it cleaner) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const imageInput = document.querySelector('[data-image-input]');
            const imagePreview = document.querySelector('[data-image-preview]');
            const galleryInput = document.querySelector('[data-gallery-input]');
            const galleryPreview = document.querySelector('[data-gallery-preview]');

            imageInput?.addEventListener('change', () => {
                const [file] = imageInput.files || [];
                if (file && imagePreview) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imagePreview.innerHTML = `<img src="${e.target.result}" class="max-h-64 rounded-3xl shadow-lg border-4 border-white" alt="Preview">`;
                    };
                    reader.readAsDataURL(file);
                }
            });

            galleryInput?.addEventListener('change', () => {
                if (!galleryPreview) return;
                galleryPreview.innerHTML = '';
                [...(galleryInput.files || [])].forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'relative rounded-3xl overflow-hidden shadow-sm aspect-square border border-slate-100 opacity-60';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        galleryPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>
</div>


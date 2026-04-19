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

<div class="space-y-12 pb-24">
    <!-- Basic Information -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
        <div class="p-8 md:p-10 border-b border-slate-50 flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                <i class="fas fa-hotel"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Basic Information</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Core details guests see when browsing</p>
            </div>
        </div>
        <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div class="space-y-2">
                    <x-ui.input-label value="Hotel Name *" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                    <x-ui.text-input type="text" name="name" :value="old('name', $hotel->name)" required placeholder="e.g. Kathmandu Heritage Inn" />
                    @error('name') <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <x-ui.input-label value="Location (City/Region) *" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                    <x-ui.text-input type="text" name="location" :value="old('location', $hotel->location)" required placeholder="e.g. Thamel, Kathmandu" />
                    @error('location') <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="p-6 bg-slate-50/80 rounded-3xl border border-slate-100 space-y-4">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Geographic Coordinates</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <x-ui.input-label value="Latitude" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                        <x-ui.text-input type="text" id="latitude" name="latitude" :value="old('latitude', $hotel->latitude)" readonly class="bg-slate-100/50 cursor-not-allowed" />
                    </div>
                    <div class="space-y-2">
                        <x-ui.input-label value="Longitude" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                        <x-ui.text-input type="text" id="longitude" name="longitude" :value="old('longitude', $hotel->longitude)" readonly class="bg-slate-100/50 cursor-not-allowed" />
                    </div>
                </div>
                <button type="button" id="btn-show-picker" class="w-full px-4 py-3 bg-white border border-slate-200 text-[10px] font-bold uppercase tracking-widest rounded-2xl hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                    <i class="fas fa-map-location-dot mr-2"></i> Update Location on Map
                </button>
            </div>
        </div>

        <!-- Hidden Map Container -->
        <div id="picker-container" class="hidden px-8 md:px-10 pb-8 md:pb-10 animate-in fade-in slide-in-from-top-4 duration-500">
            <div id="map-picker" class="w-full h-80 rounded-[2rem] border-4 border-slate-50 shadow-inner z-0 overflow-hidden"></div>
            <div class="mt-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-start gap-3">
                <i class="fas fa-lightbulb text-emerald-500 mt-0.5"></i>
                <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest leading-relaxed">
                    Tip: Click anywhere or drag the blue marker to pinpoint your hotel's exact door location for guests.
                </p>
            </div>
        </div>
    </section>

    <!-- Rooms & Categories (Dynamic Section) -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
        <div class="p-8 md:p-10 border-b border-slate-50 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                <i class="fas fa-door-open"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Room Management</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Define categories and seasonal pricing</p>
            </div>
        </div>
        <div class="p-8 md:p-10">
            @include('hotels.owner.components.hotel-rooms-repeater', ['hotel' => $hotel])
        </div>
    </section>

    <!-- Visual Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Image -->
        <section class="lg:col-span-1 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black flex flex-col">
            <div class="p-6 md:p-8 border-b border-slate-50">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Cover Photo</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Primary listing visual</p>
            </div>
            <div class="p-6 md:p-8 flex-1 flex flex-col gap-6">
                <div id="image-preview-box" class="aspect-[4/3] bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-100 flex items-center justify-center overflow-hidden">
                    @if ($imagePreview)
                        <img src="{{ $imagePreview }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-image text-4xl text-slate-200"></i>
                    @endif
                </div>
                <label class="block">
                    <input type="file" name="image" accept="image/*" hidden data-image-input>
                    <div class="w-full py-4 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-widest rounded-2xl hover:bg-slate-900 hover:text-white transition-all text-center cursor-pointer">
                        <i class="fas fa-cloud-arrow-up mr-2"></i> Select File
                    </div>
                </label>
            </div>
        </section>

        <!-- Gallery -->
        <section class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black">
            <div class="p-6 md:p-8 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Photo Gallery</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">At least 4-6 high quality photos</p>
                </div>
                <label class="cursor-pointer">
                    <input type="file" name="gallery_images[]" accept="image/*" multiple hidden data-gallery-input>
                    <span class="inline-flex items-center px-6 py-3 bg-slate-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Add Photos
                    </span>
                </label>
            </div>
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4" id="gallery-grid">
                    @foreach ($galleryImages as $image)
                        <div class="relative group rounded-2xl overflow-hidden aspect-square border-2 border-slate-50">
                             <img src="{{ $image->path }}" class="w-full h-full object-cover">
                             <div class="absolute inset-0 bg-red-600/0 group-hover:bg-red-600/40 transition-all flex items-center justify-center">
                                 <label class="hidden group-hover:flex items-center gap-2 px-3 py-1.5 bg-white text-red-600 rounded-full text-[9px] font-bold uppercase tracking-widest cursor-pointer shadow-lg">
                                     <input type="checkbox" name="remove_gallery_images[]" value="{{ $image->id }}" class="rounded-sm border-red-200 text-red-600 focus:ring-red-600">
                                     Delete
                                 </label>
                             </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4" data-gallery-preview></div>
            </div>
        </section>
    </div>

    <!-- Content & Policies -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black">
            <div class="p-8 md:p-10 border-b border-slate-50 flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i class="fas fa-align-left"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">Description *</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Tell guests what makes your place special</p>
                </div>
            </div>
            <div class="p-8 md:p-10">
                <textarea name="description" rows="10" 
                    class="w-full bg-slate-50 border-transparent rounded-[2rem] focus:ring-slate-900 focus:bg-white text-sm font-medium p-6 transition-all" 
                    placeholder="Describe your hotel, the hospitality, and nearby attractions..."
                    required>{{ old('description', $hotel->description) }}</textarea>
                @error('description') <p class="mt-2 text-[10px] font-bold text-red-600 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>
        </section>

        <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black">
            <div class="p-8 md:p-10 border-b border-slate-50 flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">Booking Policy</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Cancellation and guest rules</p>
                </div>
            </div>
            <div class="p-8 md:p-10">
                <textarea name="booking_policy" rows="10" 
                    class="w-full bg-slate-50 border-transparent rounded-[2rem] focus:ring-slate-900 focus:bg-white text-sm font-medium p-6 transition-all" 
                    placeholder="e.g. 48h Full Refund. Check-in 1 PM - 10 PM. No pets allowed."
                    >{{ old('booking_policy', $hotel->booking_policy) }}</textarea>
                @error('booking_policy') <p class="mt-2 text-[10px] font-bold text-red-600 uppercase tracking-widest">{{ $message }}</p> @enderror
            </div>
        </section>
    </div>

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

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            .leaflet-container { font-family: inherit; cursor: crosshair !important; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const btnShow = document.getElementById('btn-show-picker');
                const container = document.getElementById('picker-container');
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                
                let pickerMap = null;
                let pickerMarker = null;

                btnShow?.addEventListener('click', () => {
                    container.classList.toggle('hidden');
                    if (!container.classList.contains('hidden')) {
                        initPickerMap();
                        btnShow.innerHTML = '<i class="fas fa-times mr-2"></i> Close Map Picker';
                    } else {
                        btnShow.innerHTML = '<i class="fas fa-map-location-dot mr-2"></i> Open Map Picker';
                    }
                });

                function initPickerMap() {
                    if (pickerMap) {
                        setTimeout(() => pickerMap.invalidateSize(), 100);
                        return;
                    }

                    const initialLat = parseFloat(latInput.value) || 27.7172;
                    const initialLng = parseFloat(lngInput.value) || 85.3240;

                    pickerMap = L.map('map-picker').setView([initialLat, initialLng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(pickerMap);

                    pickerMarker = L.marker([initialLat, initialLng], { draggable: true }).addTo(pickerMap);

                    // Update inputs when marker is dragged
                    pickerMarker.on('dragend', function(e) {
                        const pos = pickerMarker.getLatLng();
                        latInput.value = pos.lat.toFixed(6);
                        lngInput.value = pos.lng.toFixed(6);
                    });

                    // Update marker and inputs when map is clicked
                    pickerMap.on('click', function(e) {
                        pickerMarker.setLatLng(e.latlng);
                        latInput.value = e.latlng.lat.toFixed(6);
                        lngInput.value = e.latlng.lng.toFixed(6);
                    });
                }
            });
        </script>
    @endpush
</div>


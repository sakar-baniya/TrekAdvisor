@php
    $imagePreview = old('existing_image', $hotel->image);
    $galleryImages = $hotel->relationLoaded('gallery') ? $hotel->gallery : collect();
@endphp

@if ($errors->any())
    <div class="admin-flash error">
        Please fix the highlighted fields and try again.
    </div>
@endif

<div class="admin-form-stack">
    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Hotel Details</h3>
                <p>Core information guests see when browsing your property.</p>
            </div>
        </div>
        <div class="admin-form-grid admin-form-grid--two">
            <label class="admin-field">
                <span>Hotel Name *</span>
                <input type="text" name="name" value="{{ old('name', $hotel->name) }}" class="admin-input" required>
                @error('name') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Location *</span>
                <input type="text" name="location" value="{{ old('location', $hotel->location) }}" class="admin-input" required>
                @error('location') <small class="admin-error">{{ $message }}</small> @enderror
            </label>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Main Hotel Image</h3>
                <p>This appears on cards and at the top of the hotel page.</p>
            </div>
        </div>
        <div class="admin-form-grid admin-form-grid--media">
            <label class="admin-upload-card">
                <input type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp" hidden data-image-input>
                <span class="admin-secondary-button">Choose Image</span>
                <small>Max 2MB. JPG, PNG, or WEBP.</small>
            </label>
            <div class="admin-image-preview" data-image-preview>
                @if ($imagePreview)
                    <img src="{{ $imagePreview }}" alt="Hotel preview">
                @else
                    <span>No image selected yet</span>
                @endif
            </div>
        </div>
        @error('image') <small class="admin-error admin-error--block">{{ $message }}</small> @enderror
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Hotel Gallery</h3>
                <p>Add multiple photos for rooms, exteriors, views, and amenities.</p>
            </div>
        </div>
        <div class="admin-form-grid">
            <label class="admin-upload-card">
                <input type="file" name="gallery_images[]" accept="image/png,image/jpeg,image/jpg,image/webp" multiple hidden data-gallery-input>
                <span class="admin-secondary-button">Choose Photos</span>
                <small>Upload multiple JPG, PNG, or WEBP files. Max 4MB each.</small>
            </label>

            @if ($galleryImages->isNotEmpty())
                <div class="admin-gallery-grid">
                    @foreach ($galleryImages as $image)
                        <label class="admin-gallery-card">
                            <img src="{{ $image->path }}" alt="Hotel gallery image {{ $loop->iteration }}">
                            <span class="admin-gallery-card__footer">
                                <input type="checkbox" name="remove_gallery_images[]" value="{{ $image->id }}">
                                <span>Remove</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif

            <div class="admin-gallery-grid admin-gallery-grid--pending" data-gallery-preview></div>
        </div>
        @error('gallery_images') <small class="admin-error admin-error--block">{{ $message }}</small> @enderror
        @error('gallery_images.*') <small class="admin-error admin-error--block">{{ $message }}</small> @enderror
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Description *</h3>
                <p>Describe the stay experience, setting, and standout amenities.</p>
            </div>
        </div>
        <div class="admin-form-grid">
            <label class="admin-field admin-field--full">
                <textarea name="description" rows="8" class="admin-input admin-textarea" required>{{ old('description', $hotel->description) }}</textarea>
                @error('description') <small class="admin-error">{{ $message }}</small> @enderror
            </label>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Booking Policy</h3>
                <p>Show guests your cancellation, check-in, and booking rules before they request.</p>
            </div>
        </div>
        <div class="admin-form-grid">
            <label class="admin-field admin-field--full">
                <textarea name="booking_policy" rows="5" class="admin-input admin-textarea" placeholder="Example: Free cancellation up to 48 hours before check-in. Check-in after 1 PM. Valid government ID required at arrival.">{{ old('booking_policy', $hotel->booking_policy) }}</textarea>
                @error('booking_policy') <small class="admin-error">{{ $message }}</small> @enderror
            </label>
        </div>
    </section>

    <div class="admin-form-actions">
        <a href="{{ route('hotel_owner.hotels.index') }}" class="admin-secondary-button">
            <span>Back to Hotels</span>
        </a>
        <button type="submit" class="admin-primary-button">
            <span>{{ $hotel->exists ? 'Update Hotel' : 'Save Hotel' }}</span>
        </button>
    </div>
</div>

<script>
    (() => {
        const imageInput = document.querySelector('[data-image-input]');
        const imagePreview = document.querySelector('[data-image-preview]');
        const galleryInput = document.querySelector('[data-gallery-input]');
        const galleryPreview = document.querySelector('[data-gallery-preview]');

        imageInput?.addEventListener('change', () => {
            const [file] = imageInput.files || [];

            if (!file || !imagePreview) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                imagePreview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        });

        galleryInput?.addEventListener('change', () => {
            if (!galleryPreview) {
                return;
            }

            galleryPreview.innerHTML = '';

            [...(galleryInput.files || [])].forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const card = document.createElement('div');
                    card.className = 'admin-gallery-card admin-gallery-card--pending';
                    card.innerHTML = `
                        <img src="${event.target.result}" alt="New gallery preview ${index + 1}">
                        <span class="admin-gallery-card__footer">
                            <span>${file.name}</span>
                        </span>
                    `;
                    galleryPreview.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
        });
    })();
</script>

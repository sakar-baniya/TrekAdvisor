@if ($errors->any())
    <div class="admin-flash error">Please fix the gear form errors and try again.</div>
@endif

@php
    $imagePreview = $gearItem->image;
@endphp

<div class="admin-form-stack">
    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Gear Details</h3>
                <p>Set pricing, stock, and item details for rentals</p>
            </div>
        </div>

        <div class="admin-form-grid admin-form-grid--two">
            <label class="admin-field">
                <span>Name *</span>
                <input type="text" name="name" value="{{ old('name', $gearItem->name) }}" class="admin-input" required />
                @error('name') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Type *</span>
                <input type="text" name="type" value="{{ old('type', $gearItem->type) }}" class="admin-input" placeholder="Backpack, Sleeping Gear, Jacket" required />
                @error('type') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field admin-field--full">
                <span>Description</span>
                <textarea name="description" rows="4" class="admin-input" placeholder="Key use case, weather suitability, and who this item is best for">{{ old('description', $gearItem->description) }}</textarea>
                @error('description') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Daily Price (USD) *</span>
                <input type="number" step="0.01" min="0" name="daily_price" value="{{ old('daily_price', $gearItem->daily_price) }}" class="admin-input" required />
                @error('daily_price') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Total Stock *</span>
                <input type="number" min="0" name="total_stock" value="{{ old('total_stock', $gearItem->total_stock) }}" class="admin-input" required />
                @error('total_stock') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Available Stock *</span>
                <input type="number" min="0" name="available_stock" value="{{ old('available_stock', $gearItem->available_stock ?? $gearItem->total_stock) }}" class="admin-input" required />
                @error('available_stock') <small class="admin-error">{{ $message }}</small> @enderror
            </label>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Item Image</h3>
                <p>Upload a photo for the admin list and rental view</p>
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
                    <img src="{{ $imagePreview }}" alt="Gear preview">
                @else
                    <span>No image selected yet</span>
                @endif
            </div>
        </div>
        @error('image') <small class="admin-error admin-error--block">{{ $message }}</small> @enderror
    </section>

    <div class="admin-form-actions">
        <a href="{{ route('admin.gear.index') }}" class="admin-secondary-button">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Gear</span>
        </a>
        <button type="submit" class="admin-primary-button">
            <i class="fas fa-floppy-disk"></i>
            <span>{{ $gearItem->exists ? 'Update Item' : 'Save Item' }}</span>
        </button>
    </div>
</div>

<script>
    (() => {
        const imageInput = document.querySelector('[data-image-input]');
        const imagePreview = document.querySelector('[data-image-preview]');

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
    })();
</script>

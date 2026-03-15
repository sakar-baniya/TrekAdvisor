<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="admin-title">{{ __('Edit Trek') }}</h2>
    </x-slot>

    <form action="{{ route('admin.treks.update', $trek->id) }}" method="POST" enctype="multipart/form-data" class="admin-form">
        @csrf
        @method('PUT')

        <div class="admin-section">
            <div class="admin-section-header">
                <h3 class="admin-section-title">Trek Details</h3>
            </div>
            <div class="admin-section-body">
                <div class="admin-field">
                    <label for="title">Trek Name</label>
                    <input type="text" name="title" id="title" class="admin-input" required value="{{ old('title', $trek->title) }}">
                    @error('title') <p class="admin-error">{{ $message }}</p> @enderror
                </div>

                <div class="admin-field">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="6" class="admin-textarea" required>{{ old('description', $trek->description) }}</textarea>
                    @error('description') <p class="admin-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="admin-section">
            <div class="admin-section-header">
                <h3 class="admin-section-title">Price & Difficulty</h3>
            </div>
            <div class="admin-section-body">
                <div class="admin-field">
                    <label for="base_price">Price (USD)</label>
                    <div class="admin-price">
                        <span>$</span>
                        <input type="number" name="base_price" id="base_price" step="0.01" class="admin-input" required value="{{ old('base_price', $trek->base_price) }}">
                    </div>
                    @error('base_price') <p class="admin-error">{{ $message }}</p> @enderror
                </div>

                <div class="admin-field">
                    <label for="difficulty">Difficulty</label>
                    <select name="difficulty" id="difficulty" class="admin-select" required>
                        <option value="Easy" {{ old('difficulty', $trek->difficulty) == 'Easy' ? 'selected' : '' }}>Easy</option>
                        <option value="Moderate" {{ old('difficulty', $trek->difficulty) == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="Difficult" {{ old('difficulty', $trek->difficulty) == 'Difficult' ? 'selected' : '' }}>Difficult</option>
                        <option value="Extreme" {{ old('difficulty', $trek->difficulty) == 'Extreme' ? 'selected' : '' }}>Extreme</option>
                    </select>
                    @error('difficulty') <p class="admin-error">{{ $message }}</p> @enderror
                </div>

                <div class="admin-field">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="admin-select" required>
                        <option value="Active" {{ old('status', $trek->status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $trek->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="admin-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="admin-section">
            <div class="admin-section-header">
                <h3 class="admin-section-title">Photos</h3>
            </div>
            <div class="admin-section-body">
                <div class="admin-field">
                    <label>Main Image</label>
                    <label for="image" class="admin-upload">
                        <i class="fas fa-image"></i>
                        <p>Upload Main Image</p>
                        <input id="image" name="image" type="file" accept="image/*" hidden />
                    </label>
                    @error('image') <p class="admin-error">{{ $message }}</p> @enderror
                </div>

                <div class="admin-field">
                    <label>Gallery Images</label>
                    <label for="gallery" class="admin-upload">
                        <i class="fas fa-images"></i>
                        <p>Upload Multiple Images</p>
                        <input id="gallery" name="gallery[]" type="file" multiple accept="image/*" hidden />
                    </label>
                    @error('gallery.*') <p class="admin-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="admin-submit">Update Trek</button>
    </form>
</x-dashboard-layout>

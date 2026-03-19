@php
    $itineraryRows = old('itinerary');

    if ($itineraryRows === null) {
        $itineraryRows = $trek->itineraries->map(fn ($day) => [
            'title' => $day->title,
            'description' => $day->description,
        ])->values()->all();
    }

    if (empty($itineraryRows)) {
        $itineraryRows = [['title' => '', 'description' => '']];
    }

    $imagePreview = old('existing_image', $trek->image);
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
                <h3>Basic Information</h3>
                <p>Core details that power search and bookings</p>
            </div>
        </div>
        <div class="admin-form-grid admin-form-grid--two">
            <label class="admin-field">
                <span>Trek Title *</span>
                <input type="text" name="title" value="{{ old('title', $trek->title) }}" class="admin-input" data-slug-source required />
                @error('title') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Slug</span>
                <input type="text" value="{{ old('slug', $trek->slug) }}" class="admin-input admin-input--muted" data-slug-target readonly />
            </label>

            <label class="admin-field">
                <span>Base Price (USD) *</span>
                <input type="number" step="0.01" min="0" name="base_price" value="{{ old('base_price', $trek->base_price) }}" class="admin-input" required />
                @error('base_price') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Difficulty *</span>
                <select name="difficulty" class="admin-input" required>
                    @foreach (['Easy', 'Moderate', 'Difficult', 'Extreme'] as $option)
                        <option value="{{ $option }}" @selected(old('difficulty', $trek->difficulty ?: 'Easy') === $option)>{{ $option }}</option>
                    @endforeach
                </select>
                @error('difficulty') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Duration (days) *</span>
                <input type="number" min="1" name="duration_days" value="{{ old('duration_days', $trek->duration_days) }}" class="admin-input" required />
                @error('duration_days') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Max Altitude (m)</span>
                <input type="number" min="0" name="max_altitude" value="{{ old('max_altitude', $trek->max_altitude) }}" class="admin-input" />
                @error('max_altitude') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <fieldset class="admin-field admin-field--full">
                <span>Status</span>
                <div class="admin-choice-row">
                    @foreach (['Active', 'Inactive'] as $option)
                        <label class="admin-choice-pill">
                            <input type="radio" name="status" value="{{ $option }}" @checked(old('status', $trek->status ?: 'Active') === $option)>
                            <span>{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
                @error('status') <small class="admin-error">{{ $message }}</small> @enderror
            </fieldset>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Trek Image</h3>
                <p>Upload a main hero image for cards and detail pages</p>
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
                    <img src="{{ $imagePreview }}" alt="Trek preview">
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
                <h3>Description *</h3>
                <p>Use this as the main trek overview for customers and staff</p>
            </div>
        </div>
        <div class="admin-form-grid">
            <label class="admin-field admin-field--full">
                <textarea name="description" rows="8" class="admin-input admin-textarea" required>{{ old('description', $trek->description) }}</textarea>
                @error('description') <small class="admin-error">{{ $message }}</small> @enderror
            </label>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Day-by-Day Itinerary</h3>
                <p>Add as many days as you need. Empty rows are ignored on save.</p>
            </div>
            <button type="button" class="admin-secondary-button" data-add-itinerary>
                <i class="fas fa-plus"></i>
                <span>Add Day</span>
            </button>
        </div>
        <div class="admin-itinerary-stack" data-itinerary-list>
            @foreach ($itineraryRows as $index => $day)
                <div class="admin-itinerary-card" data-itinerary-item>
                    <div class="admin-itinerary-card__header">
                        <strong>Day <span data-day-number>{{ $index + 1 }}</span></strong>
                        <button type="button" class="admin-link-button" data-remove-itinerary>Remove</button>
                    </div>
                    <div class="admin-form-grid">
                        <label class="admin-field">
                            <span>Title</span>
                            <input type="text" name="itinerary[{{ $index }}][title]" value="{{ $day['title'] ?? '' }}" class="admin-input" />
                            @error("itinerary.$index.title") <small class="admin-error">{{ $message }}</small> @enderror
                        </label>
                        <label class="admin-field admin-field--full">
                            <span>Description</span>
                            <textarea name="itinerary[{{ $index }}][description]" rows="4" class="admin-input admin-textarea">{{ $day['description'] ?? '' }}</textarea>
                            @error("itinerary.$index.description") <small class="admin-error">{{ $message }}</small> @enderror
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="admin-form-actions">
        <a href="{{ route('admin.treks.index') }}" class="admin-secondary-button">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Treks</span>
        </a>
        <button type="submit" class="admin-primary-button">
            <i class="fas fa-floppy-disk"></i>
            <span>{{ $trek->exists ? 'Update Trek' : 'Save Trek' }}</span>
        </button>
    </div>
</div>

<template id="itinerary-template">
    <div class="admin-itinerary-card" data-itinerary-item>
        <div class="admin-itinerary-card__header">
            <strong>Day <span data-day-number></span></strong>
            <button type="button" class="admin-link-button" data-remove-itinerary>Remove</button>
        </div>
        <div class="admin-form-grid">
            <label class="admin-field">
                <span>Title</span>
                <input type="text" class="admin-input" data-itinerary-field="title" />
            </label>
            <label class="admin-field admin-field--full">
                <span>Description</span>
                <textarea rows="4" class="admin-input admin-textarea" data-itinerary-field="description"></textarea>
            </label>
        </div>
    </div>
</template>

<script>
    (() => {
        const slugSource = document.querySelector('[data-slug-source]');
        const slugTarget = document.querySelector('[data-slug-target]');
        const imageInput = document.querySelector('[data-image-input]');
        const imagePreview = document.querySelector('[data-image-preview]');
        const list = document.querySelector('[data-itinerary-list]');
        const addButton = document.querySelector('[data-add-itinerary]');
        const template = document.getElementById('itinerary-template');

        const slugify = (value) => value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

        const refreshDayNumbers = () => {
            [...list.querySelectorAll('[data-itinerary-item]')].forEach((item, index) => {
                item.querySelector('[data-day-number]').textContent = index + 1;
                item.querySelector('[data-itinerary-field="title"]')?.setAttribute('name', `itinerary[${index}][title]`);
                item.querySelector('[data-itinerary-field="description"]')?.setAttribute('name', `itinerary[${index}][description]`);
                item.querySelector('input[name*="[title]"]')?.setAttribute('name', `itinerary[${index}][title]`);
                item.querySelector('textarea[name*="[description]"]')?.setAttribute('name', `itinerary[${index}][description]`);
            });
        };

        slugSource?.addEventListener('input', () => {
            slugTarget.value = slugify(slugSource.value);
        });

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

        addButton?.addEventListener('click', () => {
            const clone = template.content.firstElementChild.cloneNode(true);
            clone.querySelector('[data-itinerary-field="title"]').setAttribute('name', '');
            clone.querySelector('[data-itinerary-field="description"]').setAttribute('name', '');
            list.appendChild(clone);
            refreshDayNumbers();
        });

        list?.addEventListener('click', (event) => {
            const button = event.target.closest('[data-remove-itinerary]');
            if (!button) {
                return;
            }

            const items = list.querySelectorAll('[data-itinerary-item]');
            if (items.length === 1) {
                items[0].querySelector('input').value = '';
                items[0].querySelector('textarea').value = '';
                return;
            }

            button.closest('[data-itinerary-item]').remove();
            refreshDayNumbers();
        });

        refreshDayNumbers();
    })();
</script>

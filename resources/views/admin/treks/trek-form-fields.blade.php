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
    $galleryImages = $trek->relationLoaded('gallery') ? $trek->gallery : collect();
@endphp

@if ($errors->any())
    <div class="admin-flash error">
        Please fix the highlighted fields and try again.
    </div>
@endif

<div class="admin-form-stack">
    <x-dashboard.basic-information-section :trek="$trek" :edit="isset($edit) ? $edit : false" :errors="$errors ?? null" />

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Trek Media</h3>
                <p>Upload all photos here. Select one to be your primary "Hero" image.</p>
            </div>
        </div>
        <div class="admin-form-grid">
            <label class="admin-upload-card">
                <input type="file" name="gallery_images[]" accept="image/png,image/jpeg,image/jpg,image/webp" multiple hidden data-gallery-input>
                <span class="admin-secondary-button">Choose Photos</span>
                <small>Upload multiple JPG, PNG, or WEBP files. Max 4MB each.</small>
            </label>

            <!-- Show existing combined images -->
            @if ($trek->images->isNotEmpty())
                <div class="media-gallery-grid">
                    @foreach ($trek->images as $image)
                        <x-dashboard.media-card :image="$image" :isPrimary="$image->sort_order === 0" />
                    @endforeach
                </div>
            @endif

            <div class="admin-gallery-grid admin-gallery-grid--pending" data-gallery-preview></div>
        </div>
        @error('gallery_images') <small class="admin-error admin-error--block">{{ $message }}</small> @enderror
        @error('gallery_images.*') <small class="admin-error admin-error--block">{{ $message }}</small> @enderror
        @error('primary_image') <small class="admin-error admin-error--block">{{ $message }}</small> @enderror
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

    <!-- No form actions here; sticky action bar is used in parent -->
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

        const galleryInput = document.querySelector('[data-gallery-input]');
        const galleryPreview = document.querySelector('[data-gallery-preview]');
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
                        <img src="${event.target.result}" alt="New preview ${index + 1}">
                        <div class="admin-gallery-card__footer" style="flex-direction: column; gap: 4px; align-items: flex-start;">
                            <div>
                                <input type="radio" name="primary_image" value="new_${index}">
                                <span>${file.name}</span>
                            </div>
                            <small class="admin-input--muted">(Primary?)</small>
                        </div>
                    `;
                    galleryPreview.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
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

{{-- MediaCard Component --}}
@props(['image', 'isPrimary' => false])
<div class="media-card">
    <div class="media-card__thumb">
        <img src="{{ $image->path }}" alt="Trek image" />
        <button type="button" class="media-card__remove" aria-label="Remove image" name="remove_gallery_images[]" value="{{ $image->id }}">
            <i class="fas fa-xmark"></i>
        </button>
        @if($isPrimary)
            <span class="media-card__badge">Primary</span>
        @else
            <button type="button" class="media-card__set-primary" name="primary_image" value="{{ $image->id }}">Set as primary</button>
        @endif
    </div>
</div>

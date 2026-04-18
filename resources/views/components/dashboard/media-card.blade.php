@props(['image', 'isPrimary' => false])

@php
    // Determine the correct image path (handle existing absolute URLs vs relative storage paths)
    $path = Str::startsWith($image->path, ['http://', 'https://', '/images/', '/storage/']) 
        ? $image->path 
        : \Illuminate\Support\Facades\Storage::url($image->path);
@endphp

<div class="relative group rounded-2xl overflow-hidden aspect-video bg-slate-100 border border-slate-200/60 shadow-sm transition-all duration-300">
     
    <img src="{{ $path }}" alt="Trek photo" class="w-full h-full object-cover transition-opacity duration-300">
    
    <!-- Top Row: Remove Action (Always Visible) -->
    <div class="absolute top-3 right-3 z-20">
        <label class="cursor-pointer block" title="Mark for removal">
            <input type="checkbox" name="remove_gallery_images[]" value="{{ $image->id }}" class="peer sr-only" onchange="this.form.submit()">
            <div class="w-8 h-8 rounded-xl bg-white/90 text-red-500 flex items-center justify-center transition-all hover:bg-red-50 peer-checked:bg-red-500 peer-checked:text-white shadow-sm backdrop-blur-sm border border-slate-200/50">
                <i class="fas fa-trash-alt text-xs peer-checked:hidden"></i>
                <i class="fas fa-circle-notch fa-spin text-xs hidden peer-checked:block"></i>
            </div>
        </label>
    </div>
    

    
</div>

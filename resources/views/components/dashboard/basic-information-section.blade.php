<section class="bg-white border border-slate-200/70 rounded-2xl p-6 md:p-7 overflow-hidden">
    <div class="mb-6">
        <h3 class="text-slate-900 text-base font-semibold tracking-tight">Basic Information</h3>
        <p class="text-slate-500 text-sm mt-1">Core details and identity for this adventure</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Trek Title (full width) -->
        <div class="md:col-span-2 space-y-1">
            <label for="title" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Trek Title *</label>
            <input type="text" name="title" id="title" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" value="{{ old('title', $trek->title ?? '') }}" required placeholder="e.g. Everest Base Camp Luxury Trek">
            @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Slug (full width) -->
        <div class="md:col-span-2 space-y-1">
            <label for="slug" class="text-xs font-semibold uppercase tracking-wider text-slate-500">URL Slug *</label>
            <div class="relative">
                <input type="text" name="slug" id="slug" class="w-full rounded-xl border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 pr-12" value="{{ old('slug', $trek->slug ?? '') }}" readonly required>
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-900 transition-colors" onclick="document.getElementById('slug').removeAttribute('readonly');this.style.display='none';">
                    <i class="fas fa-edit text-xs"></i>
                </button>
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Auto-generated from title. Edit carefully.</p>
            @error('slug')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Base Price -->
        <div class="space-y-1">
            <label for="base_price" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Base Price (NPR) *</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">NPR</span>
                <input type="number" name="base_price" id="base_price" class="w-full rounded-xl border-slate-200 bg-white pl-12 pr-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" value="{{ old('base_price', $trek->base_price ?? '') }}" min="0" step="0.01" required>
            </div>
            @error('base_price')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Duration -->
        <div class="space-y-1">
            <label for="duration_days" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Duration (Days) *</label>
            <div class="relative">
                <input type="number" name="duration_days" id="duration_days" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" value="{{ old('duration_days', $trek->duration_days ?? '') }}" min="1" required>
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">days</span>
            </div>
            @error('duration_days')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Max Altitude -->
        <div class="space-y-1">
            <label for="max_altitude" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Max Altitude (m)</label>
            <div class="relative">
                <input type="number" name="max_altitude" id="max_altitude" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" value="{{ old('max_altitude', $trek->max_altitude ?? '') }}" min="0">
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">meters</span>
            </div>
            @error('max_altitude')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Difficulty -->
        <div class="space-y-1">
            <label for="difficulty" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Difficulty *</label>
            <select name="difficulty" id="difficulty" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 appearance-none cursor-pointer" required>
                <option value="">Select level...</option>
                @foreach(['easy' => 'Easy', 'moderate' => 'Moderate', 'difficult' => 'Difficult', 'extreme' => 'Extreme'] as $value => $label)
                    <option value="{{ $value }}" @selected((old('difficulty') !== null ? old('difficulty') : strtolower($trek->difficulty ?? '')) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('difficulty')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Status (Full Width on Grid to balance) -->
        <div class="md:col-span-2 space-y-3 pt-4 border-t border-slate-100">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Status</label>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="radio" name="status" value="active" class="w-4 h-4 text-slate-900 focus:ring-slate-900 border-slate-200" {{ (old('status') !== null ? old('status') : strtolower($trek->status ?? 'active')) === 'active' ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">Active</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="radio" name="status" value="draft" class="w-4 h-4 text-slate-900 focus:ring-slate-900 border-slate-200" {{ (old('status') !== null ? old('status') : strtolower($trek->status ?? 'active')) === 'draft' ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">Draft</span>
                </label>
            </div>
            @error('status')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</section>

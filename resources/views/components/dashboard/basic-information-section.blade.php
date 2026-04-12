{{-- BasicInformationSection Component --}}
@props(['trek', 'edit' => false, 'errors' => null])
@php
    $isEdit = $edit ?? false;
    $required = '<span style="color:var(--u-danger);">*</span>';
@endphp
<div class="basic-info-section">
    <div class="section-header-row">
        <h2 class="section-title">Basic Information</h2>
    </div>
    <div class="basic-info-grid">
        <!-- Trek Title (full width) -->
        <div class="form-group form-group--full">
            <label for="title">Trek Title {!! $required !!}</label>
            <input type="text" name="title" id="title" class="u-input @error('title') error @enderror" value="{{ old('title', $trek->title ?? '') }}" required autocomplete="off">
            @error('title')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <!-- Status (right, compact) -->
        <div class="form-group form-group--status">
            <label for="status" class="form-label">Status {!! $required !!}</label>
            <div class="segmented-control compact" role="group" aria-label="Trek Status">
                <input type="radio" name="status" id="status-active" value="Active" {{ old('status', $trek->status ?? 'Active') === 'Active' ? 'checked' : '' }} required>
                <label for="status-active">Active</label>
                <input type="radio" name="status" id="status-inactive" value="Inactive" {{ old('status', $trek->status ?? 'Active') === 'Inactive' ? 'checked' : '' }}>
                <label for="status-inactive">Inactive</label>
            </div>
            @error('status')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <!-- Slug (full width) -->
        <div class="form-group form-group--full">
            <label for="slug">Slug {!! $required !!}</label>
            <div class="slug-row">
                <input type="text" name="slug" id="slug" class="u-input @error('slug') error @enderror" value="{{ old('slug', $trek->slug ?? '') }}" readonly required aria-describedby="slugHelp">
                <button type="button" class="slug-edit-btn" aria-label="Edit slug" onclick="document.getElementById('slug').removeAttribute('readonly');this.style.display='none';"><i class="fas fa-edit"></i></button>
            </div>
            <small id="slugHelp" class="form-helper">Auto-generated from title. Click edit to customize.</small>
            @error('slug')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <!-- Numeric Fields Row: Base Price, Duration, Max Altitude -->
        <div class="form-group">
            <label for="base_price">Base Price (NPR) {!! $required !!}</label>
            <div class="input-prefix-row">
                <span class="input-prefix">NPR</span>
                <input type="number" name="base_price" id="base_price" class="u-input @error('base_price') error @enderror" value="{{ old('base_price', $trek->base_price ?? '') }}" min="0" step="0.01" required>
            </div>
            @error('base_price')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="duration_days">Duration (days) {!! $required !!}</label>
            <input type="number" name="duration_days" id="duration_days" class="u-input @error('duration_days') error @enderror" value="{{ old('duration_days', $trek->duration_days ?? '') }}" min="1" required>
            <small class="form-helper">Number of days</small>
            @error('duration_days')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="max_altitude">Max Altitude (m)</label>
            <input type="number" name="max_altitude" id="max_altitude" class="u-input @error('max_altitude') error @enderror" value="{{ old('max_altitude', $trek->max_altitude ?? '') }}" min="0">
            <small class="form-helper">Highest point in meters</small>
            @error('max_altitude')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <!-- Difficulty (full width) -->
        <div class="form-group form-group--full">
            <label for="difficulty">Difficulty {!! $required !!}</label>
            <select name="difficulty" id="difficulty" class="u-input @error('difficulty') error @enderror" required>
                <option value="">Select difficulty level</option>
                <option value="Easy" {{ old('difficulty', $trek->difficulty ?? '') === 'Easy' ? 'selected' : '' }}>Easy</option>
                <option value="Moderate" {{ old('difficulty', $trek->difficulty ?? '') === 'Moderate' ? 'selected' : '' }}>Moderate</option>
                <option value="Difficult" {{ old('difficulty', $trek->difficulty ?? '') === 'Difficult' ? 'selected' : '' }}>Difficult</option>
                <option value="Extreme" {{ old('difficulty', $trek->difficulty ?? '') === 'Extreme' ? 'selected' : '' }}>Extreme</option>
            </select>
            @error('difficulty')<span class="form-error">{{ $message }}</span>@enderror
        </div>
    </div>
</div>

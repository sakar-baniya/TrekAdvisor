@if ($errors->any())
    <div class="admin-flash error">
        Please fix the departure form errors and try again.
    </div>
@endif

<div class="admin-form-stack">
    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Departure Details</h3>
                <p>Set dates, seats, and pricing for a specific trek run</p>
            </div>
        </div>

        <div class="admin-form-grid admin-form-grid--two">
            <label class="admin-field">
                <span>Trek *</span>
                <select name="trek_id" class="admin-input" required>
                    <option value="">Select trek</option>
                    @foreach ($treks as $trek)
                        <option value="{{ $trek->id }}" @selected(old('trek_id', $selectedTrekId) == $trek->id)>{{ $trek->title }}</option>
                    @endforeach
                </select>
                @error('trek_id') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Status *</span>
                <select name="status" class="admin-input" required>
                    @foreach (['available' => 'Available', 'full' => 'Full', 'completed' => 'Completed'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $departure->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Start Date *</span>
                <input type="date" name="start_date" value="{{ old('start_date', optional($departure->start_date)->format('Y-m-d')) }}" class="admin-input" required />
                @error('start_date') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>End Date *</span>
                <input type="date" name="end_date" value="{{ old('end_date', optional($departure->end_date)->format('Y-m-d')) }}" class="admin-input" required />
                @error('end_date') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Price (USD) *</span>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $departure->price) }}" class="admin-input" required />
                @error('price') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Capacity *</span>
                <input type="number" min="1" name="capacity" value="{{ old('capacity', $departure->capacity) }}" class="admin-input" required />
                @error('capacity') <small class="admin-error">{{ $message }}</small> @enderror
            </label>

            <label class="admin-field">
                <span>Booked Seats</span>
                <input type="number" min="0" name="booked_seats" value="{{ old('booked_seats', $departure->booked_seats ?? 0) }}" class="admin-input" />
                @error('booked_seats') <small class="admin-error">{{ $message }}</small> @enderror
            </label>
        </div>
    </section>

    <div class="admin-form-actions">
        <a href="{{ route('admin.departures.index') }}" class="admin-secondary-button">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Departures</span>
        </a>
        <button type="submit" class="admin-primary-button">
            <i class="fas fa-floppy-disk"></i>
            <span>{{ $departure->exists ? 'Update Departure' : 'Create Departure' }}</span>
        </button>
    </div>
</div>

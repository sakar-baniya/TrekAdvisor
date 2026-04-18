@if ($errors->any())
    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
        <p class="text-[10px] font-semibold text-red-800 uppercase tracking-widest leading-none">Form Validation Error</p>
        <p class="text-xs font-semibold text-red-600 mt-2">Please fix the departure fields and try again.</p>
    </div>
@endif

<div class="space-y-8">
    <!-- Departure Core Details -->
    <section class="bg-white border border-slate-200/70 rounded-2xl p-6 md:p-7 overflow-hidden text-black">
        <div class="mb-6">
            <h3 class="text-slate-900 text-base font-semibold tracking-tight">Departure Logistics</h3>
            <p class="text-slate-500 text-sm mt-1">Set dates, seats, and specific pricing for this trek run</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Assigned Trek *</label>
                <select name="trek_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" required>
                    <option value="">Select trek</option>
                    @foreach ($treks as $trek)
                        <option value="{{ $trek->id }}" @selected(old('trek_id', $selectedTrekId) == $trek->id)>{{ $trek->title }}</option>
                    @endforeach
                </select>
                @error('trek_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Booking Status *</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" required>
                    @foreach (['available' => 'Available', 'full' => 'Fully Booked', 'completed' => 'Expedition Completed'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $departure->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Start Date *</label>
                <input type="date" name="start_date" value="{{ old('start_date', optional($departure->start_date)->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" required>
                @error('start_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">End Date *</label>
                <input type="date" name="end_date" value="{{ old('end_date', optional($departure->end_date)->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" required>
                @error('end_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Individual Price (NPR) *</label>
                <input type="number" step="1" min="0" name="price" value="{{ old('price', $departure->price) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" required>
                @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Total Seat Capacity *</label>
                <input type="number" min="1" name="capacity" value="{{ old('capacity', $departure->capacity) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30" required>
                @error('capacity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Adjust Booked Seats</label>
                <input type="number" min="0" name="booked_seats" value="{{ old('booked_seats', $departure->booked_seats ?? 0) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30">
                @error('booked_seats') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <!-- Form Actions -->
    <div class="flex items-center gap-4">
        <button type="submit" class="bg-slate-900 text-white hover:bg-slate-800 rounded-xl px-10 py-2.5 text-sm font-semibold transition-all shadow-lg shadow-slate-900/10">
            <i class="fas fa-save mr-2"></i> {{ $departure->exists ? 'Update Departure' : 'Create Departure' }}
        </button>
        <a href="{{ route('staff.departures.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold rounded-xl transition-all">
            Cancel
        </a>
    </div>
</div>

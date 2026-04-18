@if ($errors->any())
    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
        <p class="text-[10px] font-black text-red-800 uppercase tracking-widest leading-none">Form Validation Error</p>
        <p class="text-xs font-semibold text-red-600 mt-2">Please fix the departure fields and try again.</p>
    </div>
@endif

<div class="space-y-12">
    <!-- Departure Core Details -->
    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden text-black">
        <div class="p-8 md:p-10 border-b border-slate-50">
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Departure Logistics</h3>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Set dates, seats, and specific pricing for this trek run</p>
        </div>
        
        <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <x-input-label value="Assigned Trek *" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                <select name="trek_id" class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-900 focus:ring-slate-900 focus:border-slate-900 py-3" required>
                    <option value="">Select trek</option>
                    @foreach ($treks as $id => $title)
                        <option value="{{ $id }}" @selected(old('trek_id', $selectedTrekId) == $id)>{{ $title }}</option>
                    @endforeach
                </select>
                @error('trek_id') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <x-input-label value="Booking Status *" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                <select name="status" class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-900 focus:ring-slate-900 focus:border-slate-900 py-3" required>
                    @foreach (['available' => 'Available', 'full' => 'Fully Booked', 'completed' => 'Expedition Completed'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $departure->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <x-input-label value="Start Date *" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                <x-text-input type="date" name="start_date" :value="old('start_date', optional($departure->start_date)->format('Y-m-d'))" required />
                @error('start_date') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <x-input-label value="End Date *" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                <x-text-input type="date" name="end_date" :value="old('end_date', optional($departure->end_date)->format('Y-m-d'))" required />
                @error('end_date') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <x-input-label value="Individual Price (NPR) *" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                <x-text-input type="number" step="1" min="0" name="price" :value="old('price', $departure->price)" required />
                @error('price') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <x-input-label value="Total Seat Capacity *" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                <x-text-input type="number" min="1" name="capacity" :value="old('capacity', $departure->capacity)" required />
                @error('capacity') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <x-input-label value="Adjust Booked Seats" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                <x-text-input type="number" min="0" name="booked_seats" :value="old('booked_seats', $departure->booked_seats ?? 0)" />
                @error('booked_seats') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <!-- Form Actions -->
    <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
        <button type="submit" class="w-full sm:w-auto px-12 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20">
            <i class="fas fa-save mr-2"></i> {{ $departure->exists ? 'Update Departure' : 'Create Departure' }}
        </button>
        <a href="{{ route('admin.departures.index') }}" class="w-full sm:w-auto text-center px-8 py-4 bg-white border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-50 transition-all">
            Cancel
        </a>
    </div>
</div>

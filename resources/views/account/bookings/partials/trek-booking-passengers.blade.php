<div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm animate-fadeIn">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-base">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h2 class="text-base font-semibold text-slate-900">Traveler Details</h2>
                <p class="text-sm text-slate-500">Official names and passport information for everyone in this booking.</p>
            </div>
        </div>
        @if ($canEditPassengers)
            <x-ui.button variant="secondary" data-passenger-edit-open>
                Edit Travelers
            </x-ui.button>
        @endif
    </div>

    @if ($booking->passengers->isEmpty())
        <div class="text-center py-6 bg-slate-50 rounded-lg border border-dashed border-slate-200">
            <p class="text-xs font-medium text-slate-500 italic">No traveler details provided yet.</p>
        </div>
    @else
        <div data-passenger-section>
            <!-- View Mode -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-passenger-view>
                @foreach ($booking->passengers as $index => $passenger)
                    <div class="p-5 rounded-lg bg-slate-50/50 border border-slate-200/50">
                        <span class="inline-block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-3">Traveler {{ $index + 1 }}</span>
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Legal Name</span>
                                <span class="text-xs font-semibold text-slate-900">{{ $passenger->full_name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Age</span>
                                <span class="text-xs font-semibold text-slate-900">{{ $passenger->age ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Passport</span>
                                <span class="text-xs font-semibold text-slate-900 font-mono">{{ $passenger->passport_number ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Edit Mode -->
            @if ($canEditPassengers)
                <div class="hidden" data-passenger-edit>
                    <form method="POST" action="{{ route('account.bookings.treks.passengers', $booking) }}" class="space-y-8">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach ($booking->passengers as $index => $passenger)
                                <div class="space-y-4 p-5 rounded-xl bg-slate-50/50 border border-slate-200">
                                    <h4 class="text-xs font-semibold text-slate-900">Traveler {{ $index + 1 }}</h4>
                                    <input type="hidden" name="passengers[{{ $index }}][id]" value="{{ $passenger->id }}">
                                    
                                    <x-ui.input 
                                        label="Full Name" 
                                        name="passengers[{{ $index }}][full_name]" 
                                        :value="old('passengers.'.$index.'.full_name', $passenger->full_name)" 
                                        required 
                                    />
                                    <div class="grid grid-cols-2 gap-4">
                                        <x-ui.input 
                                            label="Age" 
                                            type="number" 
                                            name="passengers[{{ $index }}][age]" 
                                            :value="old('passengers.'.$index.'.age', $passenger->age)" 
                                        />
                                        <x-ui.input 
                                            label="Passport #" 
                                            name="passengers[{{ $index }}][passport_number]" 
                                            :value="old('passengers.'.$index.'.passport_number', $passenger->passport_number)" 
                                        />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                            <x-ui.button variant="secondary" type="button" data-passenger-edit-cancel>
                                Cancel
                            </x-ui.button>
                            <x-ui.button type="submit">
                                Save Travelers
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    @endif
</div>

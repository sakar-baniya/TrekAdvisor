<div class="bg-white rounded-2xl border border-slate-200 p-6 animate-fadeIn">
    <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-users w-5 h-5 text-slate-400 text-center"></i>
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Traveler Details</h2>
                <p class="text-sm text-slate-500">Official names and passport information for everyone in this booking.</p>
            </div>
        </div>
        @if ($canEditPassengers)
            <x-ui.button variant="secondary" class="whitespace-nowrap text-sm px-4 py-2" data-passenger-edit-open>
                Edit Travelers
            </x-ui.button>
        @endif
    </div>

    @if ($booking->passengers->isEmpty())
        <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-200">
            <p class="text-sm text-slate-500 italic">No traveler details provided yet.</p>
        </div>
    @else
        <div data-passenger-section>
            <!-- View Mode -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-passenger-view>
                @foreach ($booking->passengers as $index => $passenger)
                    <div class="p-5 rounded-xl bg-slate-50/50 border border-slate-200">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-200/50 pb-2">Traveler {{ $index + 1 }}</div>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-4">
                            <div class="col-span-2">
                                <div class="text-xs font-medium uppercase tracking-wider text-slate-500 mb-1">Legal Name</div>
                                <div class="text-sm font-semibold text-slate-900">{{ $passenger->full_name }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium uppercase tracking-wider text-slate-500 mb-1">Age</div>
                                <div class="text-sm font-semibold text-slate-900">{{ $passenger->age ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium uppercase tracking-wider text-slate-500 mb-1">Passport</div>
                                <div class="text-sm font-semibold text-slate-900 font-mono">{{ $passenger->passport_number ?? '—' }}</div>
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

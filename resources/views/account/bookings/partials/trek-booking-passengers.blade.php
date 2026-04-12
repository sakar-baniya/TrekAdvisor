<section class="account-panel" id="passengers">
    <div class="account-panel__head account-panel__head--row">
        <div>
            <h2>Passenger Details</h2>
            <p>Review traveler names and passport details.</p>
        </div>
        @if ($canEditPassengers)
            <button type="button" class="btn btn--primary" data-passenger-edit-open aria-expanded="false">Edit passengers</button>
        @endif
    </div>

    @if ($booking->passengers->isEmpty())
        <p class="account-review-note">No passenger details found for this booking.</p>
    @else
        <div class="account-passenger-wrapper" data-passenger-section>
            <div class="account-passenger-view" data-passenger-view>
                <div class="account-passenger-list">
                    @foreach ($booking->passengers as $index => $passenger)
                        <div class="account-passenger-row">
                            <div class="account-passenger-heading">Passenger {{ $index + 1 }}</div>
                            <div class="account-passenger-meta">
                                <div class="account-passenger-field">
                                    <span class="account-passenger-label">Full name</span>
                                    <span class="account-passenger-value">{{ $passenger->full_name }}</span>
                                </div>
                                <div class="account-passenger-field">
                                    <span class="account-passenger-label">Age</span>
                                    <span class="account-passenger-value">{{ $passenger->age ?? 'Not provided' }}</span>
                                </div>
                                <div class="account-passenger-field">
                                    <span class="account-passenger-label">Passport number</span>
                                    <span class="account-passenger-value">{{ $passenger->passport_number ?? 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($isLocked)
                    <p class="account-review-note">Passenger details are locked after completion.</p>
                @endif
            </div>

            @if ($canEditPassengers)
                <div class="account-passenger-edit account-is-hidden" data-passenger-edit>
                    <form method="POST" action="{{ route('account.bookings.treks.passengers', $booking) }}" class="account-form">
                        @csrf
                        @method('PATCH')

                        <div class="account-passenger-grid">
                            @foreach ($booking->passengers as $index => $passenger)
                                <div class="account-passenger-card">
                                    <h3>Passenger {{ $index + 1 }}</h3>
                                    <input type="hidden" name="passengers[{{ $index }}][id]" value="{{ $passenger->id }}">
                                    <label>
                                        <span>Full name</span>
                                        <input type="text" name="passengers[{{ $index }}][full_name]" value="{{ old("passengers.$index.full_name", $passenger->full_name) }}" required>
                                    </label>
                                    <label>
                                        <span>Age</span>
                                        <input type="number" name="passengers[{{ $index }}][age]" value="{{ old("passengers.$index.age", $passenger->age) }}">
                                    </label>
                                    <label>
                                        <span>Passport number</span>
                                        <input type="text" name="passengers[{{ $index }}][passport_number]" value="{{ old("passengers.$index.passport_number", $passenger->passport_number) }}">
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="account-actions account-actions--inline">
                            <button type="submit" class="btn btn--primary">Save Passenger Details</button>
                            <button type="button" class="btn btn--secondary" data-passenger-edit-cancel>Cancel</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    @endif
</section>

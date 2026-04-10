{{-- TrekCard Component --}}
@props(['trek'])
<article class="trek-card">
    <div class="trek-card__media">
        <img src="{{ $trek->image ?: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $trek->title }}" />
        <span class="trek-status-chip {{ strtolower($trek->status) }}">{{ ucfirst($trek->status) }}</span>
    </div>
    <div class="trek-card__body">
        <div class="trek-card__header">
            <div>
                <h3 class="trek-card__title">{{ $trek->title }}</h3>
                <div class="trek-meta-chips">
                    <span class="trek-meta-chip"><i class="fas fa-mountain"></i> {{ ucfirst($trek->difficulty) }}</span>
                    <span class="trek-meta-chip"><i class="fas fa-clock"></i> {{ $trek->duration_days ?? 'N/A' }} Days</span>
                    <span class="trek-meta-chip"><i class="fas fa-location-arrow"></i> {{ $trek->max_altitude ? number_format($trek->max_altitude) . 'm' : 'N/A' }}</span>
                </div>
            </div>
            <div class="trek-card__actions">
                <button type="button" class="trek-card__kebab" aria-haspopup="true" aria-expanded="false" tabindex="0" onclick="this.nextElementSibling.classList.toggle('show')">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="trek-card__dropdown" tabindex="-1">
                    <a href="{{ route('admin.treks.edit', $trek) }}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
                    <form action="{{ route('admin.treks.destroy', $trek) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" class="dropdown-item dropdown-item--danger" data-confirm="delete-trek"><i class="fas fa-trash-alt"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="trek-card__stats">
            <div class="trek-stat"><span class="stat-label">Base Price</span><span class="stat-value">${{ number_format($trek->base_price, 0) }}</span></div>
            <div class="trek-stat"><span class="stat-label">Departures</span><span class="stat-value">{{ $trek->departures_count }} Active</span></div>
            <div class="trek-stat"><span class="stat-label">Total Bookings</span><span class="stat-value">{{ (int) $trek->total_booked_seats }}</span></div>
        </div>
        <div class="trek-card__footer">
            <a href="{{ route('admin.treks.show', $trek) }}" class="trek-card__footer-link">View Analytical Details &rarr;</a>
            <a href="{{ route('admin.departures.index', ['trek_id' => $trek->id]) }}" class="u-btn u-btn--primary trek-card__footer-cta"><i class="fas fa-calendar-alt"></i> Manage Schedule</a>
        </div>
    </div>
</article>

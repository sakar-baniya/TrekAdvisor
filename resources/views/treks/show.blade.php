<x-app-layout>
    <div class="trek-show">
        <!-- Hero Section -->
        <section class="trek-hero">
            <div class="trek-hero-bg">
                <img src="{{ $trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b' }}" alt="{{ $trek->title }}">
                <div class="trek-hero-overlay"></div>
            </div>

            <div class="container trek-hero-content">
                @php
                    $difficultyClass = [
                        'easy' => 'trek-hero-badge easy',
                        'moderate' => 'trek-hero-badge moderate',
                        'difficult' => 'trek-hero-badge difficult',
                        'extreme' => 'trek-hero-badge extreme'
                    ][strtolower($trek->difficulty)] ?? 'trek-hero-badge neutral';
                @endphp
                <div class="{{ $difficultyClass }}">
                    {{ $trek->difficulty }} Expedition
                </div>

                <h1 class="trek-hero-title">{{ $trek->title }}</h1>

                <div class="trek-hero-stats">
                    <span><i class="fas fa-calendar-alt"></i> {{ $trek->itineraries->count() }} Days</span>
                    <span class="dot"></span>
                    <span><i class="fas fa-mountain"></i> Easy Access</span>
                    <span class="dot"></span>
                    <span><i class="fas fa-tag"></i> ${{ number_format($trek->base_price) }} Starting</span>
                    <span class="dot"></span>
                    <span><i class="fas fa-star"></i> {{ $avgRating ?? 'New' }} ({{ $reviewCount }})</span>
                </div>
            </div>
        </section>

        <!-- Details Grid -->
        <div class="container trek-details">
            <div class="trek-content">
                <!-- Description -->
                <section class="content-card">
                    <h2 class="content-title">About This Trek</h2>
                    <div class="content-body">
                        {!! nl2br(e($trek->description)) !!}
                    </div>
                </section>

                <!-- Itinerary -->
                <section class="content-card">
                    <h2 class="content-title">Itinerary</h2>
                    <div class="timeline">
                        @foreach($trek->itineraries as $itinerary)
                            <div class="timeline-item">
                                <div class="timeline-badge">Day {{ $itinerary->day_number }}</div>
                                <div class="timeline-content">
                                    <h3>{{ $itinerary->title }}</h3>
                                    <p>{{ $itinerary->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Reviews -->
                <section class="content-card">
                    <h2 class="content-title">Customer Reviews</h2>
                    <div class="review-summary">
                        <div class="review-score">{{ $avgRating ?? 'New' }}</div>
                        <div class="review-count">{{ $reviewCount }} reviews</div>
                    </div>
                    @if($reviews->count() > 0)
                        <div class="review-grid">
                            @foreach($reviews as $review)
                                <div class="review-card">
                                    <div class="review-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="review-text">"{{ $review->comment }}"</p>
                                    <div class="review-user">
                                        <div class="review-avatar">{{ substr($review->user->name, 0, 1) }}</div>
                                        <div>
                                            <p class="review-name">{{ $review->user->name }}</p>
                                            <p class="review-date">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="review-empty">
                            <i class="fas fa-comment-slash"></i>
                            <p>No reviews yet.</p>
                        </div>
                    @endif
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="trek-sidebar">
                <div class="sidebar-card">
                    <h3 class="sidebar-title">Available Dates</h3>
                    <p class="sidebar-subtitle">Select a date to start booking</p>

                    <div class="date-list">
                        @forelse($trek->departures as $departure)
                            <div class="date-card">
                                <div class="date-card-header">
                                    <div>
                                        <p class="date-title">{{ $departure->start_date->format('M d, Y') }}</p>
                                        <p class="date-slots">{{ $departure->capacity - $departure->booked_seats }} slots remaining</p>
                                    </div>
                                    <p class="date-price">${{ number_format($departure->price) }}</p>
                                </div>
                                <div class="date-meta">
                                    <span>{{ $departure->start_date->format('M d') }} - {{ $departure->end_date->format('M d, Y') }}</span>
                                    <span class="dot"></span>
                                    <span>{{ $departure->status }}</span>
                                </div>
                                <a href="{{ route('bookings.create', $departure->id) }}" class="date-cta">
                                    Book This Date
                                </a>
                            </div>
                        @empty
                            <div class="date-empty">
                                <p>No dates available</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="sidebar-card dark">
                    <div class="sidebar-glow"></div>
                    <h3 class="sidebar-title">Why Trek With Us?</h3>
                    <ul class="sidebar-list">
                        <li>
                            <span class="sidebar-icon"><i class="fas fa-shield-alt"></i></span>
                            <span>Expert Local Guides</span>
                        </li>
                        <li>
                            <span class="sidebar-icon"><i class="fas fa-file-contract"></i></span>
                            <span>Permits Included</span>
                        </li>
                        <li>
                            <span class="sidebar-icon"><i class="fas fa-hotel"></i></span>
                            <span>Comfortable Guesthouses</span>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>

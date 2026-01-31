<x-app-layout>
    <div class="trek-details-container">
        <div class="trek-hero" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7)), url('{{ $trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b' }}')">
            <div class="hero-content">
                <div class="difficulty-badge difficulty-{{ strtolower($trek->difficulty) }}">{{ $trek->difficulty }}</div>
                <h1>{{ $trek->title }}</h1>
                <p class="trek-meta">
                    <span><i class="fas fa-calendar-alt"></i> {{ $trek->itineraries->count() }} Days</span>
                    <span><i class="fas fa-tag"></i> From ${{ number_format($trek->base_price) }}</span>
                </p>
            </div>
        </div>

        <div class="trek-content-grid">
            <div class="main-content">
                <section class="trek-description">
                    <h2>About this Trek</h2>
                    <div class="description-text">
                        {!! nl2br(e($trek->description)) !!}
                    </div>
                </section>

                <section class="itinerary-section">
                    <h2>Itinerary Timeline</h2>
                    <div class="timeline">
                        @foreach($trek->itineraries as $itinerary)
                            <div class="timeline-item">
                                <div class="day-marker">Day {{ $itinerary->day_number }}</div>
                                <div class="timeline-content">
                                    <h3>{{ $itinerary->title }}</h3>
                                    <p>{{ $itinerary->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="reviews-section">
                    <h2>What Adventurers Say</h2>
                    @if($reviews->count() > 0)
                        <div class="reviews-grid">
                            @foreach($reviews as $review)
                                <div class="review-card">
                                    <div class="review-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="review-comment">"{{ $review->comment }}"</p>
                                    <div class="review-user">
                                        <div class="user-avatar">{{ substr($review->user->name, 0, 1) }}</div>
                                        <div class="user-info">
                                            <p class="user-name">{{ $review->user->name }}</p>
                                            <p class="review-date">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="no-reviews">No reviews yet. Be the first to trek this peak!</p>
                    @endif
                </section>
            </div>

            <aside class="sidebar">
                <div class="booking-sidebar-card">
                    <h3>Available Departures</h3>
                    <p class="sidebar-info">Select a date to begin your booking process.</p>
                    
                    <div class="departure-list">
                        @forelse($trek->departures as $departure)
                            <div class="departure-item">
                                <div class="departure-date">
                                    <p class="date">{{ $departure->start_date->format('M d, Y') }}</p>
                                    <p class="slots">{{ $departure->capacity - $departure->booked_seats }} slots left</p>
                                </div>
                                <div class="departure-price">
                                    <p>${{ number_format($departure->price) }}</p>
                                    <a href="{{ route('bookings.create', $departure->id) }}" class="btn-book">Book Now</a>
                                </div>
                            </div>
                        @empty
                            <p class="no-departures">No scheduled departures available currently.</p>
                        @endforelse
                    </div>
                </div>

                <div class="trek-highlights">
                    <h3>Highlights</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Experienced local guides</li>
                        <li><i class="fas fa-check"></i> All permits included</li>
                        <li><i class="fas fa-check"></i> Premium accommodation</li>
                        <li><i class="fas fa-check"></i> High success rate</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    <style>
        .trek-details-container {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }

        .trek-hero {
            height: 60vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            padding-bottom: 5rem;
            color: white;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            width: 100%;
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-top: 1.5rem;
            letter-spacing: -0.05em;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .trek-meta {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .trek-meta i { margin-right: 0.5rem; }

        .trek-content-grid {
            max-width: 1200px;
            margin: -4rem auto 4rem;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 3rem;
        }

        .main-content {
            background: white;
            padding: 4rem;
            border-radius: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
        }

        h2::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 32px;
            background: #3182ce;
            margin-right: 1.5rem;
            border-radius: 4px;
        }

        .description-text {
            color: #4a5568;
            line-height: 1.8;
            font-size: 1.1rem;
            margin-bottom: 4rem;
        }

        /* Timeline Styling */
        .timeline {
            border-left: 2px solid #e2e8f0;
            margin-left: 1.25rem;
            padding-bottom: 4rem;
        }

        .timeline-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 3rem;
        }

        .day-marker {
            position: absolute;
            left: -1.25rem;
            top: 0;
            background: #3182ce;
            color: white;
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 4px 6px rgba(49,130,206,0.3);
        }

        .timeline-content h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .timeline-content p {
            color: #718096;
            line-height: 1.6;
        }

        /* Reviews Styling */
        .reviews-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .review-card {
            background: #f7fafc;
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid #edf2f7;
        }

        .review-rating { color: #f6ad55; margin-bottom: 1rem; }
        .review-comment { font-style: italic; color: #4a5568; margin-bottom: 1.5rem; }

        .review-user { display: flex; align-items: center; gap: 1rem; }
        .user-avatar {
            width: 40px; height: 40px;
            background: #3182ce; color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }
        .user-name { font-weight: 700; color: #2d3748; font-size: 0.9rem; }
        .review-date { font-size: 0.8rem; color: #a0aec0; }

        /* Sidebar Styling */
        .sidebar { display: flex; flex-direction: column; gap: 2rem; }

        .booking-sidebar-card {
            background: white;
            padding: 2.5rem;
            border-radius: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            position: sticky;
            top: 2rem;
        }

        .booking-sidebar-card h3 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; }
        .sidebar-info { color: #718096; font-size: 0.9rem; margin-bottom: 2rem; }

        .departure-list { display: flex; flex-direction: column; gap: 1.5rem; }

        .departure-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #edf2f7;
        }

        .departure-date .date { font-weight: 700; color: #2d3748; }
        .departure-date .slots { font-size: 0.8rem; color: #48bb78; font-weight: 600; }

        .departure-price { text-align: right; }
        .departure-price p { font-weight: 800; font-size: 1.25rem; color: #3182ce; margin-bottom: 0.5rem; }

        .btn-book {
            display: inline-block;
            background: #2d3748;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-book:hover { background: #1a202c; transform: scale(1.05); }

        .trek-highlights {
            background: #2d3748;
            color: white;
            padding: 2.5rem;
            border-radius: 32px;
        }

        .trek-highlights h3 { margin-bottom: 1.5rem; }
        .trek-highlights ul { list-style: none; padding: 0; }
        .trek-highlights li { margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem; font-size: 0.95rem; }
        .trek-highlights i { color: #48bb78; }
    </style>
</x-app-layout>

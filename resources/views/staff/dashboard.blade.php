<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Staff Dashboard</p>
                <h2 class="admin-page-title">Monitor today's operations across bookings and rentals</h2>
            </div>
        </div>
    </x-slot>

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-blue"><i class="fas fa-route"></i></div>
            <div>
                <p>Trek Bookings</p>
                <h3>{{ number_format($stats['today_trek_bookings']) }}</h3>
                <span>Created today</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-green"><i class="fas fa-hotel"></i></div>
            <div>
                <p>Hotel Bookings</p>
                <h3>{{ number_format($stats['today_hotel_bookings']) }}</h3>
                <span>Created today</span>
            </div>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Staff Focus</h3>
                <p>Use this space to review operational activity and handle customer support.</p>
            </div>
        </div>
        <div class="admin-note-stack">
            <article class="admin-note-card">
                <strong>Track same-day bookings and support follow-up</strong>
                <span>Staff workflows can be expanded here as the operations module grows.</span>
            </article>
        </div>
    </section>
</x-dashboard-layout>

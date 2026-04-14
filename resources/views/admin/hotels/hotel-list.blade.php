<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <h2 class="admin-page-title">All Hotels</h2>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Review pending approvals or search by hotel and owner</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.hotels.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search hotel, location, or owner" />
            <select name="status" class="admin-input">
                <option value="">All statuses</option>
                @foreach (['Pending', 'Active', 'Inactive'] as $option)
                    <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-card-list">
        @forelse ($hotels as $hotel)
            <article
                class="admin-list-card js-hotel-card"
                data-href="{{ route('hotels.show', ['hotel' => $hotel, 'return_to' => request()->fullUrl()]) }}"
                role="link"
                tabindex="0"
                style="cursor: pointer;"
            >
                <div class="admin-list-card__media">
                    <img src="{{ $hotel->image ?: 'https://via.placeholder.com/480x320?text=Hotel' }}" alt="{{ $hotel->name }}">
                </div>
                <div class="admin-list-card__content">
                    <div class="admin-list-card__top">
                        <div>
                            <h3>{{ $hotel->name }}</h3>
                            <p>{{ $hotel->location }}</p>
                        </div>
                        <span>{{ ucfirst($hotel->status) }}</span>
                    </div>

                    <div class="admin-list-card__meta">
                        <span>Owner: {{ $hotel->owner?->name ?? 'Unknown' }}</span>
                        <span>{{ $hotel->owner?->email ?? 'No email' }}</span>
                        <span>Room types: {{ $hotel->rooms->count() }}</span>
                        <span>Owner approval: {{ $hotel->owner?->approval_status === 'approved' ? 'Approved' : 'Pending' }}</span>
                    </div>

                    <p class="admin-list-card__excerpt">{{ \Illuminate\Support\Str::limit($hotel->description, 150) }}</p>

                    <div class="admin-list-card__actions">
                        @if ($hotel->status !== 'active')
                            <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active" />
                                <button type="button" class="admin-primary-button" data-confirm="approve-hotel">
                                    <span>Approve</span>
                                </button>
                            </form>
                        @endif

                        @if ($hotel->status !== 'inactive')
                            <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="inactive" />
                                <button type="button" class="admin-danger-button" data-confirm="reject-hotel">
                                    <span>Set Inactive</span>
                                </button>
                            </form>
                        @endif

                        @if ($hotel->status !== 'pending')
                            <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="pending" />
                                <button type="button" class="admin-secondary-button" data-confirm="pending-hotel">
                                    <span>Move to Pending</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="admin-panel admin-panel--empty">
                <div class="admin-panel__header">
                    <div>
                        <h3>No hotels found</h3>
                        <p>Try a different filter or search term.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </section>

    @if ($hotels->hasPages())
        <div class="admin-pagination">{{ $hotels->links() }}</div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.js-hotel-card');

            cards.forEach((card) => {
                const navigate = () => {
                    const href = card.getAttribute('data-href');
                    if (href) {
                        window.location.href = href;
                    }
                };

                card.addEventListener('click', (event) => {
                    // Do not hijack clicks on forms/buttons inside the card.
                    if (event.target.closest('a, button, input, select, textarea, label, form')) {
                        return;
                    }
                    navigate();
                });

                card.addEventListener('keydown', (event) => {
                    // Keyboard support: open card with Enter or Space.
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        navigate();
                    }
                });
            });
        });
    </script>
</x-dashboard-layout>




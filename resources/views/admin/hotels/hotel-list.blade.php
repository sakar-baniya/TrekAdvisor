@section('page-title', 'Manage Hotels')
@section('page-subtitle', 'Review partner requests and manage property listings.')

<x-layouts.dashboard>

    <!-- Filters -->
    <section class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm mb-10">
        <form method="GET" action="{{ route('admin.hotels.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="search" name="search" value="{{ $search }}" 
                   class="bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all" 
                   placeholder="Search hotel, location, or owner" />

            <select name="status" class="bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all">
                <option value="">All statuses</option>
                @foreach (['Pending', 'Active', 'Inactive'] as $option)
                    <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-slate-900 text-white rounded-xl px-4 py-3 text-sm font-bold uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">Apply Filters</button>
        </form>
    </section>

    <!-- Hotel Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        @forelse ($hotels as $hotel)
            <div class="group bg-white rounded-[2rem] border border-slate-100 shadow-sm transition-all overflow-hidden flex flex-col">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $hotel->image ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945' }}" 
                         alt="{{ $hotel->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 flex items-center gap-2">
                        @php
                            $statusConfig = match(strtolower($hotel->status)) {
                                'active' => ['bg' => 'bg-emerald-400', 'text' => 'Active'],
                                'pending' => ['bg' => 'bg-amber-400', 'text' => 'Pending Review'],
                                default => ['bg' => 'bg-slate-400', 'text' => 'Inactive']
                            };
                        @endphp
                        <span class="w-2 h-2 rounded-full {{ $statusConfig['bg'] }}"></span>
                        <span class="text-[9px] font-bold text-white uppercase tracking-widest">{{ $statusConfig['text'] }}</span>
                    </div>
                </div>

                <div class="p-8">
                    <div class="mb-6">
                        <div class="flex items-center justify-between gap-4 mb-2">
                            <h3 class="text-xl font-bold text-slate-900 font-display line-clamp-1 transition-colors">{{ $hotel->name }}</h3>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest shrink-0">{{ $hotel->location }}</span>
                        </div>
                        <div class="flex flex-col gap-1.5 text-xs text-slate-500 font-semibold italic">
                            <span>Owner: {{ $hotel->owner?->name ?? 'Unknown' }} ({{ $hotel->owner?->email ?? 'No email' }})</span>
                            <span>Rooms: {{ $hotel->rooms->count() }} registered types</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between flex-wrap gap-4">
                        <div class="flex gap-2">
                            @if ($hotel->status !== 'active')
                                <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="active" />
                                    <button type="submit" 
                                            class="px-3 py-2 bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase tracking-widest rounded-lg border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all"
                                            onclick="return confirm('Are you sure you want to {{ strtolower($hotel->status) === 'pending' ? 'approve this hotel' : 'activate this hotel' }}?')">
                                        {{ strtolower($hotel->status) === 'pending' ? 'Approve' : 'Make Active' }}
                                    </button>
                                </form>
                            @endif
                            @if ($hotel->status !== 'inactive' && $hotel->status !== 'rejected')
                                <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="inactive" />
                                    <button type="submit" 
                                            class="px-3 py-2 bg-red-50 text-red-600 text-[9px] font-bold uppercase tracking-widest rounded-lg border border-red-100 hover:bg-red-600 hover:text-white transition-all"
                                            onclick="return confirm('Are you sure you want to disable this hotel listing?')">
                                        Disable
                                    </button>
                                </form>
                            @endif
                        </div>
                        <a href="{{ route('hotels.show', $hotel) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 text-slate-600 text-[9px] font-bold uppercase tracking-widest rounded-lg hover:bg-slate-900 hover:text-white transition-all">
                            View <i class="fas fa-external-link-alt opacity-50"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white rounded-[3rem] border border-slate-100 shadow-sm flex flex-col items-center text-center">
                <i class="fas fa-hotel text-4xl text-slate-100 mb-6"></i>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No hotels found</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Adjust your filters or check for new requests.</p>
            </div>
        @endforelse
    </div>

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
</x-layouts.dashboard>





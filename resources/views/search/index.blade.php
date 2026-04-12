<x-dashboard-layout>
    <div class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Search Results</h3>
                <p class="text-muted">Results for "{{ $query ?: '...' }}"</p>
            </div>
        </div>

        @if (!$query)
            <p class="text-muted">Enter a search term to see results.</p>
        @else
            <div style="display: grid; gap: 1.5rem;">
                <div>
                    <h4 class="text-navy" style="font-weight: 700; margin-bottom: 0.5rem;">Treks</h4>
                    @if ($treks->isEmpty())
                        <p class="text-muted">No treks found.</p>
                    @else
                        <ul style="margin: 0; padding-left: 1.1rem;">
                            @foreach ($treks as $trek)
                                <li>
                                    <a href="{{ route('treks.show', $trek->slug) }}" class="text-navy" style="text-decoration: none;">
                                        {{ $trek->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div>
                    <h4 class="text-navy" style="font-weight: 700; margin-bottom: 0.5rem;">Hotels</h4>
                    @if ($hotels->isEmpty())
                        <p class="text-muted">No hotels found.</p>
                    @else
                        <ul style="margin: 0; padding-left: 1.1rem;">
                            @foreach ($hotels as $hotel)
                                <li>
                                    <a href="{{ route('hotels.show', $hotel) }}" class="text-navy" style="text-decoration: none;">
                                        {{ $hotel->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @if (in_array(auth()->user()->role, ['admin', 'staff'], true))
                    <div>
                        <h4 class="text-navy" style="font-weight: 700; margin-bottom: 0.5rem;">Users</h4>
                        @if ($users->isEmpty())
                            <p class="text-muted">No users found.</p>
                        @else
                            <ul style="margin: 0; padding-left: 1.1rem;">
                                @foreach ($users as $result)
                                    <li>
                                        <span class="text-navy">{{ $result->name }}</span>
                                        <span class="text-muted" style="margin-left: 0.5rem;">{{ $result->email }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-dashboard-layout>

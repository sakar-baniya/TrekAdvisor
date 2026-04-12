<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Support</p>
                <h2 class="admin-page-title">Contact Messages</h2>
            </div>
        </div>
    </x-slot>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Search by sender or subject</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search name, email, or subject" />
            <div class="admin-filter-tabs">
                <span class="admin-filter-tab is-active">All Messages</span>
            </div>
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Inbox</h3>
                <p>Recent contact submissions</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Received</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td>
                                <strong>{{ $message->name }}</strong>
                                <small>{{ $message->email }}</small>
                            </td>
                            <td>{{ $message->subject }}</td>
                            <td>{{ $message->created_at->format('M d, Y g:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="admin-secondary-button">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table__empty">No contact messages yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($messages->hasPages())
        <div class="admin-pagination">{{ $messages->links() }}</div>
    @endif
</x-dashboard-layout>

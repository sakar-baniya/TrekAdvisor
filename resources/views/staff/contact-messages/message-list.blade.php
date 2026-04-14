<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <h2 class="admin-page-title">Contact Inbox</h2>
            </div>
        </div>
    </x-slot>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Search and track unread or responded messages</p>
            </div>
        </div>

        <form method="GET" action="{{ route('staff.contact-messages.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search sender, email, or subject" />
            <select name="status" class="admin-input">
                <option value="">All</option>
                <option value="unread" @selected($status === 'unread')>Unread</option>
                <option value="responded" @selected($status === 'responded')>Responded</option>
            </select>
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Inbox</h3>
                <p>Recent customer contact messages</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Received</th>
                        <th>Status</th>
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
                                @if ($message->responded_at)
                                    Responded
                                @elseif (! $message->is_read)
                                    Unread
                                @else
                                    Read
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('staff.contact-messages.show', $message) }}" class="admin-secondary-button">
                                    <span>Open</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="admin-table__empty">No messages found.</td>
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

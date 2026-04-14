<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Support</p>
                <h2 class="admin-page-title">Contact Message</h2>
            </div>
            <a href="{{ route('admin.contact-messages.index') }}" class="admin-secondary-button">
                <span>Back to Inbox</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <section class="admin-show-grid">
        <article class="admin-panel">
            <div class="admin-panel__header">
                <div>
                    <h3>Message Details</h3>
                    <p>Sender info and full message</p>
                </div>
            </div>
            <div class="admin-info-list">
                <div><span>Sender</span><strong>{{ $message->name }}</strong></div>
                <div><span>Email</span><strong>{{ $message->email }}</strong></div>
                <div><span>Subject</span><strong>{{ $message->subject }}</strong></div>
                <div><span>Received</span><strong>{{ $message->created_at->format('M d, Y g:i A') }}</strong></div>
                <div><span>Status</span><strong>{{ $message->responded_at ? 'Responded' : ($message->is_read ? 'Read' : 'Unread') }}</strong></div>
            </div>
            <div class="admin-review-full">
                {{ $message->message }}
            </div>
        </article>

        <aside class="admin-side-stack">
            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Reply</h3>
                        <p>Save response and send email to sender</p>
                    </div>
                </div>

                @if ($message->responded_at)
                    <div class="admin-info-list" style="margin-bottom: 1rem;">
                        <div><span>Responded At</span><strong>{{ $message->responded_at->format('M d, Y g:i A') }}</strong></div>
                        <div><span>By</span><strong>{{ $message->respondedByStaff?->name ?? 'Admin' }}</strong></div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.contact-messages.respond', $message) }}" class="admin-status-form">
                    @csrf
                    @method('PATCH')
                    <label class="admin-field">
                        <span>Response Note</span>
                        <textarea name="staff_response" class="admin-input" rows="6" placeholder="Write your response">{{ old('staff_response', $message->staff_response) }}</textarea>
                    </label>
                    <button type="submit" class="admin-primary-button admin-primary-button--fit">Save and Send</button>
                </form>
            </section>
        </aside>
    </section>
</x-dashboard-layout>

<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Support</p>
                <h2 class="admin-page-title">Contact Message</h2>
            </div>
            <a href="{{ route('admin.contact-messages.index') }}" class="admin-secondary-button">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Inbox</span>
            </a>
        </div>
    </x-slot>

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
            </div>
            <div class="admin-review-full">
                {{ $message->message }}
            </div>
        </article>
    </section>
</x-dashboard-layout>

<x-app-layout>
    @php
        $paymentStatus = $payment?->status ?? 'unpaid';
        $isLocked = in_array($booking->status, ['completed', 'cancelled']);
    @endphp

    <section class="account-shell" id="review">
        <div class="container">
            @include('account.bookings.partials.hotel-header', ['booking' => $booking])
            @include('account.bookings.partials.hotel-summary', [
                'booking' => $booking,
                'payment' => $payment,
                'paymentStatus' => $paymentStatus,
            ])
            @include('account.bookings.partials.hotel-actions', [
                'booking' => $booking,
                'payment' => $payment,
            ])
            @include('account.bookings.partials.hotel-review', [
                'booking' => $booking,
                'review' => $review,
                'isLocked' => $isLocked,
            ])
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = document.querySelector('[data-confirm-modal]');
                if (!modal) {
                    return;
                }

                var modalTitle = modal.querySelector('[data-confirm-title]');
                var modalMessage = modal.querySelector('[data-confirm-message]');
                var confirmButton = modal.querySelector('[data-confirm-submit]');
                var activeForm = null;

                var closeModal = function () {
                    modal.classList.remove('is-open');
                    activeForm = null;
                };

                document.querySelectorAll('[data-confirm-open]').forEach(function (trigger) {
                    trigger.addEventListener('click', function () {
                        activeForm = trigger.closest('[data-confirm-form]');
                        if (!activeForm) {
                            return;
                        }

                        if (modalTitle) {
                            modalTitle.textContent = trigger.getAttribute('data-confirm-title') || 'Confirm action';
                        }
                        if (modalMessage) {
                            modalMessage.textContent = trigger.getAttribute('data-confirm-message') || 'Are you sure you want to continue?';
                        }

                        modal.classList.add('is-open');
                    });
                });

                modal.querySelectorAll('[data-confirm-close]').forEach(function (closeTrigger) {
                    closeTrigger.addEventListener('click', closeModal);
                });

                if (confirmButton) {
                    confirmButton.addEventListener('click', function () {
                        if (activeForm) {
                            activeForm.submit();
                        }
                    });
                }

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeModal();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>

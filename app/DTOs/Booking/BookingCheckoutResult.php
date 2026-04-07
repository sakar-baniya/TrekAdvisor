<?php

namespace App\DTOs\Booking;

use App\Models\Payment;

class BookingCheckoutResult
{
    public function __construct(
        public readonly Payment $payment,
        public readonly ?string $checkoutUrl,
        public readonly ?string $errorMessage = null,
    ) {
    }

    public function checkoutStarted(): bool
    {
        return filled($this->checkoutUrl);
    }
}

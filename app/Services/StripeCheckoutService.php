<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\TrekBooking;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;


/**
 * Yo StripeCheckoutService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class StripeCheckoutService
{
    /**
     * Yo method le client ko service-level kaam handle garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function client(): StripeClient
    {
        $secret = config('services.stripe.secret');

        if (! $secret) {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        return new StripeClient($secret);
    }

    /**
     * Yo method le createTrekCheckoutSession related business flow execute garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function createTrekCheckoutSession(Payment $payment, TrekBooking $booking, string $successUrl, string $cancelUrl): Session
    {
        $booking->loadMissing('departure.trek', 'user');

        return $this->client()->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'payment_method_types' => ['card'],
            'customer_email' => $payment->user?->email,
            'metadata' => [
                'payment_id' => (string) $payment->id,
                'booking_id' => (string) $booking->id,
                'booking_reference' => $booking->booking_reference,
                'payable_type' => $payment->payable_type,
            ],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => strtolower($payment->currency ?: 'NPR'),
                    'unit_amount' => (int) round(((float) $payment->amount) * 100),
                    'product_data' => [
                        'name' => $booking->departure?->trek?->title ?? 'Trek Booking',
                        'description' => 'Reference ' . $booking->booking_reference . ' for ' . $booking->total_passengers . ' passenger(s)',
                    ],
                ],
            ]],
        ]);
    }

    /**
     * Yo method le retrieveSession ko service-level kaam handle garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function retrieveSession(string $sessionId): Session
    {
        return $this->client()->checkout->sessions->retrieve($sessionId, []);
    }

    /**
     * Yo method le constructWebhookEvent ko service-level kaam handle garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function constructWebhookEvent(string $payload, string $signature): Event
    {
        $secret = config('services.stripe.webhook_secret');

        if (! $secret) {
            throw new RuntimeException('Stripe webhook secret is not configured.');
        }

        return Webhook::constructEvent($payload, $signature, $secret);
    }
}








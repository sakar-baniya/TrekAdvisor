<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TrekBooking;
use App\Services\StripeCheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use UnexpectedValueException;

class StripeCheckoutController extends Controller
{
    public function __construct(
        protected StripeCheckoutService $stripeCheckoutService
    ) {
    }

    /**
     * @throws ApiErrorException
     */
    public function retry(Payment $payment): RedirectResponse
    {
        $this->ensurePaymentOwner($payment);

        if ($payment->payment_for !== 'trek') {
            abort(404);
        }

        if ($payment->status === 'Success') {
            return redirect()->route('stripe.success', ['payment' => $payment, 'session_id' => $payment->stripe_session_id]);
        }

        $booking = TrekBooking::query()
            ->with('departure.trek', 'user')
            ->findOrFail($payment->reference_id);

        $session = $this->stripeCheckoutService->createTrekCheckoutSession(
            $payment,
            $booking,
            route('stripe.success', ['payment' => $payment]) . '?session_id={CHECKOUT_SESSION_ID}',
            route('stripe.cancel', ['payment' => $payment])
        );

        return redirect()->away($session->url);
    }

    /**
     * @throws ApiErrorException
     */
    public function success(Request $request, Payment $payment): View
    {
        $this->ensurePaymentOwner($payment);

        $sessionId = (string) $request->query('session_id', '');

        if ($sessionId !== '') {
            $session = $this->stripeCheckoutService->retrieveSession($sessionId);

            if ($session->payment_status === 'paid') {
                $payment = $this->stripeCheckoutService->markCheckoutCompleted(
                    $session->id,
                    is_string($session->payment_intent) ? $session->payment_intent : null,
                    $session->toArray()
                ) ?? $payment->fresh();
            }
        }

        $booking = TrekBooking::query()
            ->with('departure')
            ->findOrFail($payment->reference_id);

        return view('bookings.success', [
            'booking' => $booking,
            'payment' => $payment->fresh(),
            'checkoutCancelled' => false,
        ]);
    }

    public function cancel(Payment $payment): View
    {
        $this->ensurePaymentOwner($payment);

        $booking = TrekBooking::query()
            ->with('departure')
            ->findOrFail($payment->reference_id);

        return view('bookings.success', [
            'booking' => $booking,
            'payment' => $payment->fresh(),
            'checkoutCancelled' => true,
        ]);
    }

    public function webhook(Request $request): Response
    {
        $secret = config('services.stripe.webhook_secret');

        if (!$secret) {
            abort(500, 'Stripe webhook secret is not configured.');
        }

        $payload = $request->getContent();
        $signature = (string) $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (UnexpectedValueException|SignatureVerificationException $exception) {
            return response($exception->getMessage(), 400);
        }

        $object = $event->data->object;

        if ($event->type === 'checkout.session.completed' || $event->type === 'checkout.session.async_payment_succeeded') {
            $this->stripeCheckoutService->markCheckoutCompleted(
                $object->id,
                is_string($object->payment_intent ?? null) ? $object->payment_intent : null,
                (array) $object
            );
        }

        if ($event->type === 'checkout.session.expired' || $event->type === 'checkout.session.async_payment_failed') {
            $this->stripeCheckoutService->markCheckoutFailed($object->id, (array) $object);
        }

        return response('Webhook handled', 200);
    }

    protected function ensurePaymentOwner(Payment $payment): void
    {
        if ((int) $payment->user_id !== (int) Auth::id()) {
            throw new AccessDeniedHttpException();
        }
    }
}

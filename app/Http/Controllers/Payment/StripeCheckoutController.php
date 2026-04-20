<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payment\PaymentAccessService;
use App\Services\Payment\StripeCheckoutService;
use App\Services\Payment\TrekPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;

/**
 * Stripe Checkout Controller: International payments (Credit Card) handle garchha.
 */
class StripeCheckoutController extends Controller
{
    public function __construct(
        protected StripeCheckoutService $stripeCheckoutService,
        protected TrekPaymentService $trekPaymentService,
        protected PaymentAccessService $paymentAccessService,
    ) {
    }

    /**
     * @throws ApiErrorException
     */
    public function retry(Payment $payment): RedirectResponse
    {
        $this->paymentAccessService->authorizeOwner($payment, (int) Auth::id());

        if ($payment->payable_type !== 'trek') {
            abort(404);
        }

        if ($payment->status === 'success') {
            return redirect()->route('stripe.success', ['payment' => $payment, 'session_id' => $payment->stripe_session_id]);
        }

        return redirect()->away($this->stripeCheckoutService->createRetrySession($payment));
    }

    /**
     * @throws ApiErrorException
     */
    public function success(Request $request, Payment $payment): View
    {
        $this->paymentAccessService->authorizeOwner($payment, (int) Auth::id());

        $payment = $this->stripeCheckoutService->syncSuccessfulPayment(
            $payment,
            (string) $request->query('session_id', '')
        );

        return view('bookings.checkout-result', [
            'booking' => $this->trekPaymentService->getDisplayBooking($payment),
            'payment' => $payment,
            'checkoutCancelled' => false,
        ]);
    }

    /**
     * Payment Cancelled: Customer le stripe form mathi 'cancel' thichepachi result dekhaune.
     */
    public function cancel(Payment $payment): View
    {
        $this->paymentAccessService->authorizeOwner($payment, (int) Auth::id());

        return view('bookings.checkout-result', [
            'booking' => $this->trekPaymentService->getDisplayBooking($payment),
            'payment' => $payment,
            'checkoutCancelled' => true,
        ]);
    }

    /**
     * Webhook Handle: Stripe ko server bata aayeko background signals read garne.
     */
    public function webhook(Request $request): Response
    {
        try {
            $event = $this->stripeCheckoutService->createWebhookEvent(
                $request->getContent(),
                (string) $request->header('Stripe-Signature')
            );
        } catch (UnexpectedValueException|SignatureVerificationException $exception) {
            return response($exception->getMessage(), 400);
        }

        $this->stripeCheckoutService->handleWebhookEvent($event);

        return response('Webhook handled', 200);
    }
}






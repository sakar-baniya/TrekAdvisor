<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payment\EsewaCheckoutWorkflowService;
use App\Services\Payment\PaymentAccessService;
use App\Services\Payment\TrekPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Yo EsewaCheckoutController controller le esewa checkout controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class EsewaCheckoutController extends Controller
{
    public function __construct(
        protected EsewaCheckoutWorkflowService $esewaCheckoutWorkflowService,
        protected TrekPaymentService $trekPaymentService,
        protected PaymentAccessService $paymentAccessService,
    ) {
    }

    /**
     * Yo function le retry ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function retry(Payment $payment): View
    {
        $this->paymentAccessService->authorizeOwner($payment, (int) Auth::id());

        if ($payment->payable_type !== 'trek') {
            abort(404);
        }

        if ($payment->status === 'success') {
            return view('bookings.checkout-result', [
                'booking' => $this->trekPaymentService->getDisplayBooking($payment),
                'payment' => $payment,
                'checkoutCancelled' => false,
            ]);
        }

        $redirectData = $this->esewaCheckoutWorkflowService->buildRedirectData($payment);

        return view('bookings.esewa-redirect', [
            'payment' => $payment,
            'endpoint' => $redirectData['endpoint'],
            'payload' => $redirectData['payload'],
        ]);
    }

    /**
     * Yo function le success ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function success(Request $request, Payment $payment): View
    {
        $this->paymentAccessService->authorizeOwner($payment, (int) Auth::id());

        $payment = $this->esewaCheckoutWorkflowService->syncSuccessfulPayment($payment, $request->all());

        return view('bookings.checkout-result', [
            'booking' => $this->trekPaymentService->getDisplayBooking($payment),
            'payment' => $payment,
            'checkoutCancelled' => false,
        ]);
    }

    /**
     * Yo function le failure ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function failure(Request $request, Payment $payment): View
    {
        $this->paymentAccessService->authorizeOwner($payment, (int) Auth::id());

        $payment = $this->esewaCheckoutWorkflowService->markFailed($payment, $request->all());

        return view('bookings.checkout-result', [
            'booking' => $this->trekPaymentService->getDisplayBooking($payment),
            'payment' => $payment,
            'checkoutCancelled' => true,
        ]);
    }
}




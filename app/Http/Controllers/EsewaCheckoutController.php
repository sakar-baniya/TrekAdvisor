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
 * eSewa Checkout Controller: Nepal ko payment gateway (eSewa) handle garchha.
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
     * Retry Payment: Yadi payment fail bhayo bhane feri eSewa ma pathaune.
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
     * Payment Success: Bank bata paisa cutting bhayepachi booking confirm garne.
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
     * Payment Failure: Transaction cancel ya message error aayepachi handle garne.
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




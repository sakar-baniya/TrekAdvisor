<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payment\EsewaCheckoutWorkflowService;
use App\Services\Payment\PaymentAccessService;
use App\Services\Payment\TrekPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EsewaCheckoutController extends Controller
{
    public function __construct(
        protected EsewaCheckoutWorkflowService $esewaCheckoutWorkflowService,
        protected TrekPaymentService $trekPaymentService,
        protected PaymentAccessService $paymentAccessService,
    ) {
    }

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

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Customer Payment Controller: Customer le aafno eSewa/Stripe payment history herne thau.
 */
class PaymentController extends Controller
{
    /**
     * Payment List (Index): Paila gareko sabai payments ko list dekhaune.
     */
    public function index(Request $request): View
    {
        $payments = Payment::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(12);

        return view('account.payments.payment-list', [
            'payments' => $payments,
        ]);
    }
}




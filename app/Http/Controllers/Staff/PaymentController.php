<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\TrekBooking;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Yo PaymentController controller le payment controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class PaymentController extends Controller
{
    /**
     * Show a staff queue of payments that need follow-up.
     *
     * Default queue: pending + failed payments.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $payments = Payment::query()
            ->with('user')
            ->when($status !== '', fn ($query) => $query->whereIn('status', [$status, ucfirst($status)]))
            ->when($status === '', fn ($query) => $query->whereIn('status', ['pending', 'Pending', 'failed', 'Failed']))
            ->when($search !== '', function ($query) use ($search) {
                // Search either by transaction id or customer identity.
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('transaction_id', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($customerQuery) => $customerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('staff.payments.payment-list', [
            'payments' => $payments,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Show one payment with its linked booking reference.
     */
    public function show(Payment $payment): View
    {
        $payment->load('user');

        return view('staff.payments.payment-details', [
            'payment' => $payment,
            'reference' => $this->resolveReference($payment),
        ]);
    }

    /**
     * Resolve the booking record linked to this payment.
     */
    protected function resolveReference(Payment $payment): TrekBooking|HotelBooking|null
    {
        return match ($payment->payable_type) {
            'trek' => TrekBooking::query()->with(['departure.trek', 'user'])->find($payment->payable_id),
            'hotel' => HotelBooking::query()->with(['hotelRoom.hotel', 'user'])->find($payment->payable_id),
            default => null,
        };
    }
}


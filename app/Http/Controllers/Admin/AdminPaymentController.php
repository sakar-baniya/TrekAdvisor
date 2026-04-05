<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GearRental;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\TrekBooking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $type = $request->string('type')->toString();
        $search = $request->string('search')->toString();
        $from = $request->string('from')->toString();
        $to = $request->string('to')->toString();

        $payments = Payment::query()
            ->with('user')
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($type !== '', fn ($query) => $query->where('payable_type', $type))
            ->when($from !== '', fn ($query) => $query->whereDate('created_at', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('created_at', '<=', $to))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('transaction_id', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $payments->getCollection()->transform(function (Payment $payment) {
            $payment->setAttribute('reference_record', $this->resolveReference($payment));

            return $payment;
        });

        $totalAmount = Payment::query()
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($type !== '', fn ($query) => $query->where('payable_type', $type))
            ->when($from !== '', fn ($query) => $query->whereDate('created_at', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('created_at', '<=', $to))
            ->sum('amount');

        return view('admin.payments.index', compact('payments', 'status', 'type', 'search', 'from', 'to', 'totalAmount'));
    }

    public function show(Payment $payment): View
    {
        $payment->load('user');
        $reference = $this->resolveReference($payment);

        return view('admin.payments.show', [
            'payment' => $payment,
            'reference' => $reference,
        ]);
    }

    protected function resolveReference(Payment $payment): TrekBooking|HotelBooking|GearRental|null
    {
        return match ($payment->payable_type) {
            'trek' => TrekBooking::query()->with(['departure.trek', 'user'])->find($payment->payable_id),
            'hotel' => HotelBooking::query()->with(['hotelRoom.hotel', 'user'])->find($payment->payable_id),
            'gear' => GearRental::query()->with(['gearItem', 'user'])->find($payment->payable_id),
            default => null,
        };
    }
}


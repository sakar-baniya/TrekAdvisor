<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Trek;
use App\Models\TrekBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminTrekBookingController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $trekId = $request->string('trek_id')->toString();
        $status = $request->string('status')->toString();

        $bookings = TrekBooking::query()
            ->with(['user', 'departure.trek'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('booking_reference', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->when($trekId !== '', fn ($query) => $query->whereHas('departure', fn ($departureQuery) => $departureQuery->where('trek_id', $trekId)))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.trek-bookings.index', [
            'bookings' => $bookings,
            'treks' => Trek::query()->orderBy('title')->get(),
            'search' => $search,
            'selectedTrek' => $trekId,
            'selectedStatus' => $status,
        ]);
    }

    public function show(TrekBooking $trekBooking): View
    {
        $trekBooking->load(['user', 'departure.trek', 'passengers']);

        $payment = Payment::query()
            ->where('payable_type', 'trek')
            ->where('payable_id', $trekBooking->id)
            ->latest()
            ->first();

        return view('admin.trek-bookings.show', [
            'booking' => $trekBooking,
            'payment' => $payment,
        ]);
    }

    public function updateStatus(Request $request, TrekBooking $trekBooking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled'])],
        ]);

        $trekBooking->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.trek-bookings.show', $trekBooking)
            ->with('success', 'Booking status updated.');
    }
}


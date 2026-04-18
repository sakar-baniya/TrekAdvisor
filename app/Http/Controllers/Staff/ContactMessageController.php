<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Staff Contact Message Controller: Staff le customer messages herne ra reply dine thau.
 *
 * Function:
 * Contact form bata aayeka messages check garne, read mark garne, ra support reply pathaune.
 */
class ContactMessageController extends Controller
{
    /**
     * Message List (Index): Staff inbox jaha sabai messages search ra status anusar herna milcha.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $messages = ContactMessage::query()
            ->with('respondedByStaff')
            ->when($status === 'unread', fn ($query) => $query->where('is_read', false))
            ->when($status === 'responded', fn ($query) => $query->whereNotNull('responded_at'))
            ->when($search !== '', function ($query) use ($search) {
                // Search sender fields only (name, email, subject).
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('staff.contact-messages.message-list', [
            'messages' => $messages,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * View Message (Show): Specific message open garne ra 'is_read' flag update garne.
     */
    public function show(ContactMessage $contactMessage): View
    {
        if (! $contactMessage->is_read) {
            $contactMessage->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        $contactMessage->load('respondedByStaff');

        return view('staff.contact-messages.message-details', [
            'message' => $contactMessage,
        ]);
    }

    /**
     * Staff Respond: Form bata aayeko reply save garne ra customer lai email pathaune.
     */
    public function respond(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $validated = $request->validate([
            'staff_response' => ['required', 'string', 'max:4000'],
        ]);

        $contactMessage->update([
            'is_read' => true,
            'read_at' => $contactMessage->read_at ?? now(),
            'staff_response' => $validated['staff_response'],
            'responded_by_staff_id' => Auth::id(),
            'responded_at' => now(),
        ]);

        if (filled($contactMessage->email)) {
            Mail::to($contactMessage->email)->send(new ContactMessageReplyMail($contactMessage));
        }

        return redirect()
            ->route('staff.contact-messages.show', $contactMessage)
            ->with('success', 'Response saved.');
    }
}

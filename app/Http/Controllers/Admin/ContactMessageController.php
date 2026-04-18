<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Admin Contact Messages Controller: Customer support handle garne thau.
 *
 * Function:
 * Website ko contact form bata aayeko sabai messages haru herne ra email reply pathaune.
 */
class ContactMessageController extends Controller
{
    /**
     * Message List (Index): Sabai incoming messages dekhaune.
     * 
     * Why:
     * Name, email id, wa subject bata message search garera support dina sajilo huna ko lagi.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $messages = ContactMessage::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.contact-messages.message-list', [
            'messages' => $messages,
            'search' => $search,
        ]);
    }

    /**
     * Message Details (Show): Pura message ra details herne thau.
     */
    public function show(ContactMessage $contactMessage): View
    {
        return view('admin.contact-messages.message-details', [
            'message' => $contactMessage,
        ]);
    }

    /**
     * Respond (Reply): Admin le customer lai email reply garne.
     * 
     * Process:
     * 1. Response save garchha. 2. Status 'read' banauchha. 3. Real email pathauchha.
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
            ->route('admin.contact-messages.show', $contactMessage)
            ->with('success', 'Reply saved and email sent to customer.');
    }
}




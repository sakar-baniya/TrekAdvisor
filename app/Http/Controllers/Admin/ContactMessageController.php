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
 * Yo ContactMessageController controller le contact message controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class ContactMessageController extends Controller
{
    /**
     * Yo function le index ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
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
     * Yo function le show ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function show(ContactMessage $contactMessage): View
    {
        return view('admin.contact-messages.message-details', [
            'message' => $contactMessage,
        ]);
    }

    /**
     * Save admin reply and send response email to message sender.
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




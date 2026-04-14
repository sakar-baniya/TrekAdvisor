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
 * Yo ContactMessageController controller le staff ko contact inbox flow handle garcha.
 *
 * Why:
 * Contact message list, open, ra response save ko logic euta thau ma huda support flow clear huncha.
 */
class ContactMessageController extends Controller
{
    /**
     * Yo function le staff inbox ko message list banaucha with search ra status filter.
     *
     * Why:
     * Staff lai unread/responded message chhito track garna easy hunchha.
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
     * Yo function le selected message open garera first view ma read mark garcha.
     *
     * Why:
     * Team le kun message heriyo/herena clearly track garna milcha.
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
     * Yo function le staff ko response note validate garera message ma save garcha.
     *
     * Why:
     * Follow-up history, response owner, ra response time record clear rahos bhanera.
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

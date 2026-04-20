<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ContactMessageRequest;
use App\Models\ContactMessage;
use App\Mail\NewContactMessageMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

/**
 * Root Contact Controller: Website ko global contact form handle garne thau.
 */
class ContactController extends Controller
{
    /**
     * Store (Action): Form bata aayeko message database ma save garne.
     */
    public function store(ContactMessageRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $contactMessage = ContactMessage::query()->create([
            'user_id' => $request->user()?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        Mail::to('sakarbaniya2005@gmail.com')->send(new NewContactMessageMail($contactMessage));

        return back()->with('success', 'Thanks for reaching out. We will reply within one business day.');
    }
}




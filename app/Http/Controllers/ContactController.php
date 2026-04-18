<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;

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

        ContactMessage::query()->create([
            'user_id' => $request->user()?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        return back()->with('success', 'Thanks for reaching out. We will reply within one business day.');
    }
}




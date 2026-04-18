<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Confirm Password Controller: Sensitive settings hernu vanda paila password check garne thau.
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Confirm View: Sanu sensitive action garnu vanda paila password magne screen.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm Action: Password check garera 2-hour session confirm garne.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}


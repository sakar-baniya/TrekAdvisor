<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * User Session Controller: User session suru ra khatam garne thau.
 *
 * Function:
 * Customer, Staff, ra Hotel Owner ko login session manage garchha.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Login Page View: User lai login form dekhaune.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Login Action: Credential check garne ra role anusar dashboard ma pathaune.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(); //checks if email and password are correct

        $request->session()->regenerate(); //starts session for the user

        $user = Auth::user();

        // Prevent admin from logging in via frontend login
        if ($user->role === 'admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        if (($user->role === 'hotel_owner' || $user->role === 'staff') && ! $user->isApproved()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your ' . $user->role . ' account is pending admin approval.',
            ]);
        }

        return redirect()->intended(route($user->dashboardRouteName(), absolute: false));
    }

    /**
     * Logout Action: Session invalidate garera login page ma ferkaune.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}


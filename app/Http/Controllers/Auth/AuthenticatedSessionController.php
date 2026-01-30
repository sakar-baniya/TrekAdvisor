<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(); //checks if email and password are correct

        $request->session()->regenerate(); //starts session for the user

        // Simple logic: Get the role of the person who just logged in
        $role = Auth::user()->role;

        //  Redirect based on role
        return match ($role){
            'admin' => redirect()->intended('/admin/dashboard'),
            'staff' => redirect()->intended('/staff/dashboard'),
            'hotel_owner' => redirect()->intended('/hotel/dashboard'),
            'customer' => redirect()->intended('/customer/dashboard'),
            default => redirect()->intended('/'), // Fallback
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

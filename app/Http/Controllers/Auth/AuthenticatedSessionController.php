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

        // 3. Simple logic: Get the role of the person who just logged in
        $role = Auth::user()->role;

        // 4. Redirect based on role
        if ($role === 'admin' || $role === 'staff') {
            return redirect('/dashboard'); 
        }

        if ($role === 'hotel_owner') {
            // You can create a '/hotel-dashboard' later, for now let's send them home
            return redirect('/'); 
        }

        // Default: Regular customers go to the welcome/home page
        return redirect('/');
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

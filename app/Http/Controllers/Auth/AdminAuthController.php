<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Admin Auth Controller: Admin login handle garne thau.
 *
 * Function:
 * Administrator login flow, password verify, ra session security handle garchha.
 */
class AdminAuthController extends Controller
{
    /**
     * Login View: Admin login page dekhaune thau.
     */
    public function create(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Login Action: Post request validate garera admin lai dashboard ma pathaune.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Only admins can login here
        if ($user->role !== 'admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => 'Only administrators can access this login.',
            ]);
        }

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Logout Action: Sessions clean garne ra logout garne.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}


<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * User Registration Controller: Naya user register garne thau.
 *
 * Function:
 * Customer register garne ra Hotel Partner application submit garne logic herya chha.
 */
class RegisteredUserController extends Controller
{
    /**
     * Register View: Customer registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Hotel Partner View: Hotel owner banne form.
     */
    public function createHotel(): View
    {
        return view('auth.register-hotel');
    }

    /**
     * Submit Partner App: Hotel owner ko profile database ma 'pending' status ma rakhne.
     */
    public function storeHotel(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'digits:10'],
            'hotel_name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'hotel_owner',
            'phone' => $request->input('phone'),
            'approval_status' => 'pending',
        ]);

        event(new Registered($user));

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Welcome aboard! Your Hotel Partner application is now pending admin approval. We will notify you once approved.');
    }

    /**
     * Register Customer Action: Naya customer account banaune ra sidhai login garaune.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'digits:10'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->input('phone'),
            'approval_status' => 'approved',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}


<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\User\UserProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UserProfileService $profileService
    ) {
    }

    public function show(Request $request): View
    {
        $user = $request->user();
        $profile = $this->profileService->getProfile($user);

        return view('account.profile.show', compact('user', 'profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $this->profileService->updateProfile($request->user(), $validated);

        return Redirect::route('account.profile.show')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('account.profile.show')->with('status', 'password-updated');
    }
}

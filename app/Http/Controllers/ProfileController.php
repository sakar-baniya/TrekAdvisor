<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\User\UserProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UserProfileService $profileService
    ) {
    }



    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $this->profileService->updateProfile($request->user(), $validated);

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Show user profile details page.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $profile = $this->profileService->getProfile($user);

        return view('profile.show', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

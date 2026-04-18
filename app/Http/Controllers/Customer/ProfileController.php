<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\User\UserProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Customer Profile Controller: Account settings ra password change garne thau.
 *
 * Function:
 * User le aafno naam, email, ra password purai change garna pauchha yaha bata.
 */
class ProfileController extends Controller
{
    public function __construct(
        private readonly UserProfileService $profileService
    ) {
    }

    /**
     * Profile Settings View: Basic details edit garne page herne.
     */
    public function settingsProfile(Request $request): View
    {
        $user = $request->user();
        $profile = $this->profileService->getProfile($user);

        return view('settings.profile', compact('user', 'profile'));
    }

    /**
     * Security Settings View: Password ya 2FA change garne page herne.
     */
    public function settingsSecurity(Request $request): View
    {
        return view('settings.security', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Change Password View: Naya password halne chhutai screen herne.
     */
    public function settingsSecurityPassword(Request $request): View
    {
        return view('settings.security-password', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update Password (Action): Database ma naya encrypted password save garne.
     */
    public function updateSecurityPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Upload Avatar (Action): Profile picture upload garera storage ma save garne.
     */
    public function storeAvatar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $validated['avatar']->store('avatars', 'public');

        $user->update([
            'avatar_path' => $path,
        ]);

        return back()->with('status', 'avatar-updated');
    }

    /**
     * Remove Avatar (Action): Profile picture storage bata hataune.
     */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
            $user->update(['avatar_path' => null]);
        }

        return back()->with('status', 'avatar-removed');
    }



    /**
     * Update Profile Info (Action): User ko naam ra thamel update garne.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $changed = $user->name !== ($validated['name'] ?? $user->name)
            || $user->email !== ($validated['email'] ?? $user->email)
            || $user->phone !== ($validated['phone'] ?? $user->phone)
            || $user->address !== ($validated['address'] ?? $user->address);

        if ($changed) {
            $this->profileService->updateProfile($user, $validated);
            return Redirect::route('settings.profile.show')->with('status', 'profile-updated');
        }

        return Redirect::route('settings.profile.show');
    }

    /**
     * Show Profile View: Profile page ma pathaune redirect helper.
     */
    public function show(Request $request): RedirectResponse
    {
        return Redirect::route('settings.profile.show');
    }

    /**
     * Delete Account (Action): Khata purai Delete gari Session out garne.
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




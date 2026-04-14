<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Yo UserController controller le user controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class UserController extends Controller
{
    /**
     * Yo function le index ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $role = $request->string('role')->toString();

        $usersQuery = User::query()
            ->orderByDesc('created_at');

        if ($search !== '') {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role !== '') {
            $usersQuery->where('role', $role);
        }

        $users = $usersQuery->paginate(12)->withQueryString();
        $pendingHotelOwners = User::query()
            ->where('role', 'hotel_owner')
            ->where('approval_status', 'pending')
            ->count();

        return view('admin.users.user-list', [
            'users' => $users,
            'pendingHotelOwners' => $pendingHotelOwners,
            'search' => $search,
            'role' => $role,
        ]);
    }

    /**
     * Yo function le create staff ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function createStaff(): View
    {
        return view('admin.users.create-staff');
    }

    /**
     * Yo function le store staff ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function storeStaff(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:admin,staff'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'approval_status' => 'approved',
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Staff user added.');
    }

    /**
     * Yo function le approve ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function approve(User $user): RedirectResponse
    {
        if ($user->role !== 'hotel_owner') {
            return back()->with('error', 'Only hotel owner accounts require approval.');
        }

        if (! $user->isApproved()) {
            $user->update(['approval_status' => 'approved']);
        }

        return back()->with('success', 'Hotel owner approved.');
    }

    /**
     * Yo function le update role ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,staff,customer,hotel_owner'],
        ]);

        $newRole = $validated['role'];
        $wasHotelOwner = $user->role === 'hotel_owner';
        $wasApproved = $user->approval_status === 'approved';

        $user->role = $newRole;

        if ($newRole === 'hotel_owner') {
            $user->approval_status = $wasHotelOwner && $wasApproved ? 'approved' : 'pending';
        } else {
            $user->approval_status = 'approved';
        }

        $user->save();

        return back()->with('success', 'User role updated.');
    }
}




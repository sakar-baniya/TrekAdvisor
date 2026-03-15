<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $usersQuery = User::query()
            ->orderByDesc('created_at');

        if ($search !== '') {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->paginate(12)->withQueryString();
        $pendingHotelOwners = User::query()
            ->where('role', 'hotel_owner')
            ->where('is_approved', false)
            ->count();

        return view('admin.users.index', [
            'users' => $users,
            'pendingHotelOwners' => $pendingHotelOwners,
            'search' => $search,
        ]);
    }

    public function approve(User $user): RedirectResponse
    {
        if ($user->role !== 'hotel_owner') {
            return back()->with('error', 'Only hotel owner accounts require approval.');
        }

        if (! $user->is_approved) {
            $user->update(['is_approved' => true]);
        }

        return back()->with('success', 'Hotel owner approved.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,staff,customer,hotel_owner'],
        ]);

        $newRole = $validated['role'];
        $wasHotelOwner = $user->role === 'hotel_owner';
        $wasApproved = $user->is_approved;

        $user->role = $newRole;

        if ($newRole === 'hotel_owner') {
            $user->is_approved = $wasHotelOwner ? $wasApproved : false;
        } else {
            $user->is_approved = true;
        }

        $user->save();

        return back()->with('success', 'User role updated.');
    }
}

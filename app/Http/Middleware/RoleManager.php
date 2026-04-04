<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to check user role and authorization
 * 
 * Usage in routes:
 *   Route::middleware(['auth', 'role:admin'])->group(...) // Single role
 *   Route::middleware(['auth', 'role:admin,staff'])->group(...) // Multiple roles
 */
class RoleManager
{
    /**
     * Handle incoming request and check user role
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $roles Comma-separated role names (e.g., 'admin' or 'admin,staff')
     * @return Response
     */
    public function handle(Request $request, Closure $next, $roles = null): Response
    {
        // User must be authenticated
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Get current user
        $user = auth()->user();

        // If no roles specified, allow any authenticated user
        if (empty($roles)) {
            return $next($request);
        }

        // Parse roles (comma-separated)
        $allowedRoles = array_map('trim', explode(',', $roles));

        // Check if user's role is in allowed roles
        if (!in_array($user->role, $allowedRoles)) {
            // User not authorized
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized access.'], 403);
            }

            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}


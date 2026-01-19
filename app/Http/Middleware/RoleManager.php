<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, \Closure $next, $role)
    {
        // 1. Is the user logged in?
        // 2. Is their role NOT equal to the required role?
        if (!auth()->check() || auth()->user()->role !== $role) {
            
            // If they are a customer trying to enter /admin, send them back home
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}

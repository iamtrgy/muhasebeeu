<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Allow access only if the user is NOT an admin AND NOT an accountant
            if (!$user->is_admin && !$user->is_accountant) {
                return $next($request);
            }
        }

        // If not authenticated or is admin/accountant, redirect or abort
        // Redirecting to home might be safer to avoid exposing routes
        return redirect('/')->with('error', 'Unauthorized access.');
        // Alternatively, abort with a 403
        // abort(403, 'Unauthorized action.');
    }
}

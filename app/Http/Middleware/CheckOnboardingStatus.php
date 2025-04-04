<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboardingStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Skip onboarding check for admin users
            if ($user->is_admin) {
                return $next($request);
            }
            
            // If onboarding is not completed and the route is not an onboarding route
            if (!$user->onboarding_completed && 
                !$request->routeIs('onboarding.*') && 
                !$request->routeIs('logout')) {
                
                return redirect()->route('onboarding.index');
            }
        }
        
        return $next($request);
    }
}

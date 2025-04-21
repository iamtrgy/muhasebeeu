<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminToDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Redirect admins to admin dashboard
            if ($user->is_admin) {
                if ($request->routeIs('user.dashboard') || $request->routeIs('onboarding.*')) {
                    return redirect()->route('admin.dashboard');
                }
            }
            
            // Redirect accountants to accountant dashboard
            if ($user->is_accountant && $request->routeIs('user.dashboard')) {
                return redirect()->route('accountant.dashboard');
            }
        }
        
        return $next($request);
    }
} 
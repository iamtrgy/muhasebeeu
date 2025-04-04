<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class EnsureUserIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }
        
        // Admin users bypass subscription requirements
        if ($request->user()->is_admin) {
            return $next($request);
        }

        // Check if 'subscriptions' table exists
        if (!Schema::hasTable('subscriptions')) {
            Log::warning('Subscriptions table does not exist. Running in development mode.');
            // Allow access as if user is subscribed
            return $next($request);
        }

        try {
            // Check if user has an active subscription using Laravel Cashier
            if ($request->user()->subscribed('default')) {
                return $next($request);
            }
            
            // Fallback: Direct database check in case Cashier's check has issues
            if ($request->user()->hasActiveSubscription('default')) {
                Log::warning("User {$request->user()->id} has an active subscription in database but Cashier didn't detect it");
                return $next($request);
            }
        } catch (\Exception $e) {
            Log::error('Error checking subscription: ' . $e->getMessage());
            // Allow access in case of errors to prevent blocking legitimate users
            return $next($request);
        }

        // Store the intended URL for redirection after subscription
        session()->put('url.intended', $request->url());

        // Redirect to subscription plans page
        return redirect()->route('user.subscription.plans')
            ->with('warning', 'Please subscribe to access this feature.');
    }
} 
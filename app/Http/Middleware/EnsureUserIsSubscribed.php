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
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }
        
        // Admin users bypass subscription requirements
        if ($request->user()->is_admin) {
            return $next($request);
        }

        // Check if subscriptions table exists
        if (!Schema::hasTable('subscriptions')) {
            return redirect()->route('user.subscription.plans')
                ->with('warning', 'Please subscribe to access this feature.');
        }

        try {
            // Check if user has an active subscription using our custom method
            // This method properly checks for expired trials and subscription end dates
            if ($request->user()->hasActiveSubscription('default')) {
                return $next($request);
            }

            // Store the intended URL for redirection after subscription
            session()->put('url.intended', $request->url());

            // Redirect to subscription plans page if no active subscription
            return redirect()->route('user.subscription.plans')
                ->with('warning', 'Please subscribe to access this feature.');

        } catch (\Exception $e) {
            Log::error('Error checking subscription: ' . $e->getMessage());
            return redirect()->route('user.subscription.plans')
                ->with('error', 'There was an error checking your subscription. Please try again.');
        }
    }
} 
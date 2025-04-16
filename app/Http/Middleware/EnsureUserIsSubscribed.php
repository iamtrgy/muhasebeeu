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
        Log::info("EnsureUserIsSubscribed middleware entered for user: " . ($request->user() ? $request->user()->id : 'Guest'));
        
        if (! $request->user()) {
            Log::warning('User not authenticated, redirecting to login.');
            return redirect()->route('login');
        }
        
        // Admin users bypass subscription requirements
        if ($request->user()->is_admin) {
            Log::info("User {$request->user()->id} is admin, bypassing subscription check.");
            return $next($request);
        }

        // Always redirect to plans if no subscription exists
        if (!Schema::hasTable('subscriptions')) {
            Log::warning('Subscriptions table does not exist. Redirecting to plans.');
            return redirect()->route('user.subscription.plans')
                ->with('warning', 'Please subscribe to access this feature.');
        }

        try {
            Log::info("Checking subscription for user {$request->user()->id}");
            
            // First check if user has any subscription at all
            $hasAnySubscription = $request->user()->subscriptions()->exists();
            Log::info("User has any subscription: " . ($hasAnySubscription ? 'true' : 'false'));
            
            if (!$hasAnySubscription) {
                Log::info("User {$request->user()->id} has no subscription at all. Redirecting to plans.");
                return redirect()->route('user.subscription.plans')
                    ->with('warning', 'Please subscribe to access this feature.');
            }

            // Check if user has an active subscription using Laravel Cashier
            $isSubscribedCashier = $request->user()->subscribed('default');
            Log::info("Cashier check (subscribed('default')): " . ($isSubscribedCashier ? 'true' : 'false'));

            if ($isSubscribedCashier) {
                Log::info("User is subscribed (Cashier check passed).");
                return $next($request);
            }
            
            // Fallback: Direct database check in case Cashier's check has issues
            $hasActiveDb = $request->user()->hasActiveSubscription('default');
            Log::info("Fallback check (hasActiveSubscription('default')): " . ($hasActiveDb ? 'true' : 'false'));
            
            if ($hasActiveDb) {
                Log::warning("User {$request->user()->id} has an active subscription in database but Cashier didn't detect it");
                return $next($request);
            }

            Log::info("User {$request->user()->id} is NOT subscribed after both checks. Redirecting to plans.");

        } catch (\Exception $e) {
            Log::error('Error checking subscription: ' . $e->getMessage() . ' for user ' . $request->user()->id, ['exception' => $e]);
            // Redirect to plans instead of allowing access on error
            return redirect()->route('user.subscription.plans')
                ->with('warning', 'Please subscribe to access this feature.');
        }

        // Store the intended URL for redirection after subscription
        session()->put('url.intended', $request->url());
        Log::info("Redirecting user {$request->user()->id} to subscription plans.");

        // Redirect to subscription plans page
        return redirect()->route('user.subscription.plans')
            ->with('warning', 'Please subscribe to access this feature.');
    }
} 
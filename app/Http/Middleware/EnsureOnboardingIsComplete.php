<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is not onboarded or has no companies, redirect to onboarding
        if (!$user->onboarding_completed || $user->companies()->count() === 0) {
            // Reset onboarding status if user has no companies
            if ($user->onboarding_completed && $user->companies()->count() === 0) {
                $user->update([
                    'onboarding_completed' => false,
                    'onboarding_step' => 'company_creation'
                ]);
            }

            // If trying to access subscription routes, show a message
            if ($request->is('user/plans*') || $request->is('user/subscription*')) {
                return redirect()->route('onboarding.step2')
                    ->with('warning', 'Please complete company setup before accessing subscription features.');
            }
            
            return redirect()->route('onboarding.step1');
        }

        return $next($request);
    }
} 
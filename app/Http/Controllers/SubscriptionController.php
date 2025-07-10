<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionController extends Controller
{
    public function showPlans()
    {
        $user = auth()->user();
        
        // Redirect admins away from plans page - they don't need subscriptions
        if ($user && $user->is_admin) {
            return redirect()->route('user.dashboard')->with('info', 'Administrators do not need to subscribe to plans.');
        }
        
        $currentPlan = null;
        $currentPlanId = null;
        
        // Only show current plan if the user has an ACTIVE subscription
        if ($user && $user->hasActiveSubscription('default')) {
            $subscription = $user->subscription('default');
            $currentPlanId = $subscription->stripe_price;
            
            // Map Stripe price ID to plan name
            $currentPlan = match($currentPlanId) {
                env('STRIPE_BASIC_PRICE_ID') => 'basic',
                env('STRIPE_PRO_PRICE_ID') => 'pro',
                env('STRIPE_ENTERPRISE_PRICE_ID') => 'enterprise',
                default => null
            };
        }
        
        return view('user.subscriptions.plans', [
            'currentPlan' => $currentPlan,
            'currentPlanId' => $currentPlanId,
            'onGracePeriod' => $user && $user->subscription('default') && $user->hasActiveSubscription('default') ? $user->subscription('default')->onGracePeriod() : false,
            'canceled' => $user && $user->subscription('default') && $user->hasActiveSubscription('default') ? $user->subscription('default')->canceled() : false
        ]);
    }

    public function showPaymentForm(Request $request, string $plan)
    {
        return view('user.subscriptions.payment', [
            'intent' => $request->user()->createSetupIntent(),
            'plan' => $plan
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
            'plan' => 'required|in:basic,pro,enterprise'
        ]);

        $user = $request->user();
        $stripePriceId = $this->getStripePriceId($request->plan);
        
        try {
            // Check if user already has a subscription
            if ($user->subscribed('default')) {
                $subscription = $user->subscription('default');
                
                // If subscription is canceled but on grace period, resume it first
                if ($subscription->canceled() && $subscription->onGracePeriod()) {
                    $subscription->resume();
                }
                
                // If the current plan is different from the selected one, swap plans
                if ($subscription->stripe_price !== $stripePriceId) {
                    $subscription->swap($stripePriceId);
                    return redirect()->route('user.dashboard')
                        ->with('success', 'Your subscription has been updated to the ' . ucfirst($request->plan) . ' plan!');
                } else {
                    return redirect()->route('user.dashboard')
                        ->with('info', 'You are already subscribed to this plan.');
                }
            } else {
                // Create a new subscription
                $user->newSubscription('default', $stripePriceId)
                    ->create($request->payment_method);
                    
                return redirect()->route('user.dashboard')
                    ->with('success', 'Your subscription was successful!');
            }
                
        } catch (IncompletePayment $exception) {
            return redirect()->route('cashier.payment', [
                $exception->payment->id,
                'redirect' => route('user.dashboard')
            ]);
        } catch (\Exception $e) {
            return redirect()->route('user.subscription.plans')
                ->with('error', 'Subscription failed: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscription('default');
        
        // Store cancellation reason for analytics
        $reason = $request->input('reason');
        $otherReason = $request->input('other_reason_text');
        
        // Log cancellation reason
        \Log::info('Subscription cancelled', [
            'user_id' => $user->id,
            'reason' => $reason,
            'other_reason' => $otherReason
        ]);
        
        // You could store this in database for analytics
        // Example: $user->cancellationReasons()->create(['reason' => $reason, 'details' => $otherReason]);
        
        // Cancel the subscription
        $subscription->cancel();
        
        return redirect()->route('user.profile.edit', ['tab' => 'subscription'])->with('status', 'subscription-canceled');
    }

    public function resume(Request $request)
    {
        $request->user()->subscription('default')->resume();
        
        return redirect()->route('user.profile.edit', ['tab' => 'subscription'])
            ->with('success', 'Your subscription has been resumed');
    }

    /**
     * Redirect the user to Stripe's Customer Portal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function billingPortal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(route('profile.edit'));
    }

    private function getStripePriceId(string $plan): string
    {
        // Replace these with your actual Stripe Price IDs
        return match($plan) {
            'basic' => env('STRIPE_BASIC_PRICE_ID'),
            'pro' => env('STRIPE_PRO_PRICE_ID'),
            'enterprise' => env('STRIPE_ENTERPRISE_PRICE_ID'),
            default => throw new \InvalidArgumentException('Invalid plan selected.')
        };
    }
} 
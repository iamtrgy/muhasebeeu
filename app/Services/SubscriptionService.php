<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class SubscriptionService
{
    protected $userDataService;
    
    /**
     * Create a new subscription service instance
     * 
     * @param UserDataService $userDataService
     */
    public function __construct(UserDataService $userDataService)
    {
        $this->userDataService = $userDataService;
    }
    
    /**
     * Create a new subscription for a user
     * 
     * @param User $user
     * @param string $plan
     * @param int $trialDays
     * @return array
     */
    public function createSubscription(User $user, string $plan, int $trialDays = 30)
    {
        $stripePriceId = $this->getPlanPriceId($plan);
        
        try {
            // Create customer in Stripe if they don't exist
            if (!$user->stripe_id) {
                $user->createAsStripeCustomer();
            }
            
            // Create a subscription with a trial period
            $subscription = $user->newSubscription('default', $stripePriceId)
                ->trialDays($trialDays);
                
            // If we're in a development/test environment, we can use the testing approach
            if (app()->environment('local', 'testing', 'development')) {
                $subscription->create();
            } else {
                // In production, create the subscription with trial days
                $subscription->create();
            }
            
            $user->refresh();
            
            // Clear cache
            $this->userDataService->clearUserCaches($user);
            
            // Log successful subscription creation
            Log::info("Admin created trial subscription for user {$user->id} on plan {$plan} for {$trialDays} days");
            
            return [
                'success' => true,
                'message' => "Trial subscription created successfully for {$trialDays} days.",
                'subscription' => $subscription
            ];
        } catch (\Exception $e) {
            Log::error("Failed to create trial subscription: " . $e->getMessage(), [
                'user_id' => $user->id,
                'plan' => $plan,
                'trial_days' => $trialDays,
                'exception' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error creating trial subscription: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Cancel a user's subscription
     * 
     * @param User $user
     * @return array
     */
    public function cancelSubscription(User $user)
    {
        if (!$user->subscription('default') || $user->subscription('default')->canceled()) {
            return [
                'success' => false,
                'message' => 'No active subscription to cancel.'
            ];
        }
        
        $subscription = $user->subscription('default');
        $planName = $this->getPlanName($subscription->stripe_price);
        
        $subscription->cancel();
        
        // Clear cache
        $this->userDataService->clearUserCaches($user);
        
        // Record activity
        \App\Services\ActivityLogService::log(
            'subscription_cancel',
            "Subscription canceled for {$planName} plan",
            $user,
            [
                'plan' => $planName,
                'admin_id' => auth()->id()
            ]
        );
        
        return [
            'success' => true,
            'message' => 'Subscription has been canceled and will end at the current period.',
            'plan' => $planName
        ];
    }
    
    /**
     * Resume a canceled subscription
     * 
     * @param User $user
     * @return array
     */
    public function resumeSubscription(User $user)
    {
        $subscription = $user->subscription('default');
        
        if (!$subscription || !$subscription->onGracePeriod()) {
            return [
                'success' => false,
                'message' => 'No subscription available to resume. The subscription may have been fully canceled or already expired.'
            ];
        }
        
        // Check the Stripe subscription status first
        try {
            if ($subscription->stripe_id) {
                $stripe = new StripeClient(config('cashier.secret'));
                $stripeSubscription = $stripe->subscriptions->retrieve($subscription->stripe_id);
                
                // If the subscription is fully canceled in Stripe, we can't resume it
                if ($stripeSubscription->status === 'canceled') {
                    return [
                        'success' => false,
                        'message' => 'This subscription has been fully canceled in Stripe and cannot be resumed. Please create a new subscription.'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("Error checking Stripe subscription: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error checking subscription status: ' . $e->getMessage()
            ];
        }
        
        try {
            // Now try to resume it
            $planName = $this->getPlanName($subscription->stripe_price);
            
            $subscription->resume();
            
            // Clear user cache after subscription resumption
            $this->userDataService->clearUserCaches($user);
            
            // Record activity
            \App\Services\ActivityLogService::log(
                'subscription_resume',
                "Subscription resumed for {$planName} plan",
                $user,
                [
                    'plan' => $planName,
                    'admin_id' => auth()->id()
                ]
            );
            
            return [
                'success' => true,
                'message' => 'Subscription has been resumed.',
                'plan' => $planName
            ];
        } catch (\Exception $e) {
            Log::error("Failed to resume subscription: " . $e->getMessage(), [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'exception' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Could not resume subscription: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete an incomplete subscription
     * 
     * @param User $user
     * @return array
     */
    public function deleteIncompleteSubscription(User $user)
    {
        if (!$user->subscription('default') || 
            ($user->subscription('default')->stripe_status !== 'incomplete' && 
             $user->subscription('default')->stripe_status !== 'incomplete_expired')) {
            return [
                'success' => false,
                'message' => 'No incomplete subscription found to delete.'
            ];
        }
        
        $subscription = $user->subscription('default');
        
        try {
            // Try to cancel in Stripe first if it exists
            if ($subscription->stripe_id) {
                $stripe = new StripeClient(config('cashier.secret'));
                $stripe->subscriptions->cancel($subscription->stripe_id, [
                    'invoice_now' => false,
                    'prorate' => false,
                ]);
            }
        } catch (\Exception $e) {
            // Log the Stripe error but continue with the local deletion
            Log::error("Error canceling incomplete subscription in Stripe: " . $e->getMessage(), [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'stripe_id' => $subscription->stripe_id
            ]);
            // We don't rethrow - we still want to delete it locally
        }
        
        // Now delete from the database
        $subscription->delete();
        
        // Clear user cache after subscription deletion
        $this->userDataService->clearUserCaches($user);
        
        // Record activity
        \App\Services\ActivityLogService::log(
            'subscription_delete_incomplete',
            "Deleted incomplete subscription",
            $user,
            [
                'stripe_id' => $subscription->stripe_id,
                'admin_id' => auth()->id()
            ]
        );
        
        return [
            'success' => true,
            'message' => 'Incomplete subscription has been deleted.'
        ];
    }
    
    /**
     * Swap a user's subscription to a new plan
     * 
     * @param User $user
     * @param string $newPlan
     * @return array
     */
    public function swapSubscription(User $user, string $newPlan)
    {
        if (!$user->subscribed('default')) {
            return [
                'success' => false,
                'message' => 'User does not have an active subscription.'
            ];
        }
        
        $subscription = $user->subscription('default');
        $newPriceId = $this->getPlanPriceId($newPlan);
        
        // If it's the same plan, do nothing
        if ($subscription->stripe_price === $newPriceId) {
            return [
                'success' => false,
                'message' => 'User is already subscribed to this plan.'
            ];
        }
        
        // If it's canceled but on grace period, resume it first
        if ($subscription->canceled() && $subscription->onGracePeriod()) {
            $subscription->resume();
        }
        
        $oldPlanName = $this->getPlanName($subscription->stripe_price);
        
        try {
            $subscription->swap($newPriceId);
            
            // Clear user cache
            $this->userDataService->clearUserCaches($user);
            
            // Record activity
            \App\Services\ActivityLogService::log(
                'subscription_swap',
                "Subscription plan changed from {$oldPlanName} to " . ucfirst($newPlan),
                $user,
                [
                    'old_plan' => $oldPlanName,
                    'new_plan' => ucfirst($newPlan),
                    'admin_id' => auth()->id()
                ]
            );
            
            return [
                'success' => true,
                'message' => "Subscription updated to {$newPlan} plan."
            ];
        } catch (\Exception $e) {
            Log::error("Failed to swap subscription: " . $e->getMessage(), [
                'user_id' => $user->id,
                'old_plan' => $oldPlanName,
                'new_plan' => $newPlan,
                'exception' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error updating subscription: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get price ID for a plan name
     * 
     * @param string $plan
     * @return string
     */
    private function getPlanPriceId(string $plan): string
    {
        $priceId = match($plan) {
            'basic' => config('cashier.prices.basic'),
            'pro' => config('cashier.prices.pro'),
            'enterprise' => config('cashier.prices.enterprise'),
            default => config('cashier.prices.basic')
        };

        if (!$priceId) {
            throw new \RuntimeException("Stripe price ID not found for plan: $plan. Please check your configuration.");
        }

        return $priceId;
    }
    
    /**
     * Get plan name from a price ID
     * 
     * @param string $priceId
     * @return string
     */
    private function getPlanName(string $priceId): string
    {
        return match($priceId) {
            config('cashier.prices.basic') => 'Basic',
            config('cashier.prices.pro') => 'Pro',
            config('cashier.prices.enterprise') => 'Enterprise',
            default => 'Unknown'
        };
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use App\Models\User;
use App\Models\Subscription;

class StripeWebhookController extends CashierController
{
    /**
     * Handle subscription created event.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionCreated(array $payload)
    {
        Log::info('Stripe webhook: subscription created', ['payload' => $payload]);
        
        // Let Cashier handle the creation first
        $response = parent::handleCustomerSubscriptionCreated($payload);
        
        // Additional custom logic
        $this->syncSubscriptionStatus($payload['data']['object']);
        
        return $response;
    }
    
    /**
     * Handle subscription updated event.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        Log::info('Stripe webhook: subscription updated', ['payload' => $payload]);
        
        // Let Cashier handle the update first
        $response = parent::handleCustomerSubscriptionUpdated($payload);
        
        // Additional custom logic
        $this->syncSubscriptionStatus($payload['data']['object']);
        
        return $response;
    }
    
    /**
     * Handle subscription deleted event.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        Log::info('Stripe webhook: subscription deleted', ['payload' => $payload]);
        
        // Let Cashier handle the deletion
        $response = parent::handleCustomerSubscriptionDeleted($payload);
        
        // Mark as canceled locally as well
        $this->syncSubscriptionStatus($payload['data']['object']);
        
        return $response;
    }
    
    /**
     * Handle payment failed event.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleInvoicePaymentFailed(array $payload)
    {
        Log::info('Stripe webhook: payment failed', ['payload' => $payload]);
        
        // Let Cashier handle it first
        $response = parent::handleInvoicePaymentFailed($payload);
        
        // Additional logging or notification logic here
        $invoice = $payload['data']['object'];
        $user = User::where('stripe_id', $invoice['customer'])->first();
        
        if ($user) {
            Log::warning('Payment failed for user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'amount' => $invoice['amount_due'] / 100,
                'attempt_count' => $invoice['attempt_count']
            ]);
        }
        
        return $response;
    }
    
    /**
     * Sync subscription status from Stripe webhook data.
     *
     * @param  array  $stripeSubscription
     * @return void
     */
    protected function syncSubscriptionStatus(array $stripeSubscription)
    {
        try {
            // Find the local subscription
            $subscription = Subscription::where('stripe_id', $stripeSubscription['id'])->first();
            
            if (!$subscription) {
                // If subscription doesn't exist locally but should, create it
                $user = User::where('stripe_id', $stripeSubscription['customer'])->first();
                
                if ($user) {
                    Log::warning('Subscription exists in Stripe but not locally, creating...', [
                        'user_id' => $user->id,
                        'stripe_subscription_id' => $stripeSubscription['id']
                    ]);
                    
                    // Create the subscription locally
                    $subscription = $user->subscriptions()->create([
                        'name' => 'default',
                        'stripe_id' => $stripeSubscription['id'],
                        'stripe_status' => $stripeSubscription['status'],
                        'stripe_price' => $stripeSubscription['items']['data'][0]['price']['id'] ?? null,
                        'quantity' => $stripeSubscription['items']['data'][0]['quantity'] ?? 1,
                        'trial_ends_at' => $stripeSubscription['trial_end'] 
                            ? \Carbon\Carbon::createFromTimestamp($stripeSubscription['trial_end']) 
                            : null,
                        'ends_at' => $stripeSubscription['canceled_at'] 
                            ? \Carbon\Carbon::createFromTimestamp($stripeSubscription['canceled_at']) 
                            : null,
                    ]);
                }
            } else {
                // Update existing subscription
                $subscription->stripe_status = $stripeSubscription['status'];
                
                if ($stripeSubscription['canceled_at'] && !$subscription->ends_at) {
                    $subscription->ends_at = \Carbon\Carbon::createFromTimestamp($stripeSubscription['canceled_at']);
                }
                
                if ($stripeSubscription['trial_end']) {
                    $subscription->trial_ends_at = \Carbon\Carbon::createFromTimestamp($stripeSubscription['trial_end']);
                }
                
                $subscription->save();
                
                Log::info('Subscription status synced', [
                    'subscription_id' => $subscription->id,
                    'stripe_id' => $stripeSubscription['id'],
                    'status' => $stripeSubscription['status']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error syncing subscription status from webhook', [
                'error' => $e->getMessage(),
                'stripe_subscription_id' => $stripeSubscription['id'] ?? 'unknown'
            ]);
        }
    }
    
    /**
     * Handle calls to missing methods on the controller.
     * This ensures we log all webhook events, even ones we don't explicitly handle.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // Log the webhook event
        if (str_starts_with($method, 'handle')) {
            $event = $this->request->input('type');
            Log::info("Stripe webhook received: {$event}", [
                'method' => $method,
                'data' => $this->request->input('data.object')
            ]);
        }
        
        // Call parent to handle the event
        return parent::__call($method, $parameters);
    }
}
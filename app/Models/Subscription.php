<?php

namespace App\Models;

use Laravel\Cashier\Subscription as CashierSubscription;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends CashierSubscription
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'quantity' => 'integer',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a human-readable name for the subscription.
     */
    public function getNameAttribute(): string
    {
        if ($this->name) {
            return ucfirst($this->name);
        }
        
        return 'Default';
    }

    /**
     * Get the plan name based on the Stripe price ID.
     */
    public function getPlanNameAttribute(): string
    {
        $prices = [
            config('cashier.prices.basic') => 'Basic',
            config('cashier.prices.pro') => 'Pro',
            config('cashier.prices.enterprise') => 'Enterprise',
        ];

        return $prices[$this->stripe_price] ?? 'Unknown Plan';
    }

    /**
     * Check if the subscription exists in Stripe.
     */
    public function existsInStripe(): bool
    {
        if (!$this->stripe_id) {
            return false;
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $stripeSubscription = $stripe->subscriptions->retrieve($this->stripe_id);
            return $stripeSubscription && $stripeSubscription->id === $this->stripe_id;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return false;
        }
    }

    /**
     * Sync the subscription with Stripe.
     */
    public function syncWithStripe(): bool
    {
        if (!$this->stripe_id) {
            return false;
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $stripeSubscription = $stripe->subscriptions->retrieve($this->stripe_id);
            
            // Update local subscription with Stripe data
            $this->stripe_status = $stripeSubscription->status;
            
            if ($stripeSubscription->canceled_at) {
                $this->ends_at = \Carbon\Carbon::createFromTimestamp($stripeSubscription->canceled_at);
            }
            
            if ($stripeSubscription->trial_end) {
                $this->trial_ends_at = \Carbon\Carbon::createFromTimestamp($stripeSubscription->trial_end);
            }
            
            $this->save();
            
            return true;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Subscription doesn't exist in Stripe
            return false;
        }
    }

    /**
     * Mark the subscription as orphaned (doesn't exist in Stripe).
     */
    public function markAsOrphaned(): void
    {
        $this->stripe_status = 'orphaned';
        $this->ends_at = now();
        $this->save();
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('ends_at')
                  ->orWhere('ends_at', '>', now());
        })->where('stripe_status', '!=', 'orphaned');
    }

    /**
     * Scope a query to only include orphaned subscriptions.
     */
    public function scopeOrphaned($query)
    {
        return $query->where('stripe_status', 'orphaned');
    }
}
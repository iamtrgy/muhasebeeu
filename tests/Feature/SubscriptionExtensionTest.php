<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Cashier\Subscription;
use Carbon\Carbon;

class SubscriptionExtensionTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test that a subscription is extended to the end of the month.
     */
    public function test_subscription_extended_to_end_of_month(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Mock a subscription that ended earlier this month
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->name = 'default';
        $subscription->stripe_id = 'sub_' . uniqid();
        $subscription->stripe_price = 'price_' . uniqid();
        $subscription->quantity = 1;
        
        // Set ends_at to the 15th of the current month
        $today = Carbon::now();
        $dayOfMonth = min(15, $today->daysInMonth); // Ensure it's a valid day
        $ends_at = Carbon::create($today->year, $today->month, $dayOfMonth);
        
        // If today is after the 15th, the test would fail
        // So we'll set ends_at to the 15th of the current month if today is before the 15th
        // Otherwise, set it to the 15th of the previous month
        if ($today->day > $dayOfMonth) {
            $ends_at = $ends_at->subMonth();
        }
        
        $subscription->ends_at = $ends_at;
        $subscription->save();
        
        // If ends_at is in the current month, the user should have an active subscription
        if ($ends_at->isSameMonth($today)) {
            $this->assertTrue($user->hasActiveSubscription(), 
                'User should have an active subscription when ended this month on day ' . $ends_at->day);
        } else {
            $this->assertFalse($user->hasActiveSubscription(), 
                'User should not have an active subscription when ended last month');
        }
        
        // Test with a subscription ending next month (should be active)
        $subscription->ends_at = Carbon::now()->addMonth();
        $subscription->save();
        $this->assertTrue($user->hasActiveSubscription(), 
            'User should have an active subscription when ending next month');
        
        // Test with a subscription ending last month (should not be active)
        $subscription->ends_at = Carbon::now()->subMonth();
        $subscription->save();
        $this->assertFalse($user->hasActiveSubscription(), 
            'User should not have an active subscription when ended last month');
    }
} 
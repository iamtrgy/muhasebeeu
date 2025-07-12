<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\User;
use Stripe\StripeClient;

class SyncSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:sync {--fix : Fix orphaned subscriptions by deleting them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync local subscriptions with Stripe and optionally fix orphaned records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting subscription sync...');
        
        $stripe = new StripeClient(config('cashier.secret'));
        $subscriptions = Subscription::all();
        $orphanedCount = 0;
        $syncedCount = 0;
        $errorCount = 0;
        
        $this->info("Found {$subscriptions->count()} subscriptions to check.");
        
        foreach ($subscriptions as $subscription) {
            $this->line("Checking subscription {$subscription->id} (Stripe ID: {$subscription->stripe_id})");
            
            try {
                if (!$subscription->stripe_id) {
                    $this->warn("  - No Stripe ID found");
                    if ($this->option('fix')) {
                        $subscription->delete();
                        $this->info("  - Deleted subscription without Stripe ID");
                        $orphanedCount++;
                    } else {
                        $this->warn("  - Would delete (run with --fix to delete)");
                    }
                    continue;
                }
                
                // Try to retrieve from Stripe
                try {
                    $stripeSubscription = $stripe->subscriptions->retrieve($subscription->stripe_id);
                    
                    // Sync status
                    $oldStatus = $subscription->stripe_status;
                    $subscription->stripe_status = $stripeSubscription->status;
                    
                    // If canceled in Stripe but not locally
                    if ($stripeSubscription->status === 'canceled' && !$subscription->canceled()) {
                        $subscription->ends_at = now();
                        $this->info("  - Marked as canceled (was active locally)");
                    }
                    
                    // Update trial end date if applicable
                    if ($stripeSubscription->trial_end) {
                        $subscription->trial_ends_at = \Carbon\Carbon::createFromTimestamp($stripeSubscription->trial_end);
                    }
                    
                    if ($oldStatus !== $stripeSubscription->status) {
                        $subscription->save();
                        $this->info("  - Updated status from {$oldStatus} to {$stripeSubscription->status}");
                        $syncedCount++;
                    } else {
                        $this->info("  - Status already in sync: {$stripeSubscription->status}");
                    }
                    
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    // Subscription doesn't exist in Stripe
                    $this->error("  - Not found in Stripe!");
                    if ($this->option('fix')) {
                        $subscription->delete();
                        $this->info("  - Deleted orphaned subscription");
                        $orphanedCount++;
                    } else {
                        $this->warn("  - Would delete (run with --fix to delete)");
                    }
                }
                
            } catch (\Exception $e) {
                $this->error("  - Error: " . $e->getMessage());
                $errorCount++;
            }
        }
        
        $this->newLine();
        $this->info('Sync complete!');
        $this->info("Synced: {$syncedCount}");
        $this->info("Orphaned: {$orphanedCount}" . ($this->option('fix') ? ' (deleted)' : ' (found)'));
        $this->info("Errors: {$errorCount}");
        
        if (!$this->option('fix') && $orphanedCount > 0) {
            $this->newLine();
            $this->warn('Run with --fix option to delete orphaned subscriptions');
        }
        
        // Also check for users with stripe_id but no subscription
        $this->newLine();
        $this->info('Checking for users with Stripe customer ID but no local subscription...');
        
        $usersWithStripeId = User::whereNotNull('stripe_id')
            ->doesntHave('subscriptions')
            ->get();
            
        if ($usersWithStripeId->count() > 0) {
            $this->warn("Found {$usersWithStripeId->count()} users with Stripe ID but no local subscription:");
            foreach ($usersWithStripeId as $user) {
                $this->line("  - User {$user->id}: {$user->email} (Stripe ID: {$user->stripe_id})");
                
                // Check if they have a subscription in Stripe
                try {
                    $stripeSubscriptions = $stripe->subscriptions->all([
                        'customer' => $user->stripe_id,
                        'limit' => 10
                    ]);
                    
                    if (count($stripeSubscriptions->data) > 0) {
                        $this->warn("    Found {$stripeSubscriptions->count()} subscription(s) in Stripe!");
                        foreach ($stripeSubscriptions->data as $stripeSub) {
                            $this->line("    - {$stripeSub->id}: {$stripeSub->status}");
                        }
                    }
                } catch (\Exception $e) {
                    $this->error("    Error checking Stripe: " . $e->getMessage());
                }
            }
        } else {
            $this->info('No users found with Stripe ID but missing local subscription.');
        }
        
        return Command::SUCCESS;
    }
}
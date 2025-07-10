<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserSettingsController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $subscriptionData = null;
        
        // Safely get subscription data (same logic as ProfileController)
        if ($user->subscribed('default')) {
            $subscription = $user->subscription('default');
            if ($subscription) {
                try {
                    $stripeSubscription = $subscription->asStripeSubscription();
                    $subscriptionData = [
                        'plan' => $subscription->stripe_price,
                        'status' => $subscription->stripe_status,
                        'active' => $subscription->active(),
                        'on_trial' => $subscription->onTrial(),
                        'on_grace_period' => $subscription->onGracePeriod(),
                        'trial_ends_at' => $subscription->trial_ends_at,
                        'ends_at' => $subscription->ends_at,
                        'next_billing_date' => $stripeSubscription->current_period_end ?? null,
                    ];
                } catch (\Exception $e) {
                    // If Stripe subscription is invalid, mark as error
                    $subscriptionData = [
                        'error' => true,
                        'message' => 'Subscription data unavailable'
                    ];
                }
            }
        }
        
        return view('user.settings.index', compact('user', 'subscriptionData'));
    }
    
    public function updateNotifications(Request $request): RedirectResponse
    {
        $request->validate([
            'email_notifications' => 'sometimes|boolean',
            'invoice_notifications' => 'sometimes|boolean',
            'payment_notifications' => 'sometimes|boolean',
            'file_notifications' => 'sometimes|boolean',
            'tax_reminders' => 'sometimes|boolean',
        ]);
        
        $user = auth()->user();
        
        // For now, we'll just store these in the user's metadata or preferences
        // You could extend the users table or create a user_preferences table
        $preferences = [
            'email_notifications' => $request->boolean('email_notifications'),
            'invoice_notifications' => $request->boolean('invoice_notifications'),
            'payment_notifications' => $request->boolean('payment_notifications'),
            'file_notifications' => $request->boolean('file_notifications'),
            'tax_reminders' => $request->boolean('tax_reminders'),
        ];
        
        // Store in user metadata (if you have a metadata column) or session for now
        session(['user_notification_preferences' => $preferences]);
        
        return redirect()->route('user.settings', ['tab' => 'notifications'])
            ->with('success', __('Notification preferences updated successfully.'));
    }
    
    public function updateAppearance(Request $request): RedirectResponse
    {
        $request->validate([
            'theme' => 'required|in:system,light,dark',
            'language' => 'required|in:en,tr,et',
            'timezone' => 'required|string',
            'date_format' => 'required|in:Y-m-d,d/m/Y,m/d/Y',
        ]);
        
        $user = auth()->user();
        
        // For now, we'll store these in session or user metadata
        $preferences = [
            'theme' => $request->theme,
            'language' => $request->language,
            'timezone' => $request->timezone,
            'date_format' => $request->date_format,
        ];
        
        // Store in user metadata (if you have a metadata column) or session for now
        session(['user_appearance_preferences' => $preferences]);
        
        return redirect()->route('user.settings', ['tab' => 'appearance'])
            ->with('success', __('Appearance preferences updated successfully.'));
    }
}
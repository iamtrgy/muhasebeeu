<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $subscriptionData = null;
        
        // Safely get subscription data
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
        
        return view('user.profile.edit', [
            'user' => $user,
            'subscriptionData' => $subscriptionData,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('user.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

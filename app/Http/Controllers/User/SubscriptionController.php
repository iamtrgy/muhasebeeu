<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function complete(Request $request)
    {
        $user = $request->user();
        $plan = $request->session()->get('selected_plan');

        if (!$plan) {
            return redirect()->route('user.subscription.plans')
                ->with('error', 'No plan selected. Please choose a plan first.');
        }

        // Clear the selected plan from session
        $request->session()->forget('selected_plan');

        return view('user.subscriptions.complete', [
            'plan' => $plan
        ]);
    }
} 
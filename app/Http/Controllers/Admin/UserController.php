<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Folder;
use App\Models\File;
use App\Models\ActivityLog;
use App\Services\UserDataService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    protected $userDataService;
    protected $subscriptionService;
    
    /**
     * Create a new controller instance.
     *
     * @param UserDataService $userDataService
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(
        UserDataService $userDataService,
        SubscriptionService $subscriptionService
    ) {
        $this->userDataService = $userDataService;
        $this->subscriptionService = $subscriptionService;
    }
    public function index()
    {
        // Exclude admin users from the list
        $users = User::where('is_admin', false)->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        // Get user with related data using service
        $user = $this->userDataService->getUserWithDetails($user);
        
        // Get storage usage
        $storageUsage = $this->userDataService->getStorageUsage($user);
        
        // Get files count
        $filesCount = $this->userDataService->getFilesCount($user);
        
        // Get recent files
        $recentFiles = $this->userDataService->getRecentFiles($user, 20);
        
        // Get activity logs
        $activityLogs = $this->userDataService->getActivityLogs($user, 50);
        
        // Get folder files if a folder is selected
        $folderFiles = collect();
        $mainFolders = null;
        $currentFolder = null;
        $subFolders = collect();
        
        // If folder parameter is provided, get files for that folder
        if (request()->has('folder')) {
            $folder = request()->get('folder');
            $folderData = $this->userDataService->getFolderWithFiles($folder);
            $folderFiles = $folderData['files'];
            $currentFolder = $folderData['folder'];
            
            // Get subfolders if available
            if ($currentFolder) {
                $subFolders = Folder::where('parent_id', $currentFolder->id)->get();
            }
        } else {
            // Otherwise, get all main folders
            $mainFolders = $this->userDataService->getMainFolders($user);
        }
        
        return view('admin.users.details', compact(
            'user', 'storageUsage', 'activityLogs', 'filesCount', 'recentFiles',
            'currentFolder', 'subFolders', 'folderFiles', 'mainFolders'
        ));
    }
    
    /**
     * Manually verify a user's email
     */
    public function verifyUser(User $user)
    {
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
            
            // Log this activity using the User model method
            $user->logEmailVerification(true);
            
            // Clear cache for this user
            $this->userDataService->clearUserCaches($user);
            
            return redirect()->back()->with('success', 'User email has been manually verified.');
        }
        
        return redirect()->back()->with('info', 'User is already verified.');
    }
    
    /**
     * Show the subscription management form for a user
     */
    public function manageSubscription(User $user)
    {
        // Get available plans
        $plans = [
            'basic' => [
                'name' => 'Basic',
                'price_id' => env('STRIPE_BASIC_PRICE_ID'),
                'features' => ['5GB Storage', 'Basic Support', 'Up to 10 Folders']
            ],
            'pro' => [
                'name' => 'Pro',
                'price_id' => env('STRIPE_PRO_PRICE_ID'),
                'features' => ['25GB Storage', 'Priority Support', 'Unlimited Folders', 'Advanced Analytics']
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price_id' => env('STRIPE_ENTERPRISE_PRICE_ID'),
                'features' => ['100GB Storage', 'Premium Support', 'Unlimited Everything', '24/7 Customer Service']
            ]
        ];
        
        $currentPlan = null;
        $currentPlanId = null;
        
        if ($user->subscription('default')) {
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
        
        return view('admin.users.subscription', [
            'user' => $user,
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'currentPlanId' => $currentPlanId,
            'onGracePeriod' => $user->subscription('default') ? $user->subscription('default')->onGracePeriod() : false,
            'canceled' => $user->subscription('default') ? $user->subscription('default')->canceled() : false
        ]);
    }
    
    // Method moved to UserDataService

    /**
     * Update the user's subscription
     */
    public function updateSubscription(Request $request, User $user)
    {
        $request->validate([
            'action' => 'required|in:subscribe,cancel,resume,delete_incomplete',
            'plan' => 'required_if:action,subscribe|in:basic,pro,enterprise',
            'trial_days' => 'nullable|integer|min:1|max:365'
        ]);
        
        $action = $request->input('action');
        $result = [];
        
        // Handle different subscription actions using the subscription service
        switch ($action) {
            case 'subscribe':
                $plan = $request->plan;
                $trialDays = (int) $request->input('trial_days', 30);
                
                // If user already has a subscription on a different plan, swap it
                if ($user->subscribed('default')) {
                    $result = $this->subscriptionService->swapSubscriptionPlan($user, $plan);
                } else {
                    // Create a new subscription
                    $result = $this->subscriptionService->createSubscription($user, $plan, $trialDays);
                }
                break;
                
            case 'cancel':
                $result = $this->subscriptionService->cancelSubscription($user);
                break;
                
            case 'resume':
                $result = $this->subscriptionService->resumeSubscription($user);
                break;
                
            case 'delete_incomplete':
                $result = $this->subscriptionService->deleteIncompleteSubscription($user);
                break;
        }
        
        // Handle the result and redirect
        $type = $result['success'] ? 'success' : 'error';
        return redirect()->back()->with($type, $result['message']);
    }

    /**
     * Debug a user's subscription status
     */
    public function debugSubscription(User $user)
    {
        $subscriptionInfo = [];
        
        // Get subscription from database
        $subscription = $user->subscription('default');
        
        if ($subscription) {
            $subscriptionInfo['id'] = $subscription->id;
            $subscriptionInfo['stripe_id'] = $subscription->stripe_id;
            $subscriptionInfo['stripe_price'] = $subscription->stripe_price;
            $subscriptionInfo['quantity'] = $subscription->quantity;
            $subscriptionInfo['created_at'] = $subscription->created_at->format('Y-m-d H:i:s');
            $subscriptionInfo['ends_at'] = $subscription->ends_at ? $subscription->ends_at->format('Y-m-d H:i:s') : null;
            
            // Check various subscription statuses
            $subscriptionInfo['cashier_check'] = $user->subscribed('default');
            $subscriptionInfo['manual_check'] = $user->hasActiveSubscription('default');
            $subscriptionInfo['on_trial'] = $subscription->onTrial();
            $subscriptionInfo['canceled'] = $subscription->canceled();
            $subscriptionInfo['on_grace_period'] = $subscription->onGracePeriod();
            $subscriptionInfo['ended'] = $subscription->ended();
            
            // Try to get stripe subscription
            try {
                if ($user->stripe_id) {
                    $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                    $stripeSubscription = $stripe->subscriptions->retrieve($subscription->stripe_id);
                    $subscriptionInfo['stripe_status'] = $stripeSubscription->status;
                    $subscriptionInfo['stripe_current_period_end'] = date('Y-m-d H:i:s', $stripeSubscription->current_period_end);
                }
            } catch (\Exception $e) {
                $subscriptionInfo['stripe_error'] = $e->getMessage();
            }
        } else {
            $subscriptionInfo['error'] = 'No subscription found in database';
        }
        
        // Also check for any payment methods
        try {
            if ($user->stripe_id) {
                $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                $paymentMethods = $stripe->paymentMethods->all([
                    'customer' => $user->stripe_id,
                    'type' => 'card',
                ]);
                $subscriptionInfo['payment_methods_count'] = count($paymentMethods->data);
            }
        } catch (\Exception $e) {
            $subscriptionInfo['payment_methods_error'] = $e->getMessage();
        }
        
        // Get any relevant log entries
        $logs = \App\Models\ActivityLog::where('user_id', $user->id)
            ->where('action', 'LIKE', 'subscription_%')
            ->latest()
            ->take(5)
            ->get();
            
        return view('admin.users.subscription-debug', [
            'user' => $user,
            'subscriptionInfo' => $subscriptionInfo,
            'logs' => $logs
        ]);
    }
} 
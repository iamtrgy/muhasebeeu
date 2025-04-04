<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Subscription;
use App\Models\Plan;
use App\Services\UserDataService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
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
    
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin ?? false,
        ]);

        return redirect()->route('admin.users.show', $user)->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $activityLogs = \App\Models\ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get only main folders (without parent)
        $mainFolders = $user->folders()->whereNull('parent_id')->get();
        
        // Get current folder ID from query parameter if viewing a specific folder
        $currentFolderId = request()->query('folder_id');
        $currentFolder = null;
        $subFolders = collect();
        $folderFiles = collect();
        
        // If a specific folder is being viewed, get its subfolders and files
        if ($currentFolderId) {
            $currentFolder = $user->folders()->find($currentFolderId);
            
            if ($currentFolder) {
                $subFolders = $user->folders()->where('parent_id', $currentFolderId)->get();
                $folderFiles = $currentFolder->files;
            }
        } else {
            // If no specific folder is viewed, show root level files (not in any folder)
            $folderFiles = $user->files()->whereNull('folder_id')->get();
        }
            
        return view('admin.users.details', compact(
            'user', 
            'activityLogs', 
            'mainFolders', 
            'currentFolder',
            'subFolders',
            'folderFiles'
        ));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Log the request data for debugging
        \Log::info('User update request data:', [
            'user_id' => $user->id,
            'all_data' => $request->all(),
            'has_is_admin' => $request->has('is_admin'),
            'has_is_accountant' => $request->has('is_accountant'),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_admin' => 'boolean',
            'is_accountant' => 'boolean',
        ]);

        $userBefore = [
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'is_accountant' => $user->is_accountant,
        ];

        // More explicit approach to handle checkboxes
        $is_admin = $request->has('is_admin') || $request->input('is_admin') === 'on' || $request->input('is_admin') === '1';
        $is_accountant = $request->has('is_accountant') || $request->input('is_accountant') === 'on' || $request->input('is_accountant') === '1';

        \Log::info('Processed checkbox values:', [
            'is_admin' => $is_admin,
            'is_accountant' => $is_accountant,
        ]);

        // Direct update to the user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $is_admin; 
        $user->is_accountant = $is_accountant;
        $user->save();

        // Log after update
        \Log::info('User after update:', [
            'user_id' => $user->id,
            'before' => $userBefore,
            'after' => [
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'is_accountant' => $user->is_accountant,
            ]
        ]);

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Verify a user account.
     */
    public function verifyUser(User $user)
    {
        $user->email_verified_at = now();
        $user->save();
        
        return redirect()->back()->with('success', 'User verified successfully.');
    }

    /**
     * Show the subscription management page for a user.
     */
    public function manageSubscription(User $user)
    {
        // Define available plans with their features
        $plans = [
            'price_basic' => [
                'name' => 'Basic Plan',
                'features' => [
                    '500 MB Storage', 
                    'Up to 10 files',
                    'Basic support'
                ],
                'price' => '$9.99/month',
                'price_id' => env('STRIPE_BASIC_PRICE_ID')
            ],
            'price_pro' => [
                'name' => 'Pro Plan',
                'features' => [
                    '5 GB Storage',
                    'Unlimited files',
                    'Priority support',
                    'Advanced features'
                ],
                'price' => '$19.99/month',
                'price_id' => env('STRIPE_PRO_PRICE_ID')
            ],
            'price_enterprise' => [
                'name' => 'Enterprise Plan',
                'features' => [
                    '50 GB Storage',
                    'Unlimited files',
                    'Premium support',
                    'Advanced features',
                    'Custom integrations'
                ],
                'price' => '$49.99/month',
                'price_id' => env('STRIPE_ENTERPRISE_PRICE_ID')
            ]
        ];
        
        // Determine the current plan
        $currentPlan = null;
        if ($user->subscription('default')) {
            $subscription = $user->subscription('default');
            $stripePrice = $subscription->stripe_price;
            
            // Map the Stripe price ID to our plan keys
            foreach ($plans as $planId => $plan) {
                if ($plan['price_id'] === $stripePrice) {
                    $currentPlan = $planId;
                    break;
                }
            }
            
            // If we couldn't map it, just use the Stripe price ID
            if ($currentPlan === null) {
                $currentPlan = $stripePrice;
            }
        }
        
        return view('admin.users.subscription', compact('user', 'plans', 'currentPlan'));
    }

    /**
     * Update a user's subscription.
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
     * Debug a user's subscription.
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
        }
        
        return view('admin.users.subscription_debug', compact('user', 'subscriptionInfo'));
    }

    /**
     * Show the form for assigning users to accountant
     */
    public function assignUsers(User $accountant)
    {
        // Check if user is an accountant
        if (!$accountant->is_accountant) {
            return redirect()->route('admin.users.show', $accountant)
                ->with('error', 'This user is not an accountant.');
        }

        // Get currently assigned users
        $assignedUsers = $accountant->assignedUsers()->pluck('user_id')->toArray();
        
        // Get all users except admins and the current accountant
        $availableUsers = User::where('id', '!=', $accountant->id)
            ->where('is_admin', false)
            ->orderBy('name')
            ->get();

        return view('admin.users.assign', compact('accountant', 'assignedUsers', 'availableUsers'));
    }

    /**
     * Update the assigned users for an accountant
     */
    public function updateAssignedUsers(Request $request, User $accountant)
    {
        // Check if user is an accountant
        if (!$accountant->is_accountant) {
            return redirect()->route('admin.users.show', $accountant)
                ->with('error', 'This user is not an accountant.');
        }

        // Validate request
        $request->validate([
            'assigned_users' => 'array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        // Sync assigned users
        $accountant->assignedUsers()->sync($request->assigned_users ?? []);

        return redirect()->route('admin.users.show', $accountant)
            ->with('success', 'Assigned users updated successfully.');
    }

    /**
     * Show the form for assigning companies to accountant
     */
    public function assignCompanies(User $accountant)
    {
        // Check if user is an accountant
        if (!$accountant->is_accountant) {
            return redirect()->route('admin.users.show', $accountant)
                ->with('error', 'This user is not an accountant.');
        }

        // Get currently assigned companies
        $assignedCompanies = $accountant->assignedCompanies()->pluck('company_id')->toArray();
        
        // Get all companies
        $availableCompanies = Company::with('user')->orderBy('name')->get();

        return view('admin.users.assign_companies', compact('accountant', 'assignedCompanies', 'availableCompanies'));
    }

    /**
     * Update the assigned companies for an accountant
     */
    public function updateAssignedCompanies(Request $request, User $accountant)
    {
        // Check if user is an accountant
        if (!$accountant->is_accountant) {
            return redirect()->route('admin.users.show', $accountant)
                ->with('error', 'This user is not an accountant.');
        }

        // Validate request
        $request->validate([
            'assigned_companies' => 'array',
            'assigned_companies.*' => 'exists:companies,id',
        ]);

        // Sync assigned companies
        $accountant->assignedCompanies()->sync($request->assigned_companies ?? []);

        return redirect()->route('admin.users.show', $accountant)
            ->with('success', 'Assigned companies updated successfully.');
    }
} 
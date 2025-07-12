<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Basic counts
        $totalUsers = User::count();
        $totalCompanies = Company::count();
        $totalFiles = File::count();
        
        // Subscription metrics
        $activeSubscriptions = Subscription::where('stripe_status', 'active')->count();
        $trialSubscriptions = Subscription::whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now())
            ->count();
        $canceledSubscriptions = Subscription::where('stripe_status', 'canceled')->count();
        
        // Revenue metrics (this month)
        $thisMonthRevenue = Subscription::where('stripe_status', 'active')
            ->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])->count() * 29.99; // Assuming $29.99 per subscription
            
        // Users with subscriptions
        $subscribedUsers = User::whereHas('subscriptions', function($query) {
            $query->where('stripe_status', 'active');
        })->count();
        
        // Recent activity
        $recentUsers = User::latest()->take(5)->get();
        $recentCompanies = Company::with('user')->latest()->take(5)->get();
        $recentFiles = File::with([
            'folder', 
            'uploader',
            'folder.creator.companies'
        ])->latest()->take(5)->get();
        
        // Subscriptions expiring soon (next 7 days)
        $subscriptionsExpiringSoon = User::whereHas('subscriptions', function($query) {
            $query->where('stripe_status', 'active')
                  ->whereNotNull('trial_ends_at')
                  ->whereBetween('trial_ends_at', [now(), now()->addDays(7)]);
        })->with(['subscriptions' => function($query) {
            $query->where('stripe_status', 'active')
                  ->whereNotNull('trial_ends_at')
                  ->whereBetween('trial_ends_at', [now(), now()->addDays(7)]);
        }])->take(5)->get();
        
        // Recent subscription activity
        $recentSubscriptions = Subscription::with('user')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            // Basic metrics
            'total_users' => $totalUsers,
            'total_companies' => $totalCompanies, 
            'total_files' => $totalFiles,
            
            // Subscription metrics
            'active_subscriptions' => $activeSubscriptions,
            'trial_subscriptions' => $trialSubscriptions,
            'canceled_subscriptions' => $canceledSubscriptions,
            'subscribed_users' => $subscribedUsers,
            'subscription_rate' => $totalUsers > 0 ? round(($subscribedUsers / $totalUsers) * 100, 1) : 0,
            
            // Revenue metrics
            'monthly_revenue' => $thisMonthRevenue,
            'avg_revenue_per_user' => $subscribedUsers > 0 ? round($thisMonthRevenue / $subscribedUsers, 2) : 0,
            
            // Recent activity
            'recent_users' => $recentUsers,
            'recent_companies' => $recentCompanies,
            'recent_files' => $recentFiles,
            'recent_subscriptions' => $recentSubscriptions,
            'subscriptions_expiring_soon' => $subscriptionsExpiringSoon,
        ];

        return view('admin.dashboard', compact('stats'));
    }
} 
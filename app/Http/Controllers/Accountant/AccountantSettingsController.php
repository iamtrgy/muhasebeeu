<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountantSettingsController extends Controller
{
    /**
     * Display the accountant settings page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's assigned companies count for stats
        $assignedCompaniesCount = $user->assignedCompanies()->count();
        
        // Get user's review statistics
        $reviewStats = [
            'total_reviews' => 0, // TODO: Implement when reviews tracking is added
            'pending_reviews' => \App\Models\TaxCalendarTask::where('status', 'under_review')
                ->whereHas('company', function ($query) use ($user) {
                    $query->whereIn('id', $user->assignedCompanies()->pluck('companies.id'));
                })
                ->count(),
            'completed_reviews' => 0, // TODO: Implement when reviews tracking is added
        ];
        
        return view('accountant.settings.index', compact('user', 'assignedCompaniesCount', 'reviewStats'));
    }
    
    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'review_notifications' => 'boolean',
            'daily_summary' => 'boolean',
            'task_reminders' => 'boolean',
        ]);
        
        $user = Auth::user();
        
        // Update user preferences (you might want to create a preferences table)
        // For now, we'll store in user meta or settings field
        $user->update([
            'notification_preferences' => $validated
        ]);
        
        return redirect()->back()->with('success', 'Notification preferences updated successfully.');
    }
    
    /**
     * Update appearance preferences.
     */
    public function updateAppearance(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,system',
            'language' => 'required|in:en,tr,et',
            'timezone' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        $user->update([
            'appearance_preferences' => $validated
        ]);
        
        return redirect()->back()->with('success', 'Appearance preferences updated successfully.');
    }
}
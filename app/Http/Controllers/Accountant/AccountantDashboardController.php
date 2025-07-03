<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\File;
use App\Models\TaxCalendarTask;
use Illuminate\Support\Facades\DB;

class AccountantDashboardController extends Controller
{

    /**
     * Show the accountant dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $accountant = Auth::user();
        
        // Count of assigned users
        $usersCount = $accountant->assignedUsers()->count();
        
        // Count of assigned companies
        $companiesCount = $accountant->assignedCompanies()->count();
        
        // Count of tasks pending review
        $pendingReviewCount = TaxCalendarTask::where('status', 'under_review')
            ->whereHas('company', function ($query) use ($accountant) {
                $query->whereIn('id', $accountant->assignedCompanies()->pluck('companies.id'));
            })
            ->count();
        
        // Recent users and companies
        $recentUsers = $accountant->assignedUsers()->latest()->take(5)->get();
        $recentCompanies = $accountant->assignedCompanies()->latest()->take(5)->get();
        
        // Get recent files from assigned users and companies - optimized query
        $userIds = $accountant->assignedUsers()->pluck('users.id')->toArray();
        
        // More efficient query using joins instead of whereHas
        $recentFiles = File::select('files.*')
            ->join('folders', 'files.folder_id', '=', 'folders.id')
            ->whereIn('folders.created_by', $userIds)
            ->with([
                'folder:id,name,created_by', 
                'folder.creator:id,name,email',
                'folder.creator.companies:id,name,user_id',
                'uploader:id,name,email'
            ])
            ->latest('files.created_at')
            ->take(10)
            ->get();
        
        return view('accountant.dashboard.index', compact(
            'usersCount',
            'companiesCount',
            'pendingReviewCount',
            'recentUsers',
            'recentCompanies',
            'recentFiles'
        ));
    }
}

<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\File;
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
        
        // Recent users and companies
        $recentUsers = $accountant->assignedUsers()->latest()->take(5)->get();
        $recentCompanies = $accountant->assignedCompanies()->latest()->take(5)->get();
        
        // Get recent files from assigned users and companies
        $userIds = $accountant->assignedUsers()->pluck('users.id')->toArray();
        
        // Get files from folders created by assigned users
        $recentFiles = File::whereHas('folder', function($query) use ($userIds) {
                $query->whereIn('created_by', $userIds);
            })
            ->with(['folder.creator.companies', 'uploader'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('accountant.dashboard.index', compact(
            'usersCount',
            'companiesCount',
            'recentUsers',
            'recentCompanies',
            'recentFiles'
        ));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get counts for the dashboard
        $usersCount = User::count();
        $adminsCount = User::where('is_admin', true)->count();
        $foldersCount = Folder::count();
        $filesCount = File::count();
        
        // Get recent users
        $recentUsers = User::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('usersCount', 'adminsCount', 'foldersCount', 'filesCount', 'recentUsers'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function userDetails(User $user)
    {
        return view('admin.users.details', compact('user'));
    }
} 
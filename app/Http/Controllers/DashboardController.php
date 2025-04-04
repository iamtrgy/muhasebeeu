<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect admin users to the admin dashboard
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // Get only root folders (parent_id is null) that the user has explicit access to through the users relationship
        $folders = Folder::whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->whereNull('parent_id') // Only get root folders
        ->withCount(['files', 'children' => function($query) {
            $query->whereNull('deleted_at');
        }])
        ->latest()
        ->take(5)
        ->get();

        // Get files from folders the user has access to
        $recentFiles = File::whereHas('folder.users', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->with(['folder', 'uploader'])
        ->latest()
        ->take(10)
        ->get();

        // For admin users, get recent user registrations
        $recentUsers = auth()->user()->is_admin 
            ? User::latest()->take(5)->get() 
            : collect();

        return view('user.dashboard', compact('folders', 'recentFiles', 'recentUsers'));
    }
} 
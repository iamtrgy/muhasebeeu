<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_companies' => Company::count(),
            'total_folders' => Folder::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_companies' => Company::with('user')->latest()->take(5)->get(),
            'recent_files' => File::with([
                'folder', 
                'uploader',
                'folder.creator.companies'
            ])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
} 
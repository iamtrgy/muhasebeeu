<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use App\Models\TaxCalendarTask;
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

        // Get task statistics
        $pendingTasksCount = TaxCalendarTask::query()
            ->whereIn('company_id', auth()->user()->companies->pluck('id'))
            ->where('status', 'pending')
            ->count();

        $inProgressTasksCount = TaxCalendarTask::query()
            ->whereIn('company_id', auth()->user()->companies->pluck('id'))
            ->where('status', 'in_progress')
            ->count();

        $completedTasksCount = TaxCalendarTask::query()
            ->whereIn('company_id', auth()->user()->companies->pluck('id'))
            ->where('status', 'completed')
            ->count();

        // Get recent tasks including completed ones
        $tasks = TaxCalendarTask::query()
            ->with('taxCalendar')
            ->whereIn('company_id', auth()->user()->companies->pluck('id'))
            ->orderBy('status', 'asc') // Put completed tasks at the end
            ->orderBy('due_date')
            ->take(8) // Increased limit to show more tasks
            ->get();

        return view('user.dashboard', compact(
            'folders', 
            'recentFiles', 
            'pendingTasksCount',
            'inProgressTasksCount',
            'completedTasksCount',
            'tasks'
        ));
    }
} 
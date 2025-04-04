<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Folder;

class AccountantUserController extends Controller
{

    /**
     * Display a listing of users assigned to the accountant.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accountant = Auth::user();
        $users = $accountant->assignedUsers()->paginate(10);
        
        return view('accountant.users.index', compact('users'));
    }

    /**
     * Display the specified user's details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $accountant = Auth::user();
        $user = $accountant->assignedUsers()->findOrFail($id);
        
        // Get user's folders
        $folders = $user->folders()->whereNull('parent_id')->get();
        
        // Get user's companies
        $companies = $user->companies;
        
        return view('accountant.users.show', compact('user', 'folders', 'companies'));
    }

    /**
     * Display the user's folder contents.
     *
     * @param  int  $userId
     * @param  int  $folderId
     * @return \Illuminate\Http\Response
     */
    public function viewFolder($userId, $folderId)
    {
        $accountant = Auth::user();
        $user = $accountant->assignedUsers()->findOrFail($userId);
        
        // Check if folder belongs to the user
        $folder = Folder::where('id', $folderId)
            ->whereHas('users', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->firstOrFail();
        
        // Get folder contents
        $childFolders = $folder->children;
        $files = $folder->files;
        
        return view('accountant.users.folder', compact('user', 'folder', 'childFolders', 'files'));
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display documents main page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get active company
        $company = $user->activeCompany ?? $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('user.companies.index')
                ->with('error', 'Please create or select a company first.');
        }
        
        // Find the Documents folder for this company
        $rootFolder = Folder::where('name', $company->name)
            ->where('company_id', $company->id)
            ->where('parent_id', null)
            ->first();
            
        if (!$rootFolder) {
            return redirect()->route('user.folders.index')
                ->with('error', 'Company folder structure not found. Please contact support.');
        }
        
        $documentsFolder = Folder::where('name', 'Documents')
            ->where('parent_id', $rootFolder->id)
            ->where('company_id', $company->id)
            ->with(['children' => function($query) {
                $query->withCount('files')
                    ->with(['files' => function($q) {
                        $q->latest()->limit(5);
                    }]);
            }])
            ->first();
            
        if (!$documentsFolder) {
            // Create Documents folder if it doesn't exist (for old companies)
            $folderService = app(\App\Services\FolderStructureService::class);
            $folderService->createMainCategoryFolders($user, $rootFolder, $company);
            
            return redirect()->route('user.documents.index')
                ->with('info', 'Documents folder structure has been created.');
        }
        
        // Get selected category from request
        $selectedCategory = $request->get('category');
        $selectedFolder = null;
        $files = collect();
        
        if ($selectedCategory) {
            $selectedFolder = $documentsFolder->children->firstWhere('name', $selectedCategory);
            if ($selectedFolder) {
                $files = $selectedFolder->files()
                    ->with('uploader')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
            }
        }
        
        // Get recent documents across all categories
        $recentDocuments = File::whereHas('folder', function ($query) use ($documentsFolder) {
                $query->where('path', 'like', $documentsFolder->path . '%');
            })
            ->with(['folder', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Get document statistics
        $totalDocuments = File::whereHas('folder', function ($query) use ($documentsFolder) {
                $query->where('path', 'like', $documentsFolder->path . '%');
            })->count();
            
        $documentsByCategory = [];
        foreach ($documentsFolder->children as $category) {
            $documentsByCategory[$category->name] = $category->files_count;
        }
        
        return view('user.documents.index', [
            'company' => $company,
            'documentsFolder' => $documentsFolder,
            'categories' => $documentsFolder->children,
            'selectedCategory' => $selectedCategory,
            'selectedFolder' => $selectedFolder,
            'files' => $files,
            'recentDocuments' => $recentDocuments,
            'totalDocuments' => $totalDocuments,
            'documentsByCategory' => $documentsByCategory,
        ]);
    }
    
    /**
     * Show upload form for documents
     */
    public function create(Request $request)
    {
        $folderId = $request->get('folder_id');
        
        if (!$folderId) {
            return redirect()->route('user.documents.index')
                ->with('error', 'Please select a category to upload document.');
        }
        
        $folder = Folder::findOrFail($folderId);
        
        // Verify user has access
        if (!$folder->isAccessibleBy(auth()->user())) {
            abort(403, 'Unauthorized access to folder.');
        }
        
        return view('user.documents.upload', [
            'folder' => $folder
        ]);
    }
}
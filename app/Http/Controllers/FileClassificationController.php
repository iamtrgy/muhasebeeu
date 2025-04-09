<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FileClassificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware should be applied in the routes file, not here
        // $this->middleware(['auth']);
    }
    
    /**
     * Display a listing of files pending classification.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get files that have suggested folders but haven't been reviewed yet
        $pendingFiles = File::whereNotNull('suggested_folder_id')
            ->where('classification_reviewed', false)
            ->where(function($query) {
                // Only show files from companies the user has access to
                $user = auth()->user();
                if (!$user->is_admin && !$user->is_accountant) {
                    $query->whereHas('folder', function($q) use ($user) {
                        $q->whereIn('company_id', $user->companies->pluck('id'));
                    });
                }
            })
            ->with(['folder', 'suggestedFolder', 'uploader'])
            ->latest()
            ->paginate(15);
            
        return view('user.files.classification', compact('pendingFiles'));
    }
    
    /**
     * Display the specified file for classification review.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        // Check if user has access to this file
        $this->authorize('view', $file);
        
        // Make sure the file has a suggested folder and hasn't been reviewed yet
        if (!$file->suggested_folder_id || $file->classification_reviewed) {
            return redirect()->route('user.files.classification')
                ->with('warning', 'This file does not need classification review.');
        }
        
        // Load folder relationships
        $file->load(['folder', 'suggestedFolder', 'uploader']);
        
        return view('user.files.classification-show', compact('file'));
    }
    
    /**
     * Process the classification decision for the specified file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function handleClassification(Request $request, File $file)
    {
        // Check if user has access to this file
        $this->authorize('update', $file);
        
        // Validate the action
        $request->validate([
            'action' => 'required|in:accept,keep,custom',
            'selected_folder_id' => 'required_if:action,custom|exists:folders,id'
        ]);
        
        // Make sure the file has a suggested folder and hasn't been reviewed yet
        if (!$file->suggested_folder_id || $file->classification_reviewed) {
            return redirect()->route('user.files.classification')
                ->with('warning', 'This file does not need classification review.');
        }
        
        $action = $request->input('action');
        $originalPath = $file->folder->path;
        
        try {
            // Mark as reviewed regardless of the action
            $file->classification_reviewed = true;
            
            switch ($action) {
                case 'accept':
                    // Move the file to the suggested folder
                    $targetFolder = Folder::findOrFail($file->suggested_folder_id);
                    $file->folder_id = $targetFolder->id;
                    $successMessage = "File has been moved to the suggested folder: {$targetFolder->path}";
                    break;
                    
                case 'custom':
                    // Move the file to a custom folder
                    $targetFolder = Folder::findOrFail($request->input('selected_folder_id'));
                    $file->folder_id = $targetFolder->id;
                    $successMessage = "File has been moved to the selected folder: {$targetFolder->path}";
                    break;
                    
                case 'keep':
                    // Keep the file in its current folder
                    $successMessage = "File remains in its original folder: {$originalPath}";
                    break;
                    
                default:
                    return redirect()->back()->with('error', 'Invalid action selected.');
            }
            
            $file->save();
            
            // Log the classification decision
            Log::info('File classification processed', [
                'file_id' => $file->id,
                'file_name' => $file->name,
                'action' => $action,
                'original_folder' => $originalPath,
                'suggested_folder' => $file->suggestedFolder->path,
                'final_folder' => $file->folder->path,
                'user_id' => auth()->id()
            ]);
            
            // Check if there are more files to classify
            $pendingCount = File::whereNotNull('suggested_folder_id')
                ->where('classification_reviewed', false)
                ->count();
                
            if ($pendingCount > 0) {
                $successMessage .= " There are {$pendingCount} more files pending classification.";
                return redirect()->route('user.files.classification')
                    ->with('success', $successMessage);
            } else {
                $successMessage .= " All files have been classified.";
                return redirect()->route('user.dashboard')
                    ->with('success', $successMessage);
            }
            
        } catch (\Exception $e) {
            Log::error('Error processing file classification', [
                'file_id' => $file->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'There was an error processing your request: ' . $e->getMessage());
        }
    }
    
    /**
     * Process bulk classification actions for multiple files.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkClassify(Request $request)
    {
        // Validate the request
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'exists:files,id',
            'action' => 'required|in:accept_all,ignore_all'
        ]);
        
        $fileIds = $request->input('file_ids');
        $action = $request->input('action');
        $successCount = 0;
        $errorCount = 0;
        
        // Get files that need classification and user has access to
        $files = File::whereIn('id', $fileIds)
            ->whereNotNull('suggested_folder_id')
            ->where('classification_reviewed', false)
            ->where(function($query) {
                $user = auth()->user();
                if (!$user->is_admin && !$user->is_accountant) {
                    $query->whereHas('folder', function($q) use ($user) {
                        $q->whereIn('company_id', $user->companies->pluck('id'));
                    });
                }
            })
            ->with(['folder', 'suggestedFolder'])
            ->get();
            
        if ($files->isEmpty()) {
            return redirect()->route('user.files.classification')
                ->with('warning', 'No valid files found for bulk classification.');
        }
        
        foreach ($files as $file) {
            try {
                // Check authorization for each file
                if (auth()->user()->cannot('update', $file)) {
                    $errorCount++;
                    continue;
                }
                
                $file->classification_reviewed = true;
                
                if ($action === 'accept_all') {
                    // Move to suggested folder
                    $file->folder_id = $file->suggested_folder_id;
                }
                // For ignore_all we just mark as reviewed but keep current folder
                
                $file->save();
                $successCount++;
                
                // Log the action
                Log::info('Bulk classification processed', [
                    'file_id' => $file->id,
                    'file_name' => $file->name,
                    'action' => $action,
                    'original_folder' => $file->folder->path,
                    'suggested_folder' => $file->suggestedFolder->path,
                    'final_folder' => $file->folder->path,
                    'user_id' => auth()->id()
                ]);
            } catch (\Exception $e) {
                Log::error('Error in bulk classification', [
                    'file_id' => $file->id,
                    'error' => $e->getMessage()
                ]);
                $errorCount++;
            }
        }
        
        $message = "{$successCount} files successfully processed.";
        if ($errorCount > 0) {
            $message .= " {$errorCount} files could not be processed due to errors.";
            if ($successCount > 0) {
                return redirect()->route('user.files.classification')
                    ->with('warning', $message);
            } else {
                return redirect()->route('user.files.classification')
                    ->with('error', $message);
            }
        }
        
        // Check if there are more files to classify
        $pendingCount = File::whereNotNull('suggested_folder_id')
            ->where('classification_reviewed', false)
            ->count();
            
        if ($pendingCount > 0) {
            $message .= " There are {$pendingCount} more files pending classification.";
            return redirect()->route('user.files.classification')
                ->with('success', $message);
        } else {
            $message .= " All files have been classified.";
            return redirect()->route('user.dashboard')
                ->with('success', $message);
        }
    }
}

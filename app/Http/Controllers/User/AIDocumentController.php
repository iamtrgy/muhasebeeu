<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Services\AIDocumentAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AIDocumentController extends Controller
{
    protected $analyzer;
    
    public function __construct(AIDocumentAnalyzer $analyzer)
    {
        $this->analyzer = $analyzer;
    }
    
    /**
     * Analyze a single document
     */
    public function analyze(Request $request, File $file)
    {
        try {
            Log::info('AI Document Analysis requested', [
                'file_id' => $file->id,
                'user_id' => auth()->id(),
                'file_uploader' => $file->uploader ? $file->uploader->id : null
            ]);
            
            // Check if user owns the file
            if (!$file->uploader || $file->uploader->id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized - you do not own this file'
                ], 403);
            }
            
            // Check if OpenAI API key is configured
            if (!config('services.openai.key')) {
                return response()->json([
                    'success' => false,
                    'error' => 'AI service is not configured. Please contact administrator.'
                ], 503);
            }
            
            // Check if forcing new analysis
            $forceNew = $request->boolean('force_new', false);
            
            // Get or create analysis
            $analysis = $this->analyzer->getOrCreateAnalysis($file, $forceNew);
            
            if ($analysis) {
                // Get user's folders for manual selection
                $folders = auth()->user()->folders()
                    ->with('parent.parent.parent')
                    ->orderBy('name')
                    ->get()
                    ->map(function ($folder) {
                        return [
                            'id' => $folder->id,
                            'name' => $folder->name,
                            'path' => $folder->full_path
                        ];
                    });
                
                return response()->json([
                    'success' => true,
                    'analysis' => $analysis,
                    'file' => [
                        'id' => $file->id,
                        'name' => $file->original_name ?? $file->name,
                        'current_folder' => $file->folder ? $file->folder->full_path : 'Unknown',
                        'current_folder_id' => $file->folder_id
                    ],
                    'folders' => $folders
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to analyze document. Please try again.'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Document analysis failed', [
                'file_id' => $file->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Analysis failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Accept AI suggestion and move file
     */
    public function acceptSuggestion(Request $request, File $file)
    {
        $request->validate([
            'folder_id' => 'required|exists:folders,id'
        ]);
        
        // Check if user owns the file
        if ($file->uploader->id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 403);
        }
        
        // Check if user owns the target folder
        $targetFolder = auth()->user()->folders()->find($request->folder_id);
        if (!$targetFolder) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid target folder'
            ], 400);
        }
        
        try {
            // Move file to suggested folder
            $file->update([
                'folder_id' => $request->folder_id,
                'ai_suggestion_accepted' => true,
                'ai_suggested_folder_id' => $request->folder_id
            ]);
            
            Log::info('AI suggestion accepted', [
                'file_id' => $file->id,
                'old_folder' => $file->getOriginal('folder_id'),
                'new_folder' => $request->folder_id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'File moved successfully',
                'new_folder' => $targetFolder->name
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to accept AI suggestion: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to move file'
            ], 500);
        }
    }
    
    
    /**
     * Batch analyze documents
     */
    public function batchAnalyze(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'exists:files,id'
        ]);
        
        $results = $this->analyzer->batchAnalyze($request->file_ids, auth()->user());
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
    
    /**
     * Get analysis history for user
     */
    public function history(Request $request)
    {
        $tab = $request->get('tab', 'analyzed');
        $filters = $request->only(['date_from', 'date_to', 'folder', 'confidence', 'status', 'search']);
        
        // Base query for user files
        $baseQuery = File::where('uploaded_by', auth()->id())->with(['folder', 'uploader']);
        
        // Get tab-specific queries
        $analyzedQuery = clone $baseQuery;
        $analyzedQuery->whereNotNull('ai_analyzed_at');
        
        $notAnalyzedQuery = clone $baseQuery;
        $notAnalyzedQuery->whereNull('ai_analyzed_at');
        
        $allFilesQuery = clone $baseQuery;
        
        // Apply filters based on current tab
        $currentQuery = match($tab) {
            'not_analyzed' => $notAnalyzedQuery,
            'all' => $allFilesQuery,
            default => $analyzedQuery,
        };
        
        // Apply filters
        if (!empty($filters['search'])) {
            $currentQuery->where('original_name', 'like', '%' . $filters['search'] . '%');
        }
        
        if (!empty($filters['date_from'])) {
            $currentQuery->where('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $currentQuery->where('created_at', '<=', $filters['date_to']);
        }
        
        if (!empty($filters['folder'])) {
            $currentQuery->where('folder_id', $filters['folder']);
        }
        
        if (!empty($filters['confidence']) && $tab === 'analyzed') {
            $currentQuery->whereNotNull('ai_analysis')->where(function ($query) use ($filters) {
                $query->whereRaw("JSON_EXTRACT(ai_analysis, '$.confidence') >= ?", [$filters['confidence']]);
            });
        }
        
        if (!empty($filters['status']) && $tab === 'analyzed') {
            if ($filters['status'] === 'accepted') {
                $currentQuery->where('ai_suggestion_accepted', true);
            } elseif ($filters['status'] === 'pending') {
                $currentQuery->where('ai_suggestion_accepted', false);
            }
        }
        
        // Get paginated results
        $files = $currentQuery->orderBy($tab === 'analyzed' ? 'ai_analyzed_at' : 'created_at', 'desc')->paginate(20);
        
        // Get tab counts
        $analyzedCount = $analyzedQuery->count();
        $notAnalyzedCount = $notAnalyzedQuery->count();
        $allFilesCount = $allFilesQuery->count();
        
        // Calculate statistics
        $totalAnalyses = $analyzedCount;
        $acceptedCount = $analyzedQuery->where('ai_suggestion_accepted', true)->count();
        
        $avgConfidence = File::where('uploaded_by', auth()->id())
            ->whereNotNull('ai_analyzed_at')
            ->whereNotNull('ai_analysis')
            ->get()
            ->avg(function ($file) {
                return $file->ai_analysis['confidence'] ?? 0;
            });
            
        $lastAnalysis = File::where('uploaded_by', auth()->id())
            ->whereNotNull('ai_analyzed_at')
            ->orderBy('ai_analyzed_at', 'desc')
            ->first()
            ?->ai_analyzed_at;
        
        // Get user folders for filter dropdown
        $userFolders = auth()->user()->folders()->orderBy('name')->get();
            
        return view('user.ai-history-enhanced', [
            'files' => $files,
            'currentTab' => $tab,
            'filters' => $filters,
            'tabCounts' => [
                'analyzed' => $analyzedCount,
                'not_analyzed' => $notAnalyzedCount,
                'all' => $allFilesCount,
            ],
            'totalAnalyses' => $totalAnalyses,
            'acceptedCount' => $acceptedCount,
            'avgConfidence' => round($avgConfidence ?? 0),
            'lastAnalysis' => $lastAnalysis,
            'userFolders' => $userFolders,
        ]);
    }
    
    /**
     * Get statistics for AI analysis dashboard
     */
    public function stats()
    {
        $analyzed = File::where('uploaded_by', auth()->id())->whereNotNull('ai_analyzed_at')->count();
        $notAnalyzed = File::where('uploaded_by', auth()->id())->whereNull('ai_analyzed_at')->count();
        $total = $analyzed + $notAnalyzed;
        
        return response()->json([
            'analyzed' => $analyzed,
            'not_analyzed' => $notAnalyzed,
            'total' => $total,
        ]);
    }
    
    /**
     * Bulk approve AI suggestions
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'exists:files,id'
        ]);
        
        $fileIds = $request->file_ids;
        $moved = 0;
        $skipped = 0;
        $errors = [];
        
        foreach ($fileIds as $fileId) {
            try {
                $file = File::where('uploaded_by', auth()->id())
                    ->where('id', $fileId)
                    ->whereNotNull('ai_analyzed_at')
                    ->first();
                    
                if (!$file) {
                    $skipped++;
                    continue;
                }
                
                // Skip if already accepted
                if ($file->ai_suggestion_accepted) {
                    $skipped++;
                    continue;
                }
                
                // Skip if no suggested folder
                if (!$file->ai_analysis || !isset($file->ai_analysis['suggested_folder_id'])) {
                    $skipped++;
                    continue;
                }
                
                $suggestedFolderId = $file->ai_analysis['suggested_folder_id'];
                
                // Skip if already in suggested folder
                if ($file->folder_id == $suggestedFolderId) {
                    // Mark as accepted even though no move needed
                    $file->update(['ai_suggestion_accepted' => true]);
                    $skipped++;
                    continue;
                }
                
                // Check if user owns the target folder
                $targetFolder = auth()->user()->folders()->find($suggestedFolderId);
                if (!$targetFolder) {
                    $errors[] = "File {$file->original_name}: Target folder not found";
                    continue;
                }
                
                // Move file and mark as accepted
                $file->update([
                    'folder_id' => $suggestedFolderId,
                    'ai_suggestion_accepted' => true,
                    'ai_suggested_folder_id' => $suggestedFolderId
                ]);
                
                $moved++;
                
                Log::info('Bulk AI suggestion accepted', [
                    'file_id' => $file->id,
                    'old_folder' => $file->getOriginal('folder_id'),
                    'new_folder' => $suggestedFolderId,
                    'user_id' => auth()->id()
                ]);
                
            } catch (\Exception $e) {
                $errors[] = "File {$fileId}: " . $e->getMessage();
                Log::error('Bulk approve error', [
                    'file_id' => $fileId,
                    'error' => $e->getMessage(),
                    'user_id' => auth()->id()
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'moved' => $moved,
            'skipped' => $skipped,
            'errors' => $errors,
            'message' => "Processed {$moved} files successfully" . ($skipped > 0 ? ", skipped {$skipped}" : "") . ($errors ? ", " . count($errors) . " errors" : "")
        ]);
    }
}
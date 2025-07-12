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
        $analyses = File::where('uploaded_by', auth()->id())
            ->whereNotNull('ai_analyzed_at')
            ->with(['folder', 'suggestedFolder'])
            ->orderBy('ai_analyzed_at', 'desc')
            ->paginate(20);
        
        // Calculate statistics
        $totalAnalyses = File::where('uploaded_by', auth()->id())
            ->whereNotNull('ai_analyzed_at')
            ->count();
            
        $acceptedCount = File::where('uploaded_by', auth()->id())
            ->whereNotNull('ai_analyzed_at')
            ->where('ai_suggestion_accepted', true)
            ->count();
            
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
            
        return view('user.ai-history', [
            'analyses' => $analyses,
            'totalAnalyses' => $totalAnalyses,
            'acceptedCount' => $acceptedCount,
            'avgConfidence' => round($avgConfidence ?? 0),
            'lastAnalysis' => $lastAnalysis
        ]);
    }
}
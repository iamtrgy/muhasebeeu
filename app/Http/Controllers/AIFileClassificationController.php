<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Services\AIDocumentClassifierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AIFileClassificationController extends Controller
{
    protected AIDocumentClassifierService $aiClassifier;

    public function __construct(AIDocumentClassifierService $aiClassifier)
    {
        $this->aiClassifier = $aiClassifier;
    }

    /**
     * Classify a file using AI and return the suggested folder ID.
     *
     * @param Request $request
     * @param File $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function classifyFile(Request $request, File $file)
    {
        try {
            // Get the file content
            $content = Storage::disk('local')->get($file->path);
            if (!$content) {
                throw new \Exception("Could not read file contents");
            }

            // Get all available folders for the user/company context
            $folders = Folder::where('company_id', $file->company_id)
                           ->where('active', true)
                           ->get();

            if ($folders->isEmpty()) {
                return response()->json([
                    'error' => 'No folders available for classification'
                ], 400);
            }

            // Extract text from the file
            $text = $this->aiClassifier->extractTextFromFile($file);
            if (!$text) {
                return response()->json([
                    'error' => 'Could not extract text from file'
                ], 400);
            }

            // Get folder suggestion from AI
            $suggestedFolderId = $this->aiClassifier->classifyDocument($text, $folders->toArray());
            
            if (!$suggestedFolderId) {
                return response()->json([
                    'error' => 'AI could not determine appropriate folder'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'folder_id' => $suggestedFolderId
            ]);

        } catch (\Exception $e) {
            Log::error('AI Classification error: ' . $e->getMessage(), [
                'file_id' => $file->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Error during AI classification: ' . $e->getMessage()
            ], 500);
        }
    }
}

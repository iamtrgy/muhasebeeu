<?php

namespace App\Services;

use App\Models\File;
use App\Models\Folder;
use App\Services\FileService;
use App\Services\AIDocumentClassifierService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class AIFileUploadService
{
    protected $fileService;
    protected $aiClassifier;

    public function __construct(FileService $fileService, AIDocumentClassifierService $aiClassifier)
    {
        $this->fileService = $fileService;
        $this->aiClassifier = $aiClassifier;
    }

    /**
     * Upload a file with optional AI classification
     *
     * @param UploadedFile $file
     * @param Folder $folder
     * @param int $userId
     * @param bool $useAI
     * @return File
     */
    public function uploadWithAI(UploadedFile $file, Folder $folder, int $userId, bool $useAI = false)
    {
        // First, upload the file normally
        $uploadedFile = $this->fileService->uploadFile($file, $folder, $userId);

        // If AI classification is enabled, try to classify the document
        if ($useAI) {
            try {
                // Get all available folders for the company
                $availableFolders = Folder::where('company_id', $folder->company_id)
                                        ->where('active', true)
                                        ->get()
                                        ->toArray();
                
                // Extract text from the file
                $text = $this->aiClassifier->extractTextFromFile($file);
                
                if ($text) {
                    // Get folder suggestion from AI
                    $suggestedFolderId = $this->aiClassifier->classifyDocument($text, $availableFolders);
                    
                    if ($suggestedFolderId && $suggestedFolderId !== $folder->id) {
                        // Move the file to the suggested folder
                        $uploadedFile->folder_id = $suggestedFolderId;
                        $uploadedFile->save();
                        
                        Log::info('File moved to AI-suggested folder', [
                            'file_id' => $uploadedFile->id,
                            'original_folder' => $folder->id,
                            'suggested_folder' => $suggestedFolderId
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('AI Classification failed: ' . $e->getMessage(), [
                    'file_id' => $uploadedFile->id,
                    'error' => $e->getMessage()
                ]);
                // Don't throw the error, just log it and continue
            }
        }

        return $uploadedFile;
    }
}

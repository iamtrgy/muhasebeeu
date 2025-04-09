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
    public function uploadWithAI(UploadedFile $file, Folder $folder, int $userId, bool $useAI = true)
    {
        Log::info('Uploading file with AI classification', [
            'file_name' => $file->getClientOriginalName(),
            'folder_id' => $folder->id,
            'user_id' => $userId,
            'use_ai' => $useAI
        ]);
        
        // First, upload to the original folder
        $uploadedFile = $this->fileService->uploadFile($file, $folder, $userId);
        
        if (!$uploadedFile) {
            Log::error('Failed to upload file in AIFileUploadService', [
                'file_name' => $file->getClientOriginalName()
            ]);
            return null;
        }
        
        // If AI classification is not needed, return the uploaded file
        if (!$useAI) {
            Log::info('Skipping AI classification as requested');
            return $uploadedFile;
        }
        
        // Get the company name for better classification
        $companyName = $folder->company->name ?? null;

        // Now try to determine if there's a better folder for this file
        try {
            Log::info('Starting AI classification', [
                'file_id' => $uploadedFile->id,
                'mime_type' => $file->getMimeType(),
                'file_name' => $file->getClientOriginalName()
            ]);

            // Get all available folders with their hierarchy and metadata
            $availableFolders = Folder::where('company_id', $folder->company_id)
                ->where('active', true)
                ->whereNull('deleted_at')
                ->where(function ($query) use ($folder) {
                    $query->where('id', $folder->id)
                        ->orWhere('parent_id', $folder->id)
                        ->orWhereIn('parent_id', function ($subq) use ($folder) {
                            $subq->select('id')
                                ->from('folders')
                                ->where('parent_id', $folder->id);
                        });
                })
                ->with(['parent', 'parent.parent', 'company'])  // Eager load relationships
                ->get()
                ->map(function($f) {
                    // Calculate full path
                    $path = $f->name;
                    $current = $f;
                    while ($current->parent) {
                        $path = $current->parent->name . ' > ' . $path;
                        $current = $current->parent;
                    }
                    
                    // Add computed properties
                    $f->path = $path;
                    
                    // Check if it's a year folder (4 digits)
                    $f->is_year_folder = preg_match('/^20\d{2}$/', $f->name) ? 1 : 0;
                    
                    // Check if it's a month folder
                    $months = ['january', 'february', 'march', 'april', 'may', 'june',
                             'july', 'august', 'september', 'october', 'november', 'december'];
                    $f->is_month_folder = in_array(strtolower($f->name), $months);
                    
                    // Check if it's a document type folder
                    $docTypes = [
                        'income' => ['income', 'revenue', 'earnings'],
                        'expense' => ['expense', 'expenses', 'costs', 'payments'],
                        'bank' => ['bank', 'banks', 'banking', 'statements'],
                        'other' => ['other', 'misc', 'miscellaneous']
                    ];
                    
                    $f->is_document_type_folder = false;
                    $f->folder_type = null;
                    
                    foreach ($docTypes as $type => $keywords) {
                        if (in_array(strtolower($f->name), $keywords)) {
                            $f->is_document_type_folder = true;
                            $f->folder_type = $type;
                            break;
                        }
                    }
                    
                    // Get the full hierarchy type
                    $f->hierarchy_type = null;
                    if ($f->is_year_folder) $f->hierarchy_type = 'year';
                    elseif ($f->is_month_folder) $f->hierarchy_type = 'month';
                    elseif ($f->is_document_type_folder) $f->hierarchy_type = 'document_type';
                    
                    return $f;
                });

            Log::info('Retrieved folders for classification', [
                'company_id' => $folder->company_id,
                'folder_count' => $availableFolders->count(),
                'current_folder' => $folder->name,
                'current_folder_id' => $folder->id,
                'parent_folder' => $folder->parent ? $folder->parent->name : null,
                'parent_folder_id' => $folder->parent_id,
                'company_name' => $folder->company->name ?? 'Unknown Company',
                'memory_usage' => memory_get_usage(true) / 1024 / 1024 . ' MB'
            ]);

            // Log all folders for debugging
            foreach ($availableFolders as $f) {
                Log::debug('Found folder:', [
                    'id' => $f->id,
                    'name' => $f->name,
                    'path' => $f->path,
                    'parent_name' => $f->parent ? $f->parent->name : null,
                    'grandparent_name' => $f->parent && $f->parent->parent ? $f->parent->parent->name : null,
                    'company_id' => $f->company_id,
                    'company_name' => $f->company->name ?? 'Unknown Company',
                    'active' => $f->active,
                    'allow_uploads' => $f->allow_uploads,
                    'is_year_folder' => $f->is_year_folder,
                    'is_month_folder' => $f->is_month_folder,
                    'is_document_type_folder' => $f->is_document_type_folder,
                    'folder_type' => $f->folder_type,
                    'hierarchy_type' => $f->hierarchy_type
                ]);
            }

            if ($availableFolders->isEmpty()) {
                Log::error('No folders found for classification', [
                    'company_id' => $folder->company_id,
                    'current_folder' => $folder->name,
                    'current_folder_id' => $folder->id,
                    'parent_folder' => $folder->parent ? $folder->parent->name : null,
                    'parent_folder_id' => $folder->parent_id,
                    'company_name' => $folder->company->name ?? 'Unknown Company'
                ]);
                return $uploadedFile;
            }

            // Extract text from the file
            $text = $this->aiClassifier->extractTextFromFile($file);
            
            if (empty($text)) {
                Log::error('Failed to extract text from file', [
                    'file_id' => $uploadedFile->id,
                    'mime_type' => $file->getMimeType()
                ]);
                return $uploadedFile;
            }

            $folderData = $availableFolders->map(function($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'parent_id' => $folder->parent_id,
                    'path' => $folder->path,
                    'is_year_folder' => $folder->is_year_folder,
                    'is_month_folder' => $folder->is_month_folder,
                    'is_document_type_folder' => $folder->is_document_type_folder,
                    'folder_type' => $folder->folder_type,
                    'hierarchy_type' => $folder->hierarchy_type
                ];
            })->toArray();

            // Use the Claude service to classify the document
            $suggestedFolderId = $this->aiClassifier->classifyDocument(
                $text, 
                $folderData, 
                $companyName
            );
            
            if ($suggestedFolderId && $suggestedFolderId !== $folder->id) {
                $suggestedFolder = Folder::find($suggestedFolderId);
                if ($suggestedFolder) {
                    Log::info('Storing AI folder suggestion', [
                        'file_id' => $uploadedFile->id,
                        'current_folder_id' => $folder->id,
                        'suggested_folder_id' => $suggestedFolderId
                    ]);
                    
                    // Store the suggestion but don't move the file yet
                    $uploadedFile->suggested_folder_id = $suggestedFolderId;
                    $uploadedFile->classification_reviewed = false;
                    $uploadedFile->save();
                }
            } else {
                Log::warning('AI classification did not return a folder suggestion', [
                    'file_id' => $uploadedFile->id,
                    'file_name' => $file->getClientOriginalName(),
                    'current_folder' => $folder->name
                ]);
            }
        } catch (\Exception $e) {
            Log::error('AI classification failed', [
                'error' => $e->getMessage(),
                'file_id' => $uploadedFile->id,
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $uploadedFile;
    }
}

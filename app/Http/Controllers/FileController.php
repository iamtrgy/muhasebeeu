<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Services\FileService;
use App\Services\AIFileUploadService;
use App\Services\ChunkedFileUploadService;
use App\Services\AIDocumentClassifierService;
use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\ChunkUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FileController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * The file service instance.
     *
     * @var \App\Services\FileService
     */
    protected $fileService;
    
    /**
     * The AI file upload service instance.
     *
     * @var \App\Services\AIFileUploadService
     */
    protected $aiFileUploadService;
    
    /**
     * The chunked file upload service instance.
     *
     * @var \App\Services\ChunkedFileUploadService
     */
    protected $chunkedFileUploadService;
    
    /**
     * Create a new controller instance.
     *
     * @param \App\Services\FileService $fileService
     * @param \App\Services\AIFileUploadService $aiFileUploadService
     * @param \App\Services\ChunkedFileUploadService $chunkedFileUploadService
     * @return void
     */
    public function __construct(
        FileService $fileService, 
        AIFileUploadService $aiFileUploadService,
        ChunkedFileUploadService $chunkedFileUploadService
    ) {
        $this->fileService = $fileService;
        $this->aiFileUploadService = $aiFileUploadService;
        $this->chunkedFileUploadService = $chunkedFileUploadService;
    }

    public function index(Folder $folder)
    {
        $this->authorize('view', $folder);
        $files = $folder->files()
            ->with('uploader')
            ->latest()
            ->paginate(15);
        return view('files.index', compact('folder', 'files'));
    }

    public function show(Folder $folder)
    {
        $this->authorize('view', $folder);
        $files = $folder->files()
            ->with('uploader')
            ->latest()
            ->paginate(15);
        return view('folders.show', compact('folder', 'files'));
    }

    /**
     * Store a newly uploaded file in storage.
     *
     * @param \App\Http\Requests\FileUploadRequest $request
     * @param \App\Models\Folder $folder
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FileUploadRequest $request, Folder $folder)
    {
        try {
            // Log the incoming request headers for debugging
            Log::info('File upload request received', [
                'headers' => $request->headers->all(),
                'has_files' => $request->hasFile('files'),
                'content_type' => $request->header('Content-Type'),
                'method' => $request->method(),
                'ajax' => $request->ajax(),
                'ip' => $request->ip(),
                'ai_classify' => $request->boolean('ai_classify')
            ]);
            
            $this->authorize('upload', $folder);

            // Log the request data for debugging
            Log::info('File upload attempt', [
                'folder_id' => $folder->id,
                'user_id' => auth()->id(),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
                'ai_classify' => $request->boolean('ai_classify')
            ]);

            // Check if the request is too large (exceeding post_max_size)
            if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                Log::error('File upload failed: Request too large, likely exceeding post_max_size');
                $maxSize = $this->fileService->formatBytes($this->fileService->returnBytes(ini_get('post_max_size')));
                return redirect()->back()->with('error', 'The upload failed because the total file size exceeds the server limit (' . $maxSize . '). Try uploading smaller files or fewer files at once.');
            }

            $uploadedFiles = [];
            $errors = [];
            $useAI = $request->boolean('ai_classify');
            $pendingClassification = false;
            
            if (!$request->hasFile('files')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No files were uploaded.'
                ], 422);
            }

            foreach ($request->file('files') as $index => $file) {
                try {
                    // Use the AIFileUploadService to handle the upload
                    $uploadedFile = $this->aiFileUploadService->uploadWithAI($file, $folder, auth()->id(), $useAI);
                    
                    if (!$uploadedFile) {
                        throw new \Exception('Failed to upload file: ' . $file->getClientOriginalName());
                    }
                    
                    // Check if the file needs classification review
                    if ($useAI && $uploadedFile->suggested_folder_id && !$uploadedFile->classification_reviewed) {
                        $pendingClassification = true;
                    }
                    
                    $uploadedFiles[] = $uploadedFile;
                } catch (\Exception $e) {
                    Log::error('Error uploading file', [
                        'file_name' => $file->getClientOriginalName(),
                        'folder_id' => $folder->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
                }
            }

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some files failed to upload:<br>' . implode("<br>", $errors)
                ], 422);
            }

            if (empty($uploadedFiles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No files were uploaded successfully. Please try again.'
                ], 422);
            }

            // Process classification if enabled
            if ($request->has('ai_classify') && $request->ai_classify == 'true') {
                try {
                    // Extract text and classify with the AI service
                    $documentClassifier = new AIDocumentClassifierService();
                    $textContent = $documentClassifier->extractTextFromFile($request->file('files')[0]);
                    
                    if ($textContent) {
                        // Get all available folders for the company
                        $company = $folder->company;
                        $availableFolders = \App\Models\Folder::where('company_id', $company->id)
                            ->get()
                            ->map(function($f) {
                                return [
                                    'id' => $f->id,
                                    'name' => $f->name,
                                    'path' => $f->path,
                                    'parent_id' => $f->parent_id,
                                    'is_year_folder' => $f->is_year_folder,
                                    'is_month_folder' => $f->is_month_folder,
                                    'is_document_type_folder' => $f->is_document_type_folder,
                                    'folder_type' => $f->folder_type
                                ];
                            })
                            ->toArray();
                        
                        Log::info('Available folders for classification', [
                            'company_id' => $company->id,
                            'folder_count' => count($availableFolders),
                            'current_folder_id' => $folder->id,
                            'current_folder_path' => $folder->path
                        ]);
                        
                        // Get suggested folder ID from the AI classifier
                        $suggestedFolderId = $documentClassifier->classifyDocument(
                            $textContent, 
                            $availableFolders,
                            $company->name
                        );
                        
                        if ($suggestedFolderId && $suggestedFolderId != $folder->id) {
                            // Save the suggestion to the file record for user review
                            $uploadedFiles[0]->suggested_folder_id = $suggestedFolderId;
                            $uploadedFiles[0]->classification_reviewed = false;
                            $uploadedFiles[0]->save();
                            
                            Log::info('Storing AI folder suggestion', [
                                'file_id' => $uploadedFiles[0]->id,
                                'current_folder_id' => $folder->id,
                                'suggested_folder_id' => $suggestedFolderId,
                                'suggested_folder_exists' => \App\Models\Folder::find($suggestedFolderId) ? true : false
                            ]);
                            
                            $pendingClassification = true;
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error during AI classification', [
                        'error' => $e->getMessage(),
                        'file' => $request->file('files')[0]->getClientOriginalName()
                    ]);
                }
            }

            $results = [];
            foreach ($uploadedFiles as $file) {
                $results[] = [
                    'file_id' => $file->id,
                    'name' => $file->name,
                    'original_folder' => [
                        'id' => $folder->id,
                        'name' => $folder->name,
                        'path' => $folder->parent ? $folder->parent->name . ' > ' . $folder->name : $folder->name
                    ],
                    'final_folder' => [
                        'id' => $file->folder_id,
                        'name' => $file->folder->name,
                        'path' => $file->folder->parent ? $file->folder->parent->name . ' > ' . $file->folder->name : $file->folder->name
                    ],
                    'suggested_folder' => $file->suggested_folder_id ? [
                        'id' => $file->suggestedFolder->id,
                        'name' => $file->suggestedFolder->name,
                        'path' => $file->suggestedFolder->parent ? $file->suggestedFolder->parent->name . ' > ' . $file->suggestedFolder->name : $file->suggestedFolder->name
                    ] : null,
                    'needs_review' => ($file->suggested_folder_id && !$file->classification_reviewed)
                ];
            }

            $message = count($uploadedFiles) . ' file(s) uploaded successfully.';
            if ($pendingClassification) {
                $message .= ' Some files need classification review. <a href="' . route('user.files.classification') . '" class="text-blue-600 hover:underline">View pending classifications</a>';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'pending_classification' => $pendingClassification,
                'results' => $results,
                'redirect' => null
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Uncaught error in file upload: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during file upload: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Folder $folder, File $file)
    {
        $this->authorize('delete', $file);

        // Delete the file from storage
        Storage::disk('bunny')->delete($file->path);

        // Delete the file record from database
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }

    public function download(File $file)
    {
        $this->authorize('download', $file);

        // Check if file exists in Bunny storage
        if (!Storage::disk('bunny')->fileExists($file->path)) {
            abort(404, 'File not found.');
        }

        // Get the file contents from Bunny storage
        $contents = Storage::disk('bunny')->read($file->path);

        // Create response with file contents and force download
        return response($contents)
            ->header('Content-Type', $file->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $file->original_name . '"')
            ->header('Content-Length', $file->size)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function preview(File $file)
    {
        // Check if user has access to the file through folder permissions
        if (!$file->folder->canAccess(auth()->user())) {
            abort(403, 'You do not have permission to access this file.');
        }

        // Check if file exists in Bunny storage
        if (!Storage::disk('bunny')->fileExists($file->path)) {
            abort(404, 'File not found.');
        }

        // Get the file contents from Bunny storage
        $contents = Storage::disk('bunny')->read($file->path);
        
        // For PDFs and images, we can display them in the browser
        $previewableTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/svg+xml'
        ];
        
        if (in_array($file->mime_type, $previewableTypes)) {
            // Create response with file contents for inline display
            return response($contents)
                ->header('Content-Type', $file->mime_type)
                ->header('Content-Disposition', 'inline; filename="' . $file->original_name . '"')
                ->header('Content-Length', $file->size);
        }
        
        // For non-previewable files, download them
        return response($contents)
            ->header('Content-Type', $file->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $file->original_name . '"')
            ->header('Content-Length', $file->size);
    }

    /**
     * Store file uploaded in chunks to bypass PHP upload size limits
     *
     * @param \App\Http\Requests\ChunkUploadRequest $request
     * @param \App\Models\Folder $folder
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeChunk(ChunkUploadRequest $request, Folder $folder)
    {
        try {
            $this->authorize('upload', $folder);
            
            // Log chunk upload attempt with AI status
            Log::info('Chunk upload attempt', [
                'folder_id' => $folder->id,
                'user_id' => auth()->id(),
                'chunk_index' => $request->input('chunk_index'),
                'total_chunks' => $request->input('total_chunks'),
                'temp_filename' => $request->input('temp_filename'),
                'filename' => $request->input('filename'),
                'ai_classify' => $request->boolean('ai_classify')
            ]);
            
            // Get validated data
            $chunk = $request->file('chunk');
            $chunkIndex = $request->input('chunk_index');
            $totalChunks = $request->input('total_chunks');
            $tempFilename = $request->input('temp_filename');
            $originalFilename = $request->input('filename');
            $fileSize = $request->input('file_size');
            $mimeType = $request->input('mime_type');
            $useAI = $request->boolean('ai_classify', false);
            
            // Use the ChunkedFileUploadService to handle the chunk upload
            $result = $this->chunkedFileUploadService->handleChunkUpload(
                $chunk,
                $chunkIndex,
                $totalChunks,
                $tempFilename,
                $originalFilename,
                $fileSize,
                $mimeType,
                $folder,
                auth()->id(),
                $useAI
            );
            
            if (isset($result['all_chunks_received']) && $result['all_chunks_received']) {
                Log::info('All chunks received, file upload complete', [
                    'file_id' => $result['file']->id,
                    'ai_classify' => $useAI
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'file' => $result['file'],
                    'ai_classification' => [
                        'enabled' => $useAI,
                        'original_folder' => [
                            'id' => $folder->id,
                            'name' => $folder->name,
                            'path' => $folder->parent ? ($folder->parent->name . ' > ' . $folder->name) : $folder->name
                        ],
                        'final_folder' => [
                            'id' => $result['file']->folder_id,
                            'name' => $result['file']->folder->name,
                            'path' => $result['file']->folder->parent ? 
                                ($result['file']->folder->parent->name . ' > ' . $result['file']->folder->name) : 
                                $result['file']->folder->name
                        ],
                        'was_moved' => $folder->id !== $result['file']->folder_id
                    ]
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Chunk uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in chunk upload: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during chunk upload: ' . $e->getMessage()
            ], 500);
        }
    }
} 
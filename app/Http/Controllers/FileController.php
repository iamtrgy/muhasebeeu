<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Services\FileService;
use App\Services\AIFileUploadService;
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
     * Create a new controller instance.
     *
     * @param \App\Services\FileService $fileService
     * @param \App\Services\AIFileUploadService $aiFileUploadService
     * @return void
     */
    public function __construct(FileService $fileService, AIFileUploadService $aiFileUploadService)
    {
        $this->fileService = $fileService;
        $this->aiFileUploadService = $aiFileUploadService;
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
            
            foreach ($request->file('files') as $index => $file) {
                try {
                    // Use the AIFileUploadService to handle the upload
                    $uploadedFile = $this->aiFileUploadService->uploadWithAI($file, $folder, auth()->id(), $useAI);
                    $uploadedFiles[] = $uploadedFile;
                } catch (\Exception $e) {
                    Log::error('Error uploading file: ' . $e->getMessage(), [
                        'exception_class' => get_class($e),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $errors[] = 'Error uploading file: ' . $file->getClientOriginalName() . ' - ' . $e->getMessage();
                }
            }

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => implode("<br>", $errors)
                ], 422);
            }

            if (empty($uploadedFiles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No files were uploaded. Please try again.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedFiles) . ' file(s) uploaded successfully.',
                'redirect' => url()->current()
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
            
            // Log chunk upload attempt
            Log::info('Chunk upload attempt', [
                'folder_id' => $folder->id,
                'user_id' => auth()->id(),
                'chunk_index' => $request->input('chunk_index'),
                'total_chunks' => $request->input('total_chunks'),
                'temp_filename' => $request->input('temp_filename'),
                'filename' => $request->input('filename')
            ]);
            
            // Get validated data
            $chunk = $request->file('chunk');
            $chunkIndex = $request->input('chunk_index');
            $totalChunks = $request->input('total_chunks');
            $tempFilename = $request->input('temp_filename');
            $originalFilename = $request->input('filename');
            $fileSize = $request->input('file_size');
            $mimeType = $request->input('mime_type');
            
            // Use the FileService to handle the chunk upload
            $result = $this->fileService->handleChunkUpload(
                $chunk,
                $chunkIndex,
                $totalChunks,
                $tempFilename,
                $originalFilename,
                $fileSize,
                $mimeType,
                $folder,
                auth()->id()
            );
            
            if (isset($result['all_chunks_received']) && $result['all_chunks_received']) {
                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'file' => [
                        'id' => $result['file']->id,
                        'name' => $result['file']->name,
                        'size' => $result['file']->size,
                        'size_formatted' => $this->fileService->formatBytes($result['file']->size)
                    ]
                ]);
            }
            
            // If this is not the last chunk, just return success
            return response()->json([
                'success' => true,
                'message' => "Chunk #{$chunkIndex} uploaded successfully",
                'chunk_index' => $chunkIndex,
                'total_chunks' => $totalChunks
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
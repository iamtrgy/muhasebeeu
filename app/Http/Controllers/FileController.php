<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Services\FileService;
use App\Services\ChunkedFileUploadService;
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
     * The chunked file upload service instance.
     *
     * @var \App\Services\ChunkedFileUploadService
     */
    protected $chunkedFileUploadService;
    
    /**
     * Create a new controller instance.
     *
     * @param \App\Services\FileService $fileService
     * @param \App\Services\ChunkedFileUploadService $chunkedFileUploadService
     * @return void
     */
    public function __construct(
        FileService $fileService, 
        ChunkedFileUploadService $chunkedFileUploadService
    ) {
        $this->fileService = $fileService;
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
            $this->authorize('upload', $folder);
            
            if (!$request->hasFile('files')) {
                return response()->json(['error' => 'No files uploaded'], 400);
            }

            $uploadedFiles = [];
            foreach ($request->file('files') as $file) {
                $fileDetails = $this->fileService->processFileName($file, $folder);
                $filePath = $fileDetails['file_path'];

                // Store in Bunny storage
                if (!Storage::disk('bunny')->putFileAs('', $file, $filePath)) {
                    throw new \Exception("Failed to upload file: " . $file->getClientOriginalName());
                }

                // Create database record
                $fileRecord = $folder->files()->create([
                    'name' => $fileDetails['final_name'],
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $filePath,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => auth()->id(),
                ]);

                $uploadedFiles[] = $fileRecord;
            }

            return response()->json(['files' => $uploadedFiles]);
        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'folder_id' => $folder->id,
                'user_id' => auth()->id()
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
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
        } else {
            // For other file types, force download
            return $this->download($file);
        }
    }

    /**
     * Handle chunked file uploads.
     *
     * @param \App\Http\Requests\ChunkUploadRequest $request
     * @param \App\Models\Folder $folder
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeChunk(ChunkUploadRequest $request, Folder $folder)
    {
        try {
            $this->authorize('upload', $folder);
            
            // Process the chunk upload
            $result = $this->chunkedFileUploadService->processChunk(
                $request->file('file'),
                $request->input('dzuuid'),
                $request->input('dzchunkindex'),
                $request->input('dztotalchunkcount'),
                $request->input('dzchunksize'),
                $request->input('dztotalfilesize'),
                $folder,
                auth()->id()
            );
            
            if ($result['status'] === 'completed') {
                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'file' => $result['file']
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Chunk received',
                    'dzchunkindex' => $request->input('dzchunkindex')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in chunked upload', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error uploading chunk: ' . $e->getMessage()
            ], 500);
        }
    }
}
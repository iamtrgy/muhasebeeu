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
use GuzzleHttp\Client;

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
        // CRITICAL: Check authorization before processing files
        $this->authorize('upload', $folder);
        
        try {
            if (!$request->hasFile('files')) {
                return response()->json(['error' => 'No files uploaded'], 400);
            }

            // Since we're uploading files individually, handle single file
            $file = $request->file('files');
            
            // Log for debugging
            \Log::info('File upload request', [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'user_id' => auth()->id(),
                'folder_id' => $folder->id
            ]);
            
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

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'id' => $fileRecord->id,
                    'name' => $fileRecord->name,
                    'original_name' => $fileRecord->original_name,
                    'path' => $fileRecord->path,
                    'size' => $fileRecord->size,
                    'mime_type' => $fileRecord->mime_type,
                    'created_at' => $fileRecord->created_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'folder_id' => $folder->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function destroy(File $file)
    {
        try {
            $this->authorize('delete', $file);

            // Delete the file from storage
            // If file doesn't exist in storage, just proceed with database deletion
            try {
                Storage::disk('bunny')->delete($file->path);
            } catch (\Exception $e) {
                Log::warning('File not found in storage during deletion', [
                    'file_id' => $file->id,
                    'path' => $file->path,
                    'error' => $e->getMessage()
                ]);
            }

            // Delete the file record from database
            $file->delete();

            // Check if it's an AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully'
                ]);
            }

            return redirect()->back()->with('success', 'File deleted successfully.');
        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'error' => $e->getMessage(),
                'file_id' => $file->id,
                'user_id' => auth()->id()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete file: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete file: ' . $e->getMessage());
        }
    }

    public function download(File $file)
    {
        try {
            $this->authorize('download', $file);

            // Get the file URL
            $url = $file->url;
            
            // Get file contents from CDN
            $client = new Client();
            $response = $client->get($url);
            $contents = $response->getBody()->getContents();

            // Force download with headers
            return response($contents)
                ->header('Content-Type', $file->mime_type)
                ->header('Content-Disposition', 'attachment; filename="' . $file->original_name . '"')
                ->header('Content-Length', strlen($contents))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            Log::error('File download failed', [
                'error' => $e->getMessage(),
                'file_id' => $file->id,
                'user_id' => auth()->id()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to download file: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to download file: ' . $e->getMessage());
        }
    }

    public function preview(File $file)
    {
        try {
            // Check if user has access to the file through folder permissions
            if (!$file->folder->isAccessibleBy(auth()->user())) {
                throw new \Exception('You do not have permission to access this file.');
            }

            // For PDFs and images, we can display them in the browser
            $previewableTypes = [
                'application/pdf',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/svg+xml',
                'image/webp'
            ];
            
            // Redirect to CDN URL
            if (in_array($file->mime_type, $previewableTypes)) {
                return redirect()->away($file->url);
            } else {
                // For other file types, redirect to download URL
                return redirect()->away($file->download_url);
            }

        } catch (\Exception $e) {
            Log::error('File preview failed', [
                'error' => $e->getMessage(),
                'file_id' => $file->id,
                'user_id' => auth()->id()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to preview file: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to preview file: ' . $e->getMessage());
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

    /**
     * Update file notes.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\File $file
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateNotes(Request $request, File $file)
    {
        try {
            // Check if user can edit this file (owner or accountant with access)
            $this->authorize('update', $file);

            $validated = $request->validate([
                'notes' => 'nullable|string|max:1000'
            ]);

            $file->update([
                'notes' => $validated['notes']
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notes updated successfully',
                    'notes' => $file->notes
                ]);
            }

            return redirect()->back()->with('success', 'File notes updated successfully.');

        } catch (\Exception $e) {
            Log::error('File notes update failed', [
                'error' => $e->getMessage(),
                'file_id' => $file->id,
                'user_id' => auth()->id()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update notes: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update notes: ' . $e->getMessage());
        }
    }
}
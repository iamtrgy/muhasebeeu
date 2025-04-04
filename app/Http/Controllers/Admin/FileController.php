<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Folder;

class FileController extends Controller
{
    public function download(File $file)
    {
        // Download the file from Bunny storage
        $fileContent = Storage::disk('bunny')->read($file->path);
        
        // Return the file as a download response
        return response($fileContent)
            ->header('Content-Type', $file->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $file->original_name . '"');
    }

    public function destroy(File $file)
    {
        // Delete the file from storage
        Storage::disk('bunny')->delete($file->path);
        
        // Delete the file record from database
        $file->delete();
        
        return redirect()->back()->with('success', 'File deleted successfully.');
    }
    
    public function preview(File $file)
    {
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
     * Store a newly uploaded file.
     */
    public function store(Request $request, Folder $folder)
    {
        $request->validate([
            'files.*' => ['required', 'file', 'max:10240'] // 10MB max
        ]);

        $uploadedCount = 0;
        foreach ($request->file('files') as $uploadedFile) {
            try {
                $originalName = $uploadedFile->getClientOriginalName();
                
                // Store the file on Bunny storage
                $path = Storage::disk('bunny')->putFile('files', $uploadedFile);
                
                File::create([
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $uploadedFile->getSize(),
                    'folder_id' => $folder->id,
                    'uploaded_by' => auth()->id(),
                    'original_name' => $originalName
                ]);
                
                $uploadedCount++;
            } catch (\Exception $e) {
                continue;
            }
        }

        return redirect()->back()->with('success', $uploadedCount . ' ' . Str::plural('file', $uploadedCount) . ' uploaded successfully.');
    }

    /**
     * Bulk delete the specified files.
     */
    public function bulkDestroy(Request $request)
    {
        $fileIds = explode(',', $request->file_ids);
        
        // Get files to delete
        $files = File::whereIn('id', $fileIds)->get();
        
        $deletedCount = 0;
        foreach ($files as $file) {
            try {
                // Delete the file from storage
                Storage::disk('bunny')->delete($file->path);
                
                // Delete the file record from database
                $file->delete();
                
                $deletedCount++;
            } catch (\Exception $e) {
                continue;
            }
        }

        return back()->with('success', $deletedCount . ' files have been deleted successfully.');
    }
} 
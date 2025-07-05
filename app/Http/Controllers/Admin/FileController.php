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
        try {
            // Try to get the file content from Bunny storage
            $fileContent = Storage::disk('bunny')->get($file->path);
            
            // Return the file as a download response
            return response($fileContent)
                ->header('Content-Type', $file->mime_type)
                ->header('Content-Disposition', 'attachment; filename="' . $file->original_name . '"')
                ->header('Content-Length', strlen($fileContent));
        } catch (\Exception $e) {
            // If storage fails, redirect to the download URL
            return redirect($file->downloadUrl);
        }
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
        // Check if the file type is previewable
        $previewableTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'text/plain'
        ];

        if (!in_array($file->mime_type, $previewableTypes)) {
            abort(400, 'This file type cannot be previewed');
        }

        // For text files, return the contents
        if ($file->mime_type === 'text/plain') {
            try {
                $contents = Storage::disk('bunny')->get($file->path);
                return response($contents)->header('Content-Type', 'text/plain');
            } catch (\Exception $e) {
                abort(404, 'File not found');
            }
        }

        // For images and PDFs, redirect to the direct file URL
        return redirect($file->url);
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

    /**
     * Update the notes for the specified file.
     */
    public function updateNotes(Request $request, File $file)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:1000']
        ]);

        $file->update([
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully.',
            'notes' => $file->notes
        ]);
    }
} 
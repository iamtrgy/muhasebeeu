<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AccountantFileController extends Controller
{
    use AuthorizesRequests;
    /**
     * Download a file.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function download(File $file)
    {
        // Check if the accountant has access to this file
        $this->authorize('accountantAccess', $file);

        try {
            // Get the file content from Bunny storage
            $fileContents = file_get_contents($file->url);
            
            // Return response with download headers
            return response($fileContents)
                ->header('Content-Type', $file->mime_type)
                ->header('Content-Disposition', 'attachment; filename="' . $file->original_name . '"')
                ->header('Content-Length', strlen($fileContents));
        } catch (\Exception $e) {
            // If file_get_contents fails, fallback to direct URL with download parameter
            return redirect($file->downloadUrl);
        }
    }

    /**
     * Preview a file.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function preview(File $file)
    {
        // Check if the accountant has access to this file
        $this->authorize('accountantAccess', $file);

        // Check if the file type is previewable
        $previewableTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'text/plain'
        ];

        if (!in_array($file->mime_type, $previewableTypes)) {
            abort(400, 'This file type cannot be previewed');
        }

        // For text files, return the contents
        if ($file->mime_type === 'text/plain') {
            $contents = Storage::disk('bunny')->get($file->path);
            return response($contents)->header('Content-Type', 'text/plain');
        }

        // For images and PDFs, redirect to the direct file URL instead of trying to stream through Laravel
        return redirect($file->url);
    }

    /**
     * Update file notes by accountant.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\File $file
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateNotes(Request $request, File $file)
    {
        try {
            // Check if accountant has access to this file
            $this->authorize('accountantAccess', $file);

            $validated = $request->validate([
                'notes' => 'nullable|string|max:1000'
            ]);

            $file->update([
                'notes' => $validated['notes']
            ]);

            Log::info('Accountant updated file notes', [
                'file_id' => $file->id,
                'file_name' => $file->original_name,
                'accountant_id' => auth()->id(),
                'notes_length' => strlen($validated['notes'] ?? '')
            ]);

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Notes updated successfully',
                    'notes' => $file->notes
                ]);
            }

            return redirect()->back()->with('success', 'File notes updated successfully.');

        } catch (\Exception $e) {
            Log::error('Accountant file notes update failed', [
                'error' => $e->getMessage(),
                'file_id' => $file->id,
                'accountant_id' => auth()->id()
            ]);

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update notes: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update notes: ' . $e->getMessage());
        }
    }
}

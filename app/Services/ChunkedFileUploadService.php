<?php

namespace App\Services;

use App\Models\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChunkedFileUploadService
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Handle a chunked file upload
     */
    public function handleChunkUpload(
        UploadedFile $chunk,
        int $chunkIndex,
        int $totalChunks,
        string $tempFilename,
        string $originalFilename,
        int $fileSize,
        string $mimeType,
        Folder $folder,
        int $userId
    ) {
        $tempDir = storage_path('app/chunks');
        $finalPath = null;
        
        try {
            // Ensure temp directory exists
            if (!file_exists($tempDir)) {
                if (!mkdir($tempDir, 0777, true)) {
                    throw new \Exception("Failed to create temporary directory");
                }
            }

            // Store the chunk
            $chunkPath = "{$tempDir}/{$tempFilename}.part{$chunkIndex}";
            if (!file_put_contents($chunkPath, file_get_contents($chunk->getRealPath()))) {
                throw new \Exception("Failed to write chunk to temporary storage");
            }

            // Check received chunks
            $receivedChunks = glob("{$tempDir}/{$tempFilename}.part*");
            Log::info('Chunk upload status', [
                'chunk_index' => $chunkIndex,
                'total_chunks' => $totalChunks,
                'received_chunks' => count($receivedChunks)
            ]);

            if (count($receivedChunks) === $totalChunks) {
                // Create final file
                $finalPath = "{$tempDir}/{$tempFilename}";
                $finalFile = fopen($finalPath, 'wb');
                if (!$finalFile) {
                    throw new \Exception("Failed to create final file");
                }

                try {
                    // Combine chunks
                    for ($i = 0; $i < $totalChunks; $i++) {
                        $chunkContent = file_get_contents("{$tempDir}/{$tempFilename}.part{$i}");
                        if ($chunkContent === false) {
                            throw new \Exception("Failed to read chunk {$i}");
                        }
                        if (fwrite($finalFile, $chunkContent) === false) {
                            throw new \Exception("Failed to write chunk {$i} to final file");
                        }
                        unlink("{$tempDir}/{$tempFilename}.part{$i}");
                    }
                } finally {
                    fclose($finalFile);
                }

                // Create UploadedFile instance
                $uploadedFile = new UploadedFile(
                    $finalPath,
                    $originalFilename,
                    $mimeType,
                    null,
                    true
                );

                try {
                    // Regular upload
                    $fileDetails = $this->fileService->processFileName($uploadedFile, $folder);
                    $filePath = $fileDetails['file_path'];

                    // Ensure the file contents are valid
                    $fileContents = file_get_contents($finalPath);
                    if ($fileContents === false) {
                        throw new \Exception("Failed to read final file contents");
                    }

                    // Store in Bunny storage
                    if (!Storage::disk('bunny')->write($filePath, $fileContents)) {
                        throw new \Exception("Failed to write to Bunny storage");
                    }

                    // Create database record
                    $file = $folder->files()->create([
                        'name' => $fileDetails['final_name'],
                        'original_name' => $originalFilename,
                        'path' => $filePath,
                        'size' => $fileSize,
                        'mime_type' => $mimeType,
                        'uploaded_by' => $userId,
                    ]);

                    return ['all_chunks_received' => true, 'file' => $file];
                } finally {
                    // Clean up the final file
                    if (file_exists($finalPath)) {
                        unlink($finalPath);
                    }
                }
            }

            return ['all_chunks_received' => false];
        } catch (\Exception $e) {
            Log::error('Error in chunked upload: ' . $e->getMessage(), [
                'chunk_index' => $chunkIndex,
                'total_chunks' => $totalChunks,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

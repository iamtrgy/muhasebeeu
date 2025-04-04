<?php

namespace App\Services;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Upload a file to storage
     * 
     * @param UploadedFile $file
     * @param Folder $folderw
     * @param int $userId
     * @return File|null
     * @throws \Exception
     */
    public function uploadFile(UploadedFile $file, Folder $folder, int $userId)
    {
        try {
            // Log detailed information for the file
            Log::info("Processing file", [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'formatted_size' => $this->formatBytes($file->getSize()),
                'mime_type' => $file->getMimeType(),
                'memory_usage' => $this->formatBytes(memory_get_usage(true))
            ]);
            
            if (!$file->isValid()) {
                // Check for upload error codes
                $errorCode = $file->getError();
                $errorMessage = $this->getUploadErrorMessage($errorCode, $file->getClientOriginalName());
                Log::error('File upload invalid', [
                    'filename' => $file->getClientOriginalName(),
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage
                ]);
                throw new \Exception($errorMessage);
            }

            // Check individual file size
            $uploadMaxSize = $this->getMaximumFileUploadSize();
            if ($file->getSize() > $uploadMaxSize) {
                $errorMessage = 'File ' . $file->getClientOriginalName() . ' exceeds the maximum allowed size (' . $this->formatBytes($uploadMaxSize) . ').';
                Log::error('File too large', [
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'max_allowed' => $uploadMaxSize
                ]);
                throw new \Exception($errorMessage);
            }

            // Process file name and path
            $fileDetails = $this->processFileName($file, $folder);
            $originalName = $fileDetails['original_name'];
            $finalFileName = $fileDetails['final_name'];
            $filePath = $fileDetails['file_path'];
            $fileSize = $file->getSize();

            // Store file based on size
            if ($fileSize < 1024 * 1024) {
                $this->storeSmallFile($file, $filePath);
            } else {
                $this->storeLargeFile($file, $filePath);
            }
            
            // Log successful storage
            Log::info('Successfully stored file in storage', [
                'original_name' => $originalName,
                'path' => $filePath,
                'memory_usage' => $this->formatBytes(memory_get_usage(true))
            ]);
            
            // Create file record in database
            $uploadedFile = $folder->files()->create([
                'name' => $finalFileName,
                'original_name' => $originalName,
                'path' => $filePath,
                'size' => $fileSize,
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => $userId,
            ]);

            return $uploadedFile;
        } catch (\Exception $e) {
            Log::error('Error uploading file: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        } finally {
            // Force garbage collection to free memory
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
        }
    }

    /**
     * Process file name and generate paths
     * 
     * @param UploadedFile $file
     * @param Folder $folder
     * @return array
     */
    public function processFileName(UploadedFile $file, Folder $folder)
    {
        // Sanitize filename
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $baseFileName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseFileName = $this->sanitizeFileName($baseFileName);

        // Check for duplicate filename in this folder
        $counter = 1;
        $newFileName = $baseFileName;
        while ($folder->files()->where('name', $newFileName . '.' . $extension)->exists()) {
            $newFileName = $baseFileName . '_' . $counter;
            $counter++;
        }

        // Add final extension
        $finalFileName = $newFileName . '.' . $extension;

        // Generate unique filename and determine folder path
        $uniqueFileName = Str::random(40) . '.' . $extension;
        $folderPath = 'folders/' . $folder->id;
        $filePath = $folderPath . '/' . $uniqueFileName;

        return [
            'original_name' => $originalName,
            'final_name' => $finalFileName,
            'file_path' => $filePath
        ];
    }

    /**
     * Store a small file (<1MB) directly
     * 
     * @param UploadedFile $file
     * @param string $filePath
     * @throws \Exception
     */
    protected function storeSmallFile(UploadedFile $file, string $filePath)
    {
        try {
            $contents = file_get_contents($file->getRealPath());
            if ($contents === false) {
                throw new \Exception('Could not read file contents');
            }
            
            // Store file with unique name
            Storage::disk('bunny')->write($filePath, $contents);
            
            // Clear memory
            unset($contents);
            
            // Verify the file exists in storage
            if (!Storage::disk('bunny')->fileExists($filePath)) {
                throw new \Exception('File upload failed - file not found in storage');
            }
        } catch (\Exception $e) {
            Log::error('Error in storeSmallFile:', [
                'message' => $e->getMessage(),
                'file_path' => $filePath,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Store a large file using streaming
     * 
     * @param UploadedFile $file
     * @param string $filePath
     * @throws \Exception
     */
    protected function storeLargeFile(UploadedFile $file, string $filePath)
    {
        $fileSize = $file->getSize();
        Log::info('Using streaming approach for larger file', [
            'filename' => $file->getClientOriginalName(),
            'size' => $this->formatBytes($fileSize)
        ]);
        
        $tempStream = null;
        $inputStream = null;
        
        try {
            // Use php://temp for temporary storage to stream from
            $tempStream = fopen('php://temp', 'r+');
            if ($tempStream === false) {
                throw new \Exception('Failed to open temporary stream');
            }
            
            $inputStream = fopen($file->getRealPath(), 'r');
            if ($inputStream === false) {
                throw new \Exception('Failed to open input file stream');
            }
            
            // Copy data to temp stream in 1MB chunks
            $chunkSize = 1024 * 1024; // 1MB chunks
            while (!feof($inputStream)) {
                $chunk = fread($inputStream, $chunkSize);
                if ($chunk === false) {
                    throw new \Exception('Failed to read from input stream');
                }
                
                $writeResult = fwrite($tempStream, $chunk);
                if ($writeResult === false) {
                    throw new \Exception('Failed to write to temporary stream');
                }
            }
            
            // Rewind temp stream to beginning
            rewind($tempStream);
            
            // Store file using stream
            $stored = Storage::disk('bunny')->writeStream($filePath, $tempStream);
            
            Log::info('Streaming completed', [
                'filename' => $file->getClientOriginalName(),
                'stored' => $stored ? 'yes' : 'no'
            ]);
            
            if (!$stored) {
                throw new \Exception('Failed to store file in storage');
            }
        } catch (\Exception $e) {
            Log::error('Streaming error: ' . $e->getMessage(), [
                'filename' => $file->getClientOriginalName(),
                'size' => $this->formatBytes($fileSize)
            ]);
            throw $e;
        } finally {
            // Close streams if they were opened
            if (isset($inputStream) && is_resource($inputStream)) {
                fclose($inputStream);
            }
            if (isset($tempStream) && is_resource($tempStream)) {
                fclose($tempStream);
            }
        }
    }

    /**
     * Store file uploaded in chunks to bypass PHP upload size limits
     * 
     * @param array $chunkData
     * @param Folder $folder
     * @param int $userId
     * @return File|null
     * @throws \Exception
     */
    public function storeChunkedFile(array $chunkData, Folder $folder, int $userId)
    {
        $chunk = $chunkData['chunk'];
        $chunkIndex = $chunkData['chunk_index'];
        $totalChunks = $chunkData['total_chunks'];
        $tempFilename = $chunkData['temp_filename'];
        $originalName = $chunkData['filename'];
        $fileSize = $chunkData['file_size'];
        $mimeType = $chunkData['mime_type'];
        
        // Create the temporary directory if it doesn't exist
        $tempDir = storage_path('app/chunks');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        // Sanitize the temp filename
        $tempFilename = $this->sanitizeFileName($tempFilename);
        
        // Store the chunk in a temporary file
        $chunkPath = $tempDir . '/' . $tempFilename . '.part' . $chunkIndex;
        file_put_contents($chunkPath, file_get_contents($chunk->getRealPath()));
        
        Log::info("Stored chunk #{$chunkIndex} of {$totalChunks}", [
            'temp_filename' => $tempFilename,
            'chunk_path' => $chunkPath
        ]);
        
        // If this is the last chunk, combine all chunks
        if ($chunkIndex == $totalChunks - 1) {
            Log::info("Combining chunks for {$tempFilename}");
            
            // Process file name
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $baseFileName = pathinfo($originalName, PATHINFO_FILENAME);
            $baseFileName = $this->sanitizeFileName($baseFileName);
            
            // Check for duplicate filename in this folder
            $counter = 1;
            $newFileName = $baseFileName;
            while ($folder->files()->where('name', $newFileName . '.' . $extension)->exists()) {
                $newFileName = $baseFileName . '_' . $counter;
                $counter++;
            }
            
            // Add final extension
            $finalFileName = $newFileName . '.' . $extension;
            
            // Generate unique filename and determine folder path
            $uniqueFileName = Str::random(40) . '.' . $extension;
            $folderPath = 'folders/' . $folder->id;
            $filePath = $folderPath . '/' . $uniqueFileName;
            
            // Create a temporary file to combine the chunks
            $tempFinalPath = $tempDir . '/' . $tempFilename . '.combined';
            $finalFile = fopen($tempFinalPath, 'wb');
            
            if ($finalFile === false) {
                throw new \Exception('Failed to create combined file');
            }
            
            // Combine all chunks
            try {
                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkPath = $tempDir . '/' . $tempFilename . '.part' . $i;
                    if (!file_exists($chunkPath)) {
                        throw new \Exception("Chunk {$i} is missing");
                    }
                    
                    $chunkContent = file_get_contents($chunkPath);
                    if ($chunkContent === false) {
                        throw new \Exception("Failed to read chunk {$i}");
                    }
                    
                    $writeResult = fwrite($finalFile, $chunkContent);
                    if ($writeResult === false) {
                        throw new \Exception("Failed to write chunk {$i} to combined file");
                    }
                    
                    // Free memory
                    unset($chunkContent);
                    
                    // Delete the chunk file
                    unlink($chunkPath);
                }
                
                // Close the combined file
                fclose($finalFile);
                
                // Upload the combined file to storage
                $stream = fopen($tempFinalPath, 'r');
                if ($stream === false) {
                    throw new \Exception('Failed to open combined file for upload');
                }
                
                $stored = Storage::disk('bunny')->writeStream($filePath, $stream);
                fclose($stream);
                
                if (!$stored) {
                    throw new \Exception('Failed to store combined file in storage');
                }
                
                // Delete the temporary combined file
                unlink($tempFinalPath);
                
                // Create file record in database
                $uploadedFile = $folder->files()->create([
                    'name' => $finalFileName,
                    'original_name' => $originalName,
                    'path' => $filePath,
                    'size' => $fileSize,
                    'mime_type' => $mimeType,
                    'uploaded_by' => $userId,
                ]);
                
                Log::info('Successfully uploaded chunked file', [
                    'filename' => $originalName,
                    'size' => $this->formatBytes($fileSize)
                ]);
                
                return $uploadedFile;
            } catch (\Exception $e) {
                // Clean up any temporary files
                if (file_exists($tempFinalPath)) {
                    unlink($tempFinalPath);
                }
                
                Log::error('Error combining chunks: ' . $e->getMessage(), [
                    'exception_class' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
                
                throw $e;
            }
        }
        
        return null;
    }

    /**
     * Sanitize filename to prevent security issues
     * 
     * @param string $fileName
     * @return string
     */
    public function sanitizeFileName($fileName)
    {
        // Remove any directory components
        $fileName = basename($fileName);
        
        // Replace spaces and special characters
        $fileName = preg_replace('/[^a-zA-Z0-9-_.]/', '-', $fileName);
        
        // Remove multiple consecutive dashes
        $fileName = preg_replace('/-+/', '-', $fileName);
        
        // Trim dashes from beginning and end
        $fileName = trim($fileName, '-');
        
        // Ensure the filename is not empty
        if (empty($fileName)) {
            $fileName = 'file-' . Str::random(10);
        }
        
        return $fileName;
    }

    /**
     * Get maximum file upload size
     * 
     * @return int Maximum upload size in bytes
     */
    public function getMaximumFileUploadSize() 
    {
        return min(
            $this->returnBytes(ini_get('upload_max_filesize')),
            $this->returnBytes(ini_get('post_max_size'))
        );
    }
    
    /**
     * Convert PHP ini values like "2M", "8M" to bytes
     * 
     * @param string $val
     * @return int
     */
    public function returnBytes($val) 
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        
        return $val;
    }
    
    /**
     * Format bytes to human-readable format
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public function formatBytes($bytes, $precision = 2) 
    { 
        $units = ['B', 'KB', 'MB', 'GB', 'TB']; 
        
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        
        $bytes /= (1 << (10 * $pow)); 
        
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }
    
    /**
     * Get readable error message for PHP file upload error codes
     * 
     * @param int $errorCode
     * @param string $fileName
     * @return string
     */
    public function getUploadErrorMessage($errorCode, $fileName) 
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                $maxSize = $this->formatBytes($this->returnBytes(ini_get('upload_max_filesize')));
                return "File '$fileName' exceeds the upload_max_filesize directive ($maxSize) in php.ini";
            case UPLOAD_ERR_FORM_SIZE:
                return "File '$fileName' exceeds the MAX_FILE_SIZE directive specified in the HTML form";
            case UPLOAD_ERR_PARTIAL:
                return "File '$fileName' was only partially uploaded";
            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded for '$fileName'";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Missing a temporary folder";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file '$fileName' to disk";
            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension stopped the file upload";
            default:
                return "Unknown upload error for file '$fileName'";
        }
    }
}

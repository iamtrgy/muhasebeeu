<?php

namespace App\Services;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class AIDocumentAnalyzer
{
    protected $apiKey;
    protected $model;
    protected $maxTokens;
    
    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
        $this->model = config('services.openai.model', 'gpt-4o');
        $this->maxTokens = (int) config('services.openai.max_tokens', 1000);
        
        if (!$this->apiKey) {
            throw new Exception('OpenAI API key is not configured');
        }
    }
    
    /**
     * Analyze a document and suggest the best folder
     */
    public function analyzeDocument($filePath, User $user, $currentFolderId = null)
    {
        try {
            Log::info('Starting document analysis', ['filePath' => $filePath, 'userId' => $user->id, 'currentFolderId' => $currentFolderId]);
            
            // Get file content or prepare for vision API
            $fileContent = $this->prepareFileForAnalysis($filePath);
            Log::info('File prepared for analysis', ['type' => $fileContent['type']]);
            
            // Get user's available folders
            $folders = $user->folders()
                ->with('parent.parent.parent') // Load parent relationships for path
                ->orderBy('name')
                ->get()
                ->map(function ($folder) {
                    return [
                        'id' => $folder->id,
                        'name' => $folder->name,
                        'path' => $folder->full_path,
                        'description' => $folder->description ?? 'No description'
                    ];
                });
            
            Log::info('Found user folders', ['count' => $folders->count()]);
            
            // Get user's companies for context
            $userCompanies = $user->companies()->pluck('name')->toArray();
            
            // Get current folder info if provided
            $currentFolder = null;
            if ($currentFolderId) {
                $currentFolder = $folders->firstWhere('id', $currentFolderId);
            }
            
            // Call OpenAI API with user context
            $analysis = $this->callOpenAI($fileContent, $folders, $filePath, $userCompanies, $currentFolder);
            
            Log::info('AI analysis completed successfully');
            
            return [
                'success' => true,
                'analysis' => $analysis
            ];
            
        } catch (Exception $e) {
            Log::error('AI Document Analysis failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'error' => 'Failed to analyze document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Prepare file for analysis based on type
     */
    protected function prepareFileForAnalysis($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        try {
            $mimeType = Storage::mimeType($filePath);
        } catch (\Exception $e) {
            // Fallback mime type detection based on extension
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'pdf' => 'application/pdf',
                'txt' => 'text/plain',
                'csv' => 'text/csv',
                'xml' => 'text/xml',
                'json' => 'application/json',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
            $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        }
        
        // For images, we'll send the base64 encoded image
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            try {
                $content = Storage::get($filePath);
                return [
                    'type' => 'image',
                    'data' => base64_encode($content),
                    'mime' => $mimeType
                ];
            } catch (\Exception $e) {
                Log::error('Failed to read image file: ' . $e->getMessage());
                throw new Exception('Failed to read image file');
            }
        }
        
        // For PDFs, we need to convert to image since Vision API doesn't support PDFs
        if ($extension === 'pdf') {
            try {
                // Try to convert PDF to image using Imagick
                if (class_exists('Imagick')) {
                    // First try using Ghostscript directly for better PDF rendering
                    $tempImagePath = sys_get_temp_dir() . '/pdf_to_image_' . uniqid() . '.png';
                    $gsCommand = sprintf(
                        'gs -dNOPAUSE -dBATCH -sDEVICE=png16m -r300 -dFirstPage=1 -dLastPage=1 -sOutputFile=%s %s 2>&1',
                        escapeshellarg($tempImagePath),
                        escapeshellarg($filePath)
                    );
                    
                    exec($gsCommand, $output, $returnCode);
                    
                    if ($returnCode === 0 && file_exists($tempImagePath)) {
                        Log::info('PDF converted using Ghostscript directly');
                        
                        // Read the converted image
                        $imagick = new \Imagick($tempImagePath);
                        unlink($tempImagePath); // Clean up temp file
                    } else {
                        Log::warning('Ghostscript conversion failed, falling back to Imagick', [
                            'command' => $gsCommand,
                            'output' => implode("\n", $output),
                            'return_code' => $returnCode
                        ]);
                        
                        // Fallback to original Imagick method
                        $imagick = new \Imagick();
                        $imagick->setResolution(300, 300);
                        $imagick->setOption('pdf:use-cropbox', 'true');
                        $imagick->readImage($filePath . '[0]');
                        $imagick->setImageFormat('png');
                        $imagick->setImageBackgroundColor('white');
                        $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                        $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
                    }
                    
                    // Log image dimensions
                    Log::info('PDF to image conversion details', [
                        'width' => $imagick->getImageWidth(),
                        'height' => $imagick->getImageHeight(),
                        'format' => $imagick->getImageFormat()
                    ]);
                    
                    // Debug mode - uncomment to save test image
                    // $testImagePath = storage_path('app/test_pdf_conversion.png');
                    // $imagick->writeImage($testImagePath);
                    // Log::info('Test image saved to: ' . $testImagePath);
                    
                    $imageData = $imagick->getImageBlob();
                    $imagick->destroy();
                    
                    Log::info('PDF converted to image successfully');
                    
                    return [
                        'type' => 'image',  // Changed from 'pdf' to 'image'
                        'data' => base64_encode($imageData),
                        'mime' => 'image/png'
                    ];
                } else {
                    // Without Imagick, we can't process PDFs with Vision API
                    Log::error('Cannot process PDF: Imagick extension not available');
                    throw new Exception('PDF processing requires Imagick extension. Please install it or upload image files (JPG, PNG) instead.');
                }
            } catch (\Exception $e) {
                Log::error('Failed to process PDF file: ' . $e->getMessage());
                throw $e;
            }
        }
        
        // For text files, just read the content
        if (in_array($extension, ['txt', 'csv', 'xml', 'json'])) {
            return [
                'type' => 'text',
                'data' => Storage::get($filePath),
                'mime' => $mimeType
            ];
        }
        
        // For other files, return basic info
        return [
            'type' => 'other',
            'data' => "File type: $extension",
            'mime' => $mimeType
        ];
    }
    
    /**
     * Call OpenAI API for analysis
     */
    protected function callOpenAI($fileContent, $folders, $filePath, $userCompanies = [], $currentFolder = null)
    {
        // Build the system instruction
        $systemPrompt = 'You are a document analyzer. Analyze the document image and extract key information regardless of language or format. Focus on understanding who sent the document, who received it, when it was issued, and what type of document it is. Always respond in JSON format.';
        
        // Build the user prompt
        $userPrompt = $this->buildPrompt($fileContent, $folders, $filePath, $userCompanies, $currentFolder);
        
        // For image content, use the new API format
        if ($fileContent['type'] === 'image') {
            $requestData = [
                'model' => 'gpt-4.1-mini',
                'input' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'input_text',
                                'text' => $systemPrompt . "\n\n" . $userPrompt
                            ],
                            [
                                'type' => 'input_image',
                                'image_url' => "data:{$fileContent['mime']};base64,{$fileContent['data']}"
                            ]
                        ]
                    ]
                ]
            ];
            
            // Log the request for debugging
            Log::info('Sending to OpenAI /v1/responses', [
                'model' => 'gpt-4.1-mini',
                'has_image' => true,
                'content_type' => $fileContent['type'],
                'mime' => $fileContent['mime'] ?? 'unknown',
                'image_data_length' => strlen($fileContent['data']),
                'image_data_preview' => substr($fileContent['data'], 0, 100) . '...',
                'prompt_length' => strlen($systemPrompt . "\n\n" . $userPrompt),
                'prompt_preview' => substr($userPrompt, 0, 200) . '...'
            ]);
            
            // Log the actual request structure
            Log::info('OpenAI Request Structure', [
                'endpoint' => 'https://api.openai.com/v1/responses',
                'model' => $requestData['model'],
                'input_structure' => [
                    'role' => $requestData['input'][0]['role'],
                    'content_types' => array_map(function($c) { return $c['type']; }, $requestData['input'][0]['content']),
                    'text_length' => strlen($requestData['input'][0]['content'][0]['text']),
                    'image_format' => substr($requestData['input'][0]['content'][1]['image_url'], 0, 50) . '...'
                ]
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/responses', $requestData);
        } else {
            // For non-image content, fall back to chat completions
            $messages = [
                [
                    'role' => 'system',
                    'content' => $systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt
                ]
            ];
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => $this->maxTokens,
                'temperature' => 0.3
            ]);
        }
        
        if (!$response->successful()) {
            throw new Exception('OpenAI API request failed: ' . $response->body());
        }
        
        $result = $response->json();
        
        // Handle different response formats
        if (isset($result['output'][0]['content'][0]['text'])) {
            // New /v1/responses API format
            $content = $result['output'][0]['content'][0]['text'];
        } elseif (isset($result['output_text'])) {
            // Alternative new API format
            $content = $result['output_text'];
        } elseif (isset($result['choices'][0]['message']['content'])) {
            // Old chat completions format
            $content = $result['choices'][0]['message']['content'];
        } else {
            Log::error('Unexpected OpenAI response format', ['result' => $result]);
            throw new Exception('Unexpected response format from OpenAI');
        }
        
        Log::info('OpenAI raw response', ['content' => $content]);
        
        // Strip markdown code blocks if present
        $content = trim($content);
        if (preg_match('/^```(?:json)?\s*\n(.*)\n```$/s', $content, $matches)) {
            $content = trim($matches[1]);
        }
        
        // Try to parse JSON response
        $parsed = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to parse OpenAI response as JSON', [
                'error' => json_last_error_msg(),
                'content' => $content
            ]);
            throw new Exception('AI response was not valid JSON: ' . json_last_error_msg());
        }
        
        // Ensure required fields exist
        if (!isset($parsed['suggested_folder_id']) || !isset($parsed['folder_name'])) {
            Log::error('AI response missing required fields', ['parsed' => $parsed]);
            throw new Exception('AI response missing required fields');
        }
        
        return $parsed;
    }
    
    /**
     * Build the prompt for OpenAI
     */
    protected function buildPrompt($fileContent, $folders, $filePath, $userCompanies = [], $currentFolder = null)
    {
        $fileName = basename($filePath);
        
        $prompt = "File name: {$fileName}\n";
        
        if ($currentFolder) {
            $prompt .= "CURRENT FOLDER: {$currentFolder['path']} (ID: {$currentFolder['id']})\n";
            $prompt .= "IMPORTANT: Check if this file is already in the correct folder!\n\n";
        }
        
        $prompt .= "Analyze this document and find:\n";
        $prompt .= "1. Document date (CRITICAL: Use EXACT month from date for folder selection)\n";
        $prompt .= "2. Who sent it\n";
        $prompt .= "3. Who received it\n\n";
        $prompt .= "STRICT MONTH RULE:\n";
        $prompt .= "- Date 01.06.2025 = June 2025 folder (NOT April, NOT May)\n";
        $prompt .= "- Date 15.10.2024 = October 2024 folder (NOT April)\n";
        $prompt .= "- Always match month number to exact month name\n";
        $prompt .= "- 01=January, 02=February, 03=March, 04=April, 05=May, 06=June\n";
        $prompt .= "- 07=July, 08=August, 09=September, 10=October, 11=November, 12=December\n\n";
        
        if (!empty($userCompanies)) {
            $prompt .= "User owns these companies: " . implode(', ', $userCompanies) . "\n\n";
            $prompt .= "IMPORTANT RULES:\n";
            $prompt .= "1. If user's company SENT the document = Income folder\n";
            $prompt .= "2. If user's company RECEIVED the document = Expense folder\n";
            $prompt .= "3. If NEITHER sender NOR receiver is user's company = Return warning\n\n";
        }
        
        $prompt .= "FOLDER SELECTION RULE:\n";
        $prompt .= "1. Find the EXACT year from document date\n";
        $prompt .= "2. Find the EXACT month from document date\n";
        $prompt .= "3. Select Income or Expense based on transaction type\n";
        $prompt .= "4. Choose folder: CompanyName/YEAR/MONTH/TYPE\n\n";
        
        $prompt .= "Available folders:\n";
        foreach ($folders as $folder) {
            $prompt .= "ID: {$folder['id']}, Path: {$folder['path']}\n";
        }
        $prompt .= "\n";
        
        $prompt .= "Return JSON:\n";
        $prompt .= "{\n";
        $prompt .= '  "suggested_folder_id": <id or null if no match>,';
        $prompt .= '  "folder_name": "<name or null>",';
        $prompt .= '  "folder_path": "<path or null>",';
        $prompt .= '  "confidence": <0-100>,';
        $prompt .= '  "reasoning": "<what you found>",';
        $prompt .= '  "document_type": "<type>",';
        $prompt .= '  "document_date": "<YYYY-MM-DD format>",';
        $prompt .= '  "transaction_type": "<income|expense|not_related>",';
        $prompt .= '  "key_information": [],';
        $prompt .= '  "company_involved": "<company>",';
        $prompt .= '  "alternative_folders": [{"folder_id": <id>, "folder_name": "<name>", "folder_path": "<path>", "confidence": <0-100>, "reason": "<why this could work>"}],';
        $prompt .= '  "data_source": "<from image content or filename>",';
        $prompt .= '  "warning": "<warning message if document not related to user companies>"';
        $prompt .= "\n}";
        
        return $prompt;
    }
    
    /**
     * Get or create AI analysis for a file
     */
    public function getOrCreateAnalysis(File $file, $forceNew = false)
    {
        // Check if we already have an analysis (unless forced to create new)
        $existingAnalysis = $file->ai_analysis;
        
        if (!$forceNew && $existingAnalysis && !empty($existingAnalysis)) {
            // Check if file is already in suggested folder
            if (isset($existingAnalysis['suggested_folder_id']) && 
                $existingAnalysis['suggested_folder_id'] == $file->folder_id) {
                $existingAnalysis['already_in_correct_folder'] = true;
                $existingAnalysis['reasoning'] = "This file is already in the correct folder: " . $file->folder->full_path;
                
                // Ensure we have alternative folders for user to consider
                if (!isset($existingAnalysis['alternative_folders']) || empty($existingAnalysis['alternative_folders'])) {
                    $existingAnalysis['alternative_folders'] = $this->generateAlternativeFolders($file, $user);
                }
            }
            return $existingAnalysis;
        }
        
        // Get the user (uploader) of the file
        $user = $file->uploader;
        if (!$user) {
            throw new Exception('File has no associated user');
        }
        
        // Download file from CDN for analysis
        $result = $this->analyzeFileFromUrl($file, $user);
        
        if ($result['success']) {
            // Check if suggested folder is the same as current folder
            if (isset($result['analysis']['suggested_folder_id']) && 
                $result['analysis']['suggested_folder_id'] == $file->folder_id) {
                $result['analysis']['already_in_correct_folder'] = true;
                $result['analysis']['reasoning'] = "This file is already in the correct folder: " . $file->folder->full_path;
                
                // Ensure we have alternative folders for user to consider
                if (!isset($result['analysis']['alternative_folders']) || empty($result['analysis']['alternative_folders'])) {
                    $result['analysis']['alternative_folders'] = $this->generateAlternativeFolders($file, $user);
                }
            }
            
            // Save the analysis to the file
            $file->update([
                'ai_analysis' => $result['analysis'],
                'ai_analyzed_at' => now()
            ]);
            
            return $result['analysis'];
        }
        
        return null;
    }
    
    /**
     * Batch analyze multiple files
     */
    public function batchAnalyze(array $fileIds, User $user)
    {
        $results = [];
        
        foreach ($fileIds as $fileId) {
            $file = File::find($fileId);
            
            if ($file && $file->user_id === $user->id) {
                $analysis = $this->getOrCreateAnalysis($file);
                $results[$fileId] = $analysis;
            }
        }
        
        return $results;
    }
    
    /**
     * Analyze file from CDN URL
     */
    protected function analyzeFileFromUrl(File $file, User $user)
    {
        $tempPath = null;
        
        try {
            Log::info('Starting file analysis from URL', ['fileId' => $file->id, 'url' => $file->url]);
            
            // Download file to temporary location
            $tempPath = sys_get_temp_dir() . '/' . uniqid('ai_analysis_') . '_' . $file->original_name;
            
            $response = Http::timeout(30)->get($file->url);
            
            if (!$response->successful()) {
                throw new Exception('Failed to download file from CDN');
            }
            
            // Save to temporary file
            file_put_contents($tempPath, $response->body());
            
            Log::info('File downloaded to temp location', ['tempPath' => $tempPath, 'size' => filesize($tempPath)]);
            
            // Prepare file for analysis
            $fileContent = $this->prepareFileFromTempPath($tempPath, $file->original_name);
            
            // Get user's available folders
            $folders = $user->folders()
                ->with('parent.parent.parent') // Load parent relationships for path
                ->orderBy('name')
                ->get()
                ->map(function ($folder) {
                    return [
                        'id' => $folder->id,
                        'name' => $folder->name,
                        'path' => $folder->full_path,
                        'description' => $folder->description ?? 'No description'
                    ];
                });
            
            Log::info('Found user folders', ['count' => $folders->count()]);
            
            // Get user's companies for context
            $userCompanies = $user->companies()->pluck('name')->toArray();
            
            // Get current folder info
            $currentFolder = null;
            if ($file->folder_id) {
                $currentFolder = $folders->firstWhere('id', $file->folder_id);
            }
            
            // Call OpenAI API
            $analysis = $this->callOpenAI($fileContent, $folders, $file->original_name, $userCompanies, $currentFolder);
            
            Log::info('AI analysis completed successfully');
            
            return [
                'success' => true,
                'analysis' => $analysis
            ];
            
        } catch (Exception $e) {
            Log::error('File analysis from URL failed', [
                'error' => $e->getMessage(),
                'fileId' => $file->id
            ]);
            return [
                'success' => false,
                'error' => 'Failed to analyze document: ' . $e->getMessage()
            ];
        } finally {
            // Clean up temporary file
            if ($tempPath && file_exists($tempPath)) {
                unlink($tempPath);
                Log::info('Temporary file cleaned up', ['tempPath' => $tempPath]);
            }
        }
    }
    
    /**
     * Prepare file from temporary path for analysis
     */
    protected function prepareFileFromTempPath($tempPath, $fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Mime type detection
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'xml' => 'text/xml',
            'json' => 'application/json',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        // For images, we'll send the base64 encoded image
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $content = file_get_contents($tempPath);
            return [
                'type' => 'image',
                'data' => base64_encode($content),
                'mime' => $mimeType
            ];
        }
        
        // For PDFs, we need to convert to image since Vision API doesn't support PDFs
        if ($extension === 'pdf') {
            try {
                // Try to convert PDF to image using Imagick
                if (class_exists('Imagick')) {
                    // First try using Ghostscript directly for better PDF rendering
                    $tempImagePath = sys_get_temp_dir() . '/pdf_to_image_' . uniqid() . '.png';
                    $gsCommand = sprintf(
                        'gs -dNOPAUSE -dBATCH -sDEVICE=png16m -r300 -dFirstPage=1 -dLastPage=1 -sOutputFile=%s %s 2>&1',
                        escapeshellarg($tempImagePath),
                        escapeshellarg($tempPath)
                    );
                    
                    exec($gsCommand, $output, $returnCode);
                    
                    if ($returnCode === 0 && file_exists($tempImagePath)) {
                        Log::info('PDF converted using Ghostscript directly');
                        
                        // Read the converted image
                        $imagick = new \Imagick($tempImagePath);
                        unlink($tempImagePath); // Clean up temp file
                    } else {
                        Log::warning('Ghostscript conversion failed, falling back to Imagick', [
                            'command' => $gsCommand,
                            'output' => implode("\n", $output),
                            'return_code' => $returnCode
                        ]);
                        
                        // Fallback to original Imagick method
                        $imagick = new \Imagick();
                        $imagick->setResolution(300, 300);
                        $imagick->setOption('pdf:use-cropbox', 'true');
                        $imagick->readImage($tempPath . '[0]');
                        $imagick->setImageFormat('png');
                        $imagick->setImageBackgroundColor('white');
                        $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                        $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
                    }
                    
                    // Log image dimensions
                    Log::info('PDF to image conversion details', [
                        'width' => $imagick->getImageWidth(),
                        'height' => $imagick->getImageHeight(),
                        'format' => $imagick->getImageFormat()
                    ]);
                    
                    // Debug mode - uncomment to save test image
                    // $testImagePath = storage_path('app/test_pdf_conversion.png');
                    // $imagick->writeImage($testImagePath);
                    // Log::info('Test image saved to: ' . $testImagePath);
                    
                    $imageData = $imagick->getImageBlob();
                    $imagick->destroy();
                    
                    Log::info('PDF converted to image successfully from temp path');
                    
                    return [
                        'type' => 'image',  // Changed from 'pdf' to 'image'
                        'data' => base64_encode($imageData),
                        'mime' => 'image/png'
                    ];
                } else {
                    // Without Imagick, we can't process PDFs with Vision API
                    Log::error('Cannot process PDF: Imagick extension not available');
                    throw new Exception('PDF processing requires Imagick extension. Please install it or upload image files (JPG, PNG) instead.');
                }
            } catch (\Exception $e) {
                Log::error('Failed to process PDF file: ' . $e->getMessage());
                throw $e;
            }
        }
        
        // For text files, just read the content
        if (in_array($extension, ['txt', 'csv', 'xml', 'json'])) {
            return [
                'type' => 'text',
                'data' => file_get_contents($tempPath),
                'mime' => $mimeType
            ];
        }
        
        // For other files, return basic info
        return [
            'type' => 'other',
            'data' => "File type: $extension",
            'mime' => $mimeType
        ];
    }
    
    /**
     * Generate alternative folder suggestions when file is already correct
     */
    protected function generateAlternativeFolders(File $file, User $user)
    {
        try {
            $alternatives = [];
            
            // Get all user folders
            $folders = $user->folders()
                ->with('parent.parent.parent')
                ->where('folders.id', '!=', $file->folder_id) // Exclude current folder - specify table name
                ->orderBy('name')
                ->get();
        
            // Get file date if available
            $fileDate = null;
            if ($file->ai_analysis && isset($file->ai_analysis['document_date'])) {
                $fileDate = \Carbon\Carbon::parse($file->ai_analysis['document_date']);
            }
            
            foreach ($folders as $folder) {
                $confidence = 0;
                $reasons = [];
                
                // Check if folder path contains relevant keywords
                $folderPath = strtolower($folder->full_path);
                $fileName = strtolower($file->original_name ?? $file->name);
                
                // Date matching
                if ($fileDate && preg_match('/(\d{4})/', $folderPath, $yearMatch)) {
                    if ($yearMatch[1] == $fileDate->year) {
                        $confidence += 30;
                        $reasons[] = "Year matches";
                    }
                }
                
                // Month matching
                $months = ['january', 'february', 'march', 'april', 'may', 'june', 
                          'july', 'august', 'september', 'october', 'november', 'december'];
                foreach ($months as $index => $month) {
                    if (str_contains($folderPath, $month) && $fileDate && $fileDate->month == ($index + 1)) {
                        $confidence += 20;
                        $reasons[] = "Month matches";
                        break;
                    }
                }
                
                // Type matching (Income/Expense)
                if ($file->ai_analysis && isset($file->ai_analysis['transaction_type'])) {
                    $transType = strtolower($file->ai_analysis['transaction_type']);
                    if (str_contains($folderPath, $transType)) {
                        $confidence += 25;
                        $reasons[] = "Transaction type matches";
                    }
                }
                
                // Only add folders with some confidence
                if ($confidence > 0) {
                    $alternatives[] = [
                        'folder_id' => $folder->id,
                        'folder_name' => $folder->name,
                        'folder_path' => $folder->full_path,
                        'confidence' => min($confidence, 75), // Cap at 75% since current is 100%
                        'reason' => implode(', ', $reasons)
                    ];
                }
            }
            
            // Sort by confidence and take top 3
            usort($alternatives, function($a, $b) {
                return $b['confidence'] - $a['confidence'];
            });
            
            return array_slice($alternatives, 0, 3);
            
        } catch (\Exception $e) {
            Log::error('Failed to generate alternative folders', [
                'file_id' => $file->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            // Return empty array on error to prevent breaking the analysis
            return [];
        }
    }
}
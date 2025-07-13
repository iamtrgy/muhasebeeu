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
            
            Log::info('Found user folders', [
                'count' => $folders->count(),
                'available_folders' => $folders->pluck('path')->toArray()
            ]);
            
            // Get user's companies for context
            $userCompanies = $user->companies()->pluck('name')->toArray();
            
            Log::info('User companies for AI analysis', [
                'user_id' => $user->id,
                'user_companies' => $userCompanies,
                'companies_count' => count($userCompanies)
            ]);
            
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
                    'content' => $userPrompt . "\n\nFile Content:\n" . $fileContent['data']
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
        
        // Ensure required fields exist - but allow null values for deletion suggestions
        if (!array_key_exists('suggested_folder_id', $parsed) || !array_key_exists('folder_name', $parsed)) {
            Log::error('AI response missing required fields', ['parsed' => $parsed]);
            throw new Exception('AI response missing required fields');
        }
        
        // If deletion is suggested, folder fields can be null
        if (isset($parsed['suggest_deletion']) && $parsed['suggest_deletion'] === true) {
            // This is valid - document should be deleted
            Log::info('AI suggests deletion for unrelated document', [
                'reason' => $parsed['deletion_reason'] ?? 'Not related to user companies'
            ]);
        } else if (empty($parsed['suggested_folder_id']) || empty($parsed['folder_name'])) {
            // If not suggesting deletion, we need valid folder info
            Log::error('AI response has null folder info without deletion suggestion', ['parsed' => $parsed]);
            throw new Exception('AI response missing folder information');
        }
        
        // Handle zero or very low confidence cases
        if (!isset($parsed['confidence']) || $parsed['confidence'] < 10) {
            Log::warning('Very low confidence AI analysis', [
                'confidence' => $parsed['confidence'] ?? 0,
                'reasoning' => $parsed['reasoning'] ?? 'No reasoning provided'
            ]);
            
            // Set minimum confidence and add warning
            $parsed['confidence'] = max($parsed['confidence'] ?? 0, 5);
            $parsed['low_confidence_warning'] = true;
            $parsed['reasoning'] = ($parsed['reasoning'] ?? '') . ' [LOW CONFIDENCE: File may be difficult to analyze]';
        }
        
        return $parsed;
    }
    
    /**
     * Build the prompt for OpenAI
     */
    protected function buildPrompt($fileContent, $folders, $filePath, $userCompanies = [], $currentFolder = null)
    {
        $fileName = basename($filePath);
        
        $prompt = "File: {$fileName}\n";
        
        if ($currentFolder) {
            $prompt .= "Currently in: {$currentFolder['path']}\n\n";
        }
        
        $prompt .= "VISION AI ANALYSIS: Carefully examine the document image and extract the following information:\n\n";
        
        $prompt .= "1. DOCUMENT TYPE - Identify what type of document this is\n\n";
        
        $prompt .= "2. SENDER/FROM (look for):\n";
        $prompt .= "- Company name in header/letterhead\n";
        $prompt .= "- 'From:', 'Sender:', or business name at top\n";
        $prompt .= "- Who issued this document\n\n";
        
        $prompt .= "3. RECEIVER/TO (look for):\n";
        $prompt .= "- 'To:', 'Bill to:', 'Customer:', or recipient name\n";
        $prompt .= "- Who is this document addressed to\n";
        $prompt .= "- Who should pay or received this\n\n";
        
        $prompt .= "4. DATE INFORMATION:\n";
        $prompt .= "- Look for invoice date, issue date, or transaction date\n";
        $prompt .= "- CRITICAL: Use the ACTUAL year from the document, NOT folder years\n";
        $prompt .= "- If document says 2024, use 2024 (even if folders have 2025)\n";
        $prompt .= "- Format as YYYY-MM-DD\n\n";
        
        $prompt .= "5. BUSINESS RELEVANCE CHECK:\n";
        $prompt .= "- Is this a business transaction between companies?\n";
        $prompt .= "- Does it involve money/services between businesses?\n";
        $prompt .= "- Or is it personal (travel, entertainment, personal purchases)?\n\n";
        
        if (!empty($userCompanies)) {
            $prompt .= "CRITICAL: These are the ONLY companies that belong to this user:\n";
            foreach ($userCompanies as $company) {
                $prompt .= "- {$company}\n";
            }
            $prompt .= "\nIMPORTANT: Any company names NOT listed above are NOT user companies.\n";
            $prompt .= "Do NOT assume ANY company belongs to user unless explicitly listed above.\n";
            $prompt .= "Even if company names exist in the system, only the ones listed above belong to this user.\n\n";
            
            $prompt .= "DECISION LOGIC:\n\n";
            
            $prompt .= "CRITICAL DELETION RULE:\n";
            $prompt .= "SUGGEST DELETION if the document is between companies that are NOT in the user company list.\n";
            $prompt .= "Even if those companies exist in the system, if they are not user companies, DELETE the document.\n\n";
            
            $prompt .= "SUGGEST DELETION if ANY apply:\n";
            $prompt .= "- SENDER company is NOT in user company list\n";
            $prompt .= "- RECEIVER company is NOT in user company list\n";
            $prompt .= "- Document is between two companies that both exist but neither belongs to user\n";
            $prompt .= "- Transaction does not involve user company as direct participant\n";
            $prompt .= "- Document is personal/individual transaction (not business related)\n";
            $prompt .= "- Entertainment, travel, personal expense receipts\n\n";
            
            $prompt .= "SUGGEST FOLDER only if ALL conditions are met:\n";
            $prompt .= "- SENDER company is in user company list OR RECEIVER company is in user company list\n";
            $prompt .= "- EXACTLY ONE of the parties (sender/receiver) must be a user company\n";
            $prompt .= "- Document is legitimate business transaction\n";
            $prompt .= "- User company is directly involved as sender OR receiver (not both)\n";
            $prompt .= "- Transaction type: User company SENT = Income, User company RECEIVED = Expense\n";
            $prompt .= "- Use DOCUMENT YEAR (not folder year) for date-based folder selection\n\n";
            
        }
        
        $prompt .= "Available folders:\n";
        foreach ($folders as $folder) {
            $prompt .= "ID {$folder['id']}: {$folder['path']}\n";
        }
        
        $prompt .= "\nFINAL INSTRUCTION:\n";
        $prompt .= "1. Look at the image carefully and identify ALL company names\n";
        $prompt .= "2. Identify SENDER company (who issued/sent the document)\n";
        $prompt .= "3. Identify RECEIVER company (who received/should pay)\n";
        $prompt .= "4. Read the document date and extract the ACTUAL year\n";
        $prompt .= "5. CRITICAL CHECK: Is the SENDER in the user company list above?\n";
        $prompt .= "6. CRITICAL CHECK: Is the RECEIVER in the user company list above?\n";
        $prompt .= "7. If BOTH checks are NO → suggest_deletion: true, deletion_reason: 'Transaction between third parties'\n";
        $prompt .= "8. If EITHER check is YES → find folder matching DOCUMENT year and transaction type\n";
        $prompt .= "9. NEVER suggest folders for companies not in the user list\n";
        $prompt .= "10. NEVER suggest folders with wrong years - use document date year only\n\n";
        
        $prompt .= "EXAMPLE SCENARIOS:\n";
        $prompt .= "- Invoice from Company A to Company B, user owns Company C → DELETE\n";
        $prompt .= "- Invoice from User Company to Company X → INCOME folder\n";
        $prompt .= "- Invoice from Company Y to User Company → EXPENSE folder\n";
        $prompt .= "- Invoice between any non-user companies → DELETE\n\n";
        
        $prompt .= "Return JSON with this exact format:\n";
        $prompt .= "{\n";
        $prompt .= '  "suggested_folder_id": <folder_id_number or null>,';
        $prompt .= '  "folder_name": "<folder_name or null>",';
        $prompt .= '  "folder_path": "<full_folder_path or null>",';
        $prompt .= '  "confidence": <0-100>,';
        $prompt .= '  "reasoning": "<explain what you found: FROM company, TO company, and why decision was made>",';
        $prompt .= '  "document_date": "<YYYY-MM-DD>",';
        $prompt .= '  "transaction_type": "<income|expense|not_related>",';
        $prompt .= '  "suggest_deletion": <true or false>,';
        $prompt .= '  "deletion_reason": "<reason if suggesting deletion>"';
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
            // Always generate alternative folders for user to consider
            if (!isset($result['analysis']['alternative_folders']) || empty($result['analysis']['alternative_folders'])) {
                $result['analysis']['alternative_folders'] = $this->generateAlternativeFolders($file, $user);
            }
            
            // Save the analysis to the file
            $file->update([
                'ai_analysis' => $result['analysis'],
                'ai_analyzed_at' => now(),
                'ai_suggestion_accepted' => false  // Reset to false for new analysis requiring review
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
            
            Log::info('Found user folders', [
                'count' => $folders->count(),
                'available_folders' => $folders->pluck('path')->toArray()
            ]);
            
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
        $fileSize = filesize($tempPath);
        
        // Handle zero-byte or very small files
        if ($fileSize < 100) {
            Log::warning('Very small file detected', [
                'fileName' => $fileName,
                'fileSize' => $fileSize
            ]);
            
            return [
                'type' => 'small_file',
                'data' => "This is a very small file ({$fileSize} bytes). Cannot analyze content reliably. Suggest placing in 'Other' folder.",
                'mime' => 'application/octet-stream',
                'file_size' => $fileSize
            ];
        }
        
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
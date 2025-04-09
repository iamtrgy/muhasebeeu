<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Spatie\PdfToText\Pdf;

class AIDocumentClassifierService
{
    protected Client $client;
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.claude.key');
        $this->apiUrl = config('services.claude.api_url', 'https://api.anthropic.com/v1/messages');

        Log::info('Initializing AIDocumentClassifierService', [
            'api_key_exists' => !empty($this->apiKey),
            'api_url' => $this->apiUrl
        ]);

        if (empty($this->apiKey)) {
            throw new \Exception('Claude API key is not configured in config/services.php or .env');
        }

        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'anthropic-version' => '2023-06-01',
                'x-api-key' => $this->apiKey
            ]
        ]);
    }

    /**
     * Extracts text content from an uploaded file.
     * Supports plain text and PDF files.
     *
     * @param UploadedFile $file
     * @return string|null
     */
    public function extractTextFromFile(UploadedFile $file): ?string
    {
        $mimeType = $file->getMimeType();
        $path = $file->getRealPath();

        Log::info('Attempting to extract text from file', [
            'mime_type' => $mimeType,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize()
        ]);

        try {
            if ($mimeType === 'application/pdf') {
                Log::info('Extracting text from PDF file');
                
                $text = (new Pdf())
                    ->setPdf($path)
                    ->text();

                // Log the first 500 characters of extracted text
                Log::info('Successfully extracted text from PDF', [
                    'text_preview' => substr($text, 0, 500),
                    'total_length' => strlen($text)
                ]);

                return $text;
            } elseif (str_starts_with($mimeType, 'text/')) {
                Log::info('Reading text from plain text file');
                return file_get_contents($path);
            } else {
                Log::warning('Unsupported file type for text extraction', [
                    'mime_type' => $mimeType
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Failed to extract text from file', [
                'error' => $e->getMessage(),
                'mime_type' => $mimeType,
                'file_name' => $file->getClientOriginalName()
            ]);
            return null;
        }
    }

    /**
     * Clean and normalize text for API request
     */
    private function cleanText(string $text): string
    {
        // Remove any BOM and invalid UTF-8 characters
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        // Replace common problematic characters
        $text = str_replace([
            'Ã¼', 'Ã¶', 'Ã§', 'Ä±', 'ÅŸ', 'ÄŸ', 'Ã‡', 'Ã–', 'Ãœ', 'Ä°', 'ÅŸ', 'ÄŸ',
            'â€™', 'â€"', 'â€"', 'â€˜', 'â€™', 'â€œ', 'â€', '�'
        ], [
            'ü', 'ö', 'ç', 'ı', 'ş', 'ğ', 'Ç', 'Ö', 'Ü', 'İ', 'Ş', 'Ğ',
            "'", '-', '-', "'", "'", '"', '"', ''
        ], $text);
        
        // Normalize whitespace
        $text = preg_replace('/\s+/', ' ', trim($text));
        
        return $text;
    }

    /**
     * Asks Claude AI to classify the document into one of the provided folders.
     * 
     * @param string $textContent The extracted text content of the document.
     * @param array $folders An array of folder objects/arrays, each having at least 'id' and 'name'.
     * @param string $companyName
     * @return int|null The ID of the suggested folder, or null on failure/error.
     */
    public function classifyDocument(string $textContent, array $folders, string $companyName = null): ?int
    {
        if (empty($textContent) || empty($folders)) {
            Log::warning("Cannot classify document", [
                'text_empty' => empty($textContent),
                'folders_empty' => empty($folders)
            ]);
            return null;
        }
        
        Log::info('Starting document classification', [
            'text_length' => strlen($textContent),
            'num_folders' => count($folders)
        ]);
        
        // Clean and normalize the text
        $textContent = $this->cleanText($textContent);

        // Build a more focused prompt
        $prompt = $this->buildPrompt($textContent, $folders, $companyName);

        try {
            Log::info('Sending request to Claude API', [
                'prompt_length' => strlen($prompt),
                'folder_count' => count($folders)
            ]);
            
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'anthropic-version' => '2023-06-01',
                    'x-api-key' => $this->apiKey
                ],
                'json' => [
                    'model' => 'claude-3-sonnet-20240229',
                    'max_tokens' => 1024,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ]
                ]
            ]);
            
            $responseBody = $response->getBody()->getContents();
            
            Log::debug('Claude API raw response', [
                'status_code' => $response->getStatusCode(),
                'response' => json_decode($responseBody, true),
                'response_structure' => array_keys(json_decode($responseBody, true)),
                'content_type' => $response->getHeaderLine('Content-Type')
            ]);
            
            $data = json_decode($responseBody, true);
            
            if (isset($data['content'][0]['text'])) {
                $aiResponse = trim($data['content'][0]['text']);
                
                // Extract folder ID from the response
                $folderId = $this->extractFolderId($aiResponse);
                
                if ($folderId) {
                    Log::debug('Found folder ID in response', [
                        'id' => $folderId,
                        'response' => $aiResponse
                    ]);
                    
                    // Find folder name for logging
                    $folderName = 'Unknown';
                    foreach ($folders as $folder) {
                        if ($folder['id'] == $folderId) {
                            $folderName = $folder['name'];
                            break;
                        }
                    }
                    
                    Log::info('Claude suggested folder', [
                        'folder_id' => $folderId,
                        'folder_name' => $folderName
                    ]);
                    
                    // Validate that the suggested folder is a document type folder
                    $validatedFolderId = $this->validateSuggestedFolder($folderId, $folders);
                    
                    if ($validatedFolderId !== $folderId) {
                        Log::warning('Corrected folder selection to document type folder', [
                            'original_folder_id' => $folderId,
                            'corrected_folder_id' => $validatedFolderId
                        ]);
                    }
                    
                    return $validatedFolderId;
                }
            }
            
            Log::error('Failed to extract folder ID from Claude response', [
                'response' => $aiResponse ?? 'No response'
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error classifying document with Claude API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }

    /**
     * Validate and potentially correct the suggested folder ID
     * Ensures we select a document type folder, not just a year or month folder
     * 
     * @param int $folderId Suggested folder ID from AI
     * @param array $folders All available folders
     * @return int Corrected folder ID or original if already valid
     */
    private function validateSuggestedFolder(int $folderId, array $folders): int
    {
        // Get the suggested folder
        $suggestedFolder = null;
        foreach ($folders as $folder) {
            if ($folder['id'] == $folderId) {
                $suggestedFolder = $folder;
                break;
            }
        }
        
        if (!$suggestedFolder) {
            Log::error('Suggested folder ID not found in available folders', [
                'folder_id' => $folderId
            ]);
            
            // Find a fallback folder - try to find a document type folder
            $fallbackFolder = $this->findFallbackFolder($folders);
            if ($fallbackFolder) {
                Log::info('Using fallback folder since suggested folder does not exist', [
                    'original_folder_id' => $folderId,
                    'fallback_folder_id' => $fallbackFolder['id'],
                    'fallback_folder_name' => $fallbackFolder['name']
                ]);
                return $fallbackFolder['id'];
            }
            
            // If we couldn't find a document type folder, just use the first available folder
            if (!empty($folders)) {
                Log::info('Using first available folder as last resort', [
                    'folder_id' => $folders[0]['id'],
                    'folder_name' => $folders[0]['name']
                ]);
                return $folders[0]['id'];
            }
            
            return $folderId; // Return original as a last resort, though this will likely fail
        }
        
        // Check if folder properties exist before using them
        $isDocumentTypeFolder = isset($suggestedFolder['is_document_type_folder']) && $suggestedFolder['is_document_type_folder'];
        $isMonthFolder = isset($suggestedFolder['is_month_folder']) && $suggestedFolder['is_month_folder'];
        $isYearFolder = isset($suggestedFolder['is_year_folder']) && $suggestedFolder['is_year_folder'];
        
        // If it's already a document type folder, return it
        if ($isDocumentTypeFolder) {
            return $folderId;
        }
        
        // Log folder details for debugging
        Log::warning('AI suggested a non-document type folder', [
            'folder_id' => $folderId,
            'folder_name' => $suggestedFolder['name'],
            'folder_properties' => array_filter($suggestedFolder, function($key) {
                return in_array($key, ['is_document_type_folder', 'is_month_folder', 'is_year_folder', 'folder_type']);
            }, ARRAY_FILTER_USE_KEY)
        ]);
        
        // If it's a month folder, try to find an appropriate document type folder under it
        if ($isMonthFolder) {
            $documentTypeFolder = $this->findDocumentTypeFolder($suggestedFolder, $folders);
            if ($documentTypeFolder) {
                Log::info('Found document type folder under suggested month', [
                    'original_folder' => $suggestedFolder['name'],
                    'document_type_folder' => $documentTypeFolder['name']
                ]);
                return $documentTypeFolder['id'];
            }
        }
        
        // If it's a year folder, try to find appropriate month and document type folders
        if ($isYearFolder) {
            // Find document type folders in the system
            $fallbackFolder = $this->findFallbackFolder($folders);
            if ($fallbackFolder) {
                Log::info('Using fallback document type folder instead of year folder', [
                    'original_year_folder' => $suggestedFolder['name'],
                    'fallback_folder' => $fallbackFolder['name']
                ]);
                return $fallbackFolder['id'];
            }
        }
        
        // For other non-document type folders (like "Expense" category folders),
        // find children that are document type folders or use fallback
        $childDocTypeFolder = $this->findDocumentTypeFolderUnderParent($suggestedFolder['id'], $folders);
        if ($childDocTypeFolder) {
            Log::info('Found document type folder under suggested category', [
                'original_folder' => $suggestedFolder['name'],
                'document_type_folder' => $childDocTypeFolder['name']
            ]);
            return $childDocTypeFolder['id'];
        }
        
        // If we still don't have a valid document type folder, try to find any document type folder
        $fallbackFolder = $this->findFallbackFolder($folders);
        if ($fallbackFolder) {
            Log::info('Using fallback document type folder', [
                'original_folder' => $suggestedFolder['name'],
                'fallback_folder' => $fallbackFolder['name']
            ]);
            return $fallbackFolder['id'];
        }
        
        // Last resort: return the original folder ID
        Log::warning('Could not find a suitable document type folder, using original suggestion', [
            'folder_id' => $folderId
        ]);
        return $folderId;
    }
    
    /**
     * Find an appropriate document type folder (Expense/Income) under a month folder
     *
     * @param array $monthFolder The month folder to look under
     * @param array $allFolders All available folders
     * @return array|null The document type folder or null if not found
     */
    private function findDocumentTypeFolder(array $monthFolder, array $allFolders): ?array
    {
        $expenseFolder = null;
        $incomeFolder = null;
        
        foreach ($allFolders as $folder) {
            if (!empty($folder['is_document_type_folder']) && 
                isset($folder['parent_id']) && $folder['parent_id'] == $monthFolder['id']) {
                
                if (strtolower($folder['name']) == 'expense') {
                    $expenseFolder = $folder;
                } else if (strtolower($folder['name']) == 'income') {
                    $incomeFolder = $folder;
                }
            }
        }
        
        // Default to expense folder as most documents are likely expenses
        return $expenseFolder ?: $incomeFolder;
    }

    private function buildPrompt(string $text, array $folders, string $companyName = null): string
    {
        // Extract date from text
        $date = $this->extractDate($text);
        $dateInfo = null;
        
        if ($date) {
            try {
                $dateObj = new \DateTime($date);
                $year = $dateObj->format('Y');
                $month = $dateObj->format('F');
                $dateInfo = "Date: {$date} (Year: {$year}, Month: {$month})";
            } catch (\Exception $e) {
                $dateInfo = "Date: {$date} (unable to parse)";
            }
        } else {
            $dateInfo = "Date: Unknown";
        }
        
        // Analyze document type
        $isInvoice = stripos($text, 'invoice') !== false || stripos($text, 'bill') !== false;
        $isExpense = false;
        $isIncome = false;
        
        // If we have a company name, use it to determine expense vs income
        if ($companyName) {
            Log::info('Using company name to determine document category', [
                'company_name' => $companyName
            ]);
            
            // Check if our company is in the "Bill To" section (we're being billed = expense)
            if (stripos($text, 'bill to: ' . $companyName) !== false || 
                stripos($text, 'bill to:' . $companyName) !== false ||
                stripos($text, 'bill to ' . $companyName) !== false) {
                $isExpense = true;
                Log::info('Determined document is an expense (company is being billed)');
            } 
            // Check if our company is in the "From" section (we're billing someone else = income)
            elseif (stripos($text, 'from: ' . $companyName) !== false || 
                   stripos($text, 'from ' . $companyName) !== false ||
                   (stripos($text, 'bill from') !== false && stripos($text, $companyName) !== false)) {
                $isIncome = true;
                Log::info('Determined document is income (company is billing someone else)');
            }
            // Fall back to checking if document mentions both the vendor and our company
            else {
                // Try to find vendor info
                preg_match('/vendor\s*(?:name|id)?:?\s*([^\n\r,]+)/i', $text, $vendorMatches);
                $vendorName = !empty($vendorMatches[1]) ? trim($vendorMatches[1]) : null;
                
                if ($vendorName && stripos($vendorName, $companyName) === false) {
                    // If vendor is mentioned and it's not our company, it's probably an expense
                    $isExpense = true;
                    Log::info('Determined document is an expense based on vendor', [
                        'vendor_name' => $vendorName
                    ]);
                } else {
                    // Default to expense for invoices if we can't clearly determine
                    $isExpense = true;
                    Log::info('Defaulting to expense category');
                }
            }
        } else {
            // If no company name provided, use simplified logic
            $isExpense = $isInvoice && stripos($text, 'bill to') !== false; 
            $isIncome = $isInvoice && stripos($text, 'bill from') !== false;
            
            // Default to expense if unclear
            if ($isInvoice && !$isExpense && !$isIncome) {
                $isExpense = true;
            }
        }
        
        $docType = $isInvoice ? 'Invoice' : 'Unknown';
        $docCategory = $isExpense ? 'EXPENSE' : ($isIncome ? 'INCOME' : 'Unknown');
        
        $prompt = "You are an AI assistant helping to classify accounting documents into the correct folders.\n";
        $prompt .= "The folder structure follows this hierarchy: Company > Year > Month > Document Type\n\n";
        
        $prompt .= "Document Text:\n---\n{$text}\n---\n\n";
        
        $prompt .= "Document Analysis:\n";
        $prompt .= "- Type: {$docType}\n";
        $prompt .= "- Category: {$docCategory}\n";
        $prompt .= "- {$dateInfo}\n\n";
        
        if ($companyName) {
            $prompt .= "- Company Name: {$companyName}\n\n";
        }
        
        // Target year based on document date
        $targetYear = $date ? substr($date, -4) : null;
        
        $prompt .= "Available Folders:\n---\n";

        // Group folders by type for better context
        $yearFolders = [];
        $monthFolders = [];
        $typeFolders = [];
        $otherFolders = [];

        // Special case folders
        $targetYearFolder = null;
        $targetMonthFolder = null;
        
        foreach ($folders as $f) {
            $folderInfo = "ID: {$f['id']}, Name: {$f['name']}, Path: {$f['path']}";
            
            // Mark target year folder
            if (!empty($f['is_year_folder']) && $targetYear && $f['name'] === $targetYear) {
                $targetYearFolder = $f;
                $yearFolders[] = $folderInfo . " (DOCUMENT YEAR)";
            }
            elseif (!empty($f['is_year_folder'])) {
                $yearFolders[] = $folderInfo . " (Year)";
            }
            elseif (!empty($f['is_month_folder'])) {
                // If this month matches document month and parent is target year, mark it
                if ($date && strcasecmp($f['name'], $month) === 0 && 
                    isset($f['parent_id']) && $targetYearFolder && $f['parent_id'] == $targetYearFolder['id']) {
                    $targetMonthFolder = $f;
                    $monthFolders[] = $folderInfo . " (DOCUMENT MONTH)";
                } else {
                    $monthFolders[] = $folderInfo . " (Month)";
                }
            }
            elseif (!empty($f['is_document_type_folder'])) {
                if ($docCategory == 'EXPENSE' && strtolower($f['name']) == 'expense' && 
                    isset($f['parent_id']) && $targetMonthFolder && $f['parent_id'] == $targetMonthFolder['id']) {
                    $typeFolders[] = $folderInfo . " (DOCUMENT TYPE - BEST MATCH)";
                }
                elseif ($docCategory == 'INCOME' && strtolower($f['name']) == 'income' && 
                    isset($f['parent_id']) && $targetMonthFolder && $f['parent_id'] == $targetMonthFolder['id']) {
                    $typeFolders[] = $folderInfo . " (DOCUMENT TYPE - BEST MATCH)";
                }
                else {
                    $typeFolders[] = $folderInfo . " (Type: {$f['folder_type']})";
                }
            }
            else {
                $otherFolders[] = $folderInfo;
            }
        }

        if ($yearFolders) {
            $prompt .= "\nYear Folders:\n" . implode("\n", $yearFolders);
        }
        if ($monthFolders) {
            $prompt .= "\n\nMonth Folders:\n" . implode("\n", $monthFolders);
        }
        if ($typeFolders) {
            $prompt .= "\n\nDocument Type Folders:\n" . implode("\n", $typeFolders);
        }
        if ($otherFolders) {
            $prompt .= "\n\nOther Folders:\n" . implode("\n", $otherFolders);
        }

        $prompt .= "\n\nInstructions:\n";
        $prompt .= "1. This document has been identified as a {$docType} type document from {$date}\n";
        $prompt .= "2. It appears to be a {$docCategory} document\n";
        $prompt .= "3. You MUST follow this hierarchical path when choosing a folder:\n";
        $prompt .= "   a. First, select the year folder matching the document date ({$targetYear})\n";
        $prompt .= "   b. Within that year, select the correct month folder ({$month})\n";
        $prompt .= "   c. Within that month, select the document type folder (Expense/Income)\n";
        $prompt .= "4. The chosen folder MUST match ALL these criteria\n";
        $prompt .= "5. IMPORTANT: You MUST select a document type folder (Expense/Income) as the final destination\n";
        $prompt .= "6. DO NOT select just a year or month folder - you must go all the way to a document type folder\n";
        $prompt .= "7. If document is from {$targetYear} and is an {$docCategory}, look for a folder named 'Expense' or 'Income' within the {$month} folder\n";
        $prompt .= "8. Return ONLY the folder ID number, nothing else\n\n";
        $prompt .= "Respond with just the folder ID number, nothing else.";

        if ($companyName) {
            $prompt .= "\n\nCompany Name: {$companyName}";
        }

        return $prompt;
    }

    /**
     * Extract a folder ID from the AI response text
     *
     * @param string $responseText
     * @return int|null
     */
    private function extractFolderId(string $responseText): ?int
    {
        // Clean the response text to extract the folder ID
        $responseText = trim($responseText);
        
        // If it's just a number, return it directly
        if (preg_match('/^\d+$/', $responseText)) {
            return (int) $responseText;
        }
        
        // Look for folder ID patterns like "FolderID: 123" or "Folder ID: 123"
        if (preg_match('/folder\s*id:?\s*(\d+)/i', $responseText, $matches)) {
            return (int) $matches[1];
        }
        
        // Try to extract any number from the response as a last resort
        if (preg_match('/\b(\d+)\b/', $responseText, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }

    private function extractDate(string $text): ?string
    {
        // Common date formats
        $patterns = [
            '/\b\d{1,2}[-\/\.]\d{1,2}[-\/\.]\d{2,4}\b/', // DD/MM/YYYY
            '/\b\d{4}[-\/\.]\d{1,2}[-\/\.]\d{1,2}\b/', // YYYY/MM/DD
            '/\b(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]* \d{1,2},? \d{4}\b/i', // Month DD, YYYY
            '/\b\d{1,2} (?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]* \d{4}\b/i', // DD Month YYYY
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return $matches[0];
            }
        }

        return null;
    }

    private function findFallbackFolder(array $folders): ?array
    {
        // Try to find a document type folder
        foreach ($folders as $folder) {
            if (isset($folder['is_document_type_folder']) && $folder['is_document_type_folder']) {
                return $folder;
            }
        }
        
        // If no document type folder found, look for leaf folders (folders without children)
        $parentIds = array_column($folders, 'parent_id');
        foreach ($folders as $folder) {
            // If this folder ID is not a parent of any other folder, it might be a leaf folder
            if (!in_array($folder['id'], $parentIds)) {
                return $folder;
            }
        }
        
        return null;
    }

    private function findDocumentTypeFolderUnderParent(int $parentId, array $folders): ?array
    {
        foreach ($folders as $folder) {
            if (isset($folder['is_document_type_folder']) && $folder['is_document_type_folder'] && 
                isset($folder['parent_id']) && $folder['parent_id'] == $parentId) {
                return $folder;
            }
        }
        
        // If no document type folder found, look for leaf folders under this parent
        foreach ($folders as $folder) {
            if (isset($folder['parent_id']) && $folder['parent_id'] == $parentId) {
                // Check if this is a leaf folder (not a parent of any other folder)
                $isLeaf = true;
                foreach ($folders as $potentialChild) {
                    if (isset($potentialChild['parent_id']) && $potentialChild['parent_id'] == $folder['id']) {
                        $isLeaf = false;
                        break;
                    }
                }
                
                if ($isLeaf) {
                    return $folder;
                }
            }
        }
        
        return null;
    }
}

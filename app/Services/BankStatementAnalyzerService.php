<?php

namespace App\Services;

use App\Models\File;
use App\Models\BankTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class BankStatementAnalyzerService
{
    protected $openAiApiKey;
    protected $openAiBaseUrl = 'https://api.openai.com/v1';
    protected $model;

    public function __construct()
    {
        $this->openAiApiKey = config('services.openai.key');
        $this->model = config('services.openai.model', 'gpt-4o');
        
        if (empty($this->openAiApiKey)) {
            Log::error('OpenAI API key is not configured');
            throw new \Exception('OpenAI API key is not configured');
        }
    }

    /**
     * Analyze a bank statement file and extract transactions
     */
    public function analyzeStatement(File $file, $force = false)
    {
        try {
            // Check if file is already analyzed
            if ($file->statement_analyzed && !$force) {
                return [
                    'success' => false,
                    'message' => 'Statement has already been analyzed',
                    'transactions' => $file->bankTransactions
                ];
            }
            
            // If forcing re-analysis, delete existing transactions
            if ($force && $file->statement_analyzed) {
                Log::info('Deleting existing transactions for re-analysis', ['file_id' => $file->id]);
                $file->bankTransactions()->delete();
                $file->update([
                    'statement_analyzed' => false,
                    'statement_analysis_date' => null,
                    'transaction_count' => null
                ]);
            }

            // Get file content
            $fileContent = $this->getFileContent($file);
            if (!$fileContent) {
                throw new \Exception('Unable to read file content');
            }

            // Extract and prepare file content based on type
            $fileContent = $this->extractText($file, $fileContent);
            if (!$fileContent) {
                throw new \Exception('Unable to process file content');
            }

            // Analyze with AI
            $analysis = $this->analyzeWithAI($fileContent, $file);
            if (!$analysis || !isset($analysis['transactions'])) {
                throw new \Exception('AI analysis failed');
            }

            // Save transactions to database
            $transactions = $this->saveTransactions($file, $analysis['transactions']);

            // Update file status
            $file->update([
                'statement_analyzed' => true,
                'statement_analysis_date' => now(),
                'transaction_count' => count($transactions)
            ]);

            // Try to auto-match transactions with invoices
            $this->autoMatchTransactions($transactions);

            return [
                'success' => true,
                'message' => 'Statement analyzed successfully',
                'transaction_count' => count($transactions),
                'transactions' => $transactions
            ];

        } catch (\Exception $e) {
            Log::error('Bank statement analysis failed', [
                'file_id' => $file->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Analysis failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get file content from storage
     */
    protected function getFileContent(File $file)
    {
        try {
            // For Bunny storage, we need to download via URL
            $response = Http::get($file->url);
            
            if ($response->successful()) {
                return $response->body();
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get file content', [
                'file_id' => $file->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Extract text from file based on type
     */
    protected function extractText(File $file, $content)
    {
        $mimeType = $file->mime_type;
        $extension = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
        
        // For images, just encode them
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
            return [
                'type' => 'image',
                'data' => base64_encode($content),
                'mime' => $mimeType
            ];
        }
        
        // For PDFs, convert ALL pages to images
        if ($extension === 'pdf') {
            try {
                // Save content to temporary file for processing
                $tempPdfPath = sys_get_temp_dir() . '/bank_statement_' . uniqid() . '.pdf';
                file_put_contents($tempPdfPath, $content);
                
                // Get PDF page count first
                $pageCount = $this->getPdfPageCount($tempPdfPath);
                Log::info('Bank statement PDF has ' . $pageCount . ' pages');
                
                // Process multiple pages
                $allPages = [];
                $maxPagesToProcess = min($pageCount, 10); // Limit to 10 pages to avoid too large requests
                
                for ($page = 1; $page <= $maxPagesToProcess; $page++) {
                    Log::info('Processing PDF page ' . $page . ' of ' . $pageCount);
                    
                    if (class_exists('Imagick')) {
                        // First try using Ghostscript directly for better PDF rendering
                        $tempImagePath = sys_get_temp_dir() . '/pdf_to_image_' . uniqid() . '_page' . $page . '.png';
                        $gsCommand = sprintf(
                            'gs -dNOPAUSE -dBATCH -sDEVICE=png16m -r300 -dFirstPage=%d -dLastPage=%d -sOutputFile=%s %s 2>&1',
                            $page,
                            $page,
                            escapeshellarg($tempImagePath),
                            escapeshellarg($tempPdfPath)
                        );
                        
                        exec($gsCommand, $output, $returnCode);
                        
                        if ($returnCode === 0 && file_exists($tempImagePath)) {
                            $imageData = file_get_contents($tempImagePath);
                            $allPages[] = base64_encode($imageData);
                            unlink($tempImagePath);
                        } else {
                            // Fallback to Imagick
                            $imagick = new \Imagick();
                            $imagick->setResolution(300, 300);
                            $imagick->readImage($tempPdfPath . '[' . ($page - 1) . ']');
                            $imagick->setImageFormat('png');
                            $imagick->setImageBackgroundColor('white');
                            $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                            $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
                            
                            $allPages[] = base64_encode($imagick->getImageBlob());
                            $imagick->destroy();
                        }
                    }
                }
                
                unlink($tempPdfPath);
                
                Log::info('Converted ' . count($allPages) . ' PDF pages to images');
                
                return [
                    'type' => 'multi_image',
                    'pages' => $allPages,
                    'mime' => 'image/png',
                    'page_count' => count($allPages),
                    'total_pages' => $pageCount
                ];
            } catch (\Exception $e) {
                Log::error('Failed to convert PDF to images: ' . $e->getMessage());
                throw $e;
            }
        }
        
        // For text files, CSV, etc.
        if (in_array($extension, ['txt', 'csv', 'xml', 'json'])) {
            return [
                'type' => 'text',
                'data' => $content,
                'mime' => $mimeType
            ];
        }
        
        // For other files, return as text
        return [
            'type' => 'text',
            'data' => $content,
            'mime' => $mimeType
        ];
    }

    /**
     * Analyze text with OpenAI
     */
    protected function analyzeWithAI($fileContent, File $file)
    {
        $systemPrompt = 'You are a financial analyst expert at extracting transaction data from bank statements. Extract all transactions and return them in JSON format.';
        $userPrompt = $this->buildPrompt($file);
        
        try {
            // Handle multi-page PDFs
            if ($fileContent['type'] === 'multi_image') {
                Log::info('Analyzing multi-page bank statement', [
                    'page_count' => $fileContent['page_count'],
                    'total_pages' => $fileContent['total_pages']
                ]);
                
                $allTransactions = [];
                $statementInfo = null;
                
                // Process each page
                foreach ($fileContent['pages'] as $pageIndex => $pageData) {
                    $pageNum = $pageIndex + 1;
                    Log::info("Processing page {$pageNum} of {$fileContent['page_count']}");
                    
                    // Modify prompt for continuation pages
                    $pagePrompt = $userPrompt;
                    if ($pageIndex > 0) {
                        $pagePrompt .= "\n\nThis is page {$pageNum} of the statement. Continue extracting transactions from the previous pages.";
                    }
                    
                    // Use the same format as AIDocumentAnalyzer
                    $requestData = [
                        'model' => 'gpt-4.1-mini',
                        'input' => [
                            [
                                'role' => 'user',
                                'content' => [
                                    [
                                        'type' => 'input_text',
                                        'text' => $systemPrompt . "\n\n" . $pagePrompt
                                    ],
                                    [
                                        'type' => 'input_image',
                                        'image_url' => "data:{$fileContent['mime']};base64,{$pageData}"
                                    ]
                                ]
                            ]
                        ]
                    ];
                    
                    Log::info("Sending bank statement page {$pageNum} to OpenAI /v1/responses", [
                        'model' => 'gpt-4.1-mini',
                        'has_image' => true,
                        'mime' => $fileContent['mime'],
                        'image_data_length' => strlen($pageData)
                    ]);
                    
                    $response = Http::timeout(60)
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . $this->openAiApiKey,
                            'Content-Type' => 'application/json',
                        ])->post('https://api.openai.com/v1/responses', $requestData);
                    
                    if ($response->successful()) {
                        $pageResult = $this->parseAIResponse($response);
                        
                        // Collect transactions from this page
                        if (isset($pageResult['transactions'])) {
                            $allTransactions = array_merge($allTransactions, $pageResult['transactions']);
                        }
                        
                        // Keep statement info from first page
                        if ($pageIndex === 0 && !$statementInfo) {
                            $statementInfo = array_diff_key($pageResult, ['transactions' => null]);
                        }
                    }
                }
                
                // Combine all results
                $finalResult = $statementInfo ?: [];
                $finalResult['transactions'] = $allTransactions;
                
                Log::info('Multi-page analysis complete', [
                    'total_transactions' => count($allTransactions),
                    'pages_processed' => $fileContent['page_count']
                ]);
                
                return $finalResult;
            }
            
            // For single image content (PDFs converted to images), use same format as AIDocumentAnalyzer
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
                
                Log::info('Sending bank statement to OpenAI /v1/responses', [
                    'model' => 'gpt-4.1-mini',
                    'has_image' => true,
                    'mime' => $fileContent['mime'],
                    'image_data_length' => strlen($fileContent['data']),
                    'api_endpoint' => 'https://api.openai.com/v1/responses'
                ]);
                
                $response = Http::timeout(60)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->openAiApiKey,
                        'Content-Type' => 'application/json',
                    ])->post('https://api.openai.com/v1/responses', $requestData);
                
            } else {
                // For text content (CSV, etc.), use regular chat completions
                $response = Http::timeout(60)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->openAiApiKey,
                        'Content-Type' => 'application/json',
                    ])->post($this->openAiBaseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt
                        ],
                        [
                            'role' => 'user',
                            'content' => $userPrompt . "\n\nBank statement content:\n" . $fileContent['data']
                        ]
                    ],
                    'temperature' => 0.1,
                    'max_tokens' => 4000,
                    'response_format' => ['type' => 'json_object']
                ]);
            }

            if ($response->successful()) {
                return $this->parseAIResponse($response);
            }

            $errorBody = $response->body();
            $statusCode = $response->status();
            Log::error('OpenAI API request failed', [
                'status' => $statusCode,
                'body' => $errorBody
            ]);
            
            throw new \Exception("OpenAI API request failed with status {$statusCode}: {$errorBody}");
        } catch (\Exception $e) {
            Log::error('AI analysis failed', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Parse AI response and extract data
     */
    protected function parseAIResponse($response)
    {
        $result = $response->json();
        
        Log::info('OpenAI API response received', [
            'status' => $response->status(),
            'response_keys' => array_keys($result ?? [])
        ]);
        
        // Handle different response formats (same as AIDocumentAnalyzer)
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
            throw new \Exception('Unexpected response format from OpenAI');
        }
        
        Log::info('Raw AI response content', [
            'content_length' => strlen($content),
            'first_500_chars' => substr($content, 0, 500)
        ]);
        
        // Strip markdown code blocks if present
        $content = trim($content);
        if (preg_match('/^```(?:json)?\s*\n(.*)\n```$/s', $content, $matches)) {
            $content = trim($matches[1]);
        }
        
        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to decode AI response', [
                'json_error' => json_last_error_msg(),
                'content' => $content
            ]);
            throw new \Exception('Failed to parse AI response as JSON');
        }
        
        Log::info('Decoded AI response', [
            'has_transactions' => isset($decoded['transactions']),
            'transaction_count' => isset($decoded['transactions']) ? count($decoded['transactions']) : 0,
            'first_transaction' => isset($decoded['transactions'][0]) ? $decoded['transactions'][0] : null
        ]);
        
        return $decoded;
    }

    /**
     * Build the prompt for AI analysis
     */
    protected function buildPrompt(File $file)
    {
        return "Analyze this bank statement and extract all transactions. For each transaction, provide:
- transaction_date (format: YYYY-MM-DD)
- description (original transaction description)
- amount (numeric value, positive number)
- type (either 'debit' for money out or 'credit' for money in)
- balance (IMPORTANT: the account balance AFTER this transaction - this is usually shown in a separate column)
- reference_number (transaction reference if available)
- category (suggest a category like: Salary, Utilities, Office Supplies, Bank Fees, etc.)
- notes (any AI insights about this transaction, like 'Recurring monthly payment' or 'International transfer with conversion fee')

Return the result as a JSON object with a 'transactions' array containing all extracted transactions.
Also include:
- statement_period_start (YYYY-MM-DD)
- statement_period_end (YYYY-MM-DD)
- currency (3-letter code like EUR, USD)
- opening_balance (if available)
- closing_balance (if available)

Important: 
- Ensure all amounts are positive numbers
- Use 'debit' for money going out, 'credit' for money coming in
- Include ALL transactions, even small bank fees
- If you can't determine something, use null";
    }

    /**
     * Save transactions to database
     */
    protected function saveTransactions(File $file, array $transactionsData)
    {
        $transactions = [];
        
        // Handle different response structures
        if (isset($transactionsData['transactions'])) {
            // Standard structure with transactions array
            $transactionList = $transactionsData['transactions'];
            $currency = $transactionsData['currency'] ?? 'EUR';
        } elseif (isset($transactionsData[0]) && is_array($transactionsData[0])) {
            // Direct array of transactions
            $transactionList = $transactionsData;
            $currency = 'EUR'; // Default currency
        } else {
            // Unexpected structure
            Log::error('Unexpected transaction data structure', [
                'keys' => array_keys($transactionsData),
                'sample' => array_slice($transactionsData, 0, 2)
            ]);
            $transactionList = [];
            $currency = 'EUR';
        }
        
        Log::info('Saving transactions to database', [
            'file_id' => $file->id,
            'currency' => $currency,
            'transaction_count' => count($transactionList)
        ]);
        
        foreach ($transactionList as $index => $data) {
            Log::info("Processing transaction {$index}", [
                'raw_amount' => $data['amount'] ?? null,
                'abs_amount' => isset($data['amount']) ? abs($data['amount']) : null,
                'type' => $data['type'] ?? null,
                'description' => $data['description'] ?? null
            ]);
            
            // Parse amount more carefully
            $amount = 0;
            if (isset($data['amount'])) {
                // Handle various formats (string with comma, float, etc)
                $amountStr = str_replace(',', '.', (string)$data['amount']);
                $amountStr = preg_replace('/[^0-9.-]/', '', $amountStr);
                $amount = abs((float)$amountStr);
            }
            
            Log::info("Creating transaction in DB", [
                'original_amount' => $data['amount'] ?? null,
                'parsed_amount' => $amount,
                'description' => substr($data['description'] ?? '', 0, 50)
            ]);
            
            $transaction = BankTransaction::create([
                'file_id' => $file->id,
                'company_id' => $file->folder->company_id,
                'transaction_date' => Carbon::parse($data['transaction_date']),
                'description' => $data['description'],
                'amount' => $amount,
                'currency' => $currency,
                'type' => $data['type'],
                'balance' => $data['balance'] ?? null,
                'reference_number' => $data['reference_number'] ?? null,
                'category' => $data['category'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending'
            ]);
            
            $transactions[] = $transaction;
        }
        
        return $transactions;
    }

    /**
     * Get PDF page count
     */
    protected function getPdfPageCount($pdfPath)
    {
        try {
            // Try using Ghostscript first
            $command = sprintf('gs -q -dNODISPLAY -c "(%s) (r) file runpdfbegin pdfpagecount = quit" 2>&1', escapeshellarg($pdfPath));
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && !empty($output[0]) && is_numeric($output[0])) {
                return (int)$output[0];
            }
            
            // Fallback to Imagick
            if (class_exists('Imagick')) {
                $imagick = new \Imagick($pdfPath);
                $pageCount = $imagick->getNumberImages();
                $imagick->clear();
                $imagick->destroy();
                return $pageCount;
            }
            
            // Default to 1 if we can't determine
            return 1;
        } catch (\Exception $e) {
            Log::warning('Could not determine PDF page count: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Auto-match transactions with existing invoices
     */
    protected function autoMatchTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            // Skip if not a payment transaction
            if ($transaction->type !== 'credit' || $transaction->amount < 10) {
                continue;
            }
            
            // Look for matching invoices
            $potentialMatches = \App\Models\Invoice::where('company_id', $transaction->company_id)
                ->where('total', $transaction->amount)
                ->where('currency', $transaction->currency)
                ->where('invoice_date', '<=', $transaction->transaction_date)
                ->where('invoice_date', '>=', $transaction->transaction_date->subDays(60))
                ->where('status', '!=', 'paid')
                ->get();
            
            if ($potentialMatches->count() === 1) {
                // Single match with exact amount - high confidence
                $transaction->update([
                    'matched_invoice_id' => $potentialMatches->first()->id,
                    'match_confidence' => 90,
                    'match_status' => 'auto_matched'
                ]);
            } elseif ($potentialMatches->count() > 1) {
                // Multiple matches - need manual review
                // Could implement more sophisticated matching logic here
            }
        }
    }
}
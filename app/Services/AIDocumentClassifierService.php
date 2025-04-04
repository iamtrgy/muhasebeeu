<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Spatie\PdfToText\Pdf;

class AIDocumentClassifierService
{
    protected Client $httpClient;
    protected string $apiKey;
    protected string $apiUrl = 'https://api.anthropic.com/v1/messages'; // Adjust if necessary for your Claude model/version

    public function __construct()
    {
        $this->apiKey = config('services.claude.key');
        if (!$this->apiKey) {
            throw new \Exception('Claude API key is not configured in config/services.php or .env');
        }

        $this->httpClient = new Client([
            'base_uri' => $this->apiUrl,
            'timeout'  => 60.0, // Increased timeout for potentially long AI responses
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

        try {
            if ($mimeType === 'application/pdf') {
                // Ensure pdftotext binary is installed and accessible
                // You might need to specify the path in config/pdf-to-text.php
                return Pdf::getText($path);
            } elseif (str_starts_with($mimeType, 'text/')) {
                return file_get_contents($path);
            } else {
                Log::warning("AI Classifier: Unsupported file type '{$mimeType}' for text extraction.", ['path' => $path]);
                return null; // Or handle other types like images with OCR if needed
            }
        } catch (\Exception $e) {
            Log::error("AI Classifier: Failed to extract text from file.", [
                'path' => $path,
                'mime_type' => $mimeType,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Asks Claude AI to classify the document into one of the provided folders.
     *
     * @param string $textContent The extracted text content of the document.
     * @param array $folders An array of folder objects/arrays, each having at least 'id' and 'name'.
     * @return int|null The ID of the suggested folder, or null on failure/error.
     */
    public function classifyDocument(string $textContent, array $folders): ?int
    {
        if (empty($textContent) || empty($folders)) {
            Log::warning("AI Classifier: Cannot classify document due to empty text or no folders provided.");
            return null;
        }

        // --- Basic Prompt Engineering ---
        // TODO: Improve this prompt for better accuracy.
        // Consider adding context about the user/company or folder descriptions.
        $folderList = implode('\n', array_map(fn($f) => "- ID: {$f->id}, Name: {$f->name}", $folders));
        $prompt = <<<PROMPT
You are an AI assistant helping to organize accounting documents.
Based on the following document content, please identify the single most appropriate folder ID from the list provided.
Respond ONLY with the numerical folder ID. Do not add any explanation or other text.

Available Folders:
{$folderList}

Document Content:
--- Start Content ---
{$textContent}
--- End Content ---

The most appropriate folder ID is:
PROMPT;

        try {
            $response = $this->httpClient->post('', [ // POST to the base_uri defined in constructor
                'headers' => [
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => '2023-06-01', // Use the required API version
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'model' => 'claude-3-haiku-20240307', // Or your preferred Claude model
                    'max_tokens' => 10, // Just need the ID
                    'temperature' => 0.0, // Low temperature for deterministic output
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ]
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (isset($body['content'][0]['text'])) {
                $suggestedId = trim($body['content'][0]['text']);
                // Validate if the suggested ID is actually one of the provided folder IDs
                $validIds = array_column($folders, 'id');
                if (is_numeric($suggestedId) && in_array((int)$suggestedId, $validIds)) {
                    Log::info("AI Classifier: Successfully classified document into folder ID: " . (int)$suggestedId);
                    return (int)$suggestedId;
                } else {
                    Log::warning("AI Classifier: Claude returned an invalid or non-existent folder ID.", ['response_id' => $suggestedId, 'valid_ids' => $validIds]);
                }
            } else {
                Log::error("AI Classifier: Unexpected response format from Claude API.", ['response' => $body]);
            }

        } catch (RequestException $e) {
            $errorResponse = $e->hasResponse() ? (string) $e->getResponse()->getBody() : 'No response';
            Log::error("AI Classifier: HTTP request to Claude API failed.", [
                'status_code' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'error' => $e->getMessage(),
                'response_body' => $errorResponse
            ]);
        } catch (\Exception $e) {
            Log::error("AI Classifier: General error during classification.", ['error' => $e->getMessage()]);
        }

        return null; // Indicate failure
    }
}
